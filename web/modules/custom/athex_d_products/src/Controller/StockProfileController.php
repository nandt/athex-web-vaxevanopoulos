<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

	public function render($company_id) {
		// throw new NotFoundHttpException();

		return $this->layout->render($company_id, [
		]);
	}
}
