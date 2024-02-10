<?php

namespace Drupal\athex_hermes\Service;

use Symfony\Component\Yaml\Yaml;

use Drupal\athex_hermes\AthexData\SubmissionNodeData;
use Drupal\node\Entity\Node;


class NodeUpdateService {

	private function getConfig(string $type) {
		return Yaml::parseFile(join(DIRECTORY_SEPARATOR, [
			\Drupal::service('extension.path.resolver')->getPath('module', 'athex_hermes'),
			'athex-cfg',
			'node-processing.yml'
		]))[$type];
	}

	/**
	 * Deletes nodes with old Alfresco UUIDs.
	 */
	private function deleteAlfrescoOld(string $oldAlfrescoUUID) {
		if (!isset($oldAlfrescoUUID)) return;

		$uids = \Drupal::entityQuery('node')
			->condition('field_alfrescoUUID', $oldAlfrescoUUID, '=')
			->execute();

		$itemsToDelete = \Drupal::entityTypeManager()->getStorage('node')
			->loadMultiple($uids);

		foreach ($itemsToDelete as $item)
			$item->delete();
	}

	/**
	 * Prepares node data for insertion.
	 */
	private function prepNodeData(array $nodeData, string $type) {
		$config = $this->getConfig($type);

		foreach ($nodeData as $idx => $obj) {
			$nodeConfig = [
				'type' => $config['bundle'],
				'langcode' => $obj['langcode']
			];

			foreach ($config['fields'] as $field => $key)
				$nodeConfig[$field] = $obj[$key];

			$nodeData[$idx] = $nodeConfig;
		}

		return $nodeData;
	}

	/**
	 * Add/Update node based on the given object.
	 */
	public function update(SubmissionNodeData $nodeData) {
		$type = end(explode('\\', get_class($nodeData)));
		$nodeData = $nodeData->getFinalNodeData();
		if (empty($nodeData)) return;

		$this->deleteAlfrescoOld($nodeData[0]['oldAlfrescoUUID']);
		$nodeData = $this->prepNodeData($nodeData, $type);

		$masterNode = Node::create($nodeData[0]);
		for ($i = 1; $i < count($nodeData); $i++) {
			$data = $nodeData[$i];
			$masterNode->addTranslation($data['langcode'], $data);
		}

		$masterNode->save();

		return $masterNode;
	}

}
