<?php

namespace Drupal\monsido_tools\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class MonsidoSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'monsido_tools.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'monsido_tools_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    //dump($config->get('enable'));die;

    $form['enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Monsido'),
      '#description' => $this->t('Active this field to tracking your website using Monsido.<br><b>Remember to check your domain settings!</b>'),
      '#default_value' => $config->get('enable'),
      //'#options' => [0 => $this->t('Disable'), 1 => $this->t('Enable')],
    ];

    // todo: Require fields only when the field enable is true.

    $form['group'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Settings'),
      '#states' => [
        'visible' => [
          ':input[name=enable]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['group']['token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Token'),
      '#default_value' => $config->get('token'),
      //'#required' => TRUE,
      '#states' => [
        'required' => [
          [':input[name=enable]' => ['value' => 1]],
        ],
      ],
      '#size' => 40,
    ];

    $form['group']['enable_statistics'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Statistics'),
      '#default_value' => $config->get('enable_statistics'),
    ];

    $form['group']['enable_cookieless_tracking'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Cookieless Tracking'),
      '#default_value' => $config->get('enable_cookieless_tracking'),
    ];

    $form['group']['enable_document_tracking'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Document Tracking'),
      '#default_value' => $config->get('enable_document_tracking'),
      '#description' => $this->t('More options available soon.'),
    ];

    $form['group']['enable_document_tracking_class'] = [
      '#disabled' => TRUE,
      '#type' => 'textfield',
      '#title' => $this->t('Document Class'),
      '#default_value' => $config->get('enable_document_tracking_class'),
      '#description' => $this->t('Search for links, that are associated with class names and contains below mentioned class names.'),
      '#states' => [
        'visible' => [
          ':input[name="enable_document_tracking"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['group']['enable_document_tracking_ignore_class'] = [
      '#disabled' => TRUE,
      '#type' => 'textfield',
      '#title' => $this->t('Document Ignore Class'),
      '#default_value' => $config->get('enable_document_tracking_ignore_class'),
      '#description' => $this->t('Exclude links that contains class name.'),
      '#states' => [
        'visible' => [
          ':input[name="enable_document_tracking"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['group']['add_page_assist_script'] = [
      '#type' => 'radios',
      '#title' => $this->t('Add PageAssist script'),
      '#default_value' => $config->get('add_page_assist_script'),
      '#options' => [1 => $this->t('Version 1'), 2 => $this->t('Version 2')],
      '#description' => $this->t('More options available soon for Version 2.'),
      '#required' => TRUE,
      '#states' => [
        'required' => [
          [':input[name=enable]' => ['value' => 1]],
        ],
      ],
    ];

    $form['group']['add_pagecorrect'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add PageCorrect'),
      '#default_value' => $config->get('add_pagecorrect'),
    ];

    $form['group']['add_heatmaps'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add Heatmaps'),
      '#default_value' => $config->get('add_heatmaps'),
    ];

    $form['group']['add_monsido_consent_manager'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add Monsido Consent Manager'),
      '#default_value' => $config->get('add_monsido_consent_manager'),
      '#description' => $this->t('More options available soon.'),
    ];

    /*$form['group']['hr'] = [
      '#type' => 'markup',
      '#markup' => '<hr width="500" />',
    ];*/

    $form['visibility'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Visibility'),
      '#states' => [
        'visible' => [
          ':input[name=enable]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['visibility']['only_front_end'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable script only on the <i>front-end</i>'),
      '#default_value' => $config->get('only_front_end'),
      '#description' => $this->t('Only checks admin routes, more options for visibility available soon.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    //dump($form_state);die;
    $this->configFactory->getEditable(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('enable', $form_state->getValue('enable'))
      ->set('token', $form_state->getValue('token'))
      ->set('add_page_assist_script', $form_state->getValue('add_page_assist_script'))
      ->set('enable_statistics', $form_state->getValue('enable_statistics'))
      ->set('enable_cookieless_tracking', $form_state->getValue('enable_cookieless_tracking'))
      ->set('enable_document_tracking', $form_state->getValue('enable_document_tracking'))
      ->set('add_page_assist_script', $form_state->getValue('add_page_assist_script'))
      ->set('add_pagecorrect', $form_state->getValue('add_pagecorrect'))
      ->set('add_heatmaps', $form_state->getValue('add_heatmaps'))
      ->set('add_monsido_consent_manager', $form_state->getValue('add_monsido_consent_manager'))
      ->set('only_front_end', $form_state->getValue('only_front_end'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
