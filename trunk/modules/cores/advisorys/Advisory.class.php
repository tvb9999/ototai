<?php
/****
 * mordified by Sangpm
 * start time:10h am date 13-05-2010
 * end date  :
 * don't erase this info
 * he he
 * ****/
class Advisory extends BasicObject{

	private $email 		= NULL;
	private $address 	= NULL;
	private $phone 		= NULL;
	private $type		= NULL;
	private $name		= NULL;


	function __construct(){
		parent::__construct();
	  
	}
	
	function __destruct() {
		parent::__destruct ();
		unset ( $this->email );
		unset ( $this->address );
		unset ( $this->phone );
		unset ( $this->type );
		unset ( $this->name);
		
	}
	
	function getStatus($type=null) {
		global $bw;
		if(!$type)
			return $this->status;
		if($type=="image"){
			$imgArray = array('disabled.png', 'enable.png','home.png');			
			return $this->status = "<img src='{$bw->vars ['img_url']}/{$imgArray[$this->getStatus()]}' alt='{$this->getStatus()}' />";
		}
		if($type=="text")
			return $this->status?"Hiển thị":"Ẩn";
	}
	/**
	 * @param $type the $type to set
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param $phone the $phone to set
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @param $address the $address to set
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * @param $email the $email to set
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @return the $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	




	public function convertToDB() {
		isset ( $this->catId ) 			? ($dbobj ['advisoryCatId'] 		= $this->catId) 			: '';//id
		isset ( $this->title ) 			? ($dbobj ['advisoryTitle'] 		= $this->title) 			: '';//name
		isset ( $this->content ) 		? ($dbobj ['advisoryContent'] 		= $this->content) 			: '';//message
		isset ( $this->status ) 		? ($dbobj ['advisoryStatus'] 		= $this->status) 			: '';//status
		isset ( $this->email ) 			? ($dbobj ['advisoryEmail'] 		= $this->email) 			: '';//email
		isset ( $this->postdate ) 		? ($dbobj ['advisoryPostDate'] 		= $this->postdate) 			: '';//add time
		isset ( $this->phone ) 			? ($dbobj ['advisoryPhone'] 		= $this->phone) 			: '';//TITLE
		isset ( $this->address ) 		? ($dbobj ['advisoryAddress'] 		= $this->address) 			: '';//TITLE
		isset ( $this->intro ) 			? ($dbobj ['advisoryIntro'] 		= $this->intro) 			: '';//TITLE
		isset ( $this->catId ) 			? ($dbobj ['advisoryCatId'] 		= $this->catId) 			: '';//TITLE
		isset ( $this->name ) 			? ($dbobj ['advisoryName'] 			= $this->name) 				: '';//TITLE
		isset ( $this->index ) 			? ($dbobj ['advisoryIndex'] 			= $this->index) 				: 0;//TITLE
		return $dbobj;
	}

	function convertToObject($object) {
		global $vsMenu;
		$object ['advisoryCatId']  	? $this->setCatId ( $object ['advisoryCatId'] ) 		: 0;
		$object ['advisoryId'] 		? $this->setId ( $object ['advisoryId'] ) 				: 0;
		$object ['advisoryTitle']  	? $this->setTitle ( $object ['advisoryTitle'] ) 		: '';
		$object ['advisoryContent']  	? $this->setContent ( $object ['advisoryContent'] ) 	: '';
		$object ['advisoryEmail']  	? $this->setEmail ( $object ['advisoryEmail'] ) 		: '';
		$object ['advisoryStatus']  	? $this->setStatus ( $object ['advisoryStatus'] ) 		: 0;
		$object ['advisoryPostDate']  	? $this->setPostDate ( $object ['advisoryPostDate'] ) 	: '';
		$object ['advisoryAddress']  	? $this->setAddress ( $object ['advisoryAddress'] ) 	: '';
		$object ['advisoryPhone']  	? $this->setPhone ( $object ['advisoryPhone'] ) 		: '';
		$object ['advisoryIntro']  	? $this->setIntro( $object ['advisoryIntro'] ) 			: '';
		$object ['advisoryCatId']  	? $this->setCatId ( $object ['advisoryCatId'] ) 		: '';
		$object ['advisoryName']  		? $this->setName ( $object ['advisoryName'] ) 			: '';
		$object ['advisoryIndex']  		? $this->setIndex ( $object ['advisoryIndex'] ) 			: 0;
	}
	/**
	 * @param $name the $name to set
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}


}
?>