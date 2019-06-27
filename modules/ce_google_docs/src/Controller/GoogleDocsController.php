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

  public function addNode() {
    var_dump(user_load_multiple()[1]->get('name')->value);
  }

}
