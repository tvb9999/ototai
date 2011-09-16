<?php
if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (REDSUN FW is powered by <a href=\"http://www.redsunic.com\">REDSUNIC Webdesign Company</a>)";
	exit ();
}

require_once CORE_PATH . 'settings/settings.php';

class settings_admin {
	private $output = "";
	private $html = "";
	protected $module;

	public function __construct() {
		global $vsTemplate;
		$this->module = new settings ();
		$this->html = $vsTemplate->load_template ( 'skin_settings' );
	}

	function auto_run() {
		global $bw;
		
		switch ($bw->input [1]) {
			
			case 'display-obj-tab' :
				$this->displayObjTab ();
				break;
			
			case 'getObjList' :
				$this->getObjList ( $bw->input [2] );
				break;
			
			case 'editForm' :
				$this->editForm ( $bw->input [2], $bw->input [3] );
				break;
			case 'typeValue' :
				$this->output = $this->module->obj->buildElementForm ( 'settingValue', $bw->input [2], false, false, array ('size' => 27 ) );
				break;
			case 'editObj' :
				$this->editObj ();
				break;
			
			case 'deleteObj' :
				$this->deleteObj ( $bw->input [2], $bw->input [3] );
				break;
			
			case 'moduleObjTab' :
				$this->moduleObjTab ( $bw->input [2] );
				break;
			
			case 'display-obj-list-search' :
				$this->getObjListSearch ( $bw->input [2], $bw->input [3] );
				break;
			default :
				$this->loadDefault ();
		}
	}

	function getObjListSearch($searchContent = '', $catId = '') {
		global $bw, $vsStd, $vsSettings;
		
		$searchContent = VSFTextCode::removeAccent ( strip_tags ( $searchContent )," " );
		
		$this->module->setCondition ( "settingCatId = $catId" ); //Condition for get ClearSearchStrings
		$searchStrings = $this->module->getSearchStrings ();
		
		$this->module->setCondition ( 'settingCatId = ' . $catId . ' and settingTitle LIKE ' . '"%' . $searchContent . '%"' );
		
		$option = $this->module->getPageList ( "{$bw->input[0]}/display-obj-list-search/{$searchContent}/$catId", 4, 20, 1, 'setting-table' );
		
		$option ['searchStrings'] = $searchStrings;
		$option ['categoryId'] = $catId;
		$option ['search'] = 1;
		$arrayCat = $this->getArrayCatChildren ();
		$option ['cat'] = $arrayCat [$catId];
		$option ['pIndex'] = $bw->input [$pageIndex] ? $bw->input [$pageIndex] : 1;
		
		return $this->output = $this->html->objListHtml ( $option );
	}

	function moduleObjTab($module = "global", $ioption = array()) {
		global $bw;
		
		$catId = 0;
		
		if ($module != "global")
			$catId = $this->module->convertToCatId ( $module );
		
		$option ['type'] = 'moduleObj';
		
		$bw->input ['type'] = 'moduleObj';
		
		$option ['form'] = $this->editForm ( $catId, 0, $option );
		
		$option ['message'] = $ioption ['message'];
		
		$option ['list'] = $this->getObjList ( $catId, $option );
		
		return $this->output = $this->html->moduleObjTab ( $option );
	}

	function displayObjTab($module = "", $message = "") {
		global $vsMenu, $vsUser;
		
		if (! $module)
			$module = 0;
		$this->module->categories->getId ();
		$objList = $this->getObjList ( $module );
		
		$arrayCat = $this->getArrayCatChildren ();
		
		return $this->output = $this->html->displayObjTab ( $objList, $arrayCat, $message );
	}

	function getObjList($catId, $ioption = array()) {
		global $vsMenu, $vsLang, $vsUser, $bw;
		
		if (! $vsUser->checkRoot ())
			$condition = " AND settingRoot = 0";
		$this->module->setCondition ( "settingCatId in (" . $catId . ")" . $condition );
		$this->module->setOrder ( 'settingIndex' );
		$searchStrings = $this->module->getSearchStrings ();
		$url = "settings/getObjList/" . $catId;
		$pageIndex = 3;
		
		$size = $this->module->getSystemKey ( 'obj_quality', 10, "settings", 1 );
		$option = $this->module->getPageList ( $url, $pageIndex, $size, 1, 'setting-table' );
		
		$arrayCat = $this->getArrayCatChildren ();
		$option ['searchStrings'] = $searchStrings;
		$option ['cat'] = $arrayCat [$catId];
		$option ['message'] = $ioption ['message'];
		$option ['type'] = $ioption ['type'];
		$option ['pIndex'] = $bw->input [$pageIndex] ? $bw->input [$pageIndex] : 1;
		
		return $this->output = $this->html->objListHtml ( $option );
	}

	function getArrayCatChildren() {
		global $vsMenu, $vsLang;
		
		$categories = $this->module->categories->getChildren ();
		
		foreach ( $categories as $category ) {
			$temp [$category->getId ()] ['id'] = $category->getId ();
			$temp [$category->getId ()] ['title'] = ucwords ( $category->getTitle () );
			$temp [$category->getId ()] ['module'] = ucwords ( $category->getUrl () );
		}
		
		usort ( $temp, array ("settings_admin", "sorter" ) );
		
		foreach ( $temp as $key => $category )
			$arrCat [$category ['id']] = $category;
		
		$arrCat [0] ['id'] = 0;
		$arrCat [0] ['title'] = $vsLang->getWords ( 'group_global', 'Global' );
		$arrCat [0] ['module'] = $vsLang->getWords ( 'group_global_module', 'global' );
		return $arrCat;
	}

	function sorter($a, $b) {
		if ($a ["title"] > $b ["title"])
			return 1;
		return 0;
	}

	function editForm($catId = 0, $objId = 0, $option = array()) {
		global $vsLang, $bw;
		
		$option ['title'] = $vsLang->getWords ( 'add', 'Add' );
		if ($objId) {
			$option ['title'] = $vsLang->getWords ( 'edit', 'Edit' );
			$this->module->getObjectById ( $objId );
		}
		$option ['category'] = $this->getArrayCatChildren ();
		
		$option ['input'] = array ('text', 'checkbox', 'radio', 'password' );
		$option ['type'] = $bw->input ['type'];
		$option ['catId'] = $catId;
		
		$this->module->obj->setCatId ( $catId );
		return $this->output = $this->html->editForm ( $this->module->obj, $option );
	}

	function editObj() {
		global $vsLang, $bw, $vsSettings;
		
		$bw->input ['settingRoot'] = $bw->input ['settingRoot'] ? 1 : 0;
		$bw->input ['settingValue'] = $bw->input ["settingValue{$bw->input['settingId']}"]?$bw->input ["settingValue{$bw->input['settingId']}"]:0;
		$cats = $this->getArrayCatChildren ();

		$bw->input ['settingModule'] = strtolower ( $cats [$bw->input ['settingCatId']] ['module'] );
		$this->module->obj->convertToObject ( $bw->input );
		
		if ($this->module->obj->getId ()) {
			$this->module->updateObject ();
			if ($this->module->result ['status']) {
				$this->module->obj = new Setting ();
				$option ['message'] = $vsLang->getWords ( 'update_successful', 'Update successful' );
			} else {
				$option ['message'] = $vsLang->getWords ( 'update_error', 'Update error' );
				return $this->output = $this->editForm ( $bw->input ['settingCatId'], 0, $option );
			}
		} else {
			$key = $bw->input ['settingKey'];
			$option ['message'] = $vsLang->getWords ( 'insert_error', 'Insert error' );
			if ($bw->vars [$key]) {
				$option ['message'] = $vsLang->getWords ( 'insert_exist', 'This key is existed' );
				return $this->output = $this->editForm ( $bw->input ['settingCatId'], 0, $option );
			}
			
			$this->module->insertObject ();
			if ($this->module->result ['status']) {
				$option ['message'] = $vsLang->getWords ( 'insert_successful', 'Insert successful' );
				$this->module->obj = new Setting ();
			} else {
				return $this->output = $this->editForm ( $bw->input ['settingCatId'], 0, $option );
			}
		}
		
		$vsSettings->builcachesetting = 2;
		$vsSettings->buildType = $bw->input ['settingType'];
		$bw->input [3] = $bw->input ['pIndex'];
		if ($bw->input ['type'])
			return $this->moduleObjTab ( $bw->input ['settingModule'], $option );
		return $this->getObjList ( $bw->input ['settingCatId'], $option );
	}

	function deleteObj($catId = 0, $objId = 0) {
		global $vsLang, $vsSettings, $bw;
		$this->module->setCondition ( "settingId in (" . $objId . ")" );
		$this->module->deleteObjectByCondition ();
		
		$option ['message'] = $vsLang->getWords ( 'delete_error', 'Delete error' );
		if ($this->module->result ['status'])
			$option ['message'] = $vsLang->getWords ( 'delete_successful', 'Delete successful' );
		
		$vsSettings->builcachesetting = 2;
		$bw->input [3] = $bw->input ['pIndex'];
		$this->getObjList ( $catId, $option );
	}

	function loadDefault() {
		global $vsPrint;
		
		$vsPrint->addJavaScriptString ( 'init_tab', '
			$(document).ready(function(){
    			$("#page_tabs").tabs({
    				cache: false
    			});
  			});
		' );
		
		$this->output = $this->html->loadDefault ();
	
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