<?php

namespace Drupal\athex_d_products;

enum ProductType: string {
	case STOCK = 'stocks';
	case BOND = 'bonds';
	case WARRANT = 'warrants';

    public static function fromValue(string $name) {
        foreach (self::cases() as $type) {
            if ($name === $type->value) {
                return $type;
            }
        }
		return null;
	}
}
