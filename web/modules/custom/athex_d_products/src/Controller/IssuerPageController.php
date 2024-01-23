<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\athex_d_products\ProductType;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\athex_d_products\Service\IssuerDataService;
use Drupal\athex_d_products\Service\ProductPageLayoutService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IssuerPageController extends ControllerBase {

	protected $layout;
	protected $data;

	public function __construct(
		ProductPageLayoutService $layout,
		IssuerDataService $data
	) {
		$this->layout = $layout;
		$this->data = $data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.layout'),
			$container->get('athex_d_products.issuer_data')
		);
	}

	public function render($product_type, $product_id) {
		$type = ProductType::fromValue($product_type);

		if (!$type) {
			throw new NotFoundHttpException();
		}

		return $this->layout->render($type, $product_id, [
			[
				'#type' => 'container',
				$this->layout->h2('Profile Section 1'),
				// ...
			]
			// ...
		]);
	}
}
