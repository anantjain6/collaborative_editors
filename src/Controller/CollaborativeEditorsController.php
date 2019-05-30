<?php

namespace Drupal\collaborative_editors\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines CollaborativeEditorsController class.
 */
class CollaborativeEditorsController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function plugins() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Configuration will go here'),
    ];
  }
}