<?php
class AdminSession {
	private $name = "";
	private $session = "";
	private $time = 0;

	function __construct() {
	}

	function __destruct() {
		unset($this->id);
		unset($this->name);
		unset($this->session);
		unset($this->time);
	}

	/**
	 * get the Name of AdminSession class
	 *
	 * @return string $this->name of AdminSession class
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * get the Description of AdminSession class
	 *
	 * @return string $this->password of AdminSession class
	 */
	public function getSession() {
		return $this->session;
	}

	/**
	 * get Last Login time of AdminSession class
	 * @return  int $this->lastLogin of AdminSession class
	 */
	public function getTime($isInt=true,$format="SHORT") {
		global $vsDateTime;

		if($isInt)
		return $this->time;

		return $vsDateTime->GetDate($this->time,$format);
	}
		
	/**
	 * set the Name of AdminSession class
	 *
	 * @param string $name
	 */
	public function setName($name="") {
		$this->name = $name;
	}

	/**
	 * set the Password of AdminSession class
	 *
	 * @param string $description
	 */
	public function setSession($session=0){
		$this->session = md5($session);
	}

	/**
	 * set the Last Login of the AdminSession class
	 * @param int $lastlogin
	 */

	public function setTime($time=0) {
		$this->time = intval($time);
	}

	/**
	 * change Admin object to array to insert database
	 * @return array $dbobj
	 *
	 */
	function convertToDB() {
		$dbobj = array (	'loginName' 	=> $this->name,
							'loginSession'	=> $this->session,
							'loginTime'		=> $this->time,
		);

		return $dbobj;
	}

	/**
	 * change Admin from database object to Admin object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	function convertToObject($dbobj) {
		$this->name		= $dbobj['loginName'];
		$this->session	= $dbobj['loginSession'];
		$this->time		= $dbobj['loginTime'];
	}
}

?>