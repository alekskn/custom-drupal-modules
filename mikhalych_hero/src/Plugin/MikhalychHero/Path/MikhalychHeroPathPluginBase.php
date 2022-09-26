<?php

namespace Drupal\mikhalych_hero\Plugin\MikhalychHero\Path;

use Drupal\mikhalych_hero\Plugin\MikhalychHero\MikhalychHeroPluginBase;

/**
 * The base for MikhalychHero path plugin type.
 */
abstract class MikhalychHeroPathPluginBase extends MikhalychHeroPluginBase implements MikhalychHeroPathPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getMatchPath() {
    return $this->pluginDefinition['match_path'];
  }

  /**
   * {@inheritdoc}
   */
  public function getMatchType() {
    return $this->pluginDefinition['match_type'];
  }

}
