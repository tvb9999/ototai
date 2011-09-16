<?php
/*
+-----------------------------------------------------------------------------
|   VIET SOLUTION SJC  base on IPB Code version 3.0.0
|	Author: tongnguyen
|	Start Date: 5/04/2009
|	Finish Date: 11/04/2009
|	moduleName Description: This module is for management all languages in system.
+-----------------------------------------------------------------------------
*/

class Order extends BasicObject{
	private $name 	= NULL;
	private $email 	= NULL;
	public $message= NULL;
	private $userId = NULL;
	private $address = NULL;
	private $phone	=	NULL;
	private $info = NULL;
	
	public function convertToDB() {
		isset ( $this->id ) 		? ($dbobj ['orderId'] 		= $this->id) : '';
		isset ( $this->name ) 		? ($dbobj ['orderName'] 	= $this->name) : '';
		isset ( $this->address ) 	? ($dbobj ['orderAddress'] 	= $this->address) : '';
		isset ( $this->email ) 		? ($dbobj ['orderEmail'] 	= $this->email) : "";
		isset ( $this->message )	? ($dbobj ['orderMessage'] 	= $this->message) : "";
		isset ( $this->postdate ) 	? ($dbobj ['orderTime'] 		= $this->postdate) 			: '';
		isset ( $this->info ) 		? ($dbobj ['orderInfo'] 		= serialize($this->info)) 			: '';
		isset ( $this->phone ) 		? ($dbobj ['orderPhone'] 		= $this->phone) 			: '';
		isset ( $this->userId )	? ($dbobj ['userId'] 	= $this->userId) : "";
		return $dbobj;
	}
	function convertToObject($object) {
		isset ( $object ['orderId'] ) 		? $this->setId ( $object ['orderId'] ) 			: '';
		isset ( $object ['orderName'] ) 	? $this->setName( $object ['orderName'] ) 		: '';
		isset ( $object ['orderAddress'] ) 	? $this->setAddress ( $object ['orderAddress'] ): '';
		isset ( $object ['orderEmail'] ) 	? $this->setEmail ( $object ['orderEmail'] ) 	: '';
		isset ( $object ['orderMessage'] ) 	? $this->setMessage ( $object ['orderMessage'] ): '';
		isset ( $object ['userId'] ) 		? $this->setUserId ( $object ['userId'] ): '';
		isset ( $object ['orderTime'] ) 	? $this->setPostDate ( $object ['orderTime'] ): '';
		isset ( $object ['orderPhone'] ) 	? $this->setPhone ( $object ['orderPhone'] ): '';
		isset ( $object ['orderInfo'] ) 	? $this->setInfo ( unserialize($object ['orderInfo'] )): '';
	}
	/**
	 * @return the $name
	 */
	/**
	 * @return the $address
	 */
	/**
	 * @return the $info
	 */
	public function getInfo() {
		return $this->info;
	}

	/**
	 * @param $info the $info to set
	 */
	public function setInfo($info) {
		$this->info = $info;
	}

	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param $address the $address to set
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @return the $fax
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return the $note
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @return the $message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return the $userId
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @param $name the $name to set
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param $phone the $phone to set
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @param $fax the $fax to set
	 */
	public function setFax($fax) {
		$this->fax = $fax;
	}

	/**
	 * @param $email the $email to set
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param $note the $note to set
	 */
	public function setNote($note) {
		$this->note = $note;
	}

	/**
	 * @param $message the $message to set
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * @param $userId the $userId to set
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}

	
}
?>