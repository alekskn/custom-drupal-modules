<?php

namespace Drupal\mikhalych_hero\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Condition\ConditionManager;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Factory\ContainerFactory;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class MikhalychHeroPluginManager
 */
class MikhalychHeroPluginManager extends DefaultPluginManager {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $routeMatch;

  /**
   * The condition manager.
   *
   * @var \Drupal\Core\Condition\ConditionManager
   */
  protected $conditionManager;

  /**
   * MikhalychHeroPluginManager constructor.
   *
   * @param $type
   *  The MikhalychHero plugin type.
   * @param \Traversable $namespaces
   *  The namespaces.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *  The cache backend.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *  The module handler.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *  The current route match.
   * @param \Drupal\Core\Condition\ConditionManager $condition_manager
   *  The condition manager.
   */
  public function __construct(
    $type, \Traversable $namespaces, CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler, CurrentRouteMatch $current_route_match,
    ConditionManager $condition_manager
  ) {
    $this->conditionManager = $condition_manager;
    $this->routeMatch = $current_route_match;

    // E.g. entity => Entity, path => Path
    $type_camelized = Container::camelize($type);
    $subdir = "Plugin/MikhalychHero/{$type_camelized}";
    $plugin_interface = "Drupal\mikhalych_hero\Plugin\MikhalychHero\{$type_camelized}\MikhalychHero{$type_camelized}PluginInterface";
    $plugin_definition_annotation_name = "Drupal\mikhalych_hero\Annotation\MikhalychHero{$type_camelized}";

    parent::__construct($subdir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);

    $this->defaults += [
      'plugin_type' => $type,
      'enabled' => true,
      'weight' => 0,
    ];

    if ($type == 'path') {
      $this->defaults += [
        'match_type' => 'listed',
      ];
    }

    // Call hook_mikhalych_hero_TYPE_alter().
    $this->alterInfo("mikhalych_hero_{$type}");

    $this->setCacheBackend($cache_backend, "mikhalych_hero:{$type}");
    $this->factory = new ContainerFactory($this->getDiscovery());
  }

  /**
   * Gets suitable plugins for current request.
   */
  public function getSuitablePlugins() {
    $plugin_type = $this->defaults['plugin_type'];

    if ($plugin_type == 'entity') {
      return $this->getSuitableEntityPlugins();
    }

    if ($plugin_type == 'path') {
      return $this->getSuitablePathPlugins();
    }

  }

  /**
   * Gets mikhalych hero entity plugins suitable for current request.
   */
  protected function getSuitableEntityPlugins() {
    $plugins = [];

    $entity = NULL;
    foreach ($this->routeMatch->getParameters() as $parameter) {
      if ($parameter instanceof EntityInterface) {
        $entity = $parameter;
        break;
      }
    }

    if ($entity) {
      foreach ($this->getDefinitions() as $plugin_id => $plugin) {
        if ($plugin['enabled']) {
          $same_entity_type = $plugin['entity_type'] == $entity->getEntityTypeId();
          $needed_bundle = in_array($entity->bundle(), $plugin['entity_bundle']) || in_array('*', $plugin['entity_bundle']);

          if ($same_entity_type && $needed_bundle) {
            $plugins[$plugin_id] = $plugin;
            $plugins[$plugin_id]['entity'] = $entity;
          }
        }
      }
    }

    uasort($plugins, '\Drupal\Component\Utility\SortArray::sortByWeightElement');
    return $plugins;
  }

  /**
   * Gets mikhalych hero path plugins suitable for current request.
   */
  protected function getSuitablePathPlugins() {
    $plugins = [];

    foreach ($this->getDefinitions() as $plugin_id => $plugin) {
      if ($plugin['enabled']) {
        $pages = implode(PHP_EOL, $plugin['match_path']);
        /** @var \Drupal\system\Plugin\Condition\RequestPath $request_path_condition */
        $request_path_condition = $this->conditionManager
          ->createInstance('request_path');

        $request_path_condition
          ->setConfig('pages', $pages)
          ->setConfig('negate', $plugin['match_type'] == 'unlisted');

        if ($request_path_condition->execute()) {
          $plugins[$plugin_id] = $plugin;
        }
      }
    }

    uasort($plugins, '\Drupal\Component\Utility\SortArray::sortByWeightElement');
    return $plugins;
  }

}
