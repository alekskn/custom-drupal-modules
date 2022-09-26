<?php

/**
 * @file
 * Main file for custom theme hook preprocess
 */

/**
 * Implements hook_preprocess_HOOK() for mikhalych-blog-related-posts.html.twig.
 */
function template_preprocess_mikhalych_blog_related_posts(array &$variables) {
  $items = [];
  /** @var \Drupal\node\NodeInterface $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof \Drupal\node\NodeInterface) {
    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    /** @var \Drupal\node\NodeViewBuilder $node_view_builder */
    $node_view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    /** @var \Drupal\mikhalych_blog\Service\BlogManagerInterface $blog_manager */
    $blog_manager = \Drupal::service('mikhalych_blog.manager');

    $related_post_ids = $blog_manager->getRelatedPosts($node, $variables['max'], $variables['max_exact_same_tags']);

    if ($related_post_ids) {
      foreach ($related_post_ids as $id) {
        /** @var \Drupal\node\NodeInterface $related_post */
        $related_post = $node_storage->load($id);
        $items[] = $node_view_builder->view($related_post, 'teaser');
      }
    }

  }

  $variables['items'] = $items;
}

/**
 * Implements hook_preprocess_HOOK() for mikhalych-blog-random-posts.html.twig.
 */
function template_preprocess_mikhalych_blog_random_posts(array &$variables) {
  $items = [];
  /** @var \Drupal\node\NodeInterface $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof \Drupal\node\NodeInterface) {
    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    /** @var \Drupal\node\NodeViewBuilder $node_view_builder */
    $node_view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    /** @var \Drupal\mikhalych_blog\Service\BlogManagerInterface $blog_manager */
    $blog_manager = \Drupal::service('mikhalych_blog.manager');

    $random_post_ids = $blog_manager->getRandomPosts(4, [$node->id()]);

    if ($random_post_ids) {
      foreach ($random_post_ids as $id) {
        /** @var \Drupal\node\NodeInterface $related_post */
        $related_post = $node_storage->load($id);
        $items[] = $node_view_builder->view($related_post, 'teaser');
      }
    }

  }

  $variables['items'] = $items;
}
