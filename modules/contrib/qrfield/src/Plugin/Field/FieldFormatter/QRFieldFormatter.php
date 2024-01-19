<?php

namespace Drupal\qrfield\Plugin\Field\FieldFormatter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of 'qrfield_formatter'.
 *
 * This is the default implementation used for displaying QR code image.
 *
 * @FieldFormatter(
 *   id = "qrfield_formatter",
 *   label = @Translation("QR code"),
 *   field_types = {
 *      "qrfield"
 *   }
 * )
 */
class QRFieldFormatter extends QRFieldFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'display_text' => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $elements['display_text'] = [
      '#title' => $this->t('Display QR image text'),
      '#type' => 'checkbox',
      '#description' => $this->t('Shows text encoded in QR code.'),
      '#default_value' => $this->getSetting('display_text'),
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return [
      'display_text' => $this->t('Display text: @value', [
        '@value' => $this->getSetting('display_text') ? $this->t('Yes') : $this->t('No'),
      ]),
    ] + parent::settingsSummary();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    if ($this->getSetting('display_text')) {
      foreach ($elements as $delta => $element) {
        $elements[$delta]['text'] = [
          '#markup' => $element['image']['#alt'] ?? $items[$delta]->get('text')->getValue(),
        ];
      }
    }
    return $elements;
  }

}
