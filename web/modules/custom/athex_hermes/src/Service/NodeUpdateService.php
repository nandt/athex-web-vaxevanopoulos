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
	 * Add/Update nodes based on the Alfresco UUIDs of the given object.
	 */
	public function alfrescoUpdate(SubmissionNodeData $nodeData) {
		$type = end(explode('\\', get_class($nodeData)));
		$nodeData = $nodeData->getFinalNodeData();
		if (empty($nodeData)) return;

		//TODO: skip if node(s) with the alfrescoUUID already exist

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

	private function setPropsFromArray(&$obj, array $props) {
		foreach ($props as $field => $value)
			$obj->set($field, $value);
	}

	/**
	 * Update the given node based on the given object.
	 */
	public function nodeUpdate(
		Node &$node, SubmissionNodeData $nodeData
	) {
		$type = end(explode('\\', get_class($nodeData)));
		$nodeData = $nodeData->getFinalNodeData();
		if (empty($nodeData)) return;

		$nodeData = $this->prepNodeData($nodeData, $type);
		$this->setPropsFromArray($node, $nodeData[0]);

		for ($i = 1; $i < count($nodeData); $i++) {
			$data = $nodeData[$i];
			$translation = $node->getTranslation($data['langcode']);
			if ($translation)
				$this->setPropsFromArray($translation, $data);
			else
				$node->addTranslation($data['langcode'], $data);
		}

		$node->save();
	}
}
