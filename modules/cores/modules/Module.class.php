<?php
class Module extends BasicObject{
	/**
	 * change menu to array modules to insert database
	 * @return array $dbobj
	 *
	 */
	public function convertToDB() {
		isset ( $this->title ) 		? ($dbobj ['moduleTitle'] 		= $this->title) 		: '';
		isset ( $this->class ) 		? ($dbobj ['moduleClass'] 		= trim($this->class)) 	: '';
		isset ( $this->admin ) 		? ($dbobj ['moduleIsAdmin'] 	= $this->admin) 		: '';
		isset ( $this->user ) 		? ($dbobj ['moduleIsUser'] 		= $this->user) 			: '';
		isset ( $this->intro )		? ($dbobj ['moduleIntro'] 		= $this->intro) 		: '';
		isset ( $this->virtual )	? ($dbobj ['moduleVirtual'] 	= $this->virtual) 		: '';
		isset ( $this->version ) 	? ($dbobj ['moduleVersion'] 	= str_replace(".","",$this->version)) 	: '';
		return $dbobj;
	}
	/**
	 * change menu from database object to Module object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	function convertToObject($object) {
		global $vsMenu;
		isset ( $object ['moduleId'] ) 		? $this->setId 		( $object ['moduleId'] ) 		: '';
		isset ( $object ['moduleTitle'] ) 	? $this->setTitle 	( $object ['moduleTitle'] ) 	: '';
		isset ( $object ['moduleClass'] ) 	? $this->setClass 	( $object ['moduleClass'] ) 	: '';
		isset ( $object ['moduleIsAdmin'] ) ? $this->setAdmin 	( $object ['moduleIsAdmin'] ) 	: '';
		isset ( $object ['moduleIsUser'] ) 	? $this->setUser 	( $object ['moduleIsUser'] ) 	: '';
		isset ( $object ['moduleIntro'] )	? $this->setIntro 	( $object ['moduleIntro'] ) 	: '';
		isset ( $object ['moduleVirtual'] )	? $this->setVirtual ( $object ['moduleVirtual'] ) 	: '';
		isset ( $object ['moduleVersion'] ) ? $this->setVersion ( intval($object['moduleVersion'][0]).".".intval($object['moduleVersion'][1]).".".intval($object['moduleVersion'][2]).".".intval($object['moduleVersion'][3]) ) : $this->setVersion (0);
	}
	function validate() {
		global $DB, $vsLang;

		$status = true;
		$this->message = "";
		if($this->getTitle() == "") {
			$status = false;
			$this->message .= $vsLang->getWords('err_module_name_blank',"Module name can't be left blank!<br>");
		}
		if($this->getClass() == "") {
			$status = false;
			$this->message .= $vsLang->getWords('err_module_action_blank',"Module action can't be left blank!<br>");
		}
		return $status;
	}
	private $class 			= NULL;
	private $admin 			= NULL;
	private $user 			= NULL;
	private $version 		= NULL;
	private $installed 		= NULL;
	private $virtual 		= NULL;

	/**
	 * @return the $virtual
	 */
	public function getVirtual() {
		return $this->virtual;
	}

	/**
	 * @param $virtual the $virtual to set
	 */
	public function setVirtual($virtual) {
		$this->virtual = $virtual;
	}

	function __construct(){
		parent::__construct();
	}


	function __destruct(){
		unset($this->class);
		unset($this->admin);
		unset($this->user);
		unset($this->version);
	}

	/**
	 * @return unknown
	 */
	public function getInstalled() {
		return $this->installed;
	}

	/**
	 * @param unknown_type $installed
	 */
	public function setInstalled($installed=-1) {
		$this->installed = $installed;
	}

	/**
	 * @return unknown
	 */
	public function getAdmin() {
		return $this->admin;
	}

	/**
	 * @return unknown
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @return unknown
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * get Version of the Module object
	 * @return assign version to Module object
	 *
	 */
	public function getVersion() {
		return $this->version;
	}


	public function setVersion($version = "") {
		$this->version = $version;
	}

	/**
	 * @param unknown_type $admin
	 */
	public function setAdmin($admin) {
		$this->admin = $admin;
	}

	/**
	 * @param unknown_type $class
	 */
	public function setClass($class) {
		$this->class = strtolower($class);
	}

	/**
	 * @param unknown_type $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

}
?>