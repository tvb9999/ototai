<?php
class Admin extends BasicObject {
	
	private $name = NULL;
	private $password = NULL;
	private $lastLogin = NULL;
	private $joinDate = NULL;
	private $groups = NULL;

	function __construct() {
		parent::__construct ();
	}

	function __destruct() {
		parent::__destruct ();
		unset ( $this->name );
		unset ( $this->password );
		unset ( $this->lastLogin );
		unset ( $this->joinDate );
	
	}

	public function addGroup($group) {
		if (! is_object ( $group ))
			throw new Exception ( "Parameter is not an Group object!" );
		
		$this->groups [$group->getId ()] = $group;
	}

	/**
	 * get array Groups object of GroupAdmin class
	 * @return array object $this->groups of Admin class
	 */
	public function getGroups() {
		return $this->groups;
	}

	public function getMainGroup() {
		if (! count ( $this->groups ))
			return new GroupAdmin ();
		return current ( $this->groups );
	}

	/**
	 * set Groups for Admin
	 * @param array object of GroupAdmin class
	 */
	public function setGroups($groups = array()) {
		$this->groups = $groups;
	}

	public function getName() {
		return $this->name;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getLastLogin($isInt = true, $format = "SHORT") {
		if ($isInt)
			return $this->lastLogin;
		return VSFDateTime::GetDate ( $this->lastLogin, $format );
	}

	public function getJoinDate($isInt = true, $format = 'SHORT') {
		global $vsDateTime;
		if ($isInt)
			return $this->joinDate;
		return VSFDateTime::getDate ( $this->joinDate, $format );
	}

	public function setName($name = "") {
		$this->name = $name;
	}

	public function setPassword($password) {
		$this->password = md5 ( $password . 'rs' );
	}

	public function setPasswordMd5($password) {
		$this->password = $password;
	}

	public function setLastLogin($lastLogin = 0) {
		$this->lastLogin = $lastLogin;
	}

	public function setJoinDate($joinDate = 0) {
		$this->joinDate = $joinDate;
	}

	function convertRelToDB($group) {
		$dbobj = array ('adminId' => $this->id, 'groupId' => $group->getId () );
		return $dbobj;
	}

	/**
	 * change Admin object to array to insert database
	 * @return array $dbobj
	 *
	 */
	function convertToDB() {
		isset ( $this->id ) ? ($dbobj ['userId'] = $this->id) : '';
		isset ( $this->password ) ? ($dbobj ['userPassword'] = $this->password) : '';
		isset ( $this->name ) ? ($dbobj ['userName'] = $this->name) : '';
		isset ( $this->lastLogin ) ? ($dbobj ['userLastLogin'] = $this->lastLogin) : '';
		isset ( $this->joinDate ) ? ($dbobj ['userJoinDate'] = $this->joinDate) : '';
		isset ( $this->status ) ? ($dbobj ['userStatus'] = $this->status) : 0;
		isset ( $this->index ) ? ($dbobj ['userIndex'] = $this->index) : '';
		$dbobj ['userType'] = 1;
	
		return $dbobj;
	}

	/**
	 * change user from database object to user object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	function convertToObject($object) {
		isset ( $object ['userId'] ) ? $this->setId ( $object ['userId'] ) : '';
		isset ( $object ['userName'] ) ? $this->setName ( $object ['userName'] ) : '';
		isset ( $object ['userPassword'] ) ? $this->password = $object ['userPassword'] : '';
		isset ( $object ['userLastLogin'] ) ? $this->setLastLogin ( $object ['userLastLogin'] ) : '';
		isset ( $object ['userJoinDate'] ) ? $this->setJoinDate ( $object ['userJoinDate'] ) : '';
		isset ( $object ['userStatus'] ) ? $this->setStatus ( $object ['userStatus'] ) : 0;
		isset ( $object ['userIndex'] ) ? $this->setIndex ( $object ['userIndex'] ) : '';
	}
}

?>