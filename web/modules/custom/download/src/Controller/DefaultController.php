<?php

namespace Drupal\download\Controller;

use PclZip;
use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Utility\Token;
use Drupal\field\Entity\FieldConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * Default controller for the download module.
 */
class DefaultController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The file_system service.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Drupal token service container.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * Constructs services for controllerr.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\File\FileSystem $file_system
   *   The file system.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Utility\Token $token
   *   The token.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    FileSystem $file_system,
    ModuleHandlerInterface $module_handler,
    Token $token
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->fileSystem = $file_system;
    $this->moduleHandler = $module_handler;
    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('file_system'),
      $container->get('module_handler'),
      $container->get('token')
    );
  }

  /**
   * Download_download method for controller.
   */
  public function downloadDownload($bundle, $entity_type, $fieldname, $entity_id, $delta) {

    $field_name = 'download';
    $files = [];
    $entity_storage = $this->entityTypeManager->getStorage($entity_type, [$entity_id]);
    $entity = $entity_storage->load($entity_id);

    if (!class_exists('PclZip')) {
      throw new ServiceUnavailableHttpException();
    }

    $instances = $entity->getFieldDefinitions();
    $filename = $entity_type . '-' . $entity_id . '-' . $delta;

    foreach ($instances as $instance) {
      if ($instance instanceof FieldConfig && $instance->getType() == 'download_link' && $instance->getName() == $fieldname) {
        $field_name = $instance->getName();

        if (!empty($instance->getSettings()['download_filename'])) {
          $filename = $this->downloadGetFilename($instance->getSettings()['download_filename'], $entity, $entity_type);
        }
      }
    }

    $fields = $entity->get($field_name);
    foreach ($fields as $field) {
      $fieldnames = $fields = unserialize($field->download_fields);
    }

    foreach ($fieldnames as $fieldname) {
      if ($fieldname) {
        foreach ($entity->get($fieldname) as $field_obj) {
          if ($field_obj instanceof EntityReferenceItem) {
            // @todo need to get the actual file entity somehow !!!
            $file_entity_info = $field_obj->getValue();
            $file_entity = $this->entityTypeManager->getStorage('file')->load($file_entity_info['target_id']);
            $files[] = $this->fileSystem->realpath($file_entity->getFileUri());
          }
        }
      }
    }

    $filename = $filename . '.zip';
    $tmp_file = file_save_data('', 'temporary://' . $filename);
    $tmp_file->status = 0;
    $tmp_file->save();
    $archive = new PclZip($this->fileSystem->realpath($tmp_file->getFileUri()));
    $archive->add($files, PCLZIP_OPT_REMOVE_ALL_PATH);

    $this->moduleHandler->invokeAll('download_download', [$files, $entity]);

    header("Content-Type: application/force-download");
    header('Content-Description: File Transfer');
    header('Content-Disposition: inline; filename=' . $filename);
    readfile($this->fileSystem->realpath($tmp_file->getFileUri()));
    exit();
  }

  /**
   * Function to getfilename.
   */
  private function downloadGetFilename($filename, $entity, $entity_type) {

    $fn = $this->token->replace(Html::escape($filename), [$entity_type => $entity]);
    $fn = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $fn);

    return $fn;
  }

}
