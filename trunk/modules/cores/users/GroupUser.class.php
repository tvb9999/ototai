<?php
class GroupUser extends BasicObject{
	private $permission = NULL;


	function __construct() {
		parent::__construct();
	}
	/**
	 *
	 */
	public function __destruct() {
		parent::__destruct();
		unset($this->permission);
	}


	/**
	 * validate if GroupAdmin object is valid
	 * @return void
	 */
	function validate() {
		$status = true;
		if ($this->title == "") {
			$this->message .= "Group Title can't be left blank";
			$status = false;
		}
		return $status;
	}
	/**
	 * change GroupAdmin object to array to insert database
	 * @return array $dbobj
	 *
	 */
	function convertToDB() {
		$dbobj ['groupId'] 			= $this->id 					? $this->id 					: 0;
		$dbobj ['groupTitle'] 		= $this->title 					? $this->title					: '';
		$dbobj ['groupIntro'] 		= $this->intro 					? $this->intro 					: '';
		$dbobj ['groupPermission'] 	= serialize($this->permission) 	? serialize($this->permission)	: '';
		
		return $dbobj;
	}
	/**
	 * change GroupAdmin from database object to GroupAdmin object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	function convertToObject($object) {
		$this->setId 			( $object ['groupId']  			? $object ['groupId']			: 0);
		$this->setTitle 		( $object ['groupTitle']  		? $object ['groupTitle']		: '');
		$this->setIntro 		( $object ['groupIntro']  		? $object ['groupIntro']		: '');
		$this->setPermissions	( $object ['groupPermission'] 	? $object ['groupPermission']	: '');
	}
	/**
	 * delete a permission of group
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
	public function setPermissions($permissions) {
		$this->permission = $permissions;
	}

	/**
	 * get all Permissions of this GroupAdmin
	 *
	 * @return array $this->permission
	 */
	public function getPermissions() {
		return $this->permission;
	}

	/**
	 * get Permission of GroupAdmin
	 *
	 * @param string $path
	 * @return boolean
	 */
	public function getPermission($path="") {
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
}

?>