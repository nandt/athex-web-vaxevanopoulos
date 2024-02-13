<?php

namespace Drupal\athex_hermes\AthexModel;


/**
 * <wsdl:message name="addHermesSubmissionAsFileRequest">
 *     <wsdl:part name="contents" type="xsd:base64Binary"/>
 *     <wsdl:part name="langIds" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="alfrescoUUID" type="xsd:string"/>
 *     <wsdl:part name="oldAlfrescoUUID" type="xsd:string"/>
 *     <wsdl:part name="title" type="xsd:string"/>
 *     <wsdl:part name="description" type="xsd:string"/>
 *     <wsdl:part name="mimeType" type="xsd:string"/>
 *     <wsdl:part name="vocabularies" type="impl:ArrayOf_tns6_AssetVocabularySoap"/>
 *     <wsdl:part name="categories" type="impl:ArrayOf_tns6_AssetCategorySoap"/>
 *     <wsdl:part name="properties" type="impl:ArrayOfArrayOf_xsd_string"/>
 *     <wsdl:part name="tagNames" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="sspCompanyId" type="xsd:long"/>
 *     <wsdl:part name="submissionType" type="xsd:string"/>
 *     <wsdl:part name="attachmentsAsZip" type="xsd:base64Binary"/>
 * </wsdl:message>
 */
class AddSubmissionFileRq extends HermesRequest {

	/**
	 * τα περιεχόμενα του αρχείου base64 encoded
	 */
	public string $contents;

	/**
	 * @deprecated
	 * πχ "en_US","el_GR"
	 */
	public array $langIds;

	/**
	 * το AlfrescoUUID της υποβολής (χρειάζεται να την έχει το liferay για χρήση σε πιθανή ορθή επανάληψη)
	 */
	public string $alfrescoUUID;

	/**
	 * για ορθή επανάληψη, στέλνεται το AlfrescoUUID της προς αντικατάστασης υποβολής, ώστε να ξέρει το liferay τι να πανογραψει.
	 */
	public string $oldAlfrescoUUID;

	/**
	 * o τίτλος του αρχείου
	 */
	public string $title;

	/**
	 * η περιγραφή
	 */
	public string $description;

	/**
	 * @deprecated
	 */
	public string $mimeType;

	/**
	 *
	 */
	public array $vocabularies;

	/**
	 *
	 */
	public array $categories;

	/**
	 *
	 */
	public array $properties;

	/**
	 *
	 */
	public array $tagNames;

	/**
	 * το ID της εταιρείας στο ΣΣΠ
	 */
	public int $sspCompanyId;

	/**
	 * πχ  Financial Report ESEF , Financial Statement in pdf Format, Prospectus
	 */
	public string $submissionType;

	/**
	 * πιθανά attachments της υποβολής σε zip format base64 encoded
	 */
	public string $attachmentsAsZip;

}
