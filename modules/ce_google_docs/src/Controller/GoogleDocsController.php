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
  public function addNode(Request $request) {
    $users = user_load_multiple();
    $response = [];
    for ($i = 0; $i <= count($users); $i++) {
    	$response[$i]['id'] = $users[$i]->get('uid')->value;
    	$response[$i]['name'] = $users[$i]->get('name')->value;
    }
    return new JsonResponse($response);
  }
  public function listUser(Request $request) {
    $config = \Drupal::config('ce_google_docs.settings');
  	if($request->query->get('apiKey') != $config->get('api_key'))
  		return new JsonResponse(['error' => 'Invalid API Key.']);
    $users = user_load_multiple();
    $response = [];
    for ($i = 1; $i < count($users); $i++) {
    	$response[$i-1] = [
    		'uid' => $users[$i]->get('uid')->value,
    		'name' => $users[$i]->get('name')->value,
    	];
    }
    return new JsonResponse($response);
  }
  public function testConnection(Request $request) {
    $config = \Drupal::config('ce_google_docs.settings');
    $response = [];
  	if($request->query->get('apiKey') == $config->get('api_key')) {
  		$response['msg'] = 'ok';
  	} else  {
  		$response['msg'] = 'error';
  	}
    return new JsonResponse($response);
  }

}
