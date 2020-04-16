<?php

namespace Drupal\front_page\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Configure site information settings for this site.
 */
class FrontPageSettingsForm extends ConfigFormBase {

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormID().
   */
  public function getFormID() {
    return 'front_page_admin';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::configFactory()->get('front_page.settings');

    $form['front_page_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Front Page Override'),
      '#description' => $this->t('Enable this if you want the front page module to manage the home page.'),
      '#default_value' => $config->get('enable') ?: false,
    ];

    // Load any existing settings and build the by redirect by role form.
    $form['roles'] = [
      '#tree' => TRUE,
      '#type' => 'fieldset',
      '#title' => $this->t('Roles'),
    ];

    // Build the form for roles.
    $roles = user_roles();

    // Iterate each role.
    foreach ($roles as $rid => $role) {

      $role_config = $config->get('rid_' . $rid);
      $form['roles'][$rid] = [
        '#type' => 'details',
        '#open' => FALSE,
        '#title' => $this->t('Front Page for @rolename', ['@rolename' => $role->label()]),
      ];

      $form['roles'][$rid]['enabled'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable'),
        '#value' => isset($role_config['enabled']) ? $role_config['enabled'] : false,
      ];

      $form['roles'][$rid]['weigth'] = [
        '#type' => 'number',
        '#title' => $this->t('Weigth'),
        '#value' => isset($role_config['weigth']) ? $role_config['weigth'] : 0,
      ];

      $form['roles'][$rid]['path'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Path'),
        '#default_value' => isset($role_config['path']) ? $role_config['path'] : '',
        '#cols' => 20,
        '#rows' => 1,
        '#description' => $this->t('A redirect path can contain a full URL including get parameters and fragment string (eg "/node/51?page=5#anchor").'),
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Settings'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
//    parent::validateForm($form, $form_state);
    $rolesList = $form_state->getUserInput()['roles'];
    if ($rolesList) {
      foreach ($rolesList as $rid => $role) {
        if ($role['enabled'] && empty($role['path'])) {
          $form_state->setErrorByName('roles][' . $rid . '][path', $this->t('You must set the path field for redirect mode.'));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('front_page.settings');

    //Set if all config are enabled or not.
    $config->set('enable', $form_state->getValue('front_page_enable'));

    //Set config by role.
    $rolesList = $form_state->getUserInput()['roles'];
     if (is_array($rolesList)) {
      foreach ($rolesList as $rid => $role) {
        $config->set('rid_' . $rid, $role);
      }
    }

    $config->save();
    parent::submitForm($form, $form_state);
  }
}
