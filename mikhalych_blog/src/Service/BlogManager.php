<?php

namespace Drupal\mikhalych_blog\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

/**
 * Simple helpers for blog articles.
 *
 * @package Drupal\mikhalych_blog\Service
 */
class BlogManager implements BlogManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * The node view Builder.
   *
   * @var \Drupal\Core\Entity\EntityViewBuilderInterface
   */
  protected $nodeViewBuilder;

  /**
   * BlogManager constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->nodeViewBuilder = $entity_type_manager->getViewBuilder('node');
  }

  /**
   * {@inheritDoc}
   */
  public function getRelatedPostsWithExactTags(NodeInterface $node, $limit = 2) {
    $result = &drupal_static(this::class . '_' . __METHOD__ . '_' . $node->id() . '_' . $limit);

    if (!isset($result)) {
      if ($node->hasField('field_tags') && !$node->get('field_tags')->isEmpty()) {
        $query = $this->nodeStorage->getQuery()
          ->condition('status', NodeInterface::PUBLISHED)
          ->condition('type', 'article')
          ->condition('nid', $node->id(), '<>')
          ->range(0, $limit)
          ->addTag('entity_query_random');

        foreach ($node->get('field_tags')->getValue() as $field_tag) {
          $and = $query->andConditionGroup();
          $and->condition('field_tags', $field_tag['target_id']);
          $query->condition($and);
        }

        $result = $query->execute();
      } else {
        $result = [];
      }
    }

    return $result;
  }

  /**
   * {@inheritDoc}
   */
  public function getRelatedPostsWithSameTags(NodeInterface $node, $exclude_ids = [], $limit = 2) {
    $result = &drupal_static(this::class . '_' . __METHOD__ . '_' . $node->id() . '_' . $limit);

    if (!isset($result)) {
      if ($node->hasField('field_tags') && !$node->get('field_tags')->isEmpty()) {

        $field_tags_ids = [];
        foreach ($node->get('field_tags')->getValue() as $field_tag) {
          $field_tags_ids[] = $field_tag['target_id'];
        }

        $query = $this->nodeStorage->getQuery()
          ->condition('status', NodeInterface::PUBLISHED)
          ->condition('type', 'article')
          ->condition('nid', $node->id(), '<>')
          ->condition('field_tags', $field_tags_ids, 'IN')
          ->range(0, $limit)
          ->addTag('entity_query_random');

        /**
         * Exclude blog posts.
         */
        /*foreach ($exclude_ids as $id) {
          $query->condition('nid', $id, '<>');
        }*/
        // Or use operator "NOT IN"
        if ($exclude_ids) {
          $query->condition('nid', $exclude_ids, 'NOT IN');
        }

        $result = $query->execute();
      } else {
        $result = [];
      }
    }

    return $result;
  }

  /**
   * {@inheritDoc}
   */
  public function getRandomPosts($limit = 2, array $exclude_ids = []) {
    $query = $this->nodeStorage->getQuery()
      ->condition('status', NodeInterface::PUBLISHED)
      ->condition('type', 'article')
      ->range(0, $limit)
      ->addTag('entity_query_random');

    if ($exclude_ids) {
      $query->condition('nid', $exclude_ids, 'NOT IN');
    }

    return $query->execute();
  }

  /**
   * {@inheritDoc}
   */
  public function getRelatedPosts(NodeInterface $node, $max = 4, $exact_tags = 2) {
    $result = &drupal_static(this::class . '_' . __METHOD__ . '_' . $node->id() . '_' . $max . '_' . $exact_tags);

    if (!isset($result)) {

      if ($exact_tags > $max) {
        $exact_tags = $max;
      }

      $exclude_ids = [$node->id()];
      $counter = 0;
      $result = [];

      /**
       * Gets related posts with exact tags.
       */
      if ($exact_tags > 0) {
        $exact_tags_posts = $this->getRelatedPostsWithExactTags($node, $exact_tags);

        if (!empty($exact_tags_posts)) {
          $result += $exact_tags_posts;
          $counter += count($exact_tags_posts);
        }
      }

      /**
       * Gets related posts with same tags.
       */
      if ($counter < $max) {

        if (!empty($exact_tags_posts)) {
          $exclude_ids += $exact_tags_posts;
        }

        $same_tags_posts = $this->getRelatedPostsWithSameTags($node, $exclude_ids, ($max - $counter));

        if (!empty($same_tags_posts)) {
          $result += $same_tags_posts;
          $counter += count($same_tags_posts);
        }
      }

      /**
       * Gets random posts.
       */
      if ($counter < $max) {

        if (!empty($same_tags_posts)) {
          $exclude_ids += $same_tags_posts;
        }

        $random = $this->getRandomPosts(($max - $counter), $exclude_ids);
        $result += $random;
      }

    }

    return $result;
  }

}
