<?php
class UserSession {
	private $code 	= NULL;
	private $id 	= NULL;
	private $userId = NULL;
	private $userStatus = NULL;
	private $time 	= NULL;

	function __construct() {
	}

	function __destruct() {
		unset($this->id);
		unset($this->code);
		unset($this->time);
		unset($this->userId);
		unset($this->userStatus);
	}
	/**
	 * change Admin object to array to insert database
	 * @return array $dbobj
	 */
	function convertToDB() {
		isset ( $this->id ) 		? ($dbobj ['sessionId'] 	= $this->id) 		: '';
		isset ( $this->code ) 		? ($dbobj ['sessionCode'] 	= $this->code) 		: '';
		isset ( $this->time ) 		? ($dbobj ['sessionTime'] 	= $this->time) 		: '';
		isset ( $this->userId ) 	? ($dbobj ['userId'] 		= $this->userId) 	: '';
		isset ( $this->userStatus ) ? ($dbobj ['userStatus'] 	= $this->userStatus): '';
		return $dbobj;
	}

	/**
	 * change Admin from database object to Admin object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	function convertToObject($object) {
		isset ( $object ['sessionId'] ) 	? $this->setId ( $object ['sessionId'] ) 		: '';
		isset ( $object ['sessionCode'] ) 	? $this->setCode ( $object ['sessionCode'] ) 	: '';
		isset ( $object ['sessionTime'] ) 	? $this->setTime( $object ['sessionTime'] ) 	: '';
		isset ( $object ['userId'] ) 		? $this->setUserId( $object ['userId'] ) 		: '';
		isset ( $object ['userStatus'] ) 	? $this->setUserStatus( $object ['userStatus'] ): '';
	}
	/**
	 * get the Name of AdminSession class
	 *
	 * @return string $this->code of AdminSession class
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * get the Description of AdminSession class
	 *
	 * @return string $this->password of AdminSession class
	 */
	/**
	 * @return the $userId
	 */
	/**
	 * @return the $userStatus
	 */
	public function getUserStatus() {
		return $this->userStatus;
	}

	/**
	 * @param $userStatus the $userStatus to set
	 */
	public function setUserStatus($userStatus) {
		$this->userStatus = $userStatus;
	}

	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @param $userId the $userId to set
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function getId() {
		return $this->id;
	}

	/**
	 * get Last Login time of AdminSession class
	 * @return  int $this->lastLogin of AdminSession class
	 */
	public function getTime($format=null) {
		if($format)
		return VSFDateTime::GetDate($this->time,$format);
		return $this->time;
	}
		
	/**
	 * set the Name of AdminSession class
	 *
	 * @param string $code
	 */
	public function setId($id=0) {
		$this->id = intval($id);
	}

	/**
	 * set the Password of AdminSession class
	 *
	 * @param string $description
	 */
	public function setCode($code=null){
		$this->code = md5($code);
	}

	/**
	 * set the Last Login of the AdminSession class
	 * @param int $lastlogin
	 */

	public function setTime($time=0) {
		$this->time = intval($time);
	}
}

?>