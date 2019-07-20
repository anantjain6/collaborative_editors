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

  /**
   * To create Group of pads in Etherpad if noxists.
   *
   * @param string $groupMapper
   *   (optional) A unique string to map with each Etherpad Group.
   *
   * @return string
   *   Group ID.
   */
  public function createGroup($groupMapper);

  /**
   * To create Author in Etherpad if not exists.
   *
   * @param string $authorMapper
   *   A unique string to map with each Etherpad Author.
   * @param string $authorName
   *   (optional) The name of Author in Etherad.
   *
   * @return string
   *   Author ID.
   */
  public function createAuthor($authorMapper, $authorName);

  /**
   * To create session between Group and Author in Etherpad.
   *
   * @param string $groupID
   *   Group ID of Group of pads in Etherpad.
   * @param string $authorID
   *   Author ID of Author in Etherpad.
   * @param int $sessionTime
   *   (optional) Duration of session in number of seconds.
   *
   * @return string
   *   Session ID.
   */
  public function createSession($groupID, $authorID, $sessionTime);

  /**
   * To set HTML as content of Etherpad pad.
   *
   * @param string $padID
   *   The unique Pad ID.
   * @param string $content
   *   The HTML content.
   */
  public function setContent($padID, $content);

  /**
   * To get HTML content of Etherpad pad.
   *
   * @param string $padID
   *   The unique Pad ID.
   *
   * @return string
   *   HTML content of Etherpad pad.
   */
  public function getContent($padID);

  /**
   * To create pad in Etherpad if not exists.
   *
   * @param string $authorMapper
   *   A unique string to map with each Etherpad Author.
   * @param string $authorName
   *   (optional) The name of Author in Etherad.
   * @param int $setSession
   *   (optional) Wether session cookie to be set or not.
   * @param string $content
   *   (optional) The HTML content.
   * @param string $groupMapper
   *   (optional) A unique string to map with each Etherpad Group.
   * @param int $sessionTime
   *   (optional) Duration of session in number of seconds.
   *
   * @return string
   *   The unique Pad ID.
   */
  public function createPad($authorMapper, $authorName, $setSession, $content, $groupMapper, $sessionTime);

}
