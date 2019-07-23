<?php

namespace Drupal\ce_google_docs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class GoogleDocsSettingsForm.
 */
class GoogleDocsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ce_google_docs.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ce_google_docs_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ce_google_docs.settings');

    $apiKey = $config->get('api_key');
    if (!isset($apiKey)) {
      $apiKey = hash("sha256", rand());
      $this->config('ce_google_docs.settings')
        ->set('api_key', $apiKey)
        ->save();
    }
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t('You need to enter this API Key in Google Docs Add-On.'),
      '#default_value' => $apiKey,
      '#disabled' => TRUE,
    ];

    $form = parent::buildForm($form, $form_state);
    $form['actions']['submit']['#value'] = $this->t('Generate New API Key');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('ce_google_docs.settings')
      ->set('api_key', hash("sha256", rand()))
      ->save();
  }

}
