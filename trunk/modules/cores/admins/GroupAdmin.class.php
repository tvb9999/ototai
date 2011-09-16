<?php
class GroupAdmin extends BasicObject{
	private $name 		= NULL;
	private $permission = NULL;
	private $admins 	= NULL;
	private $parentId 	= NULL;

	function __construct() {
		parent::__construct();
	}

	function __destruct() {
		unset($this->id);
		unset($this->name);
		unset($this->parentId);
		unset($this->permission);
		unset($this->intro);
		unset($this->admin);
	}

	/**
	 * delete a permission of group
	 *
	 * @param unknown_type $path
	 */
	public function resetPermission() {
		$this->permission = array();
	}

	/**
	 * set all Permission for GroupAdmin
	 *
	 * @param array $permissions
	 */
	/**
	 * @return the $parentId
	 */
	public function getParentId() {
		return $this->parentId;
	}

	/**
	 * @param $parentId the $parentId to set
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;
	}

	public function setPermissions($permissions) {
		$this->permission = $permissions;
	}

	/**
	 * get all Permissions of this GroupAdmin
	 *
	 * @return array $this->permission
	 */
	public function getPermissions() {
		if(!is_array($this->permission))$this->permission=unserialize($this->permission);
		return $this->permission;
	}

	/**
	 * get Permission of GroupAdmin
	 *
	 * @param string $path
	 * @return boolean
	 */
	public function getPermission($path="") {
		if(!is_array($this->permission))$this->permission=unserialize($this->permission);
		return $this->permission[$path];
	}
	/**
	 * set Permission for GroupAdmin
	 *
	 * @param string $path
	 * @param boolean $access
	 */
	public function setPermission($path="",$access=false) {
		$this->permission[$path] = $access;
	}

	/**
	 * set array objects of Admin class to $this->admins of GroupAdmin class
	 *
	 * @param array objects $this->admins of GroupAdmin class
	 */
	public function setAdmins($admins = array()) {
		$this->admins = $admins;
	}

	/**
	 * get array object of  Admin class
	 *
	 * @return array object of Admin class
	 */
	public function getAdmins() {
		return $this->admins;
	}


	/**
	 * get the Name of GroupAdmin class
	 *
	 * @return string $this->name of GroupAdmin class
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * set the Name of GroupAdmin class
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}



	/**
	 * change GroupAdmin object to array to insert database
	 * @return array $dbobj
	 *
	 */

	function convertToDB() {
		isset ( $this->id ) 		? ($dbobj ['groupId'] 			= $this->id) 					: '';
		isset ( $this->name ) 		? ($dbobj ['groupName'] 		= $this->name) 					: '';
		isset ( $this->intro ) 		? ($dbobj ['groupIntro'] 		= $this->intro) 				: '';
		isset ( $this->parentId ) 	? ($dbobj ['groupParentId'] 	= $this->parentId) 				: '';
		isset ( $this->permission)	? ($dbobj ['groupPermission'] 	= serialize($this->permission)) : '';
		return $dbobj;
	}

	/**
	 * change GroupAdmin from database object to GroupAdmin object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */

	function convertToObject($object) {
		isset ( $object ['groupId'] ) 			? $this->setId ( $object ['groupId'] ) 				: '';
		isset ( $object ['groupName'] ) 		? $this->setName ( $object ['groupName'] ) 			: '';
		isset ( $object ['groupIntro'] ) 		? $this->setIntro ( $object ['groupIntro'] ) 		: '';
		isset ( $object ['groupParentId'] ) 	? $this->setParentId ( $object ['groupParentId'] ) 	: '';
		isset ( $object ['groupPermission'] ) 	? $this->setPermissions( $object['groupPermission']): '';
	}
}

?>