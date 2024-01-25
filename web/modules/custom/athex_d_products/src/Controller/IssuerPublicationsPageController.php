<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\athex_d_products\ProductType;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\athex_d_products\Service\ProductPageLayoutService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IssuerPublicationsPageController extends ControllerBase {

	protected $layout;

	public function __construct(
		ProductPageLayoutService $layout
	) {
		$this->layout = $layout;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.layout')
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
				$this->layout->h2('Publications Section 1'),
				// ...
			]
			// ...
		]);
	}
}
