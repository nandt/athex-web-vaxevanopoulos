<?php

namespace Drupal\download\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Download link field formatter.
 *
 * @FieldFormatter(
 *  id = "download_link_formatter",
 *  label = @Translation("Download link formatter"),
 *  field_types = {"download_link"}
 * )
 */
class DownloadLinkFormatter extends FormatterBase {

  use StringTranslationTrait;

  /**
   * The Stream Wrapper Manager.
   *
   * @var Drupal\Core\StreamWrapper\StreamWrapperManager
   */
  protected $streamWrapperManage;

  /**
   * Constructs services for fieldformatter.
   *
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManager $stream_wrapper_manage
   *   The stream wrapper manager.
   */
  public function __construct(StreamWrapperManager $stream_wrapper_manage) {
    $this->streamWrapperManage = $stream_wrapper_manage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('stream_wrapper_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $output = [];
    $entity = $items->getEntity();

    foreach ($items as $delta => $item) {
      $element = [];
      $element['container'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['download_link'],
        ],
      ];
      $valid_file_found = FALSE;
      $fname = NULL;
      if ($item->download_fields) {
        $fields = unserialize($item->download_fields);

        foreach ($fields as $fieldname) {
          $files = $entity->{$fieldname};
          if ($files instanceof FieldItemListInterface && !$files->isEmpty()) {
            foreach ($files as $file) {
              $fileEntity = $file->entity;
              $uri = $fileEntity->getFileUri();
              if ($this->streamWrapperManage->isValidUri($uri)) {
                $valid_file_found = TRUE;
                $fname = $items->getName();
              }
            }
          }
        }
      }
      if ($valid_file_found) {
        $element['container']['value'] = [
          '#type'   => 'link',
          '#title'    => $item->get('download_label')->getValue(),
          '#url' => Url::fromRoute('download.download', [
            'bundle' => $entity->bundle(),
            'entity_type' => $entity->getEntityTypeId(),
            'fieldname' => $fname,
            'entity_id' => $entity->id(),
            'delta' => $delta,
          ]),
        ];

        $output[$delta] = $element;
      }
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // $settings = $this->getSettings();
    $summary[] = $this->t('Displays a link to download all files in selected fields.');

    return $summary;
  }

}
