<?php

namespace Drupal\ce_google_docs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use GuzzleHttp\Client;

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

    $body = $request->request->get('body');
    if ($request->request->get('img_store') == 'drupal') {
      preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $body, $result);
      $result = $result[1];
      foreach ($result as $src) {
        $client = new Client();
        $response = $client->request('GET', $src);
        $name = str_replace(" ", "_", substr($response->getHeader('Content-Disposition')[0], 17, -1));
        $file = system_retrieve_file($src, 'public://' . $name, TRUE, $replace = FILE_EXISTS_RENAME);
        $new_src_url = explode($_SERVER['HTTP_HOST'], $file->url())[1];
        $body = str_replace($src, $new_src_url, $body);
      }
    }

    $node = Node::load($request->request->get('nid'));
    $node->title = $request->request->get('title');
    $node->body->summary = $request->request->get('summary');
    $node->body->value = $body;
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

    $body = $request->request->get('body');
    if ($request->request->get('img_store') == 'drupal') {
      preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $body, $result);
      $result = $result[1];
      foreach ($result as $src) {
        $client = new Client();
        $response = $client->request('GET', $src);
        $name = str_replace(" ", "_", substr($response->getHeader('Content-Disposition')[0], 17, -1));
        $file = system_retrieve_file($src, 'public://' . $name, TRUE, $replace = FILE_EXISTS_RENAME);
        $new_src_url = explode($_SERVER['HTTP_HOST'], $file->url())[1];
        $body = str_replace($src, $new_src_url, $body);
      }
    }

    $body_feild = '';
    $fields = \Drupal::entityManager()->getFieldDefinitions('node', $request->request->get('type'));
    foreach ($fields as $field => $value) {
      if ($value->getType() == "text_with_summary") {
        $body_feild = $field;
        break;
      }
    }

    $node = Node::create([
      'type' => $request->request->get('type'),
      'created' => \Drupal::time()->getRequestTime(),
      'uid' => $request->request->get('user'),
      'title' => $request->request->get('title'),
      $body_feild => [
        'summary' => $request->request->get('summary'),
        'value' => $body,
        'format' => 'full_html',
      ],
      'status' => $request->request->get('status'),
    ]);
    // Saving the node.
    $node->save();

    return new JsonResponse(['nid' => $node->id()]);
  }

  /**
   * To list all the eligible Drupal Content Type.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   HttpRequest with apiKey parameter as API Key.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   JsonResponse with Content Type(s).
   */
  public function listContentType(Request $request) {
    $config = \Drupal::config('ce_google_docs.settings');
    if ($request->query->get('apiKey') != $config->get('api_key')) {
      return new JsonResponse(['error' => 'Invalid API Key.']);
    }
    $content_type = [];
    $nodes = entity_get_bundles('node');
    // Loop through each content type.
    foreach ($nodes as $type => $node) {
      // Get all fields on the content type.
      $fields = \Drupal::entityManager()->getFieldDefinitions('node', $type);
      foreach ($fields as $field => $value) {
        if ($value->getType() == "text_with_summary") {
          array_push($content_type, [
            'id' => $type,
            'name' => $node['label'],
          ]);
          break;
        }
      }
    }

    return new JsonResponse($content_type);
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
