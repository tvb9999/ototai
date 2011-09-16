<?php

global $vsStd;
$vsStd->requireFile(CORE_PATH.'recruitments/recruitments.php');

class recruitments_public{

	protected $html;
	protected $module;
	protected $output;

	function __construct() {
		global $vsTemplate, $vsPrint;
		$this->module = new recruitments;
		$this->html = $vsTemplate->load_template('skin_recruitments');
	}

	function auto_run() {
		global $bw, $vsTemplate;

		$vsTemplate->global_template->active = 5;
		switch ($bw->input[1]) {
			case 'detail':
					$this->loadDetail($bw->input[2]);
				break;

			case 'category':
					$this->loadCategory($bw->input[2]);
				break;

			default:{
					$this->loadDefault();
				break;
			}
		}
	}
	
	public function loadCategory($catId=0) {
		global $vsPrint,$bw,$vsSettings, $vsMenu;
		$category = $vsMenu->getCategoryById($catId);
		
		$ids = $vsMenu->getChildrenIdInTree($category);
		
		if($ids){
			$this->module->setCondition("recruitmentCatId in ( {$ids}) AND recruitmentStatus > 0");
			$this->module->setOrder("recruitmentId Desc, recruitmentBegin");
		}
		
		$size = $vsSettings->getSystemKey("recruitment_show_cat_num", 8);
		$option = $this->module->getPageList("recruitment/category/".$catId, 3, $size);

		$option['category'] = $category;

	
		$vsPrint->mainTitle = $vsPrint->pageTitle = $category->getTitle();
		return $this->output = $this->html->loadDefault($option);
	}
	
	
	function loadDefault(){
		global $vsSettings;
		
		$size  = $vsSettings->getSystemKey("recruitment_show_cat_num",7,"recruitments");

		$category = $this->module->getCategories();
		
		$this->module->setCondition("recruitmentStatus > 0 AND recruitmentEnd >=".time()." AND recruitmentCatId in (".$category->getId().")");
		$this->module->setOrder("recruitmentId DESC");
		$option = $this->module->getPageList("recruitments", 1, $size);
		
		return $this->output = $this->html->loadDefault($option);
	}

	function getListWithCat(){
		global $vsMenu,$vsSettings;
		$count=0;
		$category= $this->module->getCategories();
		if (count($category->getChildren()))
		{
			foreach ($category->getChildren() as $key=> $cat) {
				$count++;
				$cat=$vsMenu->getCategoryById($key);
				$listObject=$this->module->getListWithCat($cat);
				$size =  $vsSettings->getSystemKey("recruitments_show_cat_num",8);
				$listObject =  array_slice($listObject,0,$size);
				$html .= $this->html->htmlListObject($cat,$listObject,$count);
			}
		}
		else
		{
			$listObject=$this->module->getListWithCat($category);
			$html = $this->html->htmlListObject($category,$listObject,$count);
		}
		return $html;
	}

	public function loadDetail($objId) {
		global $bw, $vsLang, $vsPrint, $vsStd, $vsSettings, $vsMenu;
		
		$query = explode('-',$objId);
		$objId = abs(intval($query[count($query)-1]));
		$obj = $this->module->getObjectById($objId);
		if(!$obj) return $vsPrint->redirect_screen('Không có dữ liệu theo yêu cầu');

		$option['category'] = $vsMenu->getCategoryById($obj->getCatId());
		$option['other'] = $this->getOtherList($obj);
		
		
		
		$obj->createSeo();
		return $this->output = $this->html->loadDetail($obj, $option);
	}

	function getGallery($objId){
		$this->module->vsRelation->setRelId($objId);
		$this->module->vsRelation->setTableName("gallery_recruitments");
		$strId=$this->module->vsRelation->getObjectByRel();
		  
		if($strId) return $this->gallerys->getFileByAlbumId($strId);
	}
	
	function getOtherList($obj){
		$objId = $obj->getId();
		
		$this->module->setCondition("recruitmentId <> {$objId} AND recruitmentStatus > 0 AND recruitmentEnd >=".time()." AND recruitmentCatId in (".$obj->getCatId().")");
		$this->module->setOrder("recruitmentId DESC");
		return $this->module->getObjectsByCondition();
	}


	public function getOutput() {
		return $this->output;
	}

	public function setOutput($output) {
		$this->output = $output;
	}
}
?>