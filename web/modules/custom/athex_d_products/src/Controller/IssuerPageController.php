<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\athex_d_mde\AthexRendering\PropertyTable;
use Drupal\athex_d_products\AthexRendering\ProductPage;
use Drupal\athex_d_products\ProductType;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\athex_d_products\Service\IssuerDataService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IssuerPageController extends ControllerBase {

	protected $data;

	public function __construct(
		IssuerDataService $data
	) {
		$this->data = $data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.issuer_data')
		);
	}

	private function dummyReplicate($data) {
		$result = [];
		for ($i = 0; $i < 6; $i++) {
			$result[] = $data;
		}
		return $result;
	}

	private function renderIssuerInfoTable() {
		return (new PropertyTable(
			[
				'IssuerInfoTableKey' => 'IssuerInfoTableENLabel'
			],
			[
				'IssuerInfoTableKey' => ['IssuerInfoTableValue']
			]
		))->render();
	}

	private function renderContactInfoTable() {
		return (new PropertyTable(
			[
				'ContactInfoTableKey' => 'ContactInfoTableENLabel'
			],
			[
				'ContactInfoTableKey' => ['ContactInfoTableValue']
			]
		))->render();
	}

	private function renderDirectorBoardTable() {
		return [
			'#type' => 'container',
			(new DataTable(
				[
					[ 'field' => 'name',	'label' => 'Name' ],
					[ 'field' => 'title',	'label' => 'Title' ]
				],
				$this->dummyReplicate([
					'name' => 'John Doe',
					'weight' => 'President'
				])
			))->render(),
			[
				'#type' => 'container',
				'#attributes' => [ 'class' => ['athex-table-footer'] ],
				[
					'#type' => 'html_tag',
					'#tag' => 'span',
					'#value' => $this->t('Data for the last 15 days')
				],
				[
					'#type' => 'html_tag',
					'#tag' => 'button',
					'#value' => $this->t('View All')
				]
			]
		];
	}

	public function render($product_type, $product_id) {
		$type = ProductType::fromValue($product_type);

		if (!$type) {
			throw new NotFoundHttpException();
		}

		$page = new ProductPage([
			'product_type' => 'stocks',
			'product_id' => $product_id
		]);

		$page->addCol('Issuer Information', $this->renderIssuerInfoTable());
		$page->addCol('Contact Information', $this->renderContactInfoTable());
		$page->addCol('Board Of Directors', $this->renderDirectorBoardTable());

		// $page->addCol(null, $raiseCapitalBlock);

		return $page->render();
	}
}
