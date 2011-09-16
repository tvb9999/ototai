<?php
require_once (CORE_PATH . "pages/Page.class.php");
class pages extends VSFObject {
	public $obj;
	protected $relTableName = "";
	protected $categories = array ();
	
	function __construct() {
		global $vsMenu, $vsStd,$DB;
		parent::__construct ();
		$this->primaryField = "pageId";
		$this->basicClassName = "Page";
		$this->tableName = 'page';
		$this->obj = $this->createBasicObject ();
		$this->categories = $this->vsMenu->getCategoryGroup ( strtolower ( $this->tableName . "s" ) );
		if(!$DB->field_exists('pageAddGoogle',$this->tableName))
			$DB->sql_add_field($this->tableName,'pageAddGoogle','varchar(250)');
		if(!$DB->field_exists('pageLatitude',$this->tableName))
			$DB->sql_add_field($this->tableName,'pageLatitude','float');
		if(!$DB->field_exists('pageLongitude',$this->tableName))
			$DB->sql_add_field($this->tableName,'pageLongitude','float');
		if(!$DB->field_exists('pageCatId',$this->tableName))
			$DB->sql_add_field($this->tableName,'pageCatId','int(11)');
		if(!$DB->field_exists('clearTitle',$this->tableName))
			$DB->sql_add_field($this->tableName,'clearTitle','text');
	}
	
	public function getCategories() {
		return $this->categories;
	}
	
	function __destruct() {
		unset ( $this );
	}
	
	function getMenuList() {
		global $vsMenu;
		$vsMenu->obj->setIsAdmin ( 0 );
		$vsMenu->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
		$menus = $vsMenu->filterMenu ( array ('isAdmin' => true, 'langId' => true ), $vsMenu->arrayTreeMenu );
		$html = "";
		$vsMenu->buildOptionMenuTree ( $menus, &$html );
		return $html;
	}
	
	function getCategoryList() {
		global $vsMenu;
		reset ( $vsMenu->arrayTreeCategory );
		$categoryRoot = current ( $vsMenu->arrayTreeCategory );
		$categories = $categoryRoot->getChildren ();
		$vsMenu->obj->setIsAdmin ( - 1 );
		$vsMenu->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
		$menus = $vsMenu->filterMenu ( array ('isAdmin' => true, 'langId' => true ), $categories );
		$html = "";
		if (count ( $menus )) {
			$vsMenu->buildOptionMenuTree ( $menus, &$html );
		}
		return $html;
	}
	
	function getVirtualCategoryList($module) {
		global $vsMenu;
		
		$categoryGroup = $vsMenu->getCategoryGroup ( $module,array("status"=>array(true,array(1,2))) );
		
		$option = array ('listStyle' => "| - -", 'id' => 'catSelect', 'size' => 10, 'multiple' => true );
		//	if(count($categoryGroup->getChildren()))
		return $html = $vsMenu->displaySelectBox ( $categoryGroup->getChildren (), $option );
		return false;
	}
	
	function getPageIdByModule($module) {
		global $vsMenu;
		$categories = $this->vsMenu->getCategoryGroup ( $module );
		$vsPrint->pageTitle = $categories->getTitle ();
		$strIds = $this->vsMenu->getChildrenIdInTree ( $categories );
		$this->vsRelation->setRelId ( $strIds );
		$this->vsRelation->setTableName ( "page_category" );
		return $this->vsRelation->getObjectByRel ();
	}
	
	function getObjByCode($code) {
		global $vsMenu;
		$categories = $this->getCategories ();
		$strIds = $this->vsMenu->getChildrenIdInTree ( $categories );
		$this->setCondition ( "pageCode='{$code}' and pageCatId in ({$strIds})" );
		return $this->getOneObjectsByCondition ();
	}
	/* 
	 * Author   	: sanhnx@redsunic.com
	 * Param  		: $keyword
	 * Return  		: Array
	 * Description  : Unknown
	 * 
	 */
	function searchTitle($keyword = '', $module = 'pages'){
		global $bw;
  		$keywords = explode ( ' ', $keyword );
//  	$keywords = strtolower(VSFTextCode::removeAccent(trim($keywords),' '));
	  	foreach ($keywords as $value){
	   		$order .= "FIELD(SUBSTRING(pageTitle,POSITION('$value' IN pageTitle),LENGTH('$value')),'$value') DESC,";
	  	}
	  	$this->setOrder(rtrim($order,','));	  	
 		$this->setLimit(array(0,10));
//	  	$this->setCondition("MATCH pageTitle AGAINST ('".str_ireplace(" ", "* ",$keyword)."' IN BOOLEAN MODE) and pageModule='".$module."'");
		$this->setCondition("pageTitle REGEXP '".rtrim(str_ireplace(" ", "|",$keyword),'|')."' and pageModule='".$module."'");
	  	return $this->getObjectsByCondition();
 	}
 	
	/* 
	 * Author   	: khamdb@redsunic.com
	 * Param  		: $pageStatus, $size
	 * Return  		: Array
	 * Description  : Unknown
	 * 
	 */
	function getPageByType($pageStatus, $size=5){
		global $bw;
		
		$this->setCondition("pageStatus > 0 and pageStatus = $pageStatus");
		$this->setLimit(array(0,$size));
		$this->setOrder("pageIndex DESC, pageId DESC");
		
		return $this->getObjectsByCondition();
	}
	
	function getHostListByModule($module, $size = 5) {
		global $vsMenu;
		$pageIds = $this->getPageIdByModule ( $module );
		if ($pageIds) {
			$this->getCondition () ? $this->setCondition ( 'pageStatus >0 and'. $this->getCondition () . ' and pageId in (' . $pageIds . ')' ) : $this->setCondition ( 'pageStatus >0 and pageId in (' . $pageIds . ')' );
			$this->getOrder () ? $this->setOrder ( $this->getOrder () . " ,pageIndex DESC, pageId DESC" ) : $this->setOrder ( "pageIndex ASC, pageId DESC" );
			$this->setLimit ( array (0, $size ) );
			return $this->getObjectsByCondition ();
		}
	}
	
	function getByMouduleName($module="pages", $objIndex = 2, $url="") {
		global $bw, $vsPrint, $vsLang, $vsSettings;
		
		$categories = $this->vsMenu->getCategoryGroup ( $module );
		if ($module == $bw->input [0]) {
			$bw->input ['catUrl'] = $categories->getCatUrl ( $bw->input [0] );
		}
		$strIds = $this->vsMenu->getChildrenIdInTree ( $categories );
		$this->vsRelation->setTableName ( "page_category" );
		$pageIds = $this->vsRelation->getRelationByOption(true,array("where"=>"relId in({$strIds}) and module='$module'"));
		
		$url =  $module . "/" . $url;
		$size = $vsSettings->getSystemKey ( 'page_' . $module . '_capability', 10);
		return $this->getByPageIds ( $pageIds, $url, $size ,$objIndex);
	}
	
	function getByPageIds($pageIds = NULL, $url = NULL, $size = 5, $objIndex = 2, $ajax=0, $callback="",$releaseUrl = false,$style="Number") {
		global $bw, $vsPrint, $vsLang,$vsSettings;
		
		if ($pageIds) {
			$this->getCondition () ? $this->setCondition ( $this->getCondition () . ' and pageId in (' . $pageIds . ')' ) : $this->setCondition ( 'pageId in (' . $pageIds . ')' );
			$this->getOrder () ? $this->setOrder ( $this->getOrder () . " ,pageIndex, pageId" ) : $this->setOrder ( "pageIndex asc, pageId desc" );
			
			$option = $this->getPageList ( $url, $objIndex, $size, $ajax, $callback ,$releaseUrl,$style);
		}
		
		return $option;
	}
	
	function getListWithCat($treeCat,$url = NULL,$size = 5,$objIndex = 20,$ajax=0,$callback='') {
		if (! is_object ( $treeCat ))
			return false;
		$strIds = $this->vsMenu->getChildrenIdInTree ( $treeCat );
		$this->vsRelation->setRelId ( $strIds );
		$this->vsRelation->setTableName ( "page_category" );
		$pageIds = $this->vsRelation->getObjectByRel ();
		return $this->getByPageIds ($pageIds,$url,$size,$objIndex,$ajax,$callback);
	}
	
	function getListWithCatModule($treeCat,$module="",$size=5) {
		global $vsMenu;
		if (! is_object ( $treeCat ))
			return false;
		$strIds = $this->vsMenu->getChildrenIdInTree ( $treeCat );
		$this->vsRelation->setTableName ( "page_category" );
		$pageIds = $this->vsRelation->getRelationByOption(true,array("where"=>"relId in({$strIds}) and module='$module'"));
		$arr['cat'] = $this->vsRelation->arrval;
		
		if(!$pageIds) return;
		$this->getCondition () ? $this->setCondition ( $this->getCondition () . ' and pageStatus >0 and pageId in (' . $pageIds . ')' ) : $this->setCondition ( 'pageStatus >0 and pageId in (' . $pageIds . ')' );
		$this->setLimit ( array (0, $size ) );
		$arr['list'] = $this->getObjectsByCondition ();
		
		return $arr;
	}
		
	function getGallery($objId,$table='gallery_pages',$groupFile=0){
		require_once(CORE_PATH."gallerys/gallerys.php");
		$gallery = new gallerys();
		$this->vsRelation->setRelId($objId);
		$this->vsRelation->setTableName($table);
		$strId=$this->vsRelation->getObjectByRel();
		if($groupFile and $strId){
			$gallery->setCondition("galleryId in ({$strId})");
			$gallery->getObjectsByCondition();
			return $gallery->getFileByAlbumId($strId,$groupFile,$gallery->getArrayObj());
		}
		if($strId) return $gallery->getFileByAlbumId($strId,$groupFile);
	}

	function getGalleryCode($objId,$code='images'){
		require_once(CORE_PATH."gallerys/gallerys.php");
		$gallery = new gallerys();
		
		$this->vsRelation->setRelId($objId);
		$this->vsRelation->setTableName("gallery_pages");
		$strId=$this->vsRelation->getObjectByRel();
		if(!$strId) return;
		$gallery->setCondition("galleryId in ({$strId}) and galleryCode = '$code'");
		$gallery->getOneObjectsByCondition();

		return $gallery->getFileByAlbumId($gallery->obj->getId());
	}
	
}
?>