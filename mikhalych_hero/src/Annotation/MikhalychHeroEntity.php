<?php

namespace Drupal\mikhalych_hero\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * MikhalychHeroEntity annotation.
 *
 * @Annotation
 */
class MikhalychHeroEntity extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The plugin status.
   *
   * By default all plugins are enabled and this value set in TRUE. You can set
   * it to FALSE, to temporary disable plugin.
   *
   * @var bool
   */
  public $enabled;

  /**
   * The entity type ID.
   *
   * @var string
   */
  public $entity_type;

  /**
   * The entity bundle.
   *
   * An array of bundles from entity_type on which page this plugin will be
   * available. Supports for wildcard (*) to match all entity type bundles.
   *
   * E.g. {"news", "page"}
   *
   * @var array
   */
  public $entity_bundle;

  /**
   * The weight of plugin.
   *
   * Plugin with higher weight will be used.
   *
   * @var int
   */
  public $weight;

}
