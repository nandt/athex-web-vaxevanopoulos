<?php

namespace Drupal\athex_sis\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;

class SisDbDataService {

	protected $logger;
	protected $config;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		ConfigFactoryInterface $configFactory
	) {
		$this->logger = $loggerFactory->get('athex_sis');
		$this->config = $configFactory->get('athex_sis.settings');
  	}

	private function getConnection() {
		return oci_connect(
			$this->config->get('username'),
			$this->config->get('password'),
			$this->config->get('connection_string')
		);
	}

	private function exec($sql) {
		$sql = oci_parse($this->getConnection(), $sql);
		oci_execute($sql);
		return $sql;
	}

	/*public function fetchAll(
		$cmd,
		$offset = 0,
		$limit = -1,
		&$rc = null,
		$flags = OCI_FETCHSTATEMENT_BY_ROW | OCI_ASSOC
	) {
		$cmd = $this->exec($cmd);
		$res = [];
		$rc = oci_fetch_all($cmd, $res, $offset, $limit, $flags);
		return $res;
	}*/

	public function fetchAllWithParams($sql, array $params = []) {
		// Debugging lines:
		$this->logger->debug('SQL: ' . $sql);
		$this->logger->debug('Params: ' . print_r($params, true));
		// end debugging

		$data = [];

		try {
			$connection = $this->getConnection();
			$stmt = oci_parse($connection, $sql);

			foreach ($params as $param => $value) {
				//echo "\nParam: $param";  //debug
				//echo "\nValue: $value";  //debug

				oci_bind_by_name($stmt, ltrim($param, ':'), $params[$param]); // Remove leading colon in parameter placeholder
			}
			oci_execute($stmt);

			while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
				$data[] = $row;
			}
		}
		catch (\Exception $e) {
			$this->logger->warning("Simulating empty response due to error:\n" . $e->getMessage());
		}

		return $data;
	}
}
