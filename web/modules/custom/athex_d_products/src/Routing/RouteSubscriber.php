<?php

namespace Drupal\athex_d_products\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RoutingEvents;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase
{

	protected function alterRoutes(RouteCollection $collection)
	{
// Fetch available product search plugins
		$pluginManager = \Drupal::service('plugin.manager.product_search');
		$pluginDefinitions = $pluginManager->getDefinitions();

// Iterate over each plugin to create a route
		foreach ($pluginDefinitions as $pluginId => $pluginDefinition) {
			$path = '/market-data/instruments/' . $pluginId;
			$route = new \Symfony\Component\Routing\Route(
				$path,
				[
					'_controller' => '\Drupal\athex_d_products\Controller\StockSearchController::render',
					'productType' => $pluginId, // Pass the plugin ID as a parameter to the controller
				],
				[
					'_permission' => 'access content',
				]
			);
			\Drupal::logger('route subcriber')->notice('Adding route for: ' . $pluginId);

// Add the route to the collection with a unique name
			$collection->add('athex_d_products.' . $pluginId . '_search', $route);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array {
		$events[RoutingEvents::ALTER][] = ['onAlterRoutes', -100];
		return $events;
	}

}
