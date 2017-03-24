<?php

namespace Drupal\showpad\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures settings.
 */
class AdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'showpad.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['credential'] = $this->buildCredentialForm();
    return parent::buildForm($form, $form_state);
  }

  /**
   * Build credential form.
   *
   * @return array
   *   Credential form.
   */
  private function buildCredentialForm() {
    $credential_settings = $this->config('showpad.settings')->get('credential');

    $form = [
      '#title' => t('Showpad Credentials'),
      '#type' => 'details',
      '#tree' => TRUE,
      "#open" => TRUE,
    ];

    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => t('Client ID'),
      '#description' => t('Your Showpad Client Id.'),
      '#default_value' => $credential_settings['client_id'],
      '#required' => TRUE,
    ];

    $form['client_secret'] = [
      '#type' => 'textfield',
      '#title' => t('Client Secret'),
      '#description' => t('Your Showpad Client Secret.'),
      '#default_value' => $credential_settings['client_secret'],
      '#required' => TRUE,
    ];

    $form['username'] = [
        '#type' => 'textfield',
        '#title' => t('Client Username'),
        '#description' => t('Your Showpad Username.'),
        '#default_value' => $credential_settings['username'],
        '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => t('Client Password'),
      '#description' => t('Your Showpad Password.'),
      '#default_value' => $credential_settings['password'],
      '#required' => TRUE,
    ];

    $form['api_url'] = [
      '#type' => 'textfield',
      '#title' => t('Api Url'),
      '#description' => t('Your Showpad Api Url.'),
      '#default_value' => $credential_settings['api_url'],
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $settings = $this->config('showpad.settings');
    $values = $form_state->getValues();

    $this->setCredentialValues($settings, $values['credential']);

    $settings->save();

    // It is required to flush all caches on save. This is because many settings
    // here impact page caches and their invalidation strategies.
    drupal_flush_all_caches();

    parent::submitForm($form, $form_state);
  }

  /**
   * Set credential values.
   *
   * @param \Drupal\Core\Config\Config $settings
   *   Showpad config settings.
   * @param array $values
   *   Credential values.
   */
  private function setCredentialValues(Config $settings, array $values) {
    $settings->set('credential.client_id', trim($values['client_id']));
    $settings->set('credential.client_secret', trim($values['client_secret']));
    $settings->set('credential.api_url', trim($values['api_url']));
    $settings->set('credential.username', trim($values['username']));
    $settings->set('credential.password', trim($values['password']));
  }
}
