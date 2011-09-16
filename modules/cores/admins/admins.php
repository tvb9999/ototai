<?php
require_once(CORE_PATH."admins/Admin.class.php");
require_once(CORE_PATH."admins/sessions.php");
require_once(CORE_PATH."admins/groupadmins.php");
class admins extends VSFObject {
	public $sessions;
	public $obj;
	public $groupadmins			=	NUll;
	protected $relTableName 	="";
	/**
	 * constructor
	 *
	 */
	function __construct(){
		parent::__construct();
		$this->primaryField = 'userId';
		$this->tableName = 'user';
		$this->basicClassName = 'Admin';
		$this->createBasicObject();
		$this->obj = &$this->basicObject;
		$this->relTableName = "admin_group";
		$this->sessions = new sessions();
		$this->groupadmins = new groupadmins();
		$this->obj = &$this->basicObject;
	}
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
	 * destructor
	 */
	function __destruct() {
		unset($this->obj);
	}

	function checkViewPermission($module="",$action="") {
		global $vsStd;
		$permFilePath = CORE_PATH .$module . "/" . $module .".perm.php";
		// $vsModule->obj->getClass() . "/" . $vsModule->obj->getClass() . ".perm.php";
		if($vsStd->requireFile($permFilePath)) {
			$permClass=$module."_perm";
			$permClass=new $permClass;
			if(method_exists($permClass,'getAdminPermission')) {
				return  $this->checkPermission($permClass->getAdminPermission(),$module,$action);
			}
		}
		return true;
	}
	public function checkPermission($permission=array(),$module="",$action="") {
		global $bw, $vsUser, $vsLang;
		$this->result['status'] = true;
		$action = $action?$action:'default';
		// If this admin is in root groups
		// return true to allow all actions
		$userPermission = array();
		if(count($vsUser->obj->getGroups()))
		foreach ($vsUser->obj->getGroups() as $group) {
			if($group->getId() == $bw->vars['root_admin_groups']) return true;
			if(is_array($group->getPermissions())){
				$userPermission = array_merge($group->getPermissions(), $userPermission);
			}
		}

		$thisPath = $module."/".$action;
		// If the module require to authorize for this action isset($permission[1][$thisPath])
		// And if this user is not allowed for this permission $userPermission[$thisPath]
		// return false
		if(!$userPermission[$thisPath] && isset($permission[1][$action]) ) {
			$this->result['status'] = false;
			$this->result['message'] = sprintf($vsLang->getWords('global_permission_denied',"<b>Cảnh báo truy cập!</b> Bạn không có quyền truy cập vào chức năng này.<br />Vui lòng liên hệ người quản trị để biết thêm thông tin. <br/> <b>'%s'</b> in <b>'%s'</b>"),$permission[1][$bw->input[action]],$permission[0]);
		}
		return $this->result['status'];
	}


	function loadAdmin() {
		global $vsLang, $vsUser,$vsPrint;
		$this->setCondition("userName='".$vsUser->obj->getName()."'");
		$this->getOneObjectsByCondition();

		// Return if there is no admin found
		if(!$this->result['status']) {
			$this->result['message'] = $vsLang->getWords('admin_no_username','Username does not exist!');
			return;
		}
		if(!$this->obj->getStatus()) {
			$this->result['status'] = false;
			$this->result['message'] = $vsLang->getWords('admin_account_disabled','Account is disabled!');
			return;
		}
		if($this->obj->getPassword()!=$vsUser->obj->getPassword()) {
			$this->result['status'] = false;
			$this->result['message'] = $vsLang->getWords('admin_wrong_password','Password is incorrect!');
			return;
		}
		$this->vsRelation->setObjectId($this->obj->getId());
		$this->vsRelation->setTableName($this->getRelTableName());
		$groupStr=$this->vsRelation->getRelByObject(1);
		if(!$groupStr)
		{
			$vsPrint->redirect_screen($vsLang->getWords('invalid_account','Tài khoản của bạn không hợp lệ vì chưa thuộc nhóm phân quyền nào'));
		}
		$array=$this->vsRelation->arrval;

		foreach ($array as $id => $group) {
			$this->obj->addGroup($this->groupadmins->getGroupById($id));
		}
		$vsUser->obj = $this->obj;
	}
	function getAdminWithGroup() {
		global $vsLang, $vsUser;
		$this->getOneObjectsByCondition();
		$this->vsRelation->setObjectId($this->obj->getId());
		$this->vsRelation->setTableName($this->getRelTableName());
		$array=$this->vsRelation->arrval;
		foreach ($array as $id => $group) {
			$this->obj->addGroup($this->groupadmins->getGroupById($id));
		}
	}
	/**
	 * delete Admin on database
	 * @return void
	 */
	function deleteAdmin() {
		global $vsLang, $DB;
		$this->deleteObjectById($this->obj->getId());
		// Delete the relationship
		$DB->simple_delete('admin_groups_user','userId='.$this->obj->getId());
		if(!$DB->simple_exec()) {
			$this->result ['status'] = false;
			$this->result ['message'] = $vsLang->getWords('admin_delete_rel','There was an error when delete admin relationship!');
			return;
		}
		$this->result ['message'] = $vsLang->getWords('admin_delete_success','You have successfully deleted an admin account name ').' <span style="color:red"> [ '.$this->obj->getName()." ]</span> ";
	}
	/**
	 * validate if Admin object is valid
	 *
	 * @return void
	 */
	function validate($checkPassword=true) {
		global $vsLang;

		$this->result ['status'] = true;
		$this->result ['message'] = "";

		if ($this->obj->getName() == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->currentArrayWords['err_admin_name_blank'];
		}

		if ($this->obj->getPassword() == "" && $checkPassword) {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->currentArrayWords['err_admin_password_blank'];
				
		}
		if($checkPassword&&$this->obj->getPassword()&&$this->obj->getName()){
			$managerObj=new admins();
			$managerObj->condition="userName='{$this->obj->getName()}'";
			$managerObj->getAllAdmin();
			if(count($managerObj->arrayAdmin)){
				$this->result ['status'] = false;
				$this->result ['message'] .= $vsLang->getWords('account_exits','this Account is exist in system');
			}
		}

		if(count($this->obj->getGroups()) < 1) {
			$this->result['status'] = false;
			$this->result['message'] .= $vsLang->currentArrayWords['err_admin_choose_group'];
		}
	}
	function getNormalGroup($user=null) {
		global $bw;
		foreach($this->groupadmins->arrayGroup as $group){
			if($group->getId()!=$bw->vars['root_admin_groups'])
			return $group;
		}
	}

	function checkRoot($user=null) {
		global $bw,$vsUser;
		if(!is_object($user))
			$user = $vsUser->obj;
		$arrGroup=$user->getGroups();
		if(!count($arrGroup) and is_array($arrGroup))
			return false;
		if(array_key_exists($bw->vars['root_admin_groups'],$arrGroup))
			return true;
		return false;
	}

	function authorizeAdmin() {
		global $vsPrint, $bw, $vsModule, $vsUser;
		$this->sessions->deleteSession();
			
		if(!$vsUser->obj->getId()) {
			$vsModule->obj->setClass('admins'); // No Admin session
			$vsModule->obj->setVirtual(0);
			if($bw->input['action'] != 'dologin') {
				$bw->input[2] = "nosession";
				$bw->input['action'] = "login";
				unset($_SESSION[APPLICATION_TYPE]['obj']);
				unset($_SESSION[APPLICATION_TYPE]['session']);
				unset($_SESSION[APPLICATION_TYPE]['groups']);
			}
		}
		else {
			$this->sessions->setCondition("sessionCode='".$vsUser->sessions->obj->getCode()."'");
			$this->sessions->getObjectsByCondition();
				
			if(!$this->sessions->result['status']) {
				$vsModule->obj->setClass('admins'); // Admin session time out
				$bw->input[2] = "timeout";
				$bw->input['action'] = "login";
				$vsUser->obj->__destruct();
				unset($_SESSION[APPLICATION_TYPE]['obj']);
				unset($_SESSION[APPLICATION_TYPE]['session']);
				unset($_SESSION[APPLICATION_TYPE]['groups']);
			}
			else {
				$vsUser->sessions->obj->setTime(time());
				$vsUser->sessions->updateLoginSession();
			}
		}
	}
}
?>
