<?php

/**
 * @file
 * Main file for all custom theme hooks preprocess.
 */

/**
 * Implements template_preprocess_HOOK() for mikhalych-hero.html.twig.
 */
function template_preprocess_mikhalych_hero(&$variables) {
  // Image is required for video. It will be used for poster.
  if (!empty($variables['video'])) {
    if (empty($variables['image'])) {
      $variables['video'] = [];
    }
  }
}
