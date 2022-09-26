<?php

namespace Drupal\mikhalych_taxonomy\Plugin\MikhalychHero\Path;

use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\mikhalych_hero\Annotation\MikhalychHeroPath;
use Drupal\mikhalych_hero\Plugin\MikhalychHero\Path\MikhalychHeroPathPluginBase;
use Drupal\media\MediaInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hero block for path.
 *
 * @MikhalychHeroPath(
 *   id = "mikhalych_tags_page",
 *   match_type = "listed",
 *   match_path = {"/tags"}
 * )
 */
class MikhalychTagsPage extends MikhalychHeroPathPluginBase {

  /**
   * {@inheritDoc}
   */
  public function getHeroImage() {
    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    $media_image = $media_storage->load(17);
    if ($media_image instanceof MediaInterface && $media_image->hasField('field_media_image')) {
      return $media_image->get('field_media_image')->entity->get('uri')->value;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function getHeroVideo() {
    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    $media_video = $media_storage->load(2);
    if ($media_video instanceof MediaInterface && $media_video->hasField('field_media_video_file')) {
      return [
        'video/mp4' => $media_video->get('field_media_video_file')->entity->get('uri')->value
      ];
    }
  }

}
