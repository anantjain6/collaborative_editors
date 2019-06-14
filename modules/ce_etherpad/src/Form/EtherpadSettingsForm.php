<?php

namespace Drupal\ce_etherpad\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EtherpadSettingsForm.
 */
class EtherpadSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ce_etherpad.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ce_etherpad_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ce_etherpad.settings');

    $apiUrl = $config->get('etherpad_api_url');
    if(!isset($apiUrl)  || trim($apiUrl) === '')
      $apiUrl = 'http://localhost:9001';

    $form['etherpad_api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Etherpad API URL'),
      '#description' => $this->t('Enter Etherpad API URL. By default it is http://localhost:9001'),
      '#default_value' => $apiUrl,
    ];

    $form['etherpad_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Etherpad API Key'),
      '#description' => $this->t('Enter Etherpad API Key. You can find API Key in APIKEY.txt on root directory of Etherpad.'),
      '#default_value' => $config->get('etherpad_api_key'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('ce_etherpad.settings')
      ->set('etherpad_api_url', $form_state->getValue('etherpad_api_url'))
      ->set('etherpad_api_key', $form_state->getValue('etherpad_api_key'))
      ->save();
  }

}
