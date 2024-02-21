<?php

namespace Drupal\athex_sis\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;

class SisDbDataService
{

	protected $logger;
	protected $config;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		ConfigFactoryInterface        $configFactory
	)
	{
		$this->logger = $loggerFactory->get('athex_sis');
		$this->config = $configFactory->get('athex_sis.settings');
	}

	private function getConnection()
	{
		return oci_connect(
			$this->config->get('username'),
			$this->config->get('password'),
			$this->config->get('connection_string')
		);
	}

	private function exec($sql)
	{
		$sql = oci_parse($this->getConnection(), $sql);
		oci_execute($sql);
		return $sql;
	}


	public function fetchAllWithParams($sql, array $params = [])
	{
		$connection = $this->getConnection();
		$stmt = oci_parse($connection, $sql);

		foreach ($params as $param => $value) {
			oci_bind_by_name($stmt, $param, $value);
		}

		oci_execute($stmt);

		$data = [];
		try {
			$connection = $this->getConnection();
			$stmt = oci_parse($connection, $sql);

			foreach ($params as $param => $value) {
				oci_bind_by_name($stmt, ltrim($param, ':'), $params[$param]);
			}
			oci_execute($stmt);

			while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
				$data[] = $row;
			}
		}

		catch (\Exception|\Error $e) {
			$this->logger->warning("Simulating empty response due to error:\n" . $e->getMessage());
		}

		return $data;
	}
}

