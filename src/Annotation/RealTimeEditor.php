<?php

namespace Drupal\collaborative_editors\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Real time editor item annotation object.
 *
 * @see \Drupal\collaborative_editors\Plugin\RealTimeEditorManager
 * @see plugin_api
 *
 * @Annotation
 */
class RealTimeEditor extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
