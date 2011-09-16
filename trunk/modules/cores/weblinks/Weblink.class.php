<?php
class Weblink extends BasicObject{
	private $address 	= NULL;
	private $fileId 	= NULL;
	private $website 	= NULL;

	function __construct() {
		parent::__construct();
	}
	
	public function convertToDB() {
		isset ( $this->id ) 			? ($dbobj ['weblinkId'] 		= $this->id) 				: '';
		isset ( $this->catId ) 			? ($dbobj ['weblinkCatId'] 		= $this->catId) 			: '';
		isset ( $this->title ) 			? ($dbobj ['weblinkTitle'] 		= $this->title) 			: '';
		isset ( $this->address ) 		? ($dbobj ['weblinkAddress'] 	= $this->address) 			: '';
		isset ( $this->website ) 		? ($dbobj ['weblinkWebsite'] 	= $this->website) 			: '';
		isset ( $this->fileId ) 		? ($dbobj ['weblinkFileId'] 	= $this->fileId) 			: '';
		isset ( $this->index ) 			? ($dbobj ['weblinkIndex'] 		= $this->index) 			: '';
		isset ( $this->status ) 		? ($dbobj ['weblinkStatus'] 	= $this->status) 			: '';
		return $dbobj;
	}

	function convertToObject($object) {
		isset ( $object ['weblinkId'] ) 		? $this->setId ( $object ['weblinkId'] ) 				: '';
		isset ( $object ['weblinkCatId'] ) 		? $this->setCatId ( $object ['weblinkCatId'] ) 			: '';
		isset ( $object ['weblinkTitle'] ) 		? $this->setTitle ( $object ['weblinkTitle'] ) 			: '';
		isset ( $object ['weblinkAddress'] ) 	? $this->setAddress( $object ['weblinkAddress'] ) 		: '';
		isset ( $object ['weblinkWebsite'] ) 	? $this->setWebsite( $object ['weblinkWebsite'] ) 		: '';		
		isset ( $object ['weblinkFileId'] ) 	? $this->setFileId( $object ['weblinkFileId'] ) 		: '';
		isset ( $object ['weblinkIndex'] ) 		? $this->setIndex( $object ['weblinkIndex'] ) 			: '';
		isset ( $object ['weblinkStatus'] ) 	? $this->setStatus ( $object ['weblinkStatus'] ) 		: '';
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress($address) {
		$this->address = $address;
	}
	
	public function getFileId() {
		return $this->fileId;
	}
	
	public function setFileId($fileId) {
		$this->fileId = $fileId;
	}

	public function getWebsite() {
		return "http://".str_replace("http://","",$this->website);
	}	

	public function setWebsite($website) {
		$this->website = $website;
	}

	function __destruct() {
		unset ( $this );
	}
	
}