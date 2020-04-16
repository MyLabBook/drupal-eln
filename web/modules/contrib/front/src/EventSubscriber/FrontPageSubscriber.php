<?php

namespace Drupal\front_page\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class FrontPageSubscriber.
 *
 * @package Drupal\front_page\EventSubscriber
 */
class FrontPageSubscriber implements EventSubscriberInterface {

  /**
   * Manage the logic.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   Managed event.
   */
  public function initData(GetResponseEvent $event) {
    global $base_path;

    // Make sure front page module is not run when using cli (drush).
    // Make sure front page module does not run when installing Drupal either.
    if (PHP_SAPI === 'cli' || drupal_installation_attempted()) {
      return;
    }

    // Don't run when site is in maintenance mode.
    if (\Drupal::state()->get('system.maintenance_mode')) {
      return;
    }

    // Ignore non index.php requests (like cron).
    if (!empty($_SERVER['SCRIPT_FILENAME']) && realpath(DRUPAL_ROOT . '/index.php') != realpath($_SERVER['SCRIPT_FILENAME'])) {
      return;
    }

    $front_page = NULL;
    $isFrontPage = \Drupal::service('path.matcher')->isFrontPage();
    if (\Drupal::config('front_page.settings')->get('enable', '') && $isFrontPage) {


      $roles = \Drupal::currentUser()->getRoles();
      $config = \Drupal::configFactory()->get('front_page.settings');
      $current_weigth = NULL;

      foreach ($roles as $role) {
        $role_config = $config->get('rid_' . $role);
        if ((isset($role_config['enabled']) && $role_config['enabled'] == TRUE)
          && (($role_config['weigth'] < $current_weigth) || $current_weigth === NULL)) {

          // $base_path can contain a / at the end, strip to avoid double slash.
          $path = rtrim($base_path, '/');
          $front_page = $path . $role_config['path'];
          $current_weigth = $role_config['weigth'];
        }
      }
    }

    if ($front_page) {
      $current_language = \Drupal::languageManager()->getCurrentLanguage();
      $url = Url::fromUserInput($front_page, ['language' => $current_language]);
      $event->setResponse(new RedirectResponse($url->toString()));

      // @todo Probably we must to remove this and manage cache by role.
      // Turn caching off for this page as it is dependant on role.
      \Drupal::service('page_cache_kill_switch')->trigger();
    }
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['initData'];
    return $events;
  }
}
