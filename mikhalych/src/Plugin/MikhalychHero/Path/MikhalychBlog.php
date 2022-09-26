<?php

namespace Drupal\mikhalych\Plugin\MikhalychHero\Path;

use Drupal\mikhalych_hero\Plugin\MikhalychHero\Path\MikhalychHeroPathPluginBase;
use Drupal\media\MediaInterface;

/**
 * Hero block for path.
 *
 * @MikhalychHeroPath(
 *   id = "mikhalych_blog",
 *   match_type = "listed",
 *   match_path = {"/blog"}
 * )
 */
class MikhalychBlog extends MikhalychHeroPathPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getHeroSubtitle() {
    return 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroImage() {
    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    $media_image = $media_storage->load(1);

    if ($media_image instanceof MediaInterface) {
      return $media_image->get('field_media_image')->entity->get('uri')->value;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroVideo() {
    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManager()->getStorage('media');
    $media_video = $media_storage->load(2);

    if ($media_video instanceof MediaInterface && $media_video->hasField('field_media_video_file')) {
      return [
        'video/mp4' => $media_video->get('field_media_video_file')->entity->get('uri')->value,
      ];
    }

    return FALSE;
  }

}
