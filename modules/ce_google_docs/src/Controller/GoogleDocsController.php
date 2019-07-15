<?php

namespace Drupal\ce_google_docs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;

/**
 * Class GoogleDocsController.
 */
class GoogleDocsController extends ControllerBase {

  /**
   * To update a node.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   HttpRequest with various parameters of a a node.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse with Node's details.
   */
  public function update(Request $request) {
    $config = \Drupal::config('ce_google_docs.settings');
    if ($request->request->get('apiKey') != $config->get('api_key')) {
      return new JsonResponse(['error' => 'Invalid API Key.']);
    }

    $node = Node::load($request->request->get('nid'));
    $node->title = $request->request->get('title');
    $node->body->summary = $request->request->get('summary');
    $node->body->value = $request->request->get('body');
    $node->save();

    return new JsonResponse(['nid' => $node->id()]);
  }

  /**
   * To save/publish a node.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   HttpRequest with various parameters of a a node.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse with Node's details.
   */
  public function publish(Request $request) {
    $config = \Drupal::config('ce_google_docs.settings');
    if ($request->request->get('apiKey') != $config->get('api_key')) {
      return new JsonResponse(['error' => 'Invalid API Key.']);
    }

    // Return new JsonResponse(['nid' => $request->query->get('body')]);.
    $node = Node::create([
      'type' => $request->request->get('type'),
      'created' => \Drupal::time()->getRequestTime(),
      'uid' => $request->request->get('user'),
      'title' => $request->request->get('title'),
      'body' => [
        'summary' => $request->request->get('summary'),
        'value' => $request->request->get('body'),
        'format' => 'full_html',
      ],
      'status' => $request->request->get('status'),
    ]);
    // Saving the node.
    $node->save();

    return new JsonResponse(['nid' => $node->id()]);
  }

  /**
   * To list all the Drupal Users.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   HttpRequest with apiKey parameter as API Key.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse with User's ID and Name.
   */
  public function listUser(Request $request) {
    $config = \Drupal::config('ce_google_docs.settings');
    if ($request->query->get('apiKey') != $config->get('api_key')) {
      return new JsonResponse(['error' => 'Invalid API Key.']);
    }
    $users = user_load_multiple();
    $response = [];
    for ($i = 1; $i < count($users); $i++) {
      $response[$i - 1] = [
        'uid' => $users[$i]->get('uid')->value,
        'name' => $users[$i]->get('name')->value,
      ];
    }
    return new JsonResponse($response);
  }

  /**
   * To test connection with Google Docs Add-On.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   HttpRequest with apiKey parameter as API Key.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse with message as 'ok' for success ad 'error' for failure.
   */
  public function testConnection(Request $request) {
    $config = \Drupal::config('ce_google_docs.settings');
    $response = [];
    if ($request->query->get('apiKey') == $config->get('api_key')) {
      $response['msg'] = 'ok';
    }
    else {
      $response['msg'] = 'error';
    }
    return new JsonResponse($response);
  }

}
