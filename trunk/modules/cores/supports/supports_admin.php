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
 +-----------------------------------------------------------------------------
 */
require_once (CORE_PATH . "supports/supports.php");

class supports_admin {
	protected $html = "";
	protected $module;
	
	protected $output = "";

	function auto_run() {
		global $bw;
		switch ($bw->input [1]) {
			case 'delete-checked-obj' :
				$this->module->delete ( rtrim ( $bw->input ['checkedObj'], "," ) );
				break;
			
			case 'visible-checked-obj' :
				$this->module->updateStatus ( rtrim ( $bw->input ['checkedObj'], "," ), array ("supportStatus" => 1 ) );
				break;
			
			case 'hide-checked-obj' :
				$this->module->updateStatus ( rtrim ( $bw->input ['checkedObj'], "," ), array ("supportStatus" => 0 ) );
				break;
			
			case 'display-obj-tab' :
				$this->displayObjTab ();
				break;
			
			case 'display-obj-list' :
				$this->getObjList ( $bw->input [2], $this->module->result ['message'] );
				break;
			
			case 'add-edit-obj-form' :
				$this->addEditObjForm ( $bw->input [2] );
				break;
			
			case 'add-edit-obj-process' :
				$this->addEditObjProcess ();
				break;
			
			case 'delete-obj' :
				$this->module->delete ( $bw->input [2] );
				break;
			
			default :
				$this->loadDefault ();
		}
	}

	function displayObjTab() {
		$option ['categoryList'] = $this->getCategoryBox ();
		$option ['objList'] = $this->getObjList ();
		$this->output = $this->html->displayObjTab ( $option );
	}

	function getObjList($catId = 0, $message = "") {
		global $bw, $vsStd, $vsSettings, $vsMenu;
		
		$categories = $this->module->getCategories ();
		// Check if the catIds is specified
		// If not just get all product
		if (! intval ( $catId )) {
			$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		} else {
			$result = $this->module->vsMenu->extractNodeInTree ( $catId, $this->module->vsMenu->arrayTreeCategory );
			if ($result)
				$strIds = trim ( $catId . "," . $this->module->vsMenu->getChildrenIdInTree ( $result ['category'] ), "," );
		}
		
		$this->module->setCondition ( $this->module->getCategoryField () . ' in (' . $strIds . ')' );
		
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10 );
		
		$total = $this->module->getNumberOfObject ();
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10, "supports", 1, 1 );
		$option = $this->module->getPageList ( "{$bw->input[0]}/display-obj-list/{$catId}", 3, $size, 1, 'obj-panel' );
		
		$option ['message'] = $message;
		$option ['categoryId'] = $catId;
		$option ['info'] = $this->module->showStatusInfo ();
		return $this->output = $this->html->objListHtml ( $this->module->getArrayObj (), $option );
	}

	function addEditObjForm($objId = 0, $option = array()) {
		global $vsLang, $vsStd, $bw, $vsMenu, $vsSettings;
		
		$obj = $this->module->createBasicObject ();
		$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Add', 'Add' );
		$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Add', "Add" );
		$nickicons = $vsMenu->getCategoryGroup ( "nickicons" )->getChildren ();
		
		if (count ( $nickicons )) {
			foreach ( $nickicons as $icon ) {
				$icon->getIsDropdown () ? $option ['icon_online'] [] = $icon : $option ['icon_offline'] [] = $icon;
			}
		}
		if ($objId) {
			$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Edit', 'Edit' );
			$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Edit', "Edit {$bw->input[0]}" );
			$obj = $this->module->getObjectById ( $objId );
			$option ['categoryId'] = $obj->createCategory ()->getId ();
		} else {
			if (count ( $option ['icon_offline'] ))
				$obj->setImageOffline ( current ( $option ['icon_offline'] )->getId () );
			if (count ( $option ['icon_online'] ))
				$obj->setImageOnline ( current ( $option ['icon_online'] )->getId () );
			$obj->setStatus(1);
		}
		
		$vsStd->requireFile ( JAVASCRIPT_PATH . "/tiny_mce/tinyMCE.php" );
		$editor = new tinyMCE ();
		
		$editor->setWidth ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_width", '400px', $bw->input [0], 1, 1 ) );
		$editor->setHeight ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_height", '150px', $bw->input [0], 1, 1 ) );
		$editor->setToolbar ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_toolbar", 'narrow', $bw->input [0], 1, 1 ) );
		$editor->setTheme ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_theme", "advanced", $bw->input [0], 1, 1 ) );
		$editor->setInstanceName ( 'supportIntro' );
		$editor->setValue ( $obj->getIntro () );
		$obj->setIntro ( $editor->createHtml () );
		
		return $this->output = $this->html->addEditObjForm ( $obj, $option );
	}

	function addEditObjProcess() {
		global $bw, $vsStd, $vsLang, $vsSettings;
		
		$bw->input ['supportStatus'] = $bw->input ['supportStatus'] ? 1 : 0;
		if (! $bw->input ['supportCatId'])
			$bw->input ['supportCatId'] = $this->module->getCategories ()->getId ();
		if ($bw->input ['fileId'])
			$bw->input ['supportAvatar'] = $bw->input ['fileId'];
		
		if ($bw->input ['supportId']) {
			$obj = $this->module->getObjectById ( $bw->input ['supportId'] );
			$imageOld = $obj->getAvatar ();
			if (! $obj) {
				$this->alertMessage ();
			}
			$objUpdate = $this->module->createBasicObject ();
			$objUpdate->convertToObject ( $bw->input );
			$this->module->updateObjectById ( $objUpdate );
			if (! $this->module->result ['status']) {
				$this->module->reportError ();
			}
		} else {
			$this->module->obj->convertToObject ( $bw->input );
			$this->module->insertObject ( $this->module->obj );
		}
		
		$this->module->result ['message'] = $vsLang->getWords ( 'update_error', 'There was an error while update information' );
		if ($this->module->result ['status'])
			$this->module->result ['message'] = $vsLang->getWords ( 'update_sucsessful', 'Inforamtion has been updated' );
		
		$this->alertMessage ( $bw->input ['supportCatId'] );
	}

	function alertMessage($cart = 0) {
		global $bw;
		print "<script>
					vsf.alert('{$this->module->result['message']}');
					vsf.get('{$bw->input[0]}/display-obj-list/{$cart}', 'obj-panel')
				</script>";
		return true;
	}

	function getCategoryBox($message = "") {
		global $bw, $vsMenu;
		$menu = $this->module->getCategories ();
		return $this->html->categoryList ( $menu );
	}

	function loadDefault() {
		global $vsPrint;
		
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		
		$vsPrint->addJavaScriptString ( 'init_tab', '
			$(document).ready(function(){
    			$("#page_tabs").tabs({
    				cache: false
    			});
  			});
		' );
		
		$this->setOutput ( $this->html->managerObjHtml () );
	}

	public function __construct() {
		global $vsTemplate;
		
		$this->module = new supports ();
		$this->html = $vsTemplate->load_template ( 'skin_supports' );
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