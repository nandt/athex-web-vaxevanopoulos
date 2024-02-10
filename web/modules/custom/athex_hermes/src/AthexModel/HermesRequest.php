<?php

namespace Drupal\athex_hermes\AthexModel;

use Symfony\Component\HttpFoundation\Request;

// <wsdl:message name="addHermesSubmissionRequest1">
//     <wsdl:part name="mapLanguageIds" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="titleMapValues" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="content" type="xsd:string"/>
//     <wsdl:part name="submissionType" type="xsd:string"/>
//     <wsdl:part name="companyId" type="xsd:long"/>
//     <wsdl:part name="alfrescoUUID" type="xsd:string"/>
//     <wsdl:part name="oldAlfrescoUUID" type="xsd:string"/>
//     <wsdl:part name="fileNames" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="mimeTypes" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="attachmentsLangs" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="attachmentDescriptions" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="fileURLs" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="vocabularies" type="impl:ArrayOf_tns6_AssetVocabularySoap"/>
//     <wsdl:part name="categories" type="impl:ArrayOf_tns6_AssetCategorySoap"/>
//     <wsdl:part name="properties" type="impl:ArrayOfArrayOf_xsd_string"/>
//     <wsdl:part name="tagNames" type="impl:ArrayOf_xsd_string"/>
//     <wsdl:part name="displayDateTimestamp" type="xsd:long"/>
// </wsdl:message>

abstract class HermesRequest {

	// private function fromXml(string $xml) {
	// 	$doc = new \DOMDocument();
	// 	$doc->loadXML($xml);
	// 	$xpath = new \DOMXPath($doc);
	// 	$properties = get_object_vars($this);

	// 	foreach ($properties as $property => $value) {
	// 		$nodes = $xpath->query("//$property");
	// 		if ($nodes->length > 0) {
	// 			$this->{$property} = $nodes->item(0)->nodeValue;
	// 		}
	// 	}
	// }

	private function fromJson(string $json) {
		$data = json_decode($json, true);
		$properties = get_class_vars(get_class($this));
		foreach ($properties as $property => $value) {
			if (!isset($this->{$property})) {
				$this->{$property} = @$data[$property];
			}
		}
	}

	public function __construct(Request $request) {
		$this->fromJson($request->getContent());
	}
}
