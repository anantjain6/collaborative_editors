<?php

namespace Drupal\ce_etherpad\Plugin\CollaborativeNetwork;

use Drupal\ce_editors\CollaborativeNetworkBase;
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
   * Set Credentials.
   *
   * @param string $apiKey
   *   The API Key of Etherpad.
   * @param string $apiUrl
   *   The API URL of Etherpad.
   */
  public function setCredentials($apiKey, $apiUrl = 'http://localhost:9001') {
    $this->apiUrl = $apiUrl;
    $this->apiKey = $apiKey;
  }

  /**
   * {@inheritdoc}
   */
  public function testConnection() {
    try {
      $client = new Client($this->apiKey, $this->apiUrl);
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

}
