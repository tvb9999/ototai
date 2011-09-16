<?php
global $vsStd;
require_once (CORE_PATH . "users/User.class.php");
require_once (CORE_PATH . "users/sessions.php");
require_once (CORE_PATH . "users/groupusers.php");

class users extends VSFObject {
	public $sessions;
	public $obj;
	public $groupusers;
	protected $relTableName = "";

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

	/**
	 * constructor
	 *
	 */
	function __construct() {
		parent::__construct ();
		
		$this->primaryField = 'userId';
		$this->basicClassName = 'User';
		$this->tableName = 'user';
		$this->relTableName = "user_group";
		$this->sessions = new usersessions ();
		$this->groupusers = new groupusers ();
		$this->obj = $this->createBasicObject ();
	
	}

	function checkUserPermission($permission = array()) {
		global $bw, $vsUser, $vsLang;
		
		$this->result ['status'] = true;
		$bw->input ['action'] = $bw->input ['action'] ? $bw->input ['action'] : 'default';
		
		// If this admin is in root groups
		// return true to allow all actions
		$userPermission = array ();
		
		foreach ( $vsUser->user->getGroups () as $group ) {
			if (is_array ( $group->getPermission () ))
				$userPermission = array_merge ( $group->getPermissions (), $userPermission );
		}
		
		$thisPath = $bw->input ['module'] . "/" . $bw->input ['action'];
		
		// If the module require to authorize for this action isset($permission[1][$thisPath])
		// And if this user is not allowed for this permission $userPermission[$thisPath]
		// return false
		if (! $userPermission [$thisPath] && isset ( $permission [1] [$bw->input ['action']] )) {
			$this->result ['status'] = false;
			$this->result ['message'] = sprintf ( $vsLang->getWords ( 'global_permission_denied', "<b>Permission denied!</b> You don't have permission to access this function.<br />Please contact your root administrators to allow the setting <b>'%s'</b> in <b>'%s'</b>" ), $permission [1] [$bw->input [action]], $permission [0] );
		}
	}

	function updateStatus($ids, $status) {
		$this->setCondition ( "userId IN (" . $ids . ")" );
		return $this->updateObjectByCondition ( $status );
	}

	function checkRoot($user = null) {
		global $bw, $vsUser;
		
		if (! is_object ( $user ))
			$user = $vsUser->obj;
		$arrGroup = $user->getGroups ();
		
		if (! count ( $arrGroup ) and is_array ( $arrGroup ))
			return false;
		if (array_key_exists ( $bw->vars ['root_admin_groups'], $arrGroup ))
			return true;
		return false;
	}

	function authorize() {
		global $DB, $bw, $vsModule, $vsUser, $vsSettings;
		$this->sessions->deleteSession ();
		if (! $vsUser->obj->getId ()) {
			$vsModule->obj->setClass ( 'users' ); // No Admin session
			$arrayAccess = array ('login-process','forgot-password-form', 'resgister', 'login-form' );
			if (! in_array ( $bw->input [1], $arrayAccess )) {
				$bw->input [2] = "nosession";
				$bw->input [1] = "login-form";
				unset ( $_SESSION [APPLICATION_TYPE] ['obj'] );
				unset ( $_SESSION [APPLICATION_TYPE] ['session'] );
				unset ( $_SESSION [APPLICATION_TYPE] ['groups'] );
			}
		} else {
			$this->sessions->setCondition ( "sessionCode='" . $vsUser->sessions->obj->getCode () . "'" );
			$this->sessions->getObjectsByCondition ();
			
			if (! $this->sessions->result ['status']) {
				$vsModule->obj->setClass ( 'users' ); // Admin session time out
				$bw->input [2] = "timeout";
				$bw->input [1] = "login-form";
				$vsUser->obj->__destruct ();
				unset ( $_SESSION [APPLICATION_TYPE] ['obj'] );
				unset ( $_SESSION [APPLICATION_TYPE] ['session'] );
				unset ( $_SESSION [APPLICATION_TYPE] ['groups'] );
			} else {
				$vsUser->sessions->obj->setTime ( time () );
				$vsUser->sessions->updateLoginSession ();
			}
		}
	}
	
	function loginCookie(){
		global $vsStd;
		if($vsStd->my_getcookie('rsuserId') and $vsStd->my_getcookie('rspassword')){
			$this->setCondition ( "userId ={$vsStd->my_getcookie('rsuserId')} and userPassword = '{$vsStd->my_getcookie('rspassword')}'" );
			$this->getOneObjectsByCondition ();
			$this->sessions->updateLoginSession ( $this->module->obj );
			$_SESSION [APPLICATION_TYPE] ['obj'] = $this->obj->convertToDB ();
		}
	}
	
}
?>