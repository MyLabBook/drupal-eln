<?php

namespace Drupal\Tests\userprotect\Unit;

use Drupal\userprotect\UserProtect;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @coversDefaultClass \Drupal\userprotect\UserProtect
 * @group userprotect
 */
class UserProtectUnitTest extends UnitTestCase {

  /**
   * @covers ::pluginManager
   */
  public function testPluginManager() {
    $container = new Container();
    $plugin_manager = $this->createMock('\Drupal\Component\Plugin\PluginManagerInterface');
    $container->set('plugin.manager.userprotect.user_protection', $plugin_manager);
    \Drupal::setContainer($container);
    $this->assertSame($plugin_manager, UserProtect::pluginManager());
  }

}
