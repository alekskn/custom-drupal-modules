<?php

namespace Drupal\mikhalych_hero\Plugin\MikhalychHero;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Common interface for all MikhalychHero plugin types.
 */
interface MikhalychHeroPluginInterface extends PluginInspectionInterface {

  /**
   * Gets plugin status.
   *
   * @return bool
   *  The plugin status.
   */
  public function getEnabled();

  /**
   * Gets plugin weight.
   *
   * @return int
   *  The plugin weight.
   */
  public function getWeight();

  /**
   * Gets hero title.
   *
   * @return string
   *  The title.
   */
  public function getHeroTitle();

  /**
   * Gets hero subtitle.
   *
   * @return string
   *  The subtitle.
   */
  public function getHeroSubTitle();

  /**
   * Gets hero image URI.
   *
   * @return string
   *  The URI of image.
   */
  public function getHeroImage();

  /**
   * Gets hero video URI.
   *
   * An array with link to the same video in different types.
   *
   * Keys of array is represents their type and value is file URI.
   *
   * @code
   * return [
   *   'video/mp4' => 'video.mp4',
   *   'video/ogg' => 'video.ogg',
   *   'video/webm' => 'video.webm',
   * ]
   * @endcode
   *
   * @return array
   *  An array with video URI's.
   */
  public function getHeroVideo();

}
