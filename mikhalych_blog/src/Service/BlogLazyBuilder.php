<?php

namespace Drupal\mikhalych_blog\Service;

/**
 * Class for blog lazy builders.
 *
 * @package Drupal\mikhalych_blog\Service
 */
class BlogLazyBuilder implements BlogLazyBuilderInterface {

  /**
   * {@inheritDoc}
   */
  public static function randomBlogPosts() {
    return [
      '#theme' => 'mikhalych_blog_random_posts',
    ];
  }

  /**
   * @return string[]|void
   */
  public static function trustedCallbacks() {
    return ['randomBlogPosts'];
  }

}
