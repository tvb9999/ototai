<?php
class UserSession {
	private $id = "";
	private $userId = "";
	private $isOnline = "";
	private $session = "";
	private $time = 0;

	function __construct() {
	}

	function __destruct() {
		unset($this->id);
		unset($this->userId);
		unset($this->session);
		unset($this->isOnline);
		unset($this->time);
	}

	public function getPrimary() {
		return 'userLoginId';
	}

	public function getUserId() {
		return $this->userId;
	}

	public function getId() {
		return $this->id;
	}

	public function getSession() {
		return $this->session;
	}

	public function getIsOnline() {
		return $this->isOnline;
	}

	public function getTime($isInt=true,$format="SHORT") {
		global $vsDateTime;

		if($isInt)
		return $this->time;

		return $vsDateTime->GetDate($this->time,$format);
	}

	public function setUserId($userId="") {
		$this->userId = $userId;
	}

	public function setId($Id) {
		$this->id = intval($Id);
	}

	public function setIsOnline($isOnline="") {
		$this->isOnline = $isOnline;
	}

	public function setSession($session=0) {
		$this->session = md5($session);
	}

	public function setTime($time=0) {
		$this->time = intval($time);
	}

	function convertToDB() {
		$dbobj = array (	'userLoginSession' 	=> $this->id,
							'userId'			=> $this->userId,
							'userIsOnline'		=> $this->isOnline,
							'userLoginTime'		=> $this->time,
							'userLoginSession'	=> $this->session,
		);

		return $dbobj;
	}

	public function convertElementToDB() {
		$this->session!=0	? ($dbobj['userLoginSession'] = $this->session ) : '';
		$this->userId >=0 	? ($dbobj['userId'] = $this->userId) : '';
		$this->isOnline     ? ($dbobj['userIsOnline'] = $this->isOnline) : '';
		$this->time        	? ($dbobj['userLoginTime'] = $this->time) : '';
		return $dbobj;
	}

	function convertToObject($dbobj) {
		$this->id		= $dbobj['userLoginId'];
		$this->session	= $dbobj['userLoginSession'];
		$this->isOnline	= $dbobj['userIsOnline'];
		$this->time		= $dbobj['userLoginTime'];
		$this->userId	= $dbobj['userId'];
	}
}
?>