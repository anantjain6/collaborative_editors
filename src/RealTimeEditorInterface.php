<?php

namespace Drupal\collaborative_editors;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines an interface for Real time editor plugins.
 */
interface RealTimeEditorInterface extends PluginInspectionInterface, ContainerFactoryPluginInterface {

  /**
   * Set content of node.
   *
   * @param  int $node_id
   *   ID of Node
   * @param string $content
   *   Content to be set as body of node
   */
  public function set(int $node_id, string $content);
}
