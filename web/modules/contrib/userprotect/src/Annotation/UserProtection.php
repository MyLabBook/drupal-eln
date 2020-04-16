<?php

namespace Drupal\userprotect\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an user protection annotation object.
 *
 * @Annotation
 */
class UserProtection extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the protection.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A brief description of the protection.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description = '';

  /**
   * A default weight used for presentation in the user interface only.
   *
   * @var int
   */
  public $weight = 0;

  /**
   * Whether this protection is enabled or disabled by default.
   *
   * @var bool
   */
  public $status = FALSE;

}
