<?php

namespace Drupal\mikhalych_hero\Plugin\MikhalychHero\Entity;

use Drupal\mikhalych_hero\Plugin\MikhalychHero\MikhalychHeroPluginInterface;

/**
 * Interface for MikhalychHero entity plugin type.
 */
interface MikhalychHeroEntityPluginInterface extends MikhalychHeroPluginInterface {

  /**
   * Gets entity type id.
   *
   * @return string
   *  The entity type id.
   */
  public function getEntityType();

  /**
   * Gets entity bundles.
   *
   * @return array
   *  An array with entity type bundles.
   */
  public function getEntityBundle();

  /**
   * Gets current entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *  The entity object.
   */
  public function getEntity();

}
