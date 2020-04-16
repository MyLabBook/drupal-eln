<?php

namespace Drupal\userprotect\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber.
 *
 * @package Drupal\userprotect\Routing
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('role_delegation.edit_form')) {
      $route->setRequirement('_userprotect_role_access_check', 'TRUE');
    }
  }

}
