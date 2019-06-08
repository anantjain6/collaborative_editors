<?php

namespace Drupal\collaborative_editors\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\collaborative_editors\RealTimeEditorManager;
use Drupal\Core\Url;

/**
 * Defines CollaborativeEditorsController class.
 */
class CollaborativeEditorsController extends ControllerBase {

  /**
   * The real time editor manager.
   *
   * @var \Drupal\collaborative_editors\RealTimeEditorManager
   */
  private $realTimeEitorManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.real_time_editor'));
  }

  /**
   * CollaborativeEditorsController constructor.
   *
   * @param \Drupal\collaborative_editors\RealTimeEditorManager $realTimeEitorManager
   *   The real time editor manager.
   */
  public function __construct(RealTimeEditorManager $realTimeEitorManager) {
    $this->realTimeEitorManager = $realTimeEitorManager;
  }

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function plugins() {
    $editors = $this->realTimeEitorManager->getDefinitions();

    $status = FALSE;


    $data = [];
    foreach ($editors as $editor) {
      $data[$editor['id']] = $this->buildRow($editor);
    }
    $form['overview'] = [
      '#theme' => 'table',
      '#header' => $this->buildHeader(),
      '#rows' => $data,
      '#empty' => $this->t('There are no plugins enabled.'),
    ];
    return $form;
  }

  /**
   * Builds the header row for the plugins listing.
   *
   * @return array
   *   A render array structure of header strings.
   *
   */
  public function buildHeader()
  {
    $header = [
      $this->t('Module'),
      $this->t('Operations'),
    ];
    return $header;
  }

  /**
   * Builds a row for a plugin in the plugins listing.
   *
   * @param \Drupal\collaborative_editors\RealTimeEditorManager $editor
   *   The plugin definition
   */
  public function buildRow($editor)
  {
    $row = [
      'title' => [
        'data' => [
          '#type' => 'markup',
          '#prefix' => '<b>' . $editor['label'] . '</b>',
        ],
      ],
      'operations' => [
        'data' => [
          '#type' => 'operations',
          '#links' => [
            'edit' => [
              'title' => $this->t('Edit'),
              'url' => URL::fromRoute($editor['id'] . '.settings'),
            ],
          ],
        ],
      ],
    ];
    return $row;
  }

}
