<?php

class Contact extends BasicObject{

	private $profile 	= NULL;
	private $email 		= NULL;
	private $address 	= NULL;
	private $isReply 	= NULL;
	private $phone 		= NULL;
	private $name  		= NULL;
	private $type		= NULL;

	function convertToDB() {
		$dbobj ['contactId'] 			= $this->id ? 		$this->id			: '';//id
		$dbobj ['contactName'] 			= $this->name ? 	$this->name			: '';//name
		$dbobj ['contactProfile'] 		= $this->profile ? 	$this->profile		: '';//profile
		$dbobj ['contactEmail'] 		= $this->email ?  	$this->email		: '';//email
		$dbobj ['contactTitle'] 		= $this->title ?	$this->title		: '';//title
		$dbobj ['contactContent'] 		= $this->content ? 	$this->content		: '';//message
		$dbobj ['contactType'] 			= $this->type ? 	$this->type 		: '';//type
		$dbobj ['contactStatus'] 		= $this->status ? 	$this->status			: '';//status
		$dbobj ['contactPostDate'] 		= $this->postdate ? $this->postdate			: '';//postdate
		$dbobj ['contactIsReply'] 		= $this->isReply ?  $this->isReply			: '';//reply
		
		return $dbobj;
	}

	function convertToObject($object) {
		global $vsMenu;
		$this->setId ( $object ['contactId'] ? 		$object ['contactId']		: '');
		$this->setTitle ( $object ['contactTitle'] ? $object ['contactTitle']			: '');
		$this->setType ( $object ['contactType'] ? 	$object ['contactType']		: '');
		$this->setContent ( $object ['contactContent'] ? $object ['contactContent']		: '');
		$this->setEmail ( $object ['contactEmail'] ? 	$object ['contactEmail']		: '');
		$this->setProfile ( $object ['contactProfile'] ? $object ['contactProfile']		: '');
		$this->setStatus ( $object ['contactStatus'] ? 	$object ['contactStatus']	: '');
		$this->setPostDate ( $object ['contactPostDate'] ? $object ['contactPostDate']	: '');
		$this->setIsReply ( $object ['contactIsReply'] ? $object ['contactIsReply']		: '');
		$this->setName ( $object ['contactName'] ? 		$object ['contactName']	: '');
		$this->setPhone ( $object ['contactPhone'] ? 	$object ['contactPhone']		: '');
	}

	function __construct(){
		parent::__construct();
	}
	
	function validate() {
		$status = true;
		return $status;
	}
	
	function __destruct() {
		parent::__destruct ();
		unset ( $this->profile );
		unset ( $this->email );
		unset ( $this->isReply );
		unset ( $this->address );
		unset ( $this->phone );
		unset ( $this->name );
		unset ( $this->type );
	}
	
	public function setType($type) {
		$this->type = $type;
	}

	
	public function getType() {
		return $this->type;
	}


	public function setName($name) {
		$this->name = $name;
	}


	public function getName() {
		return $this->name;
	}


	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function getPhone() {
		return $this->phone;
	}


	public function setIsReply($isReply) {
		$this->isReply = $isReply;
	}


	public function setAddress($address) {
		$this->address = $address;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setProfile($profile) {
		$this->profile = $profile;
	}

	public function getIsReply() {
		return $this->isReply;
	}

	public function getAddress() {
		return $this->address;
	}


	public function getEmail() {
		return $this->email;
	}


	public function getProfile() {
		return $this->profile;
	}
}
?>