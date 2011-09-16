<?php
require_once (CORE_PATH . "weblinks/weblinks.php");

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}

class weblinks_admin {
	protected $html = "";
	protected $module;
	protected $output = "";
	
	public function __construct() {
		global $vsTemplate, $vsPrint;
		$this->module = new weblinks ();
		$vsPrint->addJavaScriptFile ( "jquery/ui.datepicker" );
		$vsPrint->addCSSFile ( 'ui.datepicker' );
		$this->html = $vsTemplate->load_template ( 'skin_weblinks' );
	}
	
	function auto_run() {
		global $bw;
		switch ($bw->input [1]) {
			case 'delete-checked-obj' :
				$this->module->delete ( rtrim ( $bw->input ['checkedObj'], "," ) );
				break;
			case 'visible-checked-obj' :
				$this->module->updateStatus ( rtrim ( $bw->input ['checkedObj'], "," ), array ("weblinkStatus" => 1 ) );
				break;
			case 'hide-checked-obj' :
				$this->module->updateStatus ( rtrim ( $bw->input ['checkedObj'], "," ), array ("weblinkStatus" => 0 ) );
				break;
			case 'display-obj-tab' :
				$this->displayObjTab ();
				break;
			case 'display-obj-list' :
				$this->getObjList ( $bw->input [2], $this->module->result ['develop'] );
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
		global $bw, $vsSettings;
		if ($vsSettings->getSystemKey ( $bw->input [0] . '_category_tab', 1 ))
			$option ['categoryList'] = $this->getCategoryBox ();
		$option ['objList'] = $this->getObjList ();
		$this->output = $this->html->displayObjTab ( $option );
	}
	
	function getObjList($catId = '', $message = "") {
		global $bw, $vsStd, $vsSettings;
		$catId = intval ( $catId );
		$categories = $this->module->getCategories ();
		// Check if the catIds is specified
		// If not just get all product
		if (! intval ( $catId )) {
			$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		} else {
			$result = $this->module->vsMenu->extractNodeInTree ( $catId, $categories->getChildren () );
			if ($result)
				$strIds = trim ( $catId . "," . $this->module->vsMenu->getChildrenIdInTree ( $result ['category'] ), "," );
		}
		// Set the condition to get all product in specified category and its chidlren
		$this->module->setCondition ( $this->module->getCategoryField () . ' in (' . $strIds . ')' );
		$end = $this->module->getNumberOfObject ();
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10 );
		$limit = array ();
		if ($end > $size) {
			// Build page link for product list
			$vsStd->requireFile ( LIBS_PATH . 'Pagination.class.php' );
			$pagination = new VSFPagination ();
			$pagination->ajax = 1;
			$pagination->callbackobjectId = 'obj-panel';
			$pagination->url = "{$bw->input[0]}/display-obj-list/{$catId}/";
			$pagination->p_Size = $size;
			$pagination->p_TotalRow = $end;
			$pagination->SetCurrentPage ( 3 );
			$pagination->BuildPageLinks ();
			$limit = array ($pagination->p_StartRow, $pagination->p_Size );
		}
		$this->module->setOrder ( "{$this->module->getPrimaryField()} DESC" );
		$this->module->setLimit ( $limit );
		$this->module->getObjectsByCondition ();
		$option ['paging'] = $pagination->p_Links;
		$option ['message'] = $message;
		$option ['categoryId'] = $catId;
		$option ['info'] = $this->module->showStatusInfo ();
		return $this->output = $this->html->objListHtml ( $this->module->getArrayObj (), $option );
	}
	
	function addEditObjForm($objId = 0, $option = array()) {
		global $vsLang, $vsStd, $bw, $vsMenu, $vsSettings, $vsPrint;
		$obj = $this->module->createBasicObject ();
		$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Add', 'Add' );
		$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Add', "Add {$bw->input[0]}" );
		
		if ($objId) {
			$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Edit', 'Edit' );
			$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Edit', "Edit {$bw->input[0]}" );
			$obj = $this->module->getObjectById ( $objId );
			$obj->createCategory ();
			$this->module->vsRelation->setObjectId ( $obj->getId () );
			$this->module->vsRelation->setTableName ( "weblink_link" );
			$this->module->vsRelation->getRelByObject ();
			$option ['access'] = $this->module->vsRelation->arrval;
		}
		$vsStd->requireFile ( CORE_PATH . 'access/access.php' );
		$access = new accesses ();
		$access->setGroupby ( "accessModule,accessAction" );
		$array = $access->getObjectsByCondition ();
		
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		$vsStd->requireFile ( JAVASCRIPT_PATH . "/tiny_mce/tinyMCE.php" );
		
		$editor = new tinyMCE ();
		
		$editor->setWidth ( '100%' );
		$editor->setHeight ( '350px' );
		$editor->setToolbar ( 'full' );
		$editor->setTheme ( "advanced" );
		$editor->setInstanceName ( 'partnerContent' );
		$editor->setValue ( $obj->getContent () );
		$obj->setContent ( $editor->createHtml () );
		
		return $this->output = $this->html->addEditObjForm ( $obj, $option, $array );
	}
	
	function addEditObjProcess() {
		global $bw, $vsStd, $vsLang, $vsSettings;
		
		if ($bw->input ['weblinkStatus'])
			$bw->input ['weblinkStatus'] = 1;
		if (! $bw->input ['weblinkCatId'])
			$bw->input ['weblinkCatId'] = $this->module->getCategories ()->getId ();
		if ($bw->input ['weblinkId']) {
			$obj = $this->module->getObjectById ( $bw->input ['weblinkId'] );
			if (! $obj) {
				$alert = $vsLang->getWords ( 'weblink_editItem_error', 'found no data on demand!' );
				$this->alertMessage ($alert);
			}
			$objUpdate = $this->module->createBasicObject ();
			$objUpdate->convertToObject ( $bw->input );
			$this->module->updateObjectById ( $objUpdate );
			if($this->module->result ['status']){
				$alert = $vsLang->getWords ( 'weblink_editItem_Successful', 'you have successfully edit a weblink!' );
				$this->alertMessage ($alert);
			}
		} else {
			$this->module->obj->convertToObject ( $bw->input );
			$this->module->insertObject ( $this->module->obj );
			if($this->module->result ['status']){
				$alert = $vsLang->getWords ( 'weblink_addItem_Successful', 'you have successfully add a weblink!' );
				$this->alertMessage ($alert);
			}
		}
	}
	
	function alertMessage($alert) {
		global $bw;
		print "<script>
					vsf.alert(\"{$alert}\");
					vsf.get('{$bw->input[0]}/display-obj-list/', 'obj-panel')
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