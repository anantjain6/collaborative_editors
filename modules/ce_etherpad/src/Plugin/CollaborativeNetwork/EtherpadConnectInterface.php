<?php

namespace Drupal\ce_etherpad\Plugin\CollaborativeNetwork;

use Drupal\collaborative_editors\CollaborativeNetworkInterface;

/**
 * Provides an interface for Etherpad collaborative editor plugin.
 */
interface EtherpadConnectInterface extends CollaborativeNetworkInterface {

  /**
   * To test connection with Etherpad.
   *
   * @param string $apiUrl
   *   API URL of Etherpad.
   * @param string $apiKey
   *   API Key of Etherpad.
   *
   * @return mixed
   *   Drupal Message.
   */
  public function testConnection($apiUrl, $apiKey);

}
