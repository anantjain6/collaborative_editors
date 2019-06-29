<?php

namespace Drupal\collaborative_editors;

use Drupal\collaborative_editors\Annotation\CollaborativeNetwork;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Collaborative Network plugin manager.
 */
class CollaborativeNetworkManager extends DefaultPluginManager {

  /**
   * Constructs a new CollaborativeNetworkManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/CollaborativeNetwork', $namespaces, $module_handler, CollaborativeNetworkInterface::class, CollaborativeNetwork::class);

    $this->alterInfo('collaborative_editors_collaborative_network_info');
    $this->setCacheBackend($cache_backend, 'collaborative_editors_collaborative_network_plugins');
  }

}
