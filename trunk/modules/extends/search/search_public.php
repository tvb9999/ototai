<?php

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}
global $vsStd;
$vsStd->requireFile ( CORE_PATH . "pages/pages_public.php" );

class search_public extends pages_public {
	public $output = "";
	public $html = "";

	function __construct() {
		
		global $vsTemplate, $vsStd;
		parent::__construct ();
		$this->html = $vsTemplate->load_template ( 'skin_search' );
	
	}

	function auto_run() {
		global $bw, $vsTemplate;
		
		$bw->input ['module'] = "search";
		switch ($bw->input [1]) {
			default :
				$this->loadDefault ();
		}
	}

	function loadDefault() {
		global $bw, $vsMenu, $vsLang, $vsPrint, $vsStd;
		
		$url = "search/";
		$keywords = $bw->input ['keyword'];
		$keywords = strtolower ( VSFTextCode::removeAccent ( trim ( $keywords ) ) );
		
		$where = "pageStatus >0 and pageCatId = {$this->module->getCategories()->getId()}";
		
		$vsPrint->mainTitle = $vsPrint->pageTitle = $vsLang->getWords ( 'tour_search_result', 'Kết quả tìm kiếm' );
		$where = "pageStatus <> 0 ";
		if ($keywords) {
			$where .= " AND clearTitle LIKE '%" . $keywords . "%'";
			$url .= "&keyword=" . $bw->input ['keyword'];
		}
		
		$this->module->setCondition ( $where );
		$option = $this->module->getPageList($url."&1=",1,20,0,'',true,'detail');
		
		$this->module->vsRelation->setTableName("page_category");
		$this->module->vsRelation->getRelationByOption(true,array("where"=>"objectId in(".implode(',',array_keys($option['pageList'])).")"));
		
		$option['module'] = $this->module->vsRelation->arrval;
		
		if (! count ( $option ['pageList'] ))
			$option ['message'] = $vsLang->getWords ( 'pro_no_search', 'Không tìm thấy. Hãy nhập từ khóa khác xem sao!' );
		$this->output = $this->html->loadDefault ( $option );
	}
}
?>