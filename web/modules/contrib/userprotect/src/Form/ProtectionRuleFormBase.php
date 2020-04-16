<?php

namespace Drupal\userprotect\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\user\UserStorageInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base form controller for a protection rule.
 */
abstract class ProtectionRuleFormBase extends EntityForm {

  /**
   * The protection rule entity storage controller.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $protectionRuleStorage;

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a base class for protection rule add and edit forms.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $protection_rule_storage
   *   The protection rule entity storage controller.
   * @param \Drupal\user\UserStorageInterface $user_storage
   *   The user storage.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(
    EntityStorageInterface $protection_rule_storage,
    UserStorageInterface $user_storage,
    MessengerInterface $messenger
  ) {
    $this->protectionRuleStorage = $protection_rule_storage;
    $this->userStorage = $user_storage;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('userprotect_rule'),
      $container->get('entity_type.manager')->getStorage('user'),
      $container->get('messenger')
    );
  }

  /**
   * Construct message for bypass permission.
   */
  protected function getBypassMessage($permission, $permission_label) {
    $message = $this->t('Users with the permission "%permission".', ['%permission' => $permission_label]);
    $roles = user_role_names(FALSE, $permission);
    if (count($roles)) {
      $message .= '<br /><div class="description">' . $this->t('Currently the following roles have this permission:  %roles.', ['%roles' => implode(', ', $roles)]) . '</div>';
    }
    else {
      $message .= '<br /><div class="description">' . $this->t('Currently no roles have this permission.') . '</div>';
    }
    return ['#markup' => $message];
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $protected_entity_type = $this->entity->getProtectedEntityTypeId();

    // Help text.
    $items = [];
    // User 1.
    $account = $this->userStorage->load(1);
    if (!empty($account)) {
      $items[] = [
        '#markup' => $this->t('User @id (@name)', [
          '@id' => $account->id(),
          '@name' => $account->label(),
        ]),
      ];
    }
    // User in question (if protected entity type is "user").
    if ($protected_entity_type == 'user') {
      $account = $this->entity->getProtectedEntity();
      if (!empty($account)) {
        $items[] = [
          '#markup' => $this->t('The protected user itself (@name)', [
            '@name' => $account->label(),
          ]),
        ];
      }
      else {
        $items[] = [
          '#markup' => $this->t('The protected user itself'),
        ];
      }
    }
    // Bypass all protections.
    $permission = 'userprotect.bypass_all';
    $permission_label = $this->t('Bypass all user protections');
    $items[] = $this->getBypassMessage($permission, $permission_label);
    // Bypass this protection.
    $permission = $this->entity->getPermissionName();
    if ($permission && !$this->entity->isNew()) {
      $permission_label = $this->t('Bypass user protection for @label', ['@label' => $this->entity->label()]);
      $items[] = $this->getBypassMessage($permission, $permission_label);
    }
    switch ($protected_entity_type) {
      case 'user':
        $form['help'] = [
          '#markup' => $this->t('This user will be protected for all users except:'),
        ];
        break;

      case 'user_role':
        $form['help'] = [
          '#markup' => $this->t('This role will be protected for all users except:'),
        ];
        break;
    }

    $form['help']['list'] = [
      '#theme' => 'item_list',
      '#items' => $items,
    ];

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $this->entity->label(),
      '#required' => TRUE,
    ];
    $form['name'] = [
      '#type' => 'machine_name',
      '#machine_name' => [
        'exists' => [$this->protectionRuleStorage, 'load'],
      ],
      '#default_value' => $this->entity->id(),
      '#required' => TRUE,
    ];

    switch ($protected_entity_type) {
      case 'user':
        $form['entity_id'] = [
          '#type' => 'entity_autocomplete',
          '#target_type' => 'user',
          '#title' => $this->t('User'),
          '#default_value' => $this->entity->getProtectedEntity(),
          '#required' => TRUE,
        ];
        break;

      case 'user_role':
        $entities = array_map('Drupal\Component\Utility\Html::escape', user_role_names(TRUE));
        $form['entity_id'] = [
          '#type' => 'select',
          '#title' => $this->t('Role'),
          '#options' => $entities,
          '#default_value' => $this->entity->getProtectedEntityId(),
          '#required' => TRUE,
        ];
        break;
    }

    $protection_options = [];
    $enabled_protections = [];
    foreach ($this->entity->getProtections()->getAll() as $name => $plugin) {
      $protection_options[$name] = [
        'protection' => [
          'data' => [
            '#type' => 'item',
            '#markup' => $plugin->label(),
            '#description' => $plugin->description(),
          ],
        ],
      ];
      if ($plugin->status) {
        $enabled_protections[$name] = TRUE;
      }
    }
    $form['protection'] = [
      '#type' => 'tableselect',
      '#header' => ['protection' => $this->t('Protection')],
      '#options' => $protection_options,
      '#default_value' => $enabled_protections,
    ];

    $roles = array_map('Drupal\Component\Utility\Html::escape', user_role_names());
    $form['bypass_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Bypass for roles'),
      '#description' => $this->t('Note: this setting will be saved as user permissions.'),
      '#options' => $roles,
      '#default_value' => $this->entity->getBypassRoles(),
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Set protected entity ID.
    $entity_id = $form_state->getValue('entity_id');
    $this->entity->setProtectedEntityId($entity_id);

    // Set enabled plugins.
    foreach ($form_state->getValue('protection') as $instance_id => $enabled) {
      if ($enabled) {
        $this->entity->enableProtection($instance_id);
      }
      else {
        $this->entity->disableProtection($instance_id);
      }
    }

    // Set bypass roles.
    $bypass_roles = [];
    foreach ($form_state->getValue('bypass_roles') as $rid => $status) {
      if (!empty($status)) {
        $bypass_roles[] = $rid;
      }
    }
    $this->entity->setBypassRoles($bypass_roles);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();
    $form_state->setRedirect('userprotect.rule_list');
  }

}
