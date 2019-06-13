<?php

namespace Drupal\collaborative_editors\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\collaborative_editors\CollaborativeNetworkManager;
use Drupal\Core\Url;

/**
 * Defines CollaborativeEditorsController class.
 */
class CollaborativeEditorsController extends ControllerBase {

  /**
   * The Collaborative Network manager.
   *
   * @var \Drupal\collaborative_editors\CollaborativeNetworkManager
   */
  private $collaborativeNetworkManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.collaborative_network'));
  }

  /**
   * CollaborativeEditorsController constructor.
   *
   * @param \Drupal\collaborative_editors\CollaborativeNetworkManager $collaborativeNetworkManager
   *   The Collaborative Network manager.
   */
  public function __construct(CollaborativeNetworkManager $collaborativeNetworkManager) {
    $this->collaborativeNetworkManager = $collaborativeNetworkManager;
  }

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function plugins() {
    $editors = $this->collaborativeNetworkManager->getDefinitions();

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
   * @param \Drupal\collaborative_editors\CollaborativeNetworkManager $editor
   *   The plugin definition
   */
  public function buildRow($editor)
  {
    $row = [
      'title' => [
        'data' => [
          '#type' => 'markup',
          '#prefix' => '<b>' . $editor['label'] . '</b>',
          '#suffix' => '<div>' . $editor['description'] . '</div>',
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
