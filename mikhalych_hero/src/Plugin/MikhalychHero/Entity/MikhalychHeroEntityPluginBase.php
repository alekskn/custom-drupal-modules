<?php

namespace Drupal\mikhalych_hero\Plugin\MikhalychHero\Entity;

use Drupal\mikhalych_hero\Plugin\MikhalychHero\MikhalychHeroPluginBase;

/**
 * The base for MikhalychHero entity plugin type.
 */
abstract class MikhalychHeroEntityPluginBase extends MikhalychHeroPluginBase implements MikhalychHeroEntityPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getEntityType() {
    return $this->pluginDefinition['entity_type'];
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle() {
    return $this->pluginDefinition['match_bundle'];
  }

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    return $this->configuration['entity'];
  }

}
