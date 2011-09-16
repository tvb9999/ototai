<?php

require_once(CORE_PATH."recruitments/recruitments.php");

class recruitments_admin {
	protected $html = "";
	protected $module;

	protected $output = "";

	public function __construct(){
		global $vsTemplate, $vsPrint;
		$this->module = new recruitments();
		
		$vsPrint->addJavaScriptFile("jquery/ui.datepicker");
		$vsPrint->addCSSFile('ui.datepicker');
		$this->html = $vsTemplate->load_template('skin_recruitments');
	}

	function auto_run() {
		global $bw;

		switch($bw->input[1]){
			case 'delete-checked-obj':
				$this->module->delete(rtrim($bw->input['checkedObj'],","));
				break;
					
			case 'visible-checked-obj':
				$this->checkShowAll(1);
				break;
					
			case 'hide-checked-obj':
				$this->checkShowAll(0);
				break;

			case 'display-obj-tab':
				$this->displayObjTab();
				break;

			case 'display-obj-list':
				$this->getObjList($bw->input[2], $this->module->result['message']);
				break;
					
			case 'add-edit-obj-form':
				$this->addEditObjForm($bw->input[2]);
				break;

			case 'add-edit-obj-process':
				$this->addEditObjProcess();
				break;

			case 'delete-obj':		
					$this->deleteObj($bw->input[2]);
				break;

			default:
				$this->loadDefault();
		}
	}
	function deleteObj($ids,$cate = 0){
	global $bw;
	
		$this->module->setCondition("recruitmentId IN (".$ids .")");
		$list = $this->module->getObjectsByCondition();
	if(!count($list)) return false;

	$this->module->setCondition("recruitmentId IN (".$ids .")");
	if(!$this->module->deleteObjectByCondition()) return false;
	foreach ($list as $recruitment){
			$this->module->vsFile->deleteFile($recruitment->getImage());
		}	
	
	return $this->output = $this->getObjList($cate);
	}
	function checkShowAll($val = 0){
		global $bw;

		$this->module->setCondition("recruitmentId in ({$bw->input[2]})");
		$this->module->updateObjectByCondition(array('recruitmentStatus'=>$val));
		return $this->output = $this->getObjList($bw->input[3]);

	}

	function displayObjTab() {
		$option['categoryList'] = $this->getCategoryBox();
		$option['objList'] 	= $this->getObjList();
		$this->output = $this->html->displayObjTab($option);
	}
		
	function getObjList($catId='', $message=""){
		global $bw, $vsStd ,$vsSettings;
		if($bw->input['pageCate'])	{
			$catId = $bw->input['pageCate'];
			$bw->input[2] = $bw->input['pageCate'];
		}
		if($bw->input['pageIndex'])	$bw->input[3]=$bw->input['pageIndex'];
		$catId = intval($catId);
		$categories = $this->module->getCategories();
		// Check if the catIds is specified
		// If not just get all product
		if(!intval($catId)){
			$strIds = $this->module->vsMenu->getChildrenIdInTree( $categories);
		}else{
			$result = $this->module->vsMenu->extractNodeInTree($catId, $categories->getChildren());
			if($result)
			$strIds = trim($catId.",".$this->module->vsMenu->getChildrenIdInTree($result['category']),",");
		}

		// Set the condition to get all product in specified category and its chidlren
		if($strIds)
		$this->module->setCondition($this->module->getCategoryField().' in ('. $strIds. ')');

		$total = $this->module->getNumberOfObject();

		$size =  $vsSettings->getSystemKey("admin_{$bw->input[0]}_list_number",10);
		$option=$this->module->getPageList("{$bw->input[0]}/display-obj-list/{$catId}", 3,$size,1,'obj-panel');
		$option['message'] = $message;
		$option['categoryId'] = $catId;
                $option['info'] = $this->module->showStatusInfo();
		return $this->output = $this->html->objListHtml($this->module->getArrayObj(), $option);
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
			$option['categoryId'] = $obj->getCategory();
		}


		$vsPrint->addJavaScriptFile("tiny_mce/tiny_mce");
		$vsStd->requireFile(JAVASCRIPT_PATH."/tiny_mce/tinyMCE.php");
		$editor = new tinyMCE();
		$editor->setWidth('350px');
		$editor->setHeight('150px');
		$editor->setToolbar('narrow');
		$editor->setTheme("advanced");
		$editor->setInstanceName('recruitmentIntro');
		$editor->setValue($obj->getIntro());
		$obj->setIntro($editor->createHtml());


		$editor->setWidth('100%');
		$editor->setHeight('350px');
		$editor->setToolbar('full');
		$editor->setTheme("advanced");
		$editor->setInstanceName('recruitmentContent');
		$editor->setValue($obj->getContent());
		$obj->setContent($editor->createHtml());
			
		return $this->output = $this->html->addEditObjForm($obj, $option);
	}

	function addEditObjProcess(){
		global $bw, $vsStd, $vsLang ,$vsSettings;

		$bw->input['recruitmentPostDate'] = time();
		$bw->input['recruitmentStatus'] =$bw->input['recruitmentStatus']? 1:0;
		if(!$bw->input['recruitmentCatId'])
		$bw->input['recruitmentCatId']=$this->module->getCategories()->getId();
		if($bw->input['fileId'])
		$bw->input['recruitmentImage']=$bw->input['fileId'];
		elseif($bw->input['txtlink']){
			$bw->input['recruitmentImage']=$this->module->vsFile->copyFile($bw->input['txtlink'],$bw->input[0]);
		}
		
		$datetime = new VSFDateTime();
		$datetimeArray  	= explode("/",$bw->input['recruitmentBegin']);
		$datetime->day 		= $datetimeArray[0];
		$datetime->month 	= $datetimeArray[1];
		$datetime->year 	= $datetimeArray[2];
		$bw->input['recruitmentBegin'] = $datetime->TimeToInt();
		
		$datetimeArray  	= explode("/",$bw->input['recruitmentEnd']);
		$datetime->day 		= $datetimeArray[0];
		$datetime->month 	= $datetimeArray[1];
		$datetime->year 	= $datetimeArray[2];
		$bw->input['recruitmentEnd'] = $datetime->TimeToInt();
		
		if($bw->input['recruitmentId']){
				
			$obj = $this->module->getObjectById($bw->input['recruitmentId']);
			$imageOld =$obj->getImage();
			if(!$obj){
				$this->alertMessage();
			}
			$objUpdate = $this->module->createBasicObject();
			$objUpdate->convertToObject($bw->input);


			$this->module->updateObjectById($objUpdate);
			
			if ($bw->input ['pageDeleteImage'] || $bw->input ['recruitmentImage'])
				$this->module->vsFile->deleteFile ($imageOld);


			if(!$this->module->result['status'])
				$this->module->reportError();
		}
		else{
			$this->module->obj->convertToObject($bw->input);
			$this->module->insertObject($this->module->obj);
		}
		if($vsSettings->getSystemKey("{$bw->input[0]}_multi_file",1))
		{
			$vsStd->requireFile(LIBS_PATH."Relationship.class.php");
			$rel = new VSFRelationship();
			$rel->setObjectId($this->module->obj->getId());
			$rel->setRelId($bw->input['fileId']);
			$rel->setTableName($this->module->getRelTableName());
			$rel->insertRel();
		}
		else
		{
			if($imageOld&&$bw->input['fileId']){
				$this->module->files->deleteFile($imageOld);
			}
		}
		global $DB;
		print "<pre>";
		print_r($DB->obj);
		print "</pre>";
		$this->alertMessage();
	}
	function alertMessage() {
		global $bw ;

		print 	"<script>
					vsf.alert(\"{$this->module->result['message']}\");
					vsf.get('{$bw->input[0]}/display-obj-list/{$bw->input['pageCate']}/{$bw->input['pageIndex']}', 'obj-panel')
				</script>";	
		return true;
	}

	function getCategoryBox($message="") {
		global $bw , $vsMenu;

		$data['message'] = $message;

		$option = array('listStyle' => "| - -",
						'id'		=> 'obj-category',
						'size'		=> 10,
		);
		$menu = new Menu();
		$menu = $this->module->getCategories();
		$data['html'] = $vsMenu->displaySelectBox($menu->getChildren(), $option);

		return $this->html->categoryList($data);
	}

	function loadDefault() {
		global $vsPrint;

		$vsPrint->addJavaScriptFile("tiny_mce/tiny_mce");

		$vsPrint->addJavaScriptString('init_tab','
			$(document).ready(function(){
    			$("#page_tabs").tabs({
    				cache: false
    			});
  			});
		');

		$this->setOutput($this->html->managerObjHtml());
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