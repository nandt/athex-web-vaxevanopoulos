<?php

namespace Drupal\athex_sis_integration\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;

class DbDataService {

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

	public function fetchAll(
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
	}
}
