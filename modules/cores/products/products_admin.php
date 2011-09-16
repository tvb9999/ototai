<?php

require_once (CORE_PATH . "products/products.php");

class products_admin {
	protected $html = "";
	protected $module;
	
	protected $output = "";

	public function __construct() {
		global $vsTemplate, $vsPrint;
		$this->module = new products ();
		$this->html = $vsTemplate->load_template ( 'skin_products' );
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
			
			case 'display-product-tab' :
				$this->displayObjTab ();
				break;
			
			case 'display-obj-list' :
				$this->getObjList ( $bw->input [2], $this->module->result ['message'] );
				break;
			case 'display-obj-list-search' :
				$this->getObjListSearch ( $bw->input [2], $bw->input [3], $this->module->result ['message'] );
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

	function checkShowAll($val = 0) {
		global $bw;
		$this->module->setCondition ( "productId in ({$bw->input[2]})" );
		$this->module->updateObjectByCondition ( array ('productStatus' => $val ) );
		return $this->output = $this->getObjList ( $bw->input [3] );
	
	}

	function displayObjTab() {
		$option ['categoryList'] = $this->getCategoryBox ();
		$option ['objList'] = $this->getObjList ();
		
		$this->output = $this->html->displayObjTab ( $option );
	}

	function getObjListSearch($searchContent = '', $searchType = '', $message = "") {
		global $bw, $vsStd, $vsSettings;
		
		$searchContent = VSFTextCode::removeAccent ( strip_tags ( $searchContent ) );
		
		if ($bw->input ['pageIndex'])
			$bw->input [3] = $bw->input ['pageIndex'];
		
		$categories = $this->module->getCategories ();
		
		$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		
		$this->module->setCondition ( 'productCatId in (' . $this->module->vsMenu->getChildrenIdInTree ( $categories ) . ')' ); //Condition for get ClearSearchStrings
		$searchStrings = $this->module->getSearchStrings ();
		
		$this->module->setCondition ( 'productCatId in (' . $strIds . ') and ' . $this->module->createSearchCondition ( $searchContent, $searchType, $bw->input [0] ) );
		
		$total = $this->module->getNumberOfObject ();
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10 );
		
		$option = $this->module->getPageList ( "{$bw->input[0]}/display-obj-list-search/{$searchContent}", 3, $size, 1, 'obj-panel' );
		
		$option ['searchStrings'] = $searchStrings;
		$option ['message'] = $message;
		$option ['categoryId'] = $catId;
		$option ['search'] = 1;
		
		return $this->output = $this->html->objListHtml ( $this->module->getArrayObj (), $option );
	}

	function getObjList($catId = '', $message = "") {
		global $bw, $vsStd, $vsSettings;
		
		$catId = intval ( $catId );
		
		$categories = $this->module->getCategories ();
		
		if (! intval ( $catId )) {
			$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		} else {
			if ($vsSettings->getSystemKey ( "products_multi_category", 0, "products", 0, 1 )) {
				$vsStd->requireFile ( LIBS_PATH . "Relationship.class.php" );
				$rel = new VSFRelationship ();
				$rel->setRelId ( $catId );
				$rel->setTableName ( $this->module->getRelTableName () );
				$strIds = $rel->getObjectByRel ();
			} else {
				$result = $this->module->vsMenu->extractNodeInTree ( $catId, $categories->getChildren () );
				if ($result)
					$strIds = trim ( $catId . "," . $this->module->vsMenu->getChildrenIdInTree ( $result ['category'] ), "," );
				
			}
		}
		if ($strIds)
			$this->module->setCondition ( 'productCatId in (' . $strIds . ')' );
//		$this->module->setOrder("productIndex DESC, productId DESC");
		$searchStrings = $this->module->getSearchStrings ();
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10 );
		
		$option = $this->module->getPageList ( "{$bw->input[0]}/display-obj-list/{$catId}", 3, $size, 1, 'obj-panel' );
		
		$option ['searchStrings'] = $searchStrings;
		$option ['message'] = $message;
		$option ['categoryId'] = $catId;
		return $this->output = $this->html->objListHtml ( $this->module->getArrayObj (), $option );
	}

	function addEditObjForm($objId = 0, $option = array()) {
		global $vsLang, $vsStd, $bw, $vsSettings, $vsPrint;
		
		$obj = $this->module->createBasicObject ();
		$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Add', 'Add' );
		$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Add', "Add {$bw->input[0]}" );
		
		if ($objId) {
			$option ['formSubmit'] = $vsLang->getWords ( 'obj_EditObjFormButton_Edit', 'Edit' );
			$option ['formTitle'] = $vsLang->getWords ( 'obj_EditObjFormTitile_Edit', "Edit {$bw->input[0]}" );
			$obj = $this->module->getObjectById ( $objId );
			$option ['categoryId'] = $obj->getCategory ();
		}
		
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		$vsStd->requireFile ( JAVASCRIPT_PATH . "/tiny_mce/tinyMCE.php" );
		
		$editor = new tinyMCE ();
		if ($vsSettings->getSystemKey ( $bw->input [0] . "_intro", 1, $bw->input [0], 1, 1 )) {
			if ($vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor", 1, $bw->input [0], 1, 1 )) {
				$editor->setWidth ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_width", '100%', $bw->input [0], 1, 1 ) );
				$editor->setHeight ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_height", '150px', $bw->input [0], 1, 1 ) );
				$editor->setToolbar ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_toolbar", 'narrow', $bw->input [0], 1, 1 ) );
				$editor->setTheme ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_theme", "advanced", $bw->input [0], 1, 1 ) );
				$editor->setInstanceName ( 'productIntro' );
				$editor->setValue ( $obj->getIntro (-1) );
				$obj->setIntro ( $editor->createHtml () );
			} else
				$obj->setIntro ( "<textarea name='productIntro' style='width:375px; height:150px;'>" . strip_tags ( $this->module->obj->getIntro () ) . "</textarea>" );
		}
		
		if ($vsSettings->getSystemKey ( $bw->input [0] . "_content", 1, $bw->input [0], 1, 1 )) {
			if ($vsSettings->getSystemKey ( $bw->input [0] . "_content_editor", 1, $bw->input [0], 1, 1 )) {
				$editor->setWidth ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_width", '100%', $bw->input [0], 1, 1 ) );
				$editor->setHeight ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_height", '350px', $bw->input [0], 1, 1 ) );
				$editor->setToolbar ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_toolbar", 'full', $bw->input [0], 1, 1 ) );
				$editor->setTheme ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_theme", "advanced", $bw->input [0], 1, 1 ) );
				$editor->setInstanceName ( 'productContent' );
				$editor->setValue ( $obj->getContent (-1) );
				$obj->setContent ( $editor->createHtml () );
			} else
				$obj->setContent ( "<textarea name='productContent' style='width:100%; height:100px;'>" . strip_tags ( $this->module->obj->getContent () ) . "</textarea>" );
		}
		
		return $this->output = $this->html->addEditObjForm ( $obj, $option );
	}

	function addEditObjProcess() {
		global $bw, $vsStd, $vsLang, $vsSettings;
		
		$bw->input ['productStatus'] = $bw->input ['productStatus'] ? $bw->input ['productStatus'] : 1;
		
		if (! $bw->input ['productCatId'])
			$bw->input ['productCatId'] = $this->module->getCategories ()->getId ();
		
		if ($bw->input ['fileId'])
			$bw->input ['productImage'] = $bw->input ['fileId'];
		elseif ($bw->input ['txtlink'])
			$bw->input ['productImage'] = $this->module->vsFile->copyFile ( $bw->input ['txtlink'], "product" );
		
		if ($bw->input ['productId']) {
			$obj = $this->module->getObjectById ( $bw->input ['productId'] );
			$imageOld = $obj->getImage ();
			if (! $obj)
				$this->alertMessage ( $vsLang->getWords ( 'obj_no_exist', 'This item does not exist' ) );
			
			if ($imageOld && $bw->input ['deleteImage']) {
				$this->module->vsFile->deleteFile ( $imageOld );
				if (! $bw->input ['productImage'])
					$bw->input ['productImage'] = 0;
			}
			
			if ($imageOld && $bw->input ['fileId']){
				$this->module->vsFile->deleteFile ( $imageOld );
			}
			
			$objUpdate = $this->module->createBasicObject ();
			$objUpdate->convertToObject ( $bw->input );
			
			$this->module->updateObjectById ( $objUpdate );
			
			if (! $this->module->result ['status']) {
				$this->module->reportError ( $vsLang->getWords ( 'global_general_error', 'There is an error' ) );
			}
			$message = $vsLang->getWords ( 'edit_successful', 'Edit Successful' );
		} else {
			$bw->input ['productPostDate'] = time ();
			$this->module->obj->convertToObject ( $bw->input );
			
			$this->module->insertObject ();
			
			if (! $this->module->result ['status'])
				$this->module->reportError ( $vsLang->getWords ( 'global_general_error', 'There is an error' ) );
			
			$message = $vsLang->getWords ( 'add_successfully', 'Add Successfully' );
		}
		
		if ($vsSettings->getSystemKey ( $bw->input [0] . "_multi_category", 0, "products", 0, 1 ) && $this->module->result ['status']) {
			$vsStd->requireFile ( LIBS_PATH . "Relationship.class.php" );
			$rel = new VSFRelationship ();
			$rel->setObjectId ( $this->module->obj->getId () );
			$rel->setRelId ( $bw->input ['productCatId'] );
			$rel->setTableName ( $this->module->getRelTableName () );
			$rel->insertRel ();
		}
		
		$this->alertMessage ( $message );
	}

	function alertMessage($message = "") {
		global $bw;
		if ($message)
			print "<script>
						vsf.alert('{$message}');
						vsf.get('{$bw->input[0]}/display-obj-list/{$bw->input['productCatId']}/{$bw->input['pageIndex']}', 'obj-panel')
				 </script>";
		return true;
	}

	function getCategoryBox($message = "") {
		global $bw, $vsMenu, $vsSettings;
		$data ['message'] = $message;
		
		$option = array ('listStyle' => "| - -", 'id' => "obj-category", 'size' => 10 );
		
		if ($vsSettings->getSystemKey ( "{$bw->input[0]}_multi_cat", 1 ))
			$option ['multiple'] = "multiple";
		$menu = new Menu ();
		$menu = $this->module->getCategories ();
		$data ['html'] = $vsMenu->displaySelectBox ( $menu->getChildren (), $option );
		
		return $this->html->categoryList ( $data );
	}

	function loadDefault() {
		global $vsPrint;
		
		unset ( $_SESSION ['opt'] );
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		
		$vsPrint->addJavaScriptString ( 'init_tab', '
			$(document).ready(function(){
    			$("#page_tabs").tabs({
    				cache: false
    			});
  			});
		' );
		
		$this->setOutput ( $this->html->mainPage () );
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