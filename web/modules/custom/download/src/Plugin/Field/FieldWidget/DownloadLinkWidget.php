<?php

namespace Drupal\download\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;

/**
 * Widget for download.
 *
 * @FieldWidget(
 *  id = "download_link_widget",
 *  label = @Translation("Field selector"),
 *  field_types = {"download_link"}
 * )
 */
class DownloadLinkWidget extends WidgetBase implements WidgetInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = (!$items[$delta]->isEmpty()) ? $items[$delta]->get('download_fields')->getValue() : [];
    $default_label = ($items[$delta]->get('download_label')->getValue() != '') ? $items[$delta]->get('download_label')->getValue() : '';
    $default_value = is_array($value) ? $value : unserialize($value);

    $widget = $element;
    $widget['#delta'] = $delta;

    $entity = $items->getEntity();
    $options = [];
    foreach ($entity->getFields() as $field_name => $field) {
      if ($field instanceof FileFieldItemList) {
        $options[$field_name] = $field->getFieldDefinition()->getLabel();
      }
    }

    $element += [
      '#type' => 'fieldset',
      'download_label' => [
        '#title' => $this->t('Download label'),
        '#type' => 'textfield',
        '#default_value' => $default_label,
      ],
      'download_fields' => [
        '#title' => $this->t('Download Fields'),
        '#type' => 'checkboxes',
        '#options' => $options,
        '#default_value' => $default_value,
      ],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {

    // Serialize download fields.
    foreach ($values as $delta => $fields) {
      $values[$delta]['download_fields'] = serialize($fields['download_fields']);
    }

    return $values;
  }

}
