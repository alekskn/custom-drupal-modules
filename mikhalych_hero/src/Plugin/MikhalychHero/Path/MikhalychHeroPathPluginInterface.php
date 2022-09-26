<?php

namespace Drupal\mikhalych_hero\Plugin\MikhalychHero\Path;

use Drupal\mikhalych_hero\Plugin\MikhalychHero\MikhalychHeroPluginInterface;

/**
 * Interface for MikhalychHero path plugin type.
 */
interface MikhalychHeroPathPluginInterface extends MikhalychHeroPluginInterface {

  /**
   * Gets match paths.
   *
   * @return array
   *  An array with paths.
   */
  public function getMatchPath();

  /**
   * Gets match type.
   *
   * @return string
   *  The match type.
   */
  public function getMatchType();

}
