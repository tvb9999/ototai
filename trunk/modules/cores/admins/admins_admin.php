<?php
/*
 +-----------------------------------------------------------------------------
 |   VS FRAMEWORK 3.0.0
 |	Author: BabyWolf
 |	Homepage: http://vietsol.net
 |	If you use this code, please don't delete these comment line!
 |	Start Date: 21/09/2004
 |	Finish Date: 22/09/2004
 |	Version 2.0.0 Start Date: 07/02/2007
 |	Version 3.0.0 Start Date: 03/29/2009
 +-----------------------------------------------------------------------------
 */
if ( ! defined( 'IN_VSF' ) )
{
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit();
}

global $vsStd;
$vsStd->requireFile(CORE_PATH."admins/admins.php");
class admins_admin
{
	private $output		= NULL;
	private $html       = NULL;
	protected $module       = NULL;


	function __construct() {

		global $bw, $vsTemplate, $vsPrint;
		$this->module = new admins();
		$this->base_url = $bw->base_url;
		$vsPrint->addJavaScriptFile('encryptor');
		$vsPrint->addCSSFile('uvn-login');
		$this->html = $vsTemplate->load_template('skin_admins');
		 
	}

	function setOutput($html="") {
		$this->output = $html;
	}

	function getOutput() {
		return $this->output;
	}

	function auto_run(){
		global $bw,$vsUser;
		switch($bw->input['action']){
			/* Admin permssion zone */
			case 'savepermission':
				$this->savePermission();
				break;
			case 'getpermission':
				$this->getPermissionList($bw->input[2],$bw->input[3]);
				break;
			case 'permission':
				$this->viewPermission();
				break;
				/* Admin users zone */
			case 'deleteadmins':
				$this->removeAdmins();
				break;
					
			case 'editadmin':
				$this->addEditAdminForm('edit');
				break;
			case 'addformadmin':
				$this->addEditAdminForm();
				break;
			case 'displayadmintable':
				$this->displayAdminTable();
				break;

			case 'addeditadmin':
				$this->addEditAdmin();
				break;
			case 'addeditcontact':
				$this->addEditSSProcess();
				break;
			case 'displayadmin':
				$this->displayAdmins();
				break;
			case 'displayuseradmin':
				$this->displayUserAdmins();
				break;
			case 'display-obj-list':
				$this->	getObjList();
				break;
				/* Admin action zone */
			case 'dologin':
				$this->doLogin();
				break;

			case 'login':
				$this->displayLoginForm();
				break;
					
			case 'logout':
				$this->doLogout();
				break;
			case 'showAdmins':
				$this->showAllAdmins(1);
				break;
			case 'hiddenAdmins':
				$this->showAllAdmins(0);
				break;
					
				/* Admin Group zone */
				
			case 'deletegroups':
				$this->removeGroups();
				break;
					
			case 'displaygrouptable'://ok
				$this->output = $this->getGroupList();
				break;

			case 'addeditgroup'://ok
				$this->addEditGroup();
				break;
			case 'addgroupform':
				$this->displayEditGroupForm(0);
				break;
			case 'editgroup': //ok
				$this->displayEditGroupForm(1);
				break;

			case 'displaygroup'://ok tabso 1
				$this->displayGroups();
				break;
			case 'displayAd':
				$this->displayAd();
				break;
					
			default:
				$this->displayAd();//ok tabso 2
				break;
		}
	}
	//add adminstatus to $val
	function showAllAdmins($val){
		global $bw;
		$this->module->setCondition("userId in ({$bw->input[2]})");
		$this->module->updateObjectByCondition(array('userStatus'=>$val));
		return $this->output = $this->getObjList($bw->vars['root_admin_groups'],$this->result['message']);
	}

	function displayAd(){
		global $vsPrint;
		$vsPrint->addJavaScriptString('init_tab',
			'$(document).ready(function(){
				$("#page_tabs").tabs({fx:{opacity: "toggle"},cache:false});
			});'
			);

			$this->output = $this->html->MainPage();
	}


	//===============================================
	// ADMIN PERMISSION
	//===============================================
	/**
	 * Save permission for group
	 * @param int $groupId
	 * @param int $moduleId
	 */
	function savePermission() {
		global $bw, $vsModule;
		// First get module information and group information
		$this->module->groupadmins->getObjectById($bw->input['groupId']);
		$vsModule->getModuleById($bw->input['moduleId']);

		// If group and module is not exist return the error
		if(!$this->result['status'] && !$vsModule->result['status']) {
			$this->getPermissionList($this->result['message']."<br />".$vsModule->result['status']);
			return;
		}

		// Get the current permission and asign to temporary $currentPermission variable
		$currentPermission = $this->module->groupadmins->obj->getPermissions();
		if(!is_array($currentPermission)) $currentPermission = array();

		// Get the permssion of the current module
		require_once(CORE_PATH.$vsModule->obj->getClass()."/".$vsModule->obj->getClass().".perm.php");
		$thisControllerName = $vsModule->obj->getClass()."_perm";
		$thisModuleObject = new $thisControllerName;
		$currentModulePermission = $thisModuleObject->getAdminPermission();

		// Fill the module name before permission item
		foreach ($currentModulePermission[1] as $key => $value) {
			$newModulePermission[$vsModule->obj->getClass()."/".$key] = $value;
		}

		// Now get the preserved permission
		$preservePermission = array_diff_key($currentPermission,$newModulePermission);

		// Reset the permission of this group
		$this->module->groupadmins->obj->resetPermission();
		// Get number of permission of the module for the next loop
		$count = $bw->input['permCount'];
		for($i=1; $i<=$count; $i++) {
			// If permission is checked set permission for group
			if($bw->input['perm_'.$i]) {
				$this->module->groupadmins->obj->setPermission($vsModule->obj->getClass()."/".$bw->input['perm_'.$i],true);
			}
		}

		// Merget the preserved Permssion with the new permission
		$this->module->groupadmins->obj->setPermissions(array_merge($this->module->groupadmins->obj->getPermissions(),$preservePermission));
		// Update permissions for group after proccessing
		$this->module->groupadmins->updateObjectById($this->module->groupadmins->obj);
		$this->getPermissionList($bw->input['moduleId'],$bw->input['groupId'],$this->result['message']);
	}

	function getPermissionList($moduleId=0,$groupId=0,$message="") {
		global $vsModule,$vsStd,$vsUser;
		$vsModule->getModuleById($moduleId);
		if(!$vsModule->result['status']) {
			$this->output = $vsModule->result['message'];
			return;
		}
		$this->module->groupadmins->getObjectById($groupId);
		$listperm = $this->module->groupadmins->obj->getPermissions();
		$requireFile = CORE_PATH.$vsModule->obj->getClass()."/".$vsModule->obj->getClass().".perm.php";
		$vsStd->requireFile($requireFile);
		$className = $vsModule->obj->getClass()."_perm";
		$this_module = new $className;
		$permission = $this_module->getAdminPermission();
		$count = count($permission[1]);
		$module = $vsModule->obj->getClass()."/";
		$perm = array(
						'count'		=> $count,
						'moduleId'	=> $moduleId,
						'groupId'	=> $groupId,
						'perTitle'	=> $permission[0],
						'module'	=> $module,
						'listpermobj'	=> $listperm,
						'perm'		=>$permission[1]
		);
		if(!$vsUser->checkRoot())
		{	$selfpermission=array();
		foreach ($vsUser->obj->getGroups() as $group){
			$selfpermission = array_merge($selfpermission,$group->getPermissions());
		}
		$perm['selfpermission']=$selfpermission;
		}
		$this->output = $this->html->AdminPermList($perm,$message);
	}

	function viewPermission() {
		global $vsModule,$vsUser, $vsStd;
		$groupBoxHTML = $this->html->AdminPermGroupBox($vsUser->obj->getGroups());

		$vsModule->getEnabledModule();
		foreach ($vsModule->arrayModule as $module) {
			$requireFile = CORE_PATH.$module->getClass()."/".$module->getClass().".perm.php";
			// If the file is not exist, don't process for this module
			if(!file_exists($requireFile)) continue;
			$vsStd->requireFile($requireFile);
			$className = $module->getClass()."_perm";
				
			// If the class is not exist, don't process for this module
			if(!class_exists($className)) continue;
				
			$this_module = new $className;
				
			// If the getAdminPermission method is not exist, don't process for this module
			if(!method_exists($this_module,'getAdminPermission')) {
				unset($this_module);
				continue;
			}
				
			$permission = $this_module->getAdminPermission();
				
			$moduleItemHTML .= $this->html->ModuleOption($permission[0],$module);
		}

		$moduleListHTML = $this->html->ModuleBox($moduleItemHTML);
		//		$permTableHTML = $this->getAdminModuleList();

		$this->output = $this->html->mainAdminPermission($moduleListHTML,$groupBoxHTML);
	}

	//===============================================
	// ADMIN ZONE
	//===============================================

	function removeAdmins(){
		global $bw;
		if($bw->input[2]!=""){
			$this->module->setCondition("userId in ({$bw->input[2]})");
			$this->module->deleteObjectByCondition();
			$this->module->vsRelation->setObjectId($bw->input[2]);
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$this->module->vsRelation->delRelByObject();
			$this->module->arrayAdmin = array();
		}else{
			$this->result['message']	=	"Have some error";
		}
		$this->output = $this->getObjList($bw->vars['root_admin_groups'],$this->result['message']);
	}


	/**
	 * Process add or edit an admin
	 *
	 */
	function addEditSSProcess() {
		global $DB, $bw;
		$this->result['message'] = "Báº¡n Ä‘Ã£ cáº­p nháº­t thÃ´ng tin thÃ nh cÃ´ng! Trá»Ÿ vá»� trang chÃ­nh Ä‘á»ƒ tháº¥y sá»± thay Ä‘á»•i";
		foreach ($bw->input['system'] as $key => $value)
		{
				
			$arr= explode('-',$key);
			$bw->vars[$arr[1]]=$value;
			$setting = array (	'settingValue' 			=> $value	);
			if (!$DB->do_update ( 'system_settings', $setting, 'settingId = ' . $arr[0] )){
				$this->result['message'] = "CÃ³ lá»—i xÃ£y ra trong quÃ¡ trÃ¬nh lÆ°u dá»¯ liá»‡u";
			}
		}
		$this->displayAdmins($this->result['message']);
	}

	function addEditAdmin() {
		global $bw, $vsLang;

		$this->module->obj->setName($bw->input['userName']);

		
		if($bw->input['formType']=='edit') {
			if($bw->input['userPassword']!="")
			$this->module->obj->setPassword($bw->input['userPassword']);
		}
		else {
			$this->module->obj->setPassword($bw->input['userPassword']);
			$bw->input['groupId']=$bw->input['groupId']?$bw->input['groupId']:2;
			$this->module->obj->setName($bw->input['userName']);
		}
		
		$bw->input['userStatus']? $this->module->obj->setStatus(1):$this->module->obj->setStatus(0);
		
		$this->module->obj->setJoinDate(time());
		$this->module->obj->setLastLogin(0);
		$this->module->obj->getPassword();

		if(($bw->input['formType'] == "add") || ($bw->input['formType'] == "edit" && $bw->input['userName'] != $bw->input['oldName']) ){
			$admins = new admins();
			$admins->setCondition("userName = '".$bw->input['userName']."'");
			$array = $admins->getObjectsByCondition();
				
			if($array){
				$bw->input['userStatus'] = $bw->input['userStatus'] ? 1 : 0; 
				$admins->obj->convertToObject($bw->input);
				
				$admins->obj->setName($bw->input['oldName']);
				
				$form['type'] = $bw->input['formType'];
				$form['submit'] = $vsLang->getWords("add_bt","Thêm");
				$form['message'] = $message;
				
				if(!$admins->obj->groupIds){
					$normal = $admins->getNormalGroup();
					$admins->obj->groupIds=$normal->getId();
				}
				
				if($this->module->checkRoot()) $admins->obj->displayGroup = 1;
				elseif($vsSettings->getSystemKey("display_normal_group",0))  $admins->obj->displayGroup = 1;

				$admins->obj->setGroups($bw->input['groupIds']);
				
			
				$form['message'] = $vsLang->getWords('exist_account','This account has existed').": ".$bw->input['userName'];
				return $this->output = $this->html->AddEditAdminForm($form, $admins->obj);
			}
		}
		if($bw->input['formType']=='edit') {
			$this->module->obj->setId($bw->input['userId']);
			$this->module->updateObjectById($this->module->obj);
			
			if($this->module->result['status']){
				if($bw->input['groupIds']){
					$this->module->vsRelation->setObjectId($this->module->obj->getId());
					$this->module->vsRelation->setRelId($bw->input['groupIds']);
					$this->module->vsRelation->setTableName($this->module->getRelTableName());
					$this->module->vsRelation->insertRel();
				}
			}
		}
		else {
			$name = $bw->input['userName'];
			$objtemp = $this->module->obj;
			$this->module->setCondition("userName = '".$name."'");
			if($this->module->getObjectsByCondition()){
				$this->module->result['message']="user name [{$name}] đã tồn tại";
				$this->module->obj = $objtemp;
				return $this->output = $this->addEditAdminForm($bw->input['formType'], $this->module->result['message']);
			}
				
			$this->module->insertObject($objtemp);
			if($this->module->result['status']){
				$this->module->vsRelation->setObjectId($this->module->obj->getId());
				if($bw->input['groupIds'])
				$this->module->vsRelation->setRelId($bw->input['groupIds']);
				else
				$this->module->vsRelation->setRelId($bw->input['groupId']);
				$this->module->vsRelation->setTableName($this->module->getRelTableName());
				$this->module->vsRelation->insertRel();
			}
		}

		if($this->module->result['status']) $this->module->obj->__destruct();

		$message = $vsLang->getWords('update_fail','Information update fail');
		if($this->module->result['status']) $message = $vsLang->getWords('update_successfully','Information has been updated');
		$this->addEditAdminForm('add', $message);
	}

	function displayAdminTable() {
		global $bw;

		$this->output = $this->getObjList($bw->input[2]);
	}
	function getObjList($message=""){
		global $bw,$vsSettings,$vsUser,$DB;
		if($bw->input['pageIndex'])	$bw->input[2]=$bw->input['pageIndex'];
		$size = $vsSettings->getSystemKey("admin_{$bw->input[0]}_list_number",10);
		$this->module->setCondition("userType = 1");
		if(!$vsUser->checkRoot()){
			$this->module->vsRelation->setRelId(implode(array_keys($vsUser->obj->getGroups()),','));
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$listObj = $this->module->vsRelation->getObjectByRel();
			$this->module->vsRelation->setRelId($bw->vars['root_admin_groups']);
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$rootlistObj = $this->module->vsRelation->getObjectByRel();
			$this->module->setCondition("userId in({$listObj}) and userId not in ($rootlistObj) and userType = 1");
		}
		
		$option=$this->module->getPageList("{$bw->input[0]}/display-obj-list", 2,$size,1,'obj-list');
		
		$option['pageList'][$vsUser->obj->getId()]->current = 1;
		
		$option['message'] = $message;
		
		return $this->output = $this->html->objListHtml($option);
	}

	function addEditAdminForm($formType='add', $message="") {
		global $vsLang, $bw, $vsSettings;

		$form['type'] = $formType;
		$form['submit'] = $vsLang->getWords("add_bt","Thêm");
		$form['message'] = $message;
		if($formType=='edit'){
			$form['submit'] = $vsLang->getWords("edit_bt","Sửa");
			$this->module->getObjectById($bw->input[2]);
			$this->module->vsRelation->setObjectId($bw->input[2]);
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$listObj = $this->module->vsRelation->getRelByObject();
			
			if($listObj) $this->module->obj->setGroups($listObj);
		}
		if(!$this->module->obj->groupIds){
			$normal=$this->module->getNormalGroup();
			$this->module->obj->groupIds=$normal->getId();
		}
		
		if($this->module->checkRoot()) $this->module->obj->displayGroup = 1;
		elseif($vsSettings->getSystemKey("display_normal_group",0))  $this->module->obj->displayGroup = 1;
			
		
		
		return $this->output = $this->html->AddEditAdminForm($form, $this->module->obj);
	}
	
	function displayUserAdmins() {
		global $bw;

		// Display group box
		foreach ($this->module->groupadmins->arrayGroup as $group) {
			$groupOptionHTML .= $this->html->groupOption($group);
		}
		$groupBoxHTML = $this->html->GroupAdminBox($groupOptionHTML);

		// Display user form
		$addEditAdminForm = $this->addEditAdminForm();

		// Display admin list
		$adminListHTML = $this->getObjList($bw->vars['root_admin_groups']);

		$this->output = $this->html->MainAdmin($groupBoxHTML,$addEditAdminForm, $adminListHTML);
	}
	function displayAdmins() {
		global $bw;

		// Display group box
		$oGroup = new groupadmins();

		// $groupBoxHTML = $this->html->GroupAdminBox($oGroup->arrayGroup);

		// Display user form
		$addEditAdminForm = $this->addEditAdminForm();

		// Display admin list
		$adminListHTML = $this->getObjList($bw->vars['root_admin_groups']);

		$this->output = $this->html->MainAdmin($groupBoxHTML,$addEditAdminForm, $adminListHTML);
	}

	//===============================================
	// GROUP ZONE
	//===============================================
	//	function removeGroup() {
	//		global $bw;
	//		if($bw->input[2]!=1){
	//			$this->module->groupadmins->obj->setId($bw->input[2]);
	//			$this->module->groupadmins->deleteGroup();
	//		}else{
	//			$this->result['message']	=	"Can't delete Default Root Group!";
	//		}
	//
	//		$this->output = $this->getGroupList($this->result['message']);
	//	}
	function removeGroups() {
		global $bw;
		if($bw->input[2]!=""){
			//			$this->module->groupadmins->deleteGroups($bw->input[2]);
			$this->module->groupadmins->setCondition("groupId in ({$bw->input[2]})");
			$this->module->groupadmins->deleteObjectByCondition();
			$this->module->vsRelation->setRelId($bw->input[2]);
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$this->module->vsRelation->delObjectByRel();
		}else{
			$this->result['message']	=	"Can't delete Default Root Group!";
		}

		$this->output = $this->getGroupList($this->result['message']);
	}

	function addEditGroup() {
		global $bw;

		$this->module->groupadmins->obj->convertToObject($bw->input);

		if($bw->input['formType']) {
			$this->module->groupadmins->updateObjectById($this->module->groupadmins->obj);

		}
		else {
			$tempobj = $this->module->groupadmins->obj;
			$this->module->groupadmins->setCondition("groupName = '".$tempobj->getName()."'");
			$listad = $this->module->groupadmins->getObjectsByCondition();
			$this->module->groupadmins->obj = $tempobj;
				
			if($listad)return $this->output = $this->addEditGroupForm(0,"admin group [{$tempobj->getName()}] has been exits");
			$this->module->groupadmins->insertObject($this->module->groupadmins->obj);
				
		}
		$this->module->groupadmins = new groupadmins();
		$this->output = $this->addEditGroupForm(0,$this->result['message']);
	}

	function displayEditGroupForm($val = 0) {
		global $bw;

		$this->module->groupadmins->getObjectById($bw->input[2]);

		$this->output = $this->addEditGroupForm($val);
	}

	function addEditGroupForm($formType=0,$message="") {
		global $vsLang;
		$form['type'] = $formType;
		$form['title'] = $vsLang->getWords('group_title_add','ThÃªm nhÃ³m quáº£n trá»‹');
		$form['submit'] = $vsLang->getWords('global_bt_add','ThÃªm');
		if($form['type']) {
			$form['title'] = $vsLang->getWords('group_title_edit','Sá»­a thÃ´ng tin nhÃ³m');
			$form['submit'] = $vsLang->getWords('global_bt_edit','Sá»­a');
		}

		$addEditFormHTML = $this->html->AddEditGroupForm($form, $this->module->groupadmins->obj, $message);

		return $addEditFormHTML;
	}

	function getGroupList($message="") {
		$oGroups = new groupadmins();
		return $this->html->GroupTable($oGroups->arrayGroup,$message);
	}

	function displayGroups() {
		$groupTableHTML = $this->getGroupList();
		$addEditGroupFormHTML = $this->addEditGroupForm();

		$this->output = $this->html->MainGroup($groupTableHTML, $addEditGroupFormHTML);
	}

	function doLogout() {
		global $bw, $vsPrint;
		unset($_SESSION[APPLICATION_TYPE]['obj']);
		unset($_SESSION[APPLICATION_TYPE]['session']);
		unset($_SESSION[APPLICATION_TYPE]['groups']);
		$vsPrint->boink_it($bw->base_url);
	}

	function displayLoginForm() {
		global $bw, $vsLang;
		$error = "";
		if($bw->input[2] == "timeout")
		$error = $vsLang->getWords('admin_session_timeout','Administration session time out');
		$this->output =  $this->html->LoginForm($error);
	}

	function doLogin() {
		global $bw, $vsPrint, $vsUser;

		if($vsUser->obj->getId())
			$vsPrint->boink_it($bw->vars['board_url'].'/acprs.'.$bw->vars['php_ext']);
			
		$vsUser->obj->setName($bw->input['userName']);
		$vsUser->obj->setPassword($bw->input['userPassword']);

		$this->module->loadAdmin();
		if(!$this->module->result['status']){
			$this->output = $this->html->LoginForm($this->module->result['message']);
			return false;
		}

		$thisTime = time();
		$this->module->obj->setLastLogin($thisTime);

		$this->module->updateObjectById();
		
		if(!$this->result['status'])
			$this->output = $this->html->LoginForm($this->result['message']);
			
		$this->module->sessions->updateLoginSession();
		
		if(!$this->result['status'])
			$this->output = $this->html->LoginForm($this->result['message']);

		$vsPrint->boink_it($bw->vars['board_url'].'/acprs.'.$bw->vars['php_ext']);
	}
}
?>