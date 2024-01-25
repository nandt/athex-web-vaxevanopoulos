<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Drupal\athex_d_products\ProductType;
use Drupal\athex_d_products\Service\ProductPageLayoutService;
use Drupal\athex_d_products\Service\StockDataService;

class StockProfileController extends ControllerBase {

	protected $layout;
	protected $data;

	public function __construct(
		ProductPageLayoutService $layout,
		StockDataService $data
	) {
		$this->layout = $layout;
		$this->data = $data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.layout'),
			$container->get('athex_d_products.stock_data')
		);
	}

	public function render($product_id) {
		// throw new NotFoundHttpException();

		return $this->layout->render(ProductType::STOCK, $product_id, [
			[
				'#type' => 'container',
				$this->layout->h2('Snapshot'),
				// ...
			]
			// ...
		]);
	}
}
