<?php

if ( ! defined( 'IN_VSF' ) )
{
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit();
}

global $vsStd;
$vsStd->requireFile(CORE_PATH."users/users.php");

class users_admin 
{
    private $output		= "";
	private $html       = "";
	protected  $module;
	
	function __construct() {
		global $vsStd, $vsPrint,$vsTemplate;
		
    	$this->module = new users();
    	$vsPrint->addJavaScriptFile('encryptor');
        $this->html = $vsTemplate->load_template('skin_users');
	}
	
	
	function setOutput($html="") {
		$this->output = $html;
	}
	
	function getOutput() {
		return $this->output;	
	}
    
    function auto_run(){
		global $bw;
		switch($bw->input['action']){
			/* User group zone */
			case 'delete-user-group':
					$this->deleteGroup($bw->input[2]);
				break;
			case 'edit-group':
					$this->addEditGroupForm($bw->input[2]);
				break;
			
			case 'hide-checked-obj':
					$this->module->updateStatus(rtrim($bw->input['checkedObj'],","),array("userStatus" => 0));
					$this->getObjList($this->module->result['message']);
				break;
			case 'visible-checked-obj':
					$this->module->updateStatus(rtrim($bw->input['checkedObj'],","),array("userStatus" => 1));
					$this->getObjList($this->module->result['message']);
				break;
			case 'display-list-group':
					$this->getGroupList();
				break;
			case 'add-group':
					$this->addEditUserGroup();
				break;
			case 'display-group-tab':
					$this->displayGroup();
				break;
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
			case 'edit-obj-form':
					$this->addEditUserForm($bw->input[2]);
				break;
			case 'add-obj-form':
					$this->addEditUserForm();
				break;
			case 'add-edit-obj':
				$this->addEditUser();
				break;
			case 'display-obj-list':
					$this->getObjList();
				break;
			case 'delete-checked-obj':
					$this->module->deleteObjectById(rtrim($bw->input['checkedObj'],","));
					$this->getObjList($this->module->result['message']);
				break;
			case 'display-obj-tab':
					$this->displayUsers();
				break;
			case 'export':
					$this->exportUser();
				break;		
			
			case 'display-gift':
					$this->displayGift();
				break;
			case 'add-edit-gift-form':
					$this->addEditGiftForm();
				break;
			case 'edit-gift':
					$this->addEditGiftForm($bw->input[3]);
				break;
			case 'add-edit-gift':
					$this->addEditGift();
				break;
			
			default: 
					$this->loadDefault();
				break;
		}
	}
	
	/**
	 * Gift 
	 */
	
	function addEditGiftForm($objId=0){
		global $vsLang,$bw;
		$option['formSubmit'] = $vsLang->getWords('obj_EditObjFormButton_Add', 'Thêm');
		$option['formTitle']  = $vsLang->getWords('obj_title_editadd_opt', "Đổi quà");
		if($objId){
			$option['formSubmit'] = $vsLang->getWords('obj_EditObjFormButton_Edit', 'Sửa');
			$this->module->vsRelation->setObjectId($objId);
			$this->module->vsRelation->setTableName("user_gift");
			$this->module->vsRelation->getRelByObject(1);
			$obj = reset($this->module->vsRelation->arrval);
		}
		return $this->output = $this->html->addEditGiftForm($obj,$option);
	}
	
	function addEditGift(){
		global $bw;
		$this->module->vsRelation->setTableName("user_gift");
		
		$this->module->getObjectById($bw->input["userId"]);
		$arrInfo = $this->module->obj->getArrayInfo();
		
		if($arrInfo['userScore']<$bw->input['giftTick']){
			$arrInfo['userScore'] = $arrInfo['userScore']?$arrInfo['userScore']:0;
			print "<script>
					vsf.alert('Số dư tài khoản không đủ để thực hiện thao tác. Số dư tài khoản của bạn là: {$arrInfo['userScore']}');
				</script>";
			return $this->displayListGift($bw->input["userId"]);
		}
		$arrInfo['userScore'] = $arrInfo['userScore'] - $bw->input['giftTick'];
		$this->module->setCondition("userId=".$bw->input["userId"]);
		$this->module->updateObjectByCondition(array("userInfo"=>serialize($arrInfo)));
		
		if($bw->input['objId']){
			$this->module->vsRelation->setCondition("objectId in ({$bw->input['objId']})");
			$this->module->vsRelation->updateObjectByCondition(array("giftInfo"=>$bw->input["giftInfo"],"giftTick"=>$bw->input['giftTick'],"giftDate"=>time(),"giftBalance"=>$arrInfo['userScore']));
		}
		else{
			$this->module->vsRelation->setCondition(array("relId"=>$bw->input["userId"],"giftInfo"=>$bw->input["giftInfo"],"giftTick"=>$bw->input['giftTick'],"giftDate"=>time(),"giftBalance"=>$arrInfo['userScore']));
			$this->module->vsRelation->insertObjectByCondition();
		}
		
		$this->displayListGift($bw->input["userId"]);
	}
	
	function displayListGift($objId=0){
		global $bw;
		
		$this->module->vsRelation->setRelId($objId);
		$this->module->vsRelation->setTableName("user_gift");
		$this->module->vsRelation->getObjectByRel(true);
		
		return $this->output = $this->html->displayListGift($this->module->vsRelation->arrval);
		
	}
	
	function displayGift(){
		global $bw;
		$option['objForm'] = $this->addEditGiftForm();
		$option['objList'] = $this->displayListGift($bw->input[2]);
		$this->output = $this->html->displayGift($option);
	}
	
	function exportX(){
		global $bw,$vsStd,$vsFile;
		$list = $this->module->getObjectsByCondition();
		//define width and Name Header
		$myArr=array("Name","Birdth of day","Cell Phone","Email","Address");
		$leng =array(150,100,100,150,350);
		$act  =array('getName','getBirthday','getPhone','getEmail','getAddress');
		
		require_once(ROOT_PATH."excel/ex/ExcelWriterXML.php");
		$xml = new ExcelWriterXML('my file.xml');

		/**
		 * Add some general properties to the document
		 */
		$xml->docTitle('Export User Data');
		$xml->docAuthor('Sang Pm');
		$xml->docCompany('Vietsol.net');
		$xml->docManager('Tongnguyen');
		
		$xml->showErrorSheet(true);
		
		$sheet4 = $xml->addSheet('User Data');
		//define head users
		$fhead = $xml->addStyle('my style');
		$fhead->fontBold();
		$fhead->fontItalic();
//		$fhead->fontUnderline('DoubleAccounting');
		$fhead->alignHorizontal('Center');
		$fhead->bgColor('Blue');
		$fhead->fontColor('Red');
		$fhead->numberFormatDateTime();
		// Change the row1 height to 30 pixels
		$sheet4->rowHeight(1,'20');
		for($i=0;$i<count($myArr);$i++){
			if($leng[$i])
			$sheet4->columnWidth($i+1,$leng[$i]);
			$sheet4->writeString(1,$i+1,$myArr[$i],$fhead);
		}
		$count =1;
		if($list)
		foreach($list as $obj){	
			$count ++;
			for($i=0;$i<count($myArr);$i++){
				$sheet4->writeString($count,$i+1,$obj->$act[$i]());
			}
		}
		$xml->sendHeaders();
		$xml->writeData();
		exit();
	}
	
	function exportUser(){
		global $bw,$vsStd,$vsFile;
		$list = $this->module->getObjectsByCondition();
		$vsStd->requireFile(UTILS_PATH."excelwriter.inc.php");
		$excel=new ExcelWriter("./uploads/export_users.xls");
		if($excel==false)	
			echo $excel->error;
		$myArr=array("Name","Birdth of day","Address","Cell Phone","Email");
		$excel->writeLine($myArr,array('text-align'=>'center', 'color'=> 'red',"font-weight"=>"bold"));	
		if($list)
		foreach($list as $obj){	
			$myArr=array($obj->getName(),$obj->getBirthday(),$obj->getAddress(),$obj->getPhone(),$obj->getEmail());
			$excel->writeLine($myArr,27);
		}
		$excel->close();
		if (file_exists ( "./uploads/export_users.xls" )) {
				header ( 'Content-Description: File Transfer' );
				header ( 'Content-Type: application/octet-stream' );
				header ( 'Content-Disposition: attachment; filename = export_users.xls' );
				header ( 'Content-Transfer-Encoding: binary' );
				header ( 'Expires: 0' );
				header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
				header ( 'Pragma: public' );
				header ( 'Content-Length: ' . $vsFile->formatbytes("./uploads/export_users.xls") );
				ob_clean ();
				flush ();
				readfile ( "./uploads/export_users.xls" );
				exit ();
			}
		
		
	}
	
	
	function getObjList($message=""){
		global $bw,$vsSettings;
		$end = $this->module->getNumberOfObject();
		$size = $vsSettings->getSystemKey("admin_{$bw->input[0]}_list_number",10);
		$option=$this->module->getPageList("{$bw->input[0]}/display-obj-list/", 2, $size,1,'obj-panel');
		$option['message']=$message;
		return $this->output = $this->html->objListHtml($option);
	}
	
	function displayUsers($message='') {
		$userFormHTML = $this->addEditUserForm();
		$userTableHTML = $this->getObjList();
		return $this->setOutput($this->html->mainUserHtml($userFormHTML,$userTableHTML,$message));
	}
	//===============================================
	// USER GROUP
	//===============================================
	function deleteGroup($groupId=0) {
		if($groupId)
		$this->module->groupusers->setCondition("groupId in ($groupId)");
		$this->module->groupusers->deleteObjectByCondition();
		$this->setOutput($this->getGroupList($this->module->groupusers->result['message']));
	}
	
	function addEditUserGroup() {
		global $bw; 
		$this->module->groupusers->obj->convertToObject($bw->input);
		if($bw->input['formType']=='edit') 
			$this->module->groupusers->updateObjectById($this->module->groupusers->obj);		
		else 
			$this->module->groupusers->insertObject($this->module->groupusers->obj);
		return $this->output = $this->getGroupList("user insert success");	
		return $this->output = $this->addEditGroupForm(0,$this->module->groupusers->result['message']);
	}
	
	function getGroupList($message="") {
		$this->module->groupusers = new groupusers();
		$arrayGroup=$this->module->groupusers->getArrayObj();
		return $this->output = $this->html->groupTable($arrayGroup,$message);
	}
	
	function addEditGroupForm($groupId=0, $message = "") {
		global $vsLang;
		$group = $this->module->groupusers->createBasicObject();
		$form['type'] = "add";
		$form['title'] = $vsLang->getWords('user_group_title_add',"Add new group");
		$form['submit'] = $vsLang->getWords('user_group_bt_add',"Add");
		$form['message'] = $message;
		if($groupId) 
		{
			$group = $this->module->groupusers->getObjectById($groupId);
			$form['type'] = "edit";
			$form['title'] = $vsLang->getWords('user_group_title_edit',"Edit a group");
			$form['submit'] = $vsLang->getWords('user_group_bt_edit',"Edit");
		}
		return $this->output = $this->html->addEditGroupForm($form, $group);
	}
	
	function displayGroup() {
		$groupTableHTML = $this->getGroupList();
		$addEditGroupForm = $this->addEditGroupForm();
		
		$this->setOutput($this->html->mainGroup($addEditGroupForm,$groupTableHTML));
	}
	
	function savePermission($type='admin') {
		global $bw, $vsModule;
		
		// First get module information and group information
		$this->getGroupById($bw->input['groupId']);
		$vsModule->getModuleById($bw->input['moduleId']);
		
		// If group and module is not exist return the error
		if(!$this->result['status'] && !$vsModule->result['status']) {
			$this->getPermissionList($this->result['message']."<br />".$vsModule->result['status']);
			return;
		}
		
		// Get the current permission and asign to temporary $currentPermission variable
		$currentPermission = $this->group->getPermissions();
		if(!is_array($currentPermission)) $currentPermission = array();
		
		// Get the permssion of the current module
		require_once(CORE_PATH.$vsModule->module->getClass()."/".$vsModule->module->getClass()."_".$type.".php");
		$thisControllerName = $vsModule->module->getClass()."_".$type;
		$thisModuleObject = new $thisControllerName;
		$currentModulePermission = $thisModuleObject->getPermission();
		
		// Fill the module name before permission item
		foreach ($currentModulePermission[1] as $key => $value) {
			$newModulePermission[$vsModule->module->getClass()."/".$key] = $value;
		}

		// Now get the preserved permission
		$preservePermission = array_diff_key($currentPermission,$newModulePermission);

		// Reset the permission of this group
		$this->group->resetPermission();
		
		// Get number of permission of the module for the next loop
		$count = $bw->input['permCount'];
		for($i=1; $i<=$count; $i++) {
			// If permission is checked set permission for group
			if($bw->input['perm_'.$i]) {
				$this->group->setPermission($vsModule->module->getClass()."/".$bw->input['perm_'.$i],true);
			}
		}
		
		// Merget the preserved Permssion with the new permission
		$this->group->setPermissions(array_merge($this->group->getPermissions(),$preservePermission));
		
		// Update permissions for group after proccessing
		$this->updateGroup();
		
		$this->getPermissionList($bw->input['moduleId'],$bw->input['groupId'],$this->result['message']);
	}
	
	function getPermissionList($moduleId=0,$groupId=0,$message="") {
		global $vsModule;
				
		$this->getGroupById($groupId);
		$vsModule->getModuleById($moduleId);
		
		if(!$vsModule->result['status']) {
			$this->output = $vsModule->result['message'];
			return;
		}
		
		$requireFile = CORE_PATH.$vsModule->module->getClass()."/".$vsModule->module->getClass()."_admin.php";
		require_once($requireFile);
		$className = $vsModule->module->getClass()."_admin";		
		$this_module = new $className;
		$permission = $this_module->getPermission();
		
		$permListHTML .= $this->html->module->objPermModule($permission[0]);
		
		$count=0;
		foreach ($permission[1] as $key => $value) {
			$count++;
			$permItem['order'] = $count;
			$permItem['name'] = $value;
			$permItem['path'] = $key;
			if($this->group->getPermission($vsModule->module->getClass()."/".$key)) $permItem['checked'] = " checked";
			else $permItem['checked'] = "";
			$permListHTML .= $this->html->module->objPermItem($permItem);
		}
		
		$perm = array(	'list' 		=> $permListHTML,
						'count'		=> $count,
						'moduleId'	=> $moduleId,
						'groupId'	=> $groupId
						);
						
		$this->output = $this->html->module->objPermList($perm,$message);
	}
	
	function viewPermission() {
		global $vsModule, $vsStd;
		$groupBoxHTML = $this->html->permGroupBox($this->module->groupusers->getArrayObj());
		$vsModule->getEnabledModule();
		foreach ($vsModule->arrayModule as $module) {
			$requireFile = MODS_PATH.$module->getClass()."/".$module->getClass().".perm.php";
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
		
		$this->output = $this->html->mainPermission($moduleListHTML,$groupBoxHTML);	
	}
	
	function addEditUser() {
		
		global $bw,$vsLang;
		$bw->input['userStatus']=$bw->input['userStatus']?$bw->input['userStatus']:0;
		$this->module->setCondition("userName = '{$bw->input['userName']}'");
		$this->module->getOneObjectsByCondition();
	
		if(!$this->module->result['status'])
		{
			return $this->getObjList($this->module->result['message']);
		}
		
		if(!$bw->input['userId'] && $this->module->obj->getId())
		{
			return $this->getObjList($vsLang->getWords("exits_user","User name {$bw->input['userName']} has been exits"));
		}
		$this->module->obj->convertToObject($bw->input);
		
		if(is_array($bw->input['group']))
			$this->module->obj->setGroups($bw->input['group']);
			
		if($bw->input['userPassword']) $this->module->obj->setPassword($bw->input['userPassword']);
		
		if($this->module->obj->getId()) {
			$this->module->updateObjectById($this->module->obj);
		}
		else {
			$this->module->obj->setJoinDate(time());
			$this->module->insertObject($this->module->obj);
		}
		if($this->module->result['status']&&$bw->input['group'])
		{
			$this->module->vsRelation->setObjectId($this->module->obj->getId());
			$this->module->vsRelation->setRelId(implode(',',$bw->input['group']));
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$this->module->vsRelation->insertRel();
		}
		$bw->input[3]=$bw->input['pageIndex'];
		$this->getObjList($this->module->result['message']);
	}
	
	function addEditUserForm($userId=0, $message="") {
		$this->module->obj=$this->module->createBasicObject();
		$form['type'] = "add";
		$form['group']=$this->module->groupusers->getArrayObj();
		$form['message'] = $message;
		$form['formSubmit']="Thêm";
		$form['formTitle']="Thêm tài khoản mới";
		$form['provice'] = $this->module->vsMenu->getCategoryGroup("provice");
		if($userId){
			$form['type'] = "edit";
			$form['formSubmit']="Sửa";
			$form['formTitle']="Sửa tài khoản";
			$this->module->getObjectById($userId);
			$this->module->vsRelation->setObjectId($this->module->obj->getId());
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$this->module->vsRelation->getRelByObject(1);
			$form['cur_groups']=$this->module->vsRelation->arrval;
			
		}
		return $this->output = $this->html->addEditObjForm($this->module->obj,$form);
	}
	
	function addEditGroup() {
		global $bw;
		
		$this->group->convertToObject($bw->input);
		
		if($bw->input['formType']) {
			$this->updateGroup();			
		}
		else {
			$this->insertGroup();
		}
		
		if($this->result['status']) {
			$this->output = $this->addEditGroupForm(0,$this->result['message']);
		}
		else {
			$this->output = $this->addEditGroupForm(1,$this->result['message']);
		}
		
	}
	
	function displayEditGroupForm() {
		global $bw;
		
		$this->getGroupById($bw->input[2]);

		$this->output = $this->addEditGroupForm(1);
	}
	
	function loadDefault() {
		global $vsPrint;
		$vsPrint->addJavaScriptString('init_tab',
			'$(document).ready(function(){
				$("#page_tabs").tabs({fx:{opacity: "toggle"},cache:true});
			});'
    		);
    		
		$this->output = $this->html->MainPage();
	}
}
?>