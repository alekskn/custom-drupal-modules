<?php

namespace Drupal\mikhalych_hero\Plugin\Block;

use Drupal\mikhalych_hero\Plugin\MikhalychHeroPluginManager;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an mikhalych hero block.
 *
 * @Block(
 *   id = "mikhalych_hero",
 *   admin_label = @Translation("Mikhalych Hero"),
 *   category = @Translation("Custom")
 * )
 */
class MikhalychHeroBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The plugin manager for mikhalych hero entity plugins.
   *
   * @var \Drupal\mikhalych_hero\Plugin\MikhalychHeroPluginManager
   */
  protected $mikhalychHeroEntityManager;

  /**
   * The plugin manager for mikhalych hero path plugin.
   *
   * @var \Drupal\mikhalych_hero\Plugin\MikhalychHeroPluginManager
   */
  protected $mikhalychHeroPathManager;

  /**
   * Constructs a new MikhalychHeroBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\mikhalych_hero\Plugin\MikhalychHeroPluginManager $mikhalych_hero_entity
   *   The plugin manager for mikhalych hero entity plugins.
   * @param \Drupal\mikhalych_hero\Plugin\MikhalychHeroPluginManager $mikhalych_hero_path
   *   The plugin manager for mikhalych hero path plugin.
   */
  public function __construct(
    array $configuration, $plugin_id, $plugin_definition,
    MikhalychHeroPluginManager $mikhalych_hero_entity, MikhalychHeroPluginManager $mikhalych_hero_path
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->mikhalychHeroEntityManager = $mikhalych_hero_entity;
    $this->mikhalychHeroPathManager = $mikhalych_hero_path;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.mikhalych_hero.entity'),
      $container->get('plugin.manager.mikhalych_hero.path'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $entity_plugins = $this->mikhalychHeroEntityManager->getSuitablePlugins();
    $path_plugins = $this->mikhalychHeroPathManager->getSuitablePlugins();
    $plugins = $entity_plugins + $path_plugins;
    uasort($plugins, '\Drupal\Component\Utility\SortArray::sortByWeightElement');
    $plugin = end($plugins);

    if ($plugin['plugin_type'] == 'entity') {
      /** @var \Drupal\mikhalych_hero\Plugin\MikhalychHero\MikhalychHeroPluginInterface $instance */
      $instance = $this->mikhalychHeroEntityManager->createInstance($plugin['id'], ['entity' => $plugin['entity']]);
    }

    if ($plugin['plugin_type'] == 'path') {
      /** @var \Drupal\mikhalych_hero\Plugin\MikhalychHero\MikhalychHeroPluginInterface $instance */
      $instance = $this->mikhalychHeroPathManager->createInstance($plugin['id']);
    }

    $build['content'] = [
      '#theme' => 'mikhalych_hero',
      '#title' => $instance->getHeroTitle(),
      '#subtitle' => $instance->getHeroSubtitle(),
      '#image' => $instance->getHeroImage(),
      '#video' => $instance->getHeroVideo(),
      '#plugin_id' => $instance->getPluginId(),
    ];

    return $build;
  }

  public function getCacheContexts() {
    return [
      'url.path',
    ];
  }

}
