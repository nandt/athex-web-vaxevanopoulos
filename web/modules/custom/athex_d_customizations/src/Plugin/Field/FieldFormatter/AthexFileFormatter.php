<?php

namespace Drupal\athex_d_customizations\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Download link field formatter.
 *
 * @FieldFormatter(
 *  id = "athex_file_formatter",
 *  label = @Translation("ATHEX File formatter"),
 *  field_types = {"file"}
 * )
 */
class AthexFileFormatter extends FormatterBase {
	/**
	 * {@inheritdoc}
	 */
	public function viewElements(FieldItemListInterface $items, $langcode) {
		$output = [];

		/** @var \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator */
		$file_url_generator = \Drupal::service('file_url_generator');

		foreach ($items as $delta => $item) {
			$x = $item->entity->getFileUri();
			$output[$delta] = [
				'#title' => end(explode('.', $x)),
				'#type' => 'link',
				'#url' => \Drupal\Core\Url::fromUri(
					$file_url_generator->generateAbsoluteString($x)
				)
			];
		}

		$output[0]['#prefix'] = $this->t('Download') . '<br/>';

		return $output;
	}
}
