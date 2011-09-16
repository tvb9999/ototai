<?php

require_once (CORE_PATH . "partners/partners.php");

class partners_admin {
	protected $html = "";
	protected $module;
	protected $output = "";
	
	public function __construct() {
		global $vsTemplate, $vsPrint;
		$this->module = new partners ();
		$this->html = $vsTemplate->load_template ( 'skin_partners' );
	}
	
	function auto_run() {
		global $bw;
		switch ($bw->input [1]) {
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
				$this->getObjList ( $bw->input [2], $this->module->result ['developer'] );
				break;
			case 'display-obj-list-search' :
				$this->getObjListSearch ( $bw->input [2], $bw->input [3], $this->module->result ['message'] );
				break;
			case 'add-edit-obj-form' :
				$this->addEditObjForm ( $bw->input [2], $bw->input [3] );
				break;
			case 'add-edit-obj-process' :
				$this->addEditObjProcess ();
				break;
			case 'delete-obj' :
				$this->module->delete ( $bw->input [2] );
				break;
			case 'moduleObjTab' :
				$this->moduleObjTab ( $bw->input [2] );
				break;
			default :
				$this->loadDefault ();
				break;
		}
	}
	
	function moduleObjTab($module = "global", $ioption = array()) {
		global $bw;
		
		$catId = 0;		
		$bw->input ['key'] = $module?$module.'_banner':$bw->input[0];		
		if ($module != "global")
			$catId = $this->module->convertToCatId ( $module );
		
		$option ['type'] = 'moduleObj';
		
		$bw->input ['type'] = 'moduleObj';
		$option ['form'] = $this->addEditObjForm ( $catId, 0 ,$option);
		
		$option ['list'] = $this->getObjList ( $catId,'',$module );
		
		return $this->output = $this->html->moduleObjTab ( $option );
	}
	
	function displayObjTab() {
		global $bw, $vsSettings;
		if ($vsSettings->getSystemKey($bw->input['key'].'_category_show', 1, $option ['module'], 1,1))
			$option ['categoryList'] = $this->getCategoryBox ();
		$option ['objList'] = $this->getObjList ();
		$this->output = $this->html->displayObjTab ( $option );
	}
	
	function checkShowAll($val = 0) {
		global $bw;
		
		$this->module->setCondition ( "partnerId in ({$bw->input[2]})" );
		$this->module->updateObjectByCondition ( array ('partnerStatus' => $val ) );
		return $this->output = $this->getObjList ( $bw->input [3] );
	
	}
	
	function getObjListSearch($searchContent = '', $searchType = '', $message = "") {
		global $bw, $vsStd, $vsSettings;
		
		$searchContent = VSFTextCode::removeAccent ( strip_tags ( $searchContent ) );
		
		$categories = $this->module->getCategories ();
		
		$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		
		$this->module->setCondition ( 'partnerCatId in (' . $this->module->vsMenu->getChildrenIdInTree ( $categories ) . ')' ); //Condition for get ClearSearchStrings
		$searchStrings = $this->module->getSearchStrings ();
		
		$this->module->setCondition ( 'partnerCatId in (' . $strIds . ') and ' . $this->module->createSearchCondition ( $searchContent, $searchType, $bw->input [0] ) );
		
		$total = $this->module->getNumberOfObject ();
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10 );
		
		$option = $this->module->getPageList ( "{$bw->input[0]}/display-obj-list-search/{$searchContent}", 3, $size, 1, 'obj-panel' );
		
		$option ['searchStrings'] = $searchStrings;
		$option ['message'] = $message;
		$option ['categoryId'] = $catId;
		$option ['search'] = 1;
		
		return $this->output = $this->html->objListHtml ( $this->module->getArrayObj (), $option );
	}
	
	function getObjList($catId = '', $message = "",$module = '') {
		global $bw, $vsStd, $vsSettings,$DB;
		
		$bw->input ['key'] = $module?$module.'_banner':$bw->input[0];
		$catId = intval ( $catId );
		
		$categories = $this->module->getCategories ();
		
		if (! intval ( $catId )) {
			$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		} else {
			
			$strIds = trim ( $catId . "," . $this->module->vsMenu->getChildrenIdInTree ( $catId ), "," );
		}
		
		if($bw->input[2] != $categories->getId() and $catId){//Not Module Partners
			$this->module->setCondition ( 'partnerCatId in (' . $strIds . ')' );//$strIds
		}else{
			$this->module->setCondition ( 'partnerCatId in (' . $categories->getId() . ')' );
		}
		$searchStrings = $this->module->getSearchStrings ();
	
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10 ,$module,1,1);
		$limit = array ();
		//$this->module->setOrder ( "{$this->module->getPrimaryField()} DESC" );
		$this->module->setOrder ( "partnerPosition ASC, partnerIndex ASC" );
		$this->module->setLimit ( $limit );
		$option = $this->module->getPageList ( "partners/display-obj-list/$catId", 3, $size, 1, 'obj-panel' );
		
		$option ['searchStrings'] = $searchStrings;
		$option ['message'] = $message;
		$option ['categoryId'] = $catId;
		$option ['module'] = $module;
		return $this->output = $this->html->objListHtml ( $this->module->getArrayObj (), $option );
	}
	
	function addEditObjForm($catId = 0, $objId = 0, $option = array()) {
		global $vsLang, $vsStd, $bw, $vsMenu, $vsSettings, $vsPrint;
		
		$bw->input ['key'] = $option ['module']?$option ['module'].'_banner':$bw->input[0];
		$option ['module'] = $option ['module']?$option ['module']:$bw->input[0];
		
		$obj = $this->module->createBasicObject ();
		
		$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Add', 'Add' );
		$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Add', "Add {$bw->input[0]}" );
		$obj->setStatus ( 1 );
		if ($objId) {
			$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Edit', 'Edit' );
			$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Edit', "Edit {$bw->input[0]}" );
			$obj = $this->module->getObjectById ( $objId );
		}
		
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		$vsStd->requireFile ( JAVASCRIPT_PATH . "/tiny_mce/tinyMCE.php" );
		
		$editor = new tinyMCE ();
		
		$editor->setWidth ( $vsSettings->getSystemKey ( $bw->input ['key'] . "_content_editor_width", '100%', $option ['module'], 1, 1 ) );
		$editor->setHeight ( $vsSettings->getSystemKey ( $bw->input ['key'] . "_content_editor_height", '350px', $option ['module'], 1, 1 ) );
		$editor->setToolbar ( $vsSettings->getSystemKey ( $bw->input ['key'] . "_content_editor_toolbar", 'full', $option ['module'], 1, 1 ) );
		$editor->setTheme ( $vsSettings->getSystemKey ( $bw->input ['key'] . "_content_editor_theme", "advanced", $option ['module'], 1, 1 ) );
		$editor->setInstanceName ( 'partnerContent' );
		$editor->setValue ( $obj->getContent (-1) );
		$obj->setContent ( $editor->createHtml () );
		$datetime = new VSFDateTime ();
		$obj->setCatId ( $catId );
		return $this->output = $this->html->addEditObjForm ( $obj, $option, $array );
	}
	
	function addEditObjProcess() {
		global $bw, $vsStd, $vsLang, $vsSettings;
		
		$imageOld = $bw->input ['oldImage'];
		
		if (($bw->input ['partnerDeleteImage'] || $bw->input ['fileId']) && $imageOld) {
			$this->module->vsFile->deleteFile ( $imageOld );
		}
		
		if ($bw->input ['partnerDeleteImage'] && ! $bw->input ['fileId'])
			$bw->input ['partnerFileId'] = 0;
		
		$bw->input ['partnerStatus'] ? $bw->input ['partnerStatus'] : 1;
		
		if (! $bw->input ['partnerCatId'])
			$bw->input ['partnerCatId'] = $this->module->getCategories ()->getId ();
		
		if ($bw->input ['fileId'])
			$bw->input ['partnerFileId'] = $bw->input ['fileId'];
		
		$datetime = new VSFDateTime ();
		$datetimeArray = explode ( "/", $bw->input ['partnerExpTime'] );
		$datetime->day = $datetimeArray [0];
		$datetime->month = $datetimeArray [1];
		$datetime->year = $datetimeArray [2];
		$bw->input ['partnerExpTime'] = $datetime->TimeToInt ();
		
		$datetimeArray = explode ( "/", $bw->input ['partnerBeginTime'] );
		$datetime->day = $datetimeArray [0];
		$datetime->month = $datetimeArray [1];
		$datetime->year = $datetimeArray [2];
		$bw->input ['partnerBeginTime'] = $datetime->TimeToInt ();
		
		// If there is Object Id passed, processing updating Object
		if ($bw->input ['partnerId']) {
			$obj = $this->module->getObjectById ( $bw->input ['partnerId'] );
			$imageOld = $obj->getFileId ();
			if (! $obj) {
				$this->alertMessage ();
			}
			$objUpdate = $this->module->createBasicObject ();
			$objUpdate->convertToObject ( $bw->input );
			
			$this->module->updateObjectById ( $objUpdate );
			if ($bw->input ['relAccess']) {
				$this->module->vsRelation->setObjectId ( $this->module->obj->getId () );
				$this->module->vsRelation->setRelId ( $bw->input ['relAccess'] );
				$this->module->vsRelation->setTableName ( "partner_link" );
				$this->module->vsRelation->insertRel ();
			}
			if (! $this->module->result ['status']) {
				$this->module->reportError ();
			}
		} else { //Add partner
			$bw->input ['relAccess'] ? "" : $bw->input ['relAccess'] = - 1;
			$this->module->obj->convertToObject ( $bw->input );
			$this->module->insertObject ( $this->module->obj );
			if ($this->module->result ['status']) {
				$this->module->vsRelation->setObjectId ( $this->module->obj->getId () );
				$this->module->vsRelation->setRelId ( $bw->input ['relAccess'] );
				$this->module->vsRelation->setTableName ( "partner_link" );
				$this->module->vsRelation->insertRel ();
			}
		}
		
		//		if($vsSettings->getSystemKey("{$bw->input[0]}_multi_file",1))
		//		{
		//			$this->module->vsRelation->setObjectId($this->module->obj->getId());
		//			$this->module->vsRelation->setRelId($bw->input['fileId']);
		//			$this->module->vsRelation->setTableName($this->module->getRelTableName());
		//			$this->module->vsRelation->insertRel();
		//		}
		//		else
		//		{
		// nêu khong dùng multi file thì sẽ loại bỏ những file dư thừa
		//		if(($imageOld&&$bw->input['fileId'])||($imageOld&&$bw->input['partnerDeleteImage'])){
		//			$this->module->vsFile->deleteFile($imageOld);
		//                        if($bw->input['partnerDeleteImage']) $
		//                }
		

		$this->alertMessage ( $this->module->obj->getCatId () );
	}
	
	function alertMessage($categoryId = null) {
		global $bw;
		print "<script>
					vsf.alert(\"{$this->module->result['developer']}\");
					vsf.get('{$bw->input[0]}/display-obj-list/{$categoryId}/', 'obj-panel')
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
		$this->module->setFields ( "partnerId" );
		
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		
		$vsPrint->addJavaScriptString ( 'init_tab', '
			$(function(){
    			$("#page_tabs").tabs({cache: false});
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