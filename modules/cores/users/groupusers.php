<?php
require_once(CORE_PATH."/users/GroupUser.class.php");

class groupusers extends VSFObject {
	public $obj;
	protected $relTableName 	="";
	/**
	 * @return the $relTableName
	 */
	function getRelTableName() {
		return $this->relTableName;
	}

	/**
	 * @param $relTableName the $relTableName to set
	 */
	function setRelTableName($relTableName) {
		$this->relTableName = $relTableName;
	}

	function __construct(){
		parent::__construct();
		$this->primaryField 	= 'groupId';
		$this->basicClassName 	= 'GroupUser';
		$this->tableName 		= 'usergroup';
		$this->relTableName 	= "user_group";
		$this->getObjectsByCondition();
		$this->obj = $this->createBasicObject();
	}
	function getGroupById($id) {
		if (!intval($id))
		return $this->obj;
		return $this->arrayObj[$id];
	}
}
?>
