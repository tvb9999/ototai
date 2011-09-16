<?php


if ( ! defined( 'IN_VSF' ) )
{
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit();
}
global $vsStd;
$vsStd->requireFile(CORE_PATH . "pages/pages_public.php");	

class abouts_public extends pages_public {
	public  $output = "";
	public $html="";
	function __construct(){
		
		global $vsTemplate,$vsStd;		
		parent::__construct();			
		$this->html = $vsTemplate->load_template('skin_abouts');
		
	}
		
	function auto_run(){
		global $bw, $vsTemplate;
		
		$vsTemplate->global_template->active = 1;
		$bw->input['module']="abouts";
		switch ($bw->input[1]){
			case 'viewcategory':
				$this->RentalRequireForm();
				break;
				
			case 'viewdetails':
					$this->sendContact();
				break;
			case 'detail':				
					$this->loadDetail($bw->input[2]);
				break;		
			default:
					$this->loadDefault();
		}
	}
	
	function loadDefault(){
		global $bw, $vsPrint, $vsLang;
		$result=$this->module->getByMouduleName($bw->input['module']);
		if(count($result['pageList'])){
			$currentItem = current($result['pageList']);
//			 unset($result['pageList'][$currentItem->getId()]);
		}
		
		$this->output = $this->html->loadDefault($currentItem,$result);
	}
	function loadDetail($pageId){
		global $vsPrint, $vsLang, $bw,$vsSettings;
		
		if($bw->input['module']!="pages"){
			$categories = $this->module->vsMenu->getCategoryGroup($bw->input['module']);
		}else $categories=$this->module->getCategories();
		
		$query = explode('-',$pageId);
		$pageId = abs(intval($query[count($query)-1]));
		if($pageId == 0) return $vsPrint->redirect_screen($vsLang->getWords('page_noPageItem','Không có dữ liệu theo yêu cầu'));
		

		$catIds = $this->module->vsMenu->getChildrenIdInTree($categories);
		$this->module->vsRelation->setRelId($catIds);
		$this->module->vsRelation->setObjectId($pageId);
		$this->module->vsRelation->setTableName("page_category");
		$catId=$this->module->vsRelation->getRelByObject();
			
		if($catId == 0) return $vsPrint->redirect_screen($vsLang->getWords('page_noPageItem','Không có dữ liệu theo yêu cầu'));
			
		$this->module->getObjectById($pageId);
		$bw->input['catUrl']=$categories->getCatUrl($bw->input[0]);
		$vsPrint->pageTitle = $vsPrint->mainTitle = $this->module->obj->getTitle();
		$result=$this->module->getByMouduleName($bw->input['module']);
		$this->output = $this->html->loadDetail($this->module->obj, $result);
	}
}
?>