<?php

namespace Drupal\ce_etherpad\Plugin\Editor;

use Drupal\editor\Plugin\EditorBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines EtherpadEditor as an Editor plugin.
 *
 * @Editor(
 *   id = "ce_etherpad",
 *   label = "Etherpad",
 *   supports_content_filtering = FALSE,
 *   supports_inline_editing = TRUE,
 *   is_xss_safe = FALSE,
 *   supported_element_types = {
 *     "textarea"
 *   }
 * )
 */
class EtherpadEditor extends EditorBase {

  /**
   * {@inheritdoc}
   */
  public function getJSSettings(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return [];
  }

}
