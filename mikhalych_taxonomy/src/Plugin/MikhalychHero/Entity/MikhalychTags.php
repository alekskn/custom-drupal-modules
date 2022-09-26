<?php

namespace Drupal\mikhalych_taxonomy\Plugin\MikhalychHero\Entity;

use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\mikhalych_hero\Annotation\MikhalychHeroEntity;
use Drupal\mikhalych_hero\Plugin\MikhalychHero\Entity\MikhalychHeroEntityPluginBase;
use Drupal\mikhalych_taxonomy\Service\TagsHelperInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hero block for entity.
 *
 * @MikhalychHeroEntity(
 *   id = "mikhalych_tags",
 *   entity_type = "taxonomy_term",
 *   entity_bundle = {"*"}
 * )
 */
class MikhalychTags extends MikhalychHeroEntityPluginBase {

  /**
   * The tags helper.
   *
   * @var \Drupal\mikhalych_taxonomy\Service\TagsHelperInterface
   */
  protected $tagsHelper;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition,
                              Request $request, CurrentRouteMatch $current_route_match,
                              TitleResolverInterface $title_resolver,
                              EntityTypeManagerInterface $entity_type_manager,
                              TagsHelperInterface $tags_helper) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $request, $current_route_match, $title_resolver, $entity_type_manager);
    $this->tagsHelper = $tags_helper;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('current_route_match'),
      $container->get('title_resolver'),
      $container->get('entity_type.manager'),
      $container->get('mikhalych_taxonomy.tags.helper')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getHeroSubtitle() {
    /** @var \Drupal\taxonomy\TermInterface $term */
    $term = $this->getEntity();

    if (!$term->get('description')->isEmpty()) {
      return $term->getDescription();
    }
  }

  /**
   * {@inheritDoc}
   */
  public function getHeroImage() {
    /** @var \Drupal\taxonomy\TermInterface $term */
    $term = $this->getEntity();

    return $this->tagsHelper->getPromoUri($term->id());
  }

}
