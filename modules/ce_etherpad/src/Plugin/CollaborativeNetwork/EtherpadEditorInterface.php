<?php

namespace Drupal\ce_etherpad\Plugin\CollaborativeNetwork;

use Drupal\collaborative_editors\CollaborativeNetworkInterface;

/**
 * Provides an interface for Etherpad collaborative editor plugin.
 */
interface EtherpadEditorInterface extends CollaborativeNetworkInterface {

  /**
   * To test connection with Etherpad.
   *
   * @return mixed
   *   Drupal Message.
   */
  public function testConnection();

}
