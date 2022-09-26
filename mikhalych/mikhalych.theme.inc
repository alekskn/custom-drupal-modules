<?php

/**
 * @file
 * Main file for preprocessing custom theme hooks.
 */

/**
 * Implements template_preprocess_HOOK() for mikhalych-previous-next.html.twig
 *
 * @param $variables
 */
function template_preprocess_mikhalych_previous_next(&$variables) {
  /** @var \Drupal\Core\Entity\EntityInterface $entity */
  $entity = $variables['entity'];
  $variables['next'] = [];
  $variables['previous'] = [];

  /** @var \Drupal\Core\Entity\EntityStorageInterface $entity_storage */
  $entity_storage = \Drupal::entityTypeManager()
    ->getStorage($entity->getEntityTypeId());

  // Getting next entity ID.
  $next_entity_id = $entity_storage->getQuery()
    ->condition('type', $entity->bundle())
    ->condition('created', $entity->getCreatedTime(), '>')
    ->range(0, 1)
    ->sort('created', 'ASC')
    ->execute();

  if (!empty($next_entity_id)) {
    $next_entity_id = array_shift($next_entity_id);
    $next_entity = $entity_storage->load($next_entity_id);

    $variables['next']['entity'] = $next_entity;
    $variables['next']['label'] = $next_entity->label();
    $variables['next']['url'] = $next_entity->toUrl()
      ->toString(TRUE)->getGeneratedUrl();
  }

  // Getting previous entity ID.
  $previous_entity_id = $entity_storage->getQuery()
    ->condition('type', $entity->bundle())
    ->condition('created', $entity->getCreatedTime(), '<')
    ->range(0, 1)
    ->sort('created', 'DESC')
    ->execute();

  if (!empty($previous_entity_id)) {
    $previous_entity_id = array_shift($previous_entity_id);
    $previous_entity = $entity_storage->load($previous_entity_id);

    $variables['previous']['entity'] = $previous_entity;
    $variables['previous']['label'] = $previous_entity->label();
    $variables['previous']['url'] = $previous_entity->toUrl()
      ->toString(TRUE)->getGeneratedUrl();
  }

}

/**
 * Implements template_preprocess_HOOK() for mikhalych-label-with-icon-media-formatter.html.twig.
 */
function template_preprocess_mikhalych_label_with_icon_media_formatter(array &$variables) {
  $mime_type = $variables['mime_type'];

  $icons_path = drupal_get_path('theme', 'mikhalych_frontend') . '/assets/images/mimetypes/';
  $current_icon_filename = str_replace('/', '-', $mime_type) . '.svg';

  $icon = $icons_path . $current_icon_filename;
  $icon_default = $icons_path . 'application-octet-stream.svg';

  $variables['mime_icon_path'] = file_exists($icon) ? $icon : $icon_default;
}
