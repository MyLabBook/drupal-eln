<?php

namespace Drupal\Tests\userprotect\Unit\Plugin\UserProtection;

use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\userprotect\Plugin\UserProtection\UserProtectionBase
 * @group userprotect
 */
class UserProtectionBaseUnitTest extends UnitTestCase {

  /**
   * The module handler used for testing.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The userprotect method plugin under test.
   *
   * @var \Drupal\userprotect\Plugin\UserProtection\Mail
   */
  protected $plugin;

  /**
   * The definition of the user protection plugin under test.
   *
   * @var array
   */
  protected $pluginDefinition = [
    'id' => 'dummy',
    'label' => 'Dummy',
    'description' => 'This is a dummy plugin definition.',
    'provider' => '',
    'status' => FALSE,
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->moduleHandler = $this->createMock('\Drupal\Core\Extension\ModuleHandlerInterface');

    $this->plugin = $this->getMockBuilder('\Drupal\userprotect\Plugin\UserProtection\UserProtectionBase')
      ->setConstructorArgs([
        [],
        '',
        $this->pluginDefinition,
        $this->moduleHandler,
      ])
      ->setMethods(['t'])
      ->getMock();
    $this->plugin->expects($this->any())
      ->method('t')
      ->will($this->returnArgument(0));
  }

  /**
   * @covers ::label
   */
  public function testLabel() {
    $this->assertSame('Dummy', $this->plugin->label());
  }

  /**
   * @covers ::description
   */
  public function testDescription() {
    $this->assertSame('This is a dummy plugin definition.', $this->plugin->description());
  }

  /**
   * @covers ::setConfiguration
   * @covers ::getConfiguration
   */
  public function testGetConfiguration() {
    $configuration = [
      'status' => TRUE,
    ];
    $this->assertInstanceOf('\Drupal\userprotect\Plugin\UserProtection\UserProtectionInterface', $this->plugin->setConfiguration($configuration));
    $saved_configuration = $this->plugin->getConfiguration();
    $this->assertSame($configuration['status'], $saved_configuration['status']);
  }

  /**
   * @covers ::defaultConfiguration
   */
  public function testDefaultConfiguration() {
    $this->assertInternalType('array', $this->plugin->defaultConfiguration());
  }

}
