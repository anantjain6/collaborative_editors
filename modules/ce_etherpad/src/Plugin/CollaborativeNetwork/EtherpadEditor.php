<?php

namespace Drupal\ce_etherpad\Plugin\CollaborativeNetwork;

use Drupal\collaborative_editors\CollaborativeNetworkBase;
use EtherpadLite\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Contains Plugin ID and Plugin definition info for ce_etherpad.
 *
 * @CollaborativeNetwork(
 *  id = "ce_etherpad",
 *  label = @Translation("Etherpad"),
 *  description = @Translation("Plugin provided by ce_etherpad."),
 * )
 */
class EtherpadEditor extends CollaborativeNetworkBase implements EtherpadEditorInterface {

  /**
   * The API URL of Etherpad.
   *
   * @var string
   */
  protected $apiUrl;

  /**
   * The API Key of Etherpad.
   *
   * @var string
   */
  protected $apiKey;

  /**
   * The instance of Etherpad HTTP Client.
   *
   * @var iterable
   */
  protected $client;

  /**
   * Set Credentials.
   *
   * @param string $apiKey
   *   The API Key of Etherpad.
   * @param string $apiUrl
   *   The API URL of Etherpad.
   */
  public function __construct($apiKey, $apiUrl = 'http://localhost:9001') {
    $this->apiKey = $apiKey;
    $this->apiUrl = $apiUrl;
    $this->client = new Client($this->apiKey, $this->apiUrl);
  }

  /**
   * {@inheritdoc}
   */
  public function testConnection() {
    try {
      $client = $this->client;
      $response = $client->checkToken();
      if ($response->getMessage() == "ok") {
        drupal_set_message("Connection established successfully.");
      }
      else {
        drupal_set_message("Either or both API URL and API Key is incorrect", "error");
      }
    }
    catch (ConnectException $e) {
      drupal_set_message("Unable to connect API URL or the API URL is incorrect.", "error");
    }
    catch (ClientException $e) {
      drupal_set_message("Either or both API URL and API Key is incorrect", "error");
    }
    catch (\Throwable $e) {
      drupal_set_message("Either Http Client library is not installed. Run \"composer require 0x46616c6b/etherpad-lite-client\" in root directory of Drupal to install Etherpad Http Client libreary.", "error");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createGroup($groupMapper = NULL) {
    $client = $this->client;

    // Create Group of pads in Etherpad.
    $groupID = '';
    if (isset($groupMapper)) {
      $groupID = $client->createGroupIfNotExistsFor($groupMapper)->getData('groupID');
    }
    else {
      $groupID = $client->createGroup()->getData('groupID');
    }

    return $groupID;
  }

  /**
   * {@inheritdoc}
   */
  public function createAuthor($authorMapper, $authorName = 'anonymous') {
    $client = $this->client;

    // Create Author if not exists in Etherpad.
    $authorID = $client->createAuthorIfNotExistsFor($authorMapper, $authorName)->getData('authorID');

    return $authorID;
  }

  /**
   * {@inheritdoc}
   */
  public function createSession($groupID, $authorID, $sessionTime = 0) {
    $client = $this->client;

    if ($sessionTime == 0) {
      $sessionTime = time() + 24 * 60 * 60;
    }

    // In Etherpad create session between Group of pads and Author.
    $sessionID = $client->createSession($groupID, $authorID, $sessionTime)->getData('sessionID');

    $host = parse_url($this->apiUrl, PHP_URL_HOST);

    // Set a cookie to allow access.
    setcookie('sessionID', $sessionID, $sessionTime, '/', $host);

    return $sessionID;
  }

  /**
   * {@inheritdoc}
   */
  public function setContent($padID, $content) {
    $client = $this->client;
    $client->setHTML($padID, '<html><head></head><body>' . $content . '</body></html>');
  }

  /**
   * {@inheritdoc}
   */
  public function getContent($padID) {
    $client = $this->client;
    return $client->getHTML($padID)->getData('html');
  }

  /**
   * {@inheritdoc}
   */
  public function createPad($authorMapper, $authorName = 'anonymous', $setSession = 1, $content = NULL, $groupMapper = NULL, $sessionTime = 0) {
    $client = $this->client;

    if ($setSession == 1) {
      $groupID = $this->createGroup($groupMapper);
      $authorID = $this->createAuthor($authorMapper, $authorName);
      $sessionID = $this->createSession($groupID, $authorID, $sessionTime);
      $padID = '';

      // List all the Pad in Group.
      $pad = $client->listPads($groupID)->getData('padIDs');
      if (count($pad) > 0) {
        $padID = $pad[0];
      }
      else {
        // Create Pad in Group.
        $client->createGroupPad($groupID, 'node');

        $padID = $groupID . '$node';
      }
    }
    else {
      if (isset($groupMapper)) {
        $client->createPad($groupMapper);
        $padID = $groupMapper;
      }
      else {
        $padID = time();
        $client->createPad($padID);
      }
    }

    if (isset($content)) {
      $this->setContent($padID, $content);
    }

    return $padID;
  }

}
