<?php

namespace Drupal\ce_etherpad\Plugin\Editor;

use Drupal\editor\Plugin\EditorBase;
use Drupal\editor\Entity\Editor;
use Drupal\user\Entity\User;

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
    $config = \Drupal::config('ce_etherpad.settings');
    $userID = \Drupal::currentUser()->id();
    $userName = User::load($userID)->get('name')->value;
    $settings = [
      'host' => $config->get('etherpad_api_url'),
      'user' => $userName,
      'plugins' => [
        'drupal-storage-textfield',
        'drupal-default',
        'gjs-blocks-flexbox',
        'drupal-block',
        'html-block',
      ],
      'pluginOpts' => [
        'drupal-default' => [],
        'gjs-blocks-flexbox' => [
          'flexboxBlock' => [
            'category' => 'Layout',
          ],
        ],
      ],
      'pluginOptionsFromJs' => [
        'drupal-default' => [
          'element' => 'element',
          'format' => 'format',
        ],
        'drupal-storage-textfield' => [
          'element' => 'element',
          'format' => 'format',
        ],
      ],
    ];
    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    $libs = [
      'ce_etherpad/ce_etherpad',
    ];
    return $libs;
  }

}
