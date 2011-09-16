<?php
if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}
global $vsStd;
$vsStd->requireFile ( CORE_PATH . 'news/news.php' );

class news_public {
	
	protected $html;
	protected $module;
	protected $output;

	function __construct() {
		global $vsTemplate, $vsPrint;
		$this->module = new newses ();
		$this->html = $vsTemplate->load_template ( 'skin_news' );
	}

	function auto_run() {
		global $bw, $vsTemplate;

		switch ($bw->input [1]) {
			case 'detail' :
				$this->loadDetail ( $bw->input [2] );
				break;
			case 'category' :
				$this->loadCategory ( $bw->input [2] );
				break;
			default :
				$this->loadDefault ();
				break;
		}
	}

	public function loadCategory($catId = 0) {
		global $vsPrint, $bw, $vsSettings, $vsMenu;
		$category = $vsMenu->getCategoryById ( $catId );
		
		$ids = $vsMenu->getChildrenIdInTree ( $category );
		
		if ($ids) {
			$this->module->setCondition ( "newsCatId in ( {$ids})" );
			$this->module->setOrder ( "newsId Desc" );
		}
		
		$size = $vsSettings->getSystemKey ( "news_show_cat_num", 8, $bw->input [0], 2 );
		$option = $this->module->getPageList ( "news/category/" . $catId, 3, $size );
		
		$option ['category'] = $category;
		
		$vsPrint->mainTitle = $vsPrint->pageTitle = $category->getTitle ();
		return $this->output = $this->html->loadDefault ( $option );
	}

	function loadDefault() {
		global $vsSettings;
		
		$ids = $this->module->vsMenu->getChildrenIdInTree($this->module->getCategories());
		if($ids) 
			$this->module->setCondition ("newsCatId in ( {$ids})");
		$size = $vsSettings->getSystemKey ( "news_show_cat_num", 7, "news", 2 );
		$this->module->setOrder ( "newsId DESC" );
		$option = $this->module->getPageList ( "news", 1, $size );
		$option ['lastest'] = reset ( $option ['pageList'] );
		
		return $this->output = $this->html->loadDefault ( $option );
	}

	function getListWithCat() {
		global $vsMenu, $vsSettings;
		$count = 0;
		$category = $this->module->getCategories ();
		if (count ( $category->getChildren () )) {
			foreach ( $category->getChildren () as $key => $cat ) {
				$count ++;
				$cat = $vsMenu->getCategoryById ( $key );
				$listObject = $this->module->getListWithCat ( $cat );
				$size = $vsSettings->getSystemKey ( "news_show_cat_num", 8 );
				$listObject = array_slice ( $listObject, 0, $size );
				$html .= $this->html->htmlListObject ( $cat, $listObject, $count );
			}
		} else {
			$listObject = $this->module->getListWithCat ( $category );
			$html = $this->html->htmlListObject ( $category, $listObject, $count );
		}
		return $html;
	}

	public function loadDetail($objId) {
		global $bw, $vsLang, $vsPrint, $vsStd, $vsSettings, $vsMenu;
		
		$query = explode ( '-', $objId );
		$objId = abs ( intval ( $query [count ( $query ) - 1] ) );
		$obj = $this->module->getObjectById ( $objId );
		if (! $obj)
			return $vsPrint->redirect_screen ( 'Không có dữ liệu theo yêu cầu' );
		
		$option ['category'] = $vsMenu->getCategoryById ( $obj->getCatId () );
		$option ['other'] = $this->module->getOtherList ( $obj );
		
		$obj->createSeo ();
		return $this->output = $this->html->loadDetail ( $obj, $option );
	}

	function getGallery($objId) {
		$this->module->vsRelation->setRelId ( $objId );
		$this->module->vsRelation->setTableName ( "gallery_news" );
		$strId = $this->module->vsRelation->getObjectByRel ();
		
		if ($strId)
			return $this->gallerys->getFileByAlbumId ( $strId );
	}

	public function getOutput() {
		return $this->output;
	}

	public function setOutput($output) {
		$this->output = $output;
	}
}
?>