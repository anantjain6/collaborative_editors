<?php

namespace Drupal\ce_etherpad\Controller;

use Drupal\ce_etherpad\Plugin\CollaborativeNetwork\EtherpadEditor;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class EtherpadController.
 */
class EtherpadController extends ControllerBase {

  /**
   * To create pad in Etherpad if not exists.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   HttpRequest with nid parameter as Node ID.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse with pid parameter as Pad ID.
   */
  public function getPad(Request $request) {
    $config = \Drupal::config('ce_etherpad.settings');
    $etherpad = new EtherpadEditor($config->get('etherpad_api_key'), $config->get('etherpad_api_url'));
    $nodeID = $request->query->get('nid');
    $response = [];
    if ($nodeID == 'add') {
      $response['pid'] = $etherpad->createPad($user->uid, $user->name, $config->get('session_cookie'));
    }
    else {
      $userID = \Drupal::currentUser()->id();
      $userName = User::load($userID)->get('name')->value;
      $content = $etherpad->get($nodeID);
      $response['pid'] = $etherpad->createPad($userID, $userName, $config->get('session_cookie'), $content, $nodeID);
    }
    return new JsonResponse($response);
  }

  /**
   * To get HTML content of Etherpad pad.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   HttpRequest with pid parameter as Pad ID.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse with content parameter as HTML content of pad.
   */
  public function getContent(Request $request) {
    $config = \Drupal::config('ce_etherpad.settings');
    $etherpad = new EtherpadEditor($config->get('etherpad_api_key'), $config->get('etherpad_api_url'));
    $padID = $request->query->get('pid');
    $content = $etherpad->getContent($padID);
    $response = [
      'content' => $content,
    ];
    return new JsonResponse($response);
  }

}
