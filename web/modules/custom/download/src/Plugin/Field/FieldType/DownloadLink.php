<?php

namespace Drupal\download\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides field type DownloadLink.
 *
 * @FieldType(
 *   id = "download_link",
 *   label = @Translation("Download Link"),
 *   module = "download",
 *   category = @Translation("Reference"),
 *   description = @Translation("Provides a link to download all files from one or more fields"),
 *   default_formatter = "download_link_formatter",
 *   default_widget = "download_link_widget"
 * )
 */
class DownloadLink extends FieldItemBase implements FieldItemInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'download_fields' => [
          'type' => 'text',
          'size' => 'small',
          'not null' => FALSE,
        ],
        'download_label' => [
          'type' => 'text',
          'size' => 'small',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['download_fields'] = DataDefinition::create('string')->setLabel(t('Download Link'));
    $properties['download_label'] = DataDefinition::create('string')->setLabel(t('Text to display'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'download_filename' => 'all_files.zip',
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $form['download_filename'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Filename for the download'),
      '#description' => $this->t('Please do NOT include the .zip extension'),
      '#default_value' => $this->getSetting('download_filename'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('download_fields')->getValue();
    return $value === NULL || $value === '';
  }

}
