<?php

namespace Drupal\collaborative_editors;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Collaborative Network plugins.
 */
interface CollaborativeNetworkInterface extends PluginInspectionInterface {

  /**
   * Set content of node.
   *
   * @param int $node_id
   *   ID of Node.
   * @param string $content
   *   Content to be set as body of node.
   */
  public function set(int $node_id, string $content);

  /**
   * Get content of node.
   *
   * @param int $node_id
   *   ID of Node.
   */
  public function get(int $node_id);

}
