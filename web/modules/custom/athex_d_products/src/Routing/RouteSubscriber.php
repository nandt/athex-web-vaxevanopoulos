<?php

namespace Drupal\athex_d_products\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RoutingEvents;
// Import the necessary classes for your constructor
use Drupal\athex_d_products\Plugin\ProductSearchManager;
use Psr\Log\LoggerInterface;


class RouteSubscriber extends RouteSubscriberBase {

	/**
	 * The product search plugin manager service.
	 *
	 * @var \Drupal\athex_d_products\Plugin\ProductSearchManager
	 */
	protected $pluginManager;

	/**
	 * The logger factory service.
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Constructs a new RouteSubscriber object.
	 *
	 * @param \Drupal\athex_d_products\Plugin\ProductSearchManager $productSearchManager
	 *  The product search plugin manager service.
	 * @param \Psr\Log\LoggerInterface $logger
	 *  The logger factory service.
	 */
	public function __construct(ProductSearchManager $productSearchManager, LoggerInterface $logger)
	{
		$this->pluginManager = $productSearchManager;
		$this->logger = $logger;
	}

	protected function alterRoutes(RouteCollection $collection)
	{
		// Removed \Drupal::service call
		$pluginDefinitions = $this->pluginManager->getDefinitions();

		// Iterate over each plugin to create a route
		foreach ($pluginDefinitions as $pluginId => $pluginDefinition) {
			\Drupal::logger('Processing plugin ID:')->notice('Processing plugin ID: ' . $pluginId);

			$path = '/market-data/instruments/' . $pluginId;
			\Drupal::logger('Registering path:')->notice('Registering path: ' . $path);
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
			\Drupal::logger('from subriber suggestion 2')->notice('Adding route for plugin ID: ' . $pluginId);
			// Removed \Drupal::logger call
			$this->logger->notice('Adding route for: ' . $pluginId);

			// Add the route to the collection with a unique name
			$collection->add('athex_d_products.' . $pluginId , $route);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array {
		$events[RoutingEvents::ALTER][] = ['onAlterRoutes', 1000];
		return $events;
	}

}
