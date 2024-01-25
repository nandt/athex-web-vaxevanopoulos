<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Drupal\athex_d_products\ProductType;


class ProductProfileController extends ControllerBase {

	public function render($product_type, $product_id) {
		$svc = [
			ProductType::STOCK->value => 'athex_d_products.stock_profile_rendering'
		][
			ProductType::fromValue($product_type)->value
		];

		if (!$svc)
			throw new NotFoundHttpException();

		return \Drupal::service($svc)->render($product_id);
	}
}
