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
|	advisorys Description: This advisorys is for management all advisoryses in system.
+-----------------------------------------------------------------------------
*/
require_once (CORE_PATH . "advisorys/advisorys.php");

class advisorys_admin {
	protected $html = "";
	protected $module;
	
	protected $output = "";

	public function __construct() {
		global $vsTemplate;
		$this->module = new advisorys ();
		$this->html = $vsTemplate->load_template ( 'skin_advisorys' );
	}

	function auto_run() {
		global $bw;
		
		switch ($bw->input [1]) {
			case 'delete-checked-obj' :
				$this->module->delete ( rtrim ( $bw->input ['checkedObj'], "," ) );
				break;
			
			case 'visible-checked-obj' :
				$this->checkShowAll ( 1 );
				break;
			
			case 'hide-checked-obj' :
				$this->checkShowAll ( 0 );
				break;
			
			case 'home-checked-obj' :
				$this->checkShowAll ( 2 );
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
				$this->deleteObj ( $bw->input [2] );
				break;
			case 'reply' :
				$this->showReplyadvisoryForm ( $bw->input [2] );
				break;
			
			case 'replyProcess' :
				$this->replyProcess ( $bw->input [2], $bw->input [3] );
				break;
			
			default :
				$this->loadDefault ();
		}
	}

	function replyProcess($advisoryId, $advisoryType = 0) {
		global $bw, $vsLang, $vsStd, $vsSettings;
		
		$vsStd->requireFile ( LIBS_PATH . "Email.class.php", true );
		
		$this->email = new Emailer ();
		$message = $this->email->clean_message ( $bw->input ['Content'] );
		$this->email->setTo ( $bw->input ['email'] );
		$this->email->setSubject ( $vsSettings->getSystemKey ( "global_websitename", "www.vietsol.net" ) );
		$this->email->setBody ( $message );
		$this->email->sendMail ();
		
		$option ['message'] = $vsLang->getWords ( 'advisory_send_fail', 'Send mail fail' );
		
		if (! $this->email->error) {
			$this->module->getObjectById ( $advisoryId );
			$this->module->updateObjectById ( $this->module->obj );
			
			$option ['message'] = $vsLang->getWords ( 'advisory_send_success', 'You have successfully send mail' );
		}
		print "<script>
					vsf.alert(\"{$option['message']}\");
					$('#albumn-reply').remove();
				</script>";
		return true;
	}

	function showReplyadvisoryForm($advisoryId) {
		global $bw, $vsStd, $vsSettings, $vsPrint;
		
		$this->module->getObjectById ( $advisoryId );
		
		$message = <<<EOF
			<br />
			On: <strong>{$this->module->obj->getPostDate()}, 
						{$this->module->obj->getName()} <i>
						&lt;{$this->module->obj->getEmail()}&gt;</i></strong> wrote:<br />
					{$this->module->obj->getIntro()}
	        <blockquote style="border-left: 2px solid rgb(16, 16, 255); margin: 0pt 0pt 0pt 0.8ex; padding-left: 1ex; background:#F4F4F4;">
	        	From: {$this->module->obj->getEmail()} <{$this->module->obj->getEmail()}><br/>
	        	Subject: {$this->module->obj->getTitle()}<br/>
	        	To: {$vsSettings->getSystemKey("global_websitename","www.vietsol.net")}<br/>
	        	Address:{$this->module->obj->getAddress()}<br/>
	        	Phone:{$this->module->obj->getPhone()}<br/>
	        	Date:{$this->module->obj->getPostDate('LONG')}<br/>
	        	
	        	
	        </blockquote><br /><br />
	        
			
EOF;
		
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		$vsStd->requireFile ( JAVASCRIPT_PATH . "/tiny_mce/tinyMCE.php" );
		
		$editor = new tinyMCE ();
		$editor->setWidth ( '100%' );
		$editor->setHeight ( '500px' );
		$editor->setToolbar ( 'narrow' );
		$editor->setTheme ( "advanced" );
		$editor->setInstanceName ( 'newsIntro' );
		$editor->setValue ( $message );
		$this->module->obj->setIntro ( $editor->createHtml () );
		
		$option ['obj'] = $this->module->obj;
		$option ['currenPage'] = $bw->input [4];
		$this->module->obj->setStatus ( 1 );
		$this->module->updateObjectById ( $this->module->obj );
		
		return $this->output = $this->html->replyadvisoryForm ( $option );
	}

	function deleteObj($ids = "") {
		global $bw;
		
		$this->module->setCondition ( "advisoryId IN (" . $ids . ")" );
		if (! $this->module->deleteObjectByCondition ())
			return false;
		
		return $this->output = $this->getObjList ();
	}

	function checkShowAll($val = 0) {
		global $bw;
		$this->module->setCondition ( "advisoryId in ({$bw->input[2]})" );
		$this->module->updateObjectByCondition ( array ('advisoryStatus' => $val ) );
		return $this->output = $this->getObjList ();
	}

	function displayObjTab() {
		$option ['categoryList'] = $this->getCategoryBox ();
		$option ['objList'] = $this->getObjList ();
		$this->output = $this->html->displayObjTab ( $option );
	}

	function getObjList($catId = '', $message = "") {
		global $bw, $vsStd, $vsSettings;
		if ($bw->input ['pageCate']) {
			$catId = $bw->input ['pageCate'];
			$bw->input [2] = $bw->input ['pageCate'];
		}
		if ($bw->input ['pageIndex'])
			$bw->input [3] = $bw->input ['pageIndex'];
		$catId = intval ( $catId );
		$categories = $this->module->getCategories ();
		
		if (! $catId) {
			$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		} else {
			$result = $this->module->vsMenu->extractNodeInTree ( $catId, $categories->getChildren () );
			if ($result)
				$strIds = trim ( $catId . "," . $this->module->vsMenu->getChildrenIdInTree ( $result ['category'] ), "," );
		}
		
		// Set the condition to get all product in specified category and its chidlren
		if ($strIds)
			$this->module->setCondition ( 'advisoryCatId in (' . $strIds . ')' );
		
		$total = $this->module->getNumberOfObject ();
		
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10,$bw->input[0],1,1 );
		
		$option = $this->module->getPageList ( "{$bw->input[0]}/display-obj-list/{$catId}", 3, $size, 1, 'obj-panel' );
		
		$option ['message'] = $message;
		$option ['categoryId'] = $catId;
		return $this->output = $this->html->objListHtml ( $this->module->getArrayObj (), $option );
	}

	function addEditObjForm($objId = 0, $option = array()) {
		global $vsLang, $vsStd, $bw, $vsSettings, $vsPrint;
		
		$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Add', 'Add' );
		$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Add', "Add {$bw->input[0]}" );
		$this->module->obj->setStatus(1);
		if ($objId) {
			$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Edit', 'Edit' );
			$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Edit', "Edit {$bw->input[0]}" );
			$this->module->getObjectById ( $objId );
			$option ['categoryId'] = $this->module->obj->getCategory ();
		}
		
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		$vsStd->requireFile ( JAVASCRIPT_PATH . "/tiny_mce/tinyMCE.php" );
		
		$editor = new tinyMCE ();
		if ($vsSettings->getSystemKey ( $bw->input [0] . '_intro_editor', 0,$bw->input [0],1,1 )) {
			$editor->setWidth ( '100%' );
			$editor->setHeight ( '150px' );
			$editor->setToolbar ( 'narrow' );
			$editor->setTheme ( "advanced" );
			$editor->setInstanceName ( 'advisoryIntro' );
			$editor->setValue ( $this->module->obj->getIntro (-1) );
			$obj->setIntro ( $editor->createHtml () );
		} else
			$this->module->obj->setIntro ( "<textarea name='advisoryIntro' style='width:100%;height:150px;'>" . strip_tags ( $this->module->obj->getIntro () ) . "</textarea>" );
		
		$editor->setWidth ( '100%' );
		$editor->setHeight ( '350px' );
		$editor->setToolbar ( 'full' );
		$editor->setTheme ( "advanced" );
		$editor->setInstanceName ( 'advisoryContent' );
		$editor->setValue ( $this->module->obj->getContent (-1) );
		$this->module->obj->setContent ( $editor->createHtml () );
		
		return $this->output = $this->html->addEditObjForm ( $this->module->obj, $option );
	}

	function addEditObjProcess() {
		global $bw, $vsStd, $vsLang, $vsSettings;
		
		$bw->input ['advisoryPostDate'] = time ();
		$bw->input ['advisoryStatus'] = $bw->input ['advisoryStatus'] ? $bw->input ['advisoryStatus'] : 0;
		if (! $bw->input ['advisoryCatId'])
			$bw->input ['advisoryCatId'] = $this->module->getCategories ()->getId ();
		
		$this->module->result ['message'] = $vsLang->getWords ( 'update_fail', 'Update fail' );
		
		if ($bw->input ['advisoryId']) {
			$this->module->obj->convertToObject ( $bw->input );
			$this->module->updateObjectById ( $this->module->obj );
			
			if ($this->module->result ['status'])
				$this->module->result ['message'] = $vsLang->getWords ( 'update_successful', 'Update successful' );
		} else {
			$this->module->obj->convertToObject ( $bw->input );
			$this->module->insertObject ( $this->module->obj );
		}
		$this->alertMessage ();
	}

	function alertMessage() {
		global $bw;
		
		print "<script>
					vsf.alert('{$this->module->result['message']}');
					vsf.get('{$bw->input[0]}/display-obj-list/{$bw->input['pageCate']}/{$bw->input['pageIndex']}', 'obj-panel')
				</script>";
		return true;
	}

	function getCategoryBox($message = "") {
		global $bw, $vsMenu;
		
		$data ['message'] = $message;
		
		$option = array ('listStyle' => "| - -", 'id' => 'obj-category', 'size' => 10 );
		$menu = new Menu ();
		$menu = $this->module->getCategories ();
		$data ['html'] = $vsMenu->displaySelectBox ( $menu->getChildren (), $option );
		
		return $this->html->categoryList ( $data );
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