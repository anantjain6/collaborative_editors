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
    if (isset($_SESSION["api_key"])) {
      $apiKey = $_SESSION["api_key"];
      unset($_SESSION["api_key"]);
    }
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t('You need to enter this API Key in Google Docs Add-On.'),
      '#default_value' => $apiKey,
      '#required' => TRUE,
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['generate_key'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate API Key'),
      '#submit' => ['::generateKey'],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('ce_google_docs.settings')
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function generateKey(array &$form, FormStateInterface $form_state) {
    $_SESSION['api_key'] = hash("sha256", rand());
  }

}
