<?php

namespace Drupal\collaborative_editors;

use Drupal\Component\Plugin\PluginBase;
use Drupal\node\Entity\Node;

/**
 * Base class for Real time editor plugins.
 */
abstract class RealTimeEditorBase extends PluginBase implements RealTimeEditorInterface {

  /**
   * {@inheritdoc}
   */
  public function setset(int $node_id, string $content) {
    $node = Node::load($node_id);
    $node->body->value = $content;
    $node->save();
  }
}
