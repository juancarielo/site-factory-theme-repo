<?php

namespace Drupal\endroid_qr_code\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form that configures forms module settings.
 */
class QRCodeConfigurationForm extends ConfigFormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    return $instance;
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'endroid_qr_code_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('endroid_qr_code.settings');

    $form['set_size'] = [
      '#type' => 'range',
      '#title' => $this->t('QR Size'),
      '#default_value' => $config->get('set_size'),
      '#min' => 100,
      '#max' => 1000,
      '#step' => 100,
    ];
    $form['set_margin'] = [
      '#type' => 'range',
      '#title' => $this->t('QR Margin'),
      '#default_value' => $config->get('set_margin'),
      '#min' => 0,
      '#max' => 200,
      '#step' => 5,
    ];
    $form['logo_file'] = [
      '#type' => 'managed_file',
      '#title' => 'Logo',
      '#description' => $this->t('Logo to be used within QR code.'),
      '#default_value' => [$config->get('logo_file')],
      '#upload_location' => 'public://'
    ];
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => 'Label',
      '#description' => $this->t('Label for QR code.'),
      '#default_value' => [$config->get('label')]
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    // Set file status to permanent.
    $config = $this->config('endroid_qr_code.settings')
      ->set('set_size', (int) $values['set_size'])
      ->set('set_margin', (int) $values['set_margin'])
      ->set('label', $values['label']);
    $image = $form_state->getValue('logo_file');
    if ($image) {
      $file = $this->entityTypeManager->getStorage('file')->load($image[0]);
      $file->setPermanent();
      $file->save();
      $config->set('logo_file', $file->id());
    }
    else {
      $config->set('logo_file', '');
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'endroid_qr_code.settings',
    ];
  }

}
