<?php

namespace Drupal\athex_hermes\AthexModel;


/**
 * <wsdl:message name="addHermesSubmissionRequest1">
 *     <wsdl:part name="mapLanguageIds" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="titleMapValues" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="content" type="xsd:string"/>
 *     <wsdl:part name="submissionType" type="xsd:string"/>
 *     <wsdl:part name="companyId" type="xsd:long"/>
 *     <wsdl:part name="alfrescoUUID" type="xsd:string"/>
 *     <wsdl:part name="oldAlfrescoUUID" type="xsd:string"/>
 *     <wsdl:part name="fileNames" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="mimeTypes" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="attachmentsLangs" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="attachmentDescriptions" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="fileURLs" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="vocabularies" type="impl:ArrayOf_tns6_AssetVocabularySoap"/>
 *     <wsdl:part name="categories" type="impl:ArrayOf_tns6_AssetCategorySoap"/>
 *     <wsdl:part name="properties" type="impl:ArrayOfArrayOf_xsd_string"/>
 *     <wsdl:part name="tagNames" type="impl:ArrayOf_xsd_string"/>
 *     <wsdl:part name="displayDateTimestamp" type="xsd:long"/>
 * </wsdl:message>
 */
class AddSubmissionRq extends HermesRequest {

	/**
	 * πχ "en_US","el_GR"
	 */
	public array $mapLanguageIds;

	/**
	 * οι τίτλοι του άρθρου σε ελληνικά, αγγλικά
	 * έρχεται με την σειρά που δείχνει το mapLanguageIds
	 */
	public array $titleMapValues;

	/**
	 * το html περιεχόμενο(το περιεχόμενο περιλαμβάνει και τις δύο γλώσσες στο ίδιο string), που εσωκλείεται μέσα σε xml
	 */
	public string $content;

	/**
	 * πχ (Financial Statement, Announcement)
	 */
	public string $submissionType;

	/**
	 * Το ΣΣΠ id της εταιρείας
	 */
	public int $companyId;

	/**
	 * το AlfrescoUUID της υποβολής (χρειάζεται να την έχει το liferay για χρήση σε πιθανή ορθή επανάληψη)
	 */
	public string $alfrescoUUID;

	/**
	 * για ορθή επανάληψη, στέλνεται το AlfrescoUUID της προς αντικατάστασης υποβολής, ώστε να ξέρει το liferay τι να πανογραψει.
	 */
	public ?string $oldAlfrescoUUID;

	/**
	 * τα ονόματα των πιθανών attachments που μπορεί να έχει κάποια ανακοινωση
	 */
	public ?array $fileNames;

	/**
	 * τα mimeTypes των πιθανών attachments που μπορεί να έχει κάποια ανακοινωση
	 */
	public ?array $mimeTypes;

	/**
	 * @deprecated
	 * η γλώσσα που είναι γραμμένη το περιεχόμενο των πιθανών attachments που μπορεί να έχει κάποια ανακοινωση
	 */
	public ?array $attachmentsLangs;

	/**
	 * η περιγραφή των πιθανών attachments που μπορεί να έχει κάποια ανακοινωση
	 */
	public ?array $attachmentDescriptions;

	/**
	 * τα urls στο alfresco των πιθανών attachments που μπορεί να έχει κάποια ανακοινωση, ώστε το liferay να  μπορεί να τα ανακτήσει όταν κληθεί η addHermesSubmission
	 */
	public ?array $fileURLs;

	/**
	 * τα liferay vocabularies της υποβολής (πχ Company, FiscalYear …)
	 */
	public array $vocabularies;

	/**
	 * η liferay κατηγοριοποίηση με βάση τα παραπάνω vocabularies πχ η εταιρεία που έκανε την υποβολή ή πχ το οικ. Ετος για την οποία έγινε
	 */
	public array $categories;

	/**
	 * ιδιότητες των κατηγοριών πχ για την εταιρεία μπορεί να δηλωθεί το σσπ id sspCompanyId:21
	 */
	public array $properties;

	/**
	 * Liferay tags
	 */
	public array $tagNames;

	/**
	 * αναφέρεται στο ποια ημερομηνία θα αποθηκευτεί στο Liferay (πχ ημ/νια και ώρα πρωτοκόλλησης του εγγράφου, ημ/νια και ώρα δημιουργίας του αρχείου στο liferay …).
	 */
	public ?int $displayDateTimestamp;

}

/*
// Testing Code

require_once 'HermesRequest.php';

$test = new AddSubmissionRq("{
	\"mapLanguageIds\": [\"en_US\",\"el_GR\"],
	\"titleMapValues\": [\"Title in English\",\"Τίτλος στα Ελληνικά\"],
	\"content\": \"<xml>Content in English</xml><xml>Περιεχόμενο στα Ελληνικά</xml>\",
	\"submissionType\": \"Financial Statement\",
	\"companyId\": 10154,
	\"alfrescoUUID\": \"123456\",
	\"oldAlfrescoUUID\": \"123456\",
	\"fileNames\": [\"file1\",\"file2\"],
	\"mimeTypes\": [\"application/pdf\",\"application/pdf\"],
	\"attachmentsLangs\": [\"en_US\",\"el_GR\"],
	\"attachmentDescriptions\": [\"Description in English\",\"Περιγραφή στα Ελληνικά\"],
	\"fileURLs\": [\"http://file1\",\"http://file2\"],
	\"vocabularies\": [{\"name\":\"Company\",\"values\":[\"Company1\",\"Company2\"]}],
	\"categories\": [{\"name\":\"FiscalYear\",\"values\":[\"2019\",\"2020\"]}],
	\"properties\": [[\"sspCompanyId:21\"],[\"sspCompanyId:21\"]],
	\"tagNames\": [\"tag1\",\"tag2\"],
	\"displayDateTimestamp\": 1612137600
}");

print_r($test->titleMapValues);
echo "\n";

/**/
