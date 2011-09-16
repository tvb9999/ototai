<?php
require_once(CORE_PATH."/admins/GroupAdmin.class.php");

class groupadmins extends VSFObject{
	public $obj = NULL;
	public $arrayGroup = NULL;

	/**
	 * constructor
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->primaryField = 'groupId';
		$this->tableName = 'admingroup';
		$this->basicClassName='GroupAdmin';
		$this->createBasicObject();
		$this->obj = &$this->basicObject;
		$this->arrayGroup  = $this->getObjectsByCondition();
	}
	/**
	 * destructor
	 */
	function __destruct() {
		parent::__destruct();
		unset($this->arrayGroup );
		unset($this->obj);
		unset($this->objObjSource);
		unset($this->result);
	}

	function getGroupById($id) {
		if (!intval($id))
		return $this->obj;
		return $this->arrayObj[$id];
	}

	/**
	 * delete GroupAdmin on database
	 * @return void
	 */
	function deleteGroup() {
		global $vsLang, $DB;
		$this->result ['status'] = true;
		$this->getGroupById($this->obj->getId());
		if (! $this->result ['status'])
		return;
		$this->deleteObjectById($this->obj->getId());
	}
	function deleteGroups($list) {
		global $vsLang, $DB;
		$this->result ['status'] = true;
		$this->setCondition("groupId in ({$list})");
		$this->deleteObjectByCondition();

	}

	/**
	 * validate if GroupAdmin object is valid
	 *
	 * @return void
	 */
	function validate() {
		global $vsLang;

		$this->result ['status'] = true;
		$this->result ['message'] = "";
		if ($this->obj->name == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->currentArrayWords['err_group_name_blank'];
		}
	}

}
?>
