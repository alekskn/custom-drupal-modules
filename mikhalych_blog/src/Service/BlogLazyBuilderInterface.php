<?php

namespace Drupal\mikhalych_blog\Service;

use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Interface for blog lazy builders.
 *
 * @package Drupal\mikhalych_blog\Service
 */
interface BlogLazyBuilderInterface extends TrustedCallbackInterface {

  /**
   * Gets random posts with theme hook mikhalych_blog_random_posts.
   *
   * @return array
   *  Render array with theme hook.
   */
  public static function randomBlogPosts();

}
