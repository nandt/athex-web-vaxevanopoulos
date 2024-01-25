<?php

namespace Drupal\athex_d_products\Controller;

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

	public function render($product_type, $product_id) {
		$type = ProductType::fromValue($product_type);

		if (!$type) {
			throw new NotFoundHttpException();
		}

		$page = new ProductPage([
			'product_type' => 'stock',
			'product_id' => $product_id
		]);

		return $page->render();
	}
}
