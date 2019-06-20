<?php

namespace Drupal\collaborative_editors;

use Drupal\Component\Plugin\PluginBase;
use Drupal\node\Entity\Node;

/**
 * Base class for Collaborative Network plugins.
 */
abstract class CollaborativeNetworkBase extends PluginBase implements CollaborativeNetworkInterface {

  /**
   * {@inheritdoc}
   */
  public function set(int $node_id, string $content) {
    $node = Node::load($node_id);
    $node->body->value = $content;
    $node->save();
  }

  /**
   * {@inheritdoc}
   */
  public function get(int $node_id) {
    $node = Node::load($node_id);
    return $node->body->value;
  }

}
