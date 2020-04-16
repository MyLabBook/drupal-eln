<?php

namespace Drupal\Tests\userprotect\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests module installation and uninstallation.
 *
 * @group userprotect
 */
class InstallTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [];

  /**
   * Module handler to ensure installed modules.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Module installer.
   *
   * @var \Drupal\Core\Extension\ModuleInstallerInterface
   */
  protected $moduleInstaller;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->moduleHandler = $this->container->get('module_handler');
    $this->moduleInstaller = $this->container->get('module_installer');
  }

  /**
   * Test installation and uninstallation.
   */
  public function testInstallationAndUninstallation() {
    // Install userprotect.
    $this->assertFalse($this->moduleHandler->moduleExists('userprotect'));
    $this->assertTrue($this->moduleInstaller->install(['userprotect']));
    $this->assertTrue($this->moduleHandler->moduleExists('userprotect'));

    // Test default configuration.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->hasPermission('userprotect.mail.edit'), 'Authenticated user can edit own mail address.');
    $this->assertTrue($account->hasPermission('userprotect.pass.edit'), 'Authenticated user can edit own password.');
    $this->assertTrue($account->hasPermission('userprotect.account.edit'), 'Authenticated user can edit own account.');

    // Ensure an authenticated user can edit its own account.
    $this->drupalLogin($account);
    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertResponse(200, 'Authenticated user has access to edit page of own account.');

    // Uninstall userprotect.
    $this->moduleInstaller->uninstall(['userprotect']);

    // Workaround https://www.drupal.org/node/2021959
    // See \Drupal\Core\Test\FunctionalTestSetupTrait::rebuildContainer.
    unset($this->moduleHandler);
    $this->rebuildContainer();
    $this->moduleHandler = $this->container->get('module_handler');

    // Assert that userprotect is uninstalled.
    $this->assertFalse($this->moduleHandler->moduleExists('userprotect'));
  }

}
