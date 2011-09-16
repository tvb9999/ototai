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
require_once(CORE_PATH."polls/polls.php");

class polls_admin {
	protected $html = "";
	protected $module;

	protected $output = "";

	public function __construct(){
		global $vsTemplate;

		$this->module = new polls();
		$this->html = $vsTemplate->load_template('skin_polls');
	}

	function auto_run() {
		global $bw;
		switch($bw->input[1]){
			case 'delete-checked-obj':
				$this->module->delete(rtrim($bw->input['checkedObj'],","));
				break;
					
			case 'visible-checked-obj':
				$this->module->updateStatus(rtrim($bw->input['checkedObj'],","),array("pollStatus" => 1));
				break;

			case 'hide-checked-obj':
				$this->module->updateStatus(rtrim($bw->input['checkedObj'],","),array("pollStatus" => 0));
				break;

			case 'display-obj-tab':
				$this->displayObjTab();
				break;

			case 'display-obj-list':
				$this->getObjList($bw->input[2],$bw->input[3], $this->module->result['message']);
				break;
					
			case 'add-edit-obj-form':
				$this->addEditObjForm($bw->input[2]);
				break;

			case 'add-edit-obj-process':
				$this->addEditObjProcess();
				break;

			case 'delete-obj':
				$this->module->delete($bw->input[2]);
				break;

			case "display-answer-tab":
				$this->displayAnswer();
				break;	
				
			default:
				$this->loadDefault();
		}
	}

	function displayAnswer(){
		$menu = $this->module->getCategories();
		$answerList = $this->getObjList('','answerListHtml');
		$this->output = $this->html->displayAnswer($menu,$answerList);
	}
	
	function displayObjTab() {
		global $bw, $vsSettings;
		if($vsSettings->getSystemKey($bw->input[0].'_category_tab',1))
		$option['categoryList'] = $this->getCategoryBox();
		$this->output = $this->html->displayObjTab($option);
	}
		
	function getObjList($catId='',$action="objListHtml",$message=""){
		global $bw, $vsSettings;
		$catId = intval($catId);
		$categories = $this->module->getCategories();
		// Check if the catIds is specified
		// If not just get all product
		if(intval($catId)){
			$result = $this->module->vsMenu->extractNodeInTree($catId, $categories->getChildren());
			if($result)
			$strIds = trim($catId.",".$this->module->vsMenu->getChildrenIdInTree($result['category']),",");
		}
		if(!$strIds)
		$strIds = $this->module->vsMenu->getChildrenIdInTree($categories);
		// Set the condition to get all product in specified category and its chidlren
		$this->module->setCondition($this->module->getCategoryField().' in ('. $strIds. ')');
		$size = $vsSettings->getSystemKey("admin_{$bw->input[0]}_list_number",10);

		$option=$this->module->getPageList("{$bw->input[0]}/display-obj-list/{$catId}/$action/", 3,$size,1,'obj-panel');
		$option['message'] = $message;
		$option['categoryId'] = $catId;
		$option['totalClick'] = $this->module->getTotalClick($catId);
		return $this->output = $this->html->$action($this->module->getArrayObj(), $option);
	}

	function addEditObjForm($objId=0, $option=array()){
		global $vsLang, $vsStd,$bw;
		$obj = $this->module->createBasicObject();
		$option['formSubmit'] = $vsLang->getWords('obj_EditObjFormButton_Add', 'Add');
		$option['formTitle']  = $vsLang->getWords('obj_EditObjFormTitile_Add', "Add {$bw->input[0]}");

		if($objId){
			$option['formSubmit'] = $vsLang->getWords('obj_EditObjFormButton_Edit', 'Edit');
			$option['formTitle']  = $vsLang->getWords('obj_EditObjFormTitile_Edit', "Edit {$bw->input[0]}");
			$obj = $this->module->getObjectById($objId);
			$obj->createCategory();
			$option['categoryId'] = $obj->getCategory()->getId();
			$obj->setStatus($obj->getStatus()?'checked':'');
		} else
		$obj->setStatus('checked');

		$vsStd->requireFile(UTILS_PATH."class_editor.php");
		$editor = new class_editor();
		$editor->setValue($obj->getContent());
		$editorHtml=$editor->createEditor("pollContent",array('width'=>'100%','height'=>'350px'));
		$obj->setContent($editorHtml);
		$editor->setToolbarSet("narrow");
		$editor->setValue($obj->getIntro());
		$editorHtml=$editor->createEditor("pollIntro",array('width'=>'100%','height'=>'150px'));
		$obj->setIntro($editorHtml);
		return $this->output = $this->html->addEditObjForm($obj, $option);
	}

	function addEditObjProcess(){
		global $bw, $vsStd, $vsLang;
		if($bw->input['pollStatus']) $bw->input['pollStatus'] 	= 1;
		if(!$bw->input['pollCatId'])
			$bw->input['pollCatId']=$this->module->getCategories()->getId();
		if($bw->input['fileId'])
			$bw->input['pollImage']=$bw->input['fileId'];
		// If there is Object Id passed, processing updating Object
		if($bw->input['pollId']){
			$obj = $this->module->getObjectById($bw->input['pollId']);
			$imageOld =$obj->getImage();
			if(!$obj){
				$this->alertMessage();
			}
			$objUpdate = $this->module->createBasicObject();
			$objUpdate->convertToObject($bw->input);
			$this->module->updateObjectById($objUpdate);
			if(!$this->module->result['status']){
				$this->module->reportError();
			}
		}
		else{
			$this->module->obj->convertToObject($bw->input);
			$this->module->insertObject($this->module->obj);
		}

		// nêu khong dùng multi file thì sẽ loại bỏ những file dư thừa
		if($imageOld&&$bw->input['fileId']){
			$this->module->vsFile->deleteFile($imageOld);
		}
		$this->alertMessage();
	}
	function alertMessage() {
		global $bw ;
		print 	"<script>
					vsf.alert(\"{$this->module->result['message']}\");
					vsf.get('{$bw->input[0]}/display-obj-list/{$bw->input['pollCatId']}/answerListHtml', 'answer-panel')
				</script>";	
		return true;
	}

	function getCategoryBox($message="") {
		global $bw , $vsMenu;
		$menu = $this->module->getCategories();
		return $this->html->categoryList($menu);
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