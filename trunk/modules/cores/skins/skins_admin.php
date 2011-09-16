<?php
/*
 +-----------------------------------------------------------------------------
 |   VSF version 3.0.0.0
 |	Author: BabyWolf
 |	Homepage: http://www.vietsol.net
 |	If you use this code, please don't delete these comment lines!
 |	Start Date: 10/21/2007
 |	Finish Date: 10/21/2007
 |	Modified Start Date: 10/27/2007
 |	Modified Finish Date: 10/28/2007
 |	News Description: This News is for management all newses in system.
 +-----------------------------------------------------------------------------
 */

class skins_admin  {
	private $output = "";
	private $html = "";
	protected $module;

	public function __construct(){
		global $vsTemplate, $bw;
		$this->module = new skins();
		$this->html = $vsTemplate->load_template('skin_skins');
	}

	function auto_run() {
		global $bw;
		switch($bw->input[1]){
			case 'display-obj-list':
				$this->getObjList();
				break;
			case 'add-edit-obj':
				$this->addEditSkinProcess();
				break;
			case 'add-obj':
				$this->addEditObjForm();
				break;
			case 'edit-obj':
				$this->addEditObjForm($bw->input[2]);
				break;
			default:
				$this->loadDefault();
		}
	}

	function getObjList($message=""){
		global $bw, $vsSettings;
		$size = $vsSettings->getSystemKey("admin_{$bw->input[0]}_list_number",10);
		$option=$this->module->getPageList("{$bw->input[0]}/display-obj-list/", 2, $size,1,'obj-panel');
		$option['message'] = $message;
		return $this->output= $this->html->skinList($option);
	}

	function addEditObjForm($objId=0, $option=array()){
		global $vsLang, $vsStd,$bw ,$vsSettings, $vsPrint;
		$obj = $this->module->createBasicObject();
		$option['formSubmit'] = $vsLang->getWords('obj_EditObjFormButton_Add', 'Add');
		$option['formTitle']  = $vsLang->getWords('obj_EditObjFormTitile_Add', "Add {$bw->input[0]}");
		if($objId){
			$option['formSubmit'] = $vsLang->getWords('obj_EditObjFormButton_Edit', 'Edit');
			$option['formTitle']  = $vsLang->getWords('obj_EditObjFormTitile_Edit', "Edit {$bw->input[0]}");
			$obj = $this->module->getObjectById($objId);
		}
		return $this->output = $this->html->addEditObjForm($obj, $option);
	}

	function addEditSkinProcess(){
		global $bw, $vsStd, $vsLang;
		if($bw->input['skinStatus']) $bw->input['skinStatus'] = 1;
		if($bw->input['skinDefault']) $bw->input['skinDefault'] = 1;
		// If there is skin Id passed, processing updating skin
		if($bw->input['skinId']){
			$this->module->obj->convertToObject($bw->input);
			$this->module->updateSkin();
		}
		else {
			$this->module->obj->convertToObject($bw->input);
			$this->module->insertSkin($bw->input['skinInherit']);
		}
		if($this->result['status']) $this->module->obj->__destruct();
		///////////

		/////////////////////
		$form['message'] = $this->result['message'];
		$skinList = $this->getObjList();
	}

	function loadDefault() {
		global $vsPrint;
		$addEditFormHTML = $this->addEditObjForm();
		$skinList = $this->getObjList();
		$this->output=$this->html->MainPage($addEditFormHTML, $skinList);
	}


	public function getHtml() {
		return $this->html;
	}

	public function getOutput() {
		return $this->output;
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	public function setOutput($output) {
		$this->output = $output;
	}
}
?>