<?php

namespace Drupal\mikhalych_taxonomy\Service;

/**
 * Class with helper for taxonomy vocabulary tags.
 *
 * @package Drupal\mikhalych_taxonomy\Service
 */
interface TagsHelperInterface {

  /**
   * Gets promo image uri from taxonomy term.
   *
   * @param int $tid
   *  The term id.
   *
   * @return string|null
   *  The file uri, null otherwise.
   */
  public function getPromoUri($tid);

}
