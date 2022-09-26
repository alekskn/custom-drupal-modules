<?php

namespace Drupal\mikhalych\Plugin\MikhalychHero\Entity;

use Drupal\mikhalych_hero\Annotation\MikhalychHeroEntity;
use Drupal\mikhalych_hero\Plugin\MikhalychHero\Entity\MikhalychHeroEntityPluginBase;

/**
 * Hero block for article node type.
 *
 * @MikhalychHeroEntity(
 *   id = "mikhalych_hero_article",
 *   entity_type = "node",
 *   entity_bundle = {"article"}
 * )
 */
class NodeArticle extends MikhalychHeroEntityPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getHeroSubTitle() {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->getEntity();

    return $node->get('body')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroImage() {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->getEntity();
    /** @var \Drupal\media\MediaInterface $featured_image */
    $media = $node->get('field_featured_image')->entity;

    return $media->get('field_media_image')->entity->get('uri')->value;
  }

}
