<?php

namespace Drupal\collaborative_editors\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

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
    $table['plugins'] = [
      '#type' => 'table',
      '#header' => array(t('Plugin'), t('Operations')),
      '#empty' => t('There are no plugins enabled.'),
    ];
    if(\Drupal::moduleHandler()->moduleExists('ce_etherpad'))
    {
      $table['plugins']['ce_etherpad'] = [
        'title' => [
          'data' => [
            '#type' => 'markup',
            '#prefix' => '<b>Etherpad</b>',
          ],
        ],
        'operations' => [
          'data' => [
            '#type' => 'operations',
            '#links' => [
              'edit' => [
                'title' => $this->t('Edit'),
                'url' => URL::fromRoute('ce_etherpad.settings'),
              ],
            ],
          ],
        ],
      ];
    }
    return $table;
  }
}