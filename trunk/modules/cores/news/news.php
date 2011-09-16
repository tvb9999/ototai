<?php
require_once(CORE_PATH."news/News.class.php");
class newses extends VSFObject {
	public $obj;
	protected $categoryField 	="";
	protected $categories 		= array();

	function __construct(){
		$this->requireFileUseFull();
		parent::__construct();
		$this->categoryField 	= "newsCatId";
		$this->primaryField 	= 'newsId';
		$this->basicClassName 	= 'News';
		$this->tableName 		= 'news';
		$this->obj = $this->createBasicObject();
		$this->obj	=&$this->basicObject;
		$this->fields = $this->obj->convertToDB();
		$this->categories = array();
		$this->categories = $this->vsMenu->getCategoryGroup(($this->tableName));
	}

	
	public function getRelTableName() {
		return $this->relTableName;
	}

	public function setRelTableName($relTableName) {
		$this->relTableName = $relTableName;
	}

	public function setCategories($categories) {
		$this->categories = $categories;
	}

	function requireFileUseFull() {
		global $vsStd;
		$vsStd->requireFile(UTILS_PATH."TextCode.class.php");
	}


	public function setCategoryField($categoryField) {
		$this->categoryField = $categoryField;
	}

	/**
	 * @return the $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @return the $categoryField
	 */
	public function getCategoryField() {
		return $this->categoryField;
	}
	/**
	 * @return the $categoryField
	 */
	public function getListWithCat($treeCat) {
		if(!is_object($treeCat))
		return false;
		$ids=$this->vsMenu->getChildrenIdInTree($treeCat);
		if($ids)
		$this->condition = "newsCatId in ( {$ids})";
		$this->setOrder("newsIndex Desc, newsId Desc");
		$this->limit=array(0,30);
		return $this->getObjectsByCondition();
	}


	public function getOtherList($obj) {
		global  $vsSettings;
		$cat=$this->vsMenu->getCategoryById($obj->getCatId());
		$ids=$this->vsMenu->getChildrenIdInTree($cat);
		$this->condition = "newsId < {$obj->getId()} and newsStatus <>0";
		$this->setOrder("newsIndex Desc, newsId Desc");
		$size =  $vsSettings->getSystemKey("user_{$bw->input[0]}_list_number_other",10);
		$this->setLimit(array(0,$size));
		if($ids)
		$this->condition .= " and newsCatId in ( {$ids})";
		return $this->getObjectsByCondition();
	}


	public function getHotList($size) {
		$ids = $this->vsMenu->getChildrenIdInTree($this->getCategories());
		$this->condition .= " newsStatus > 0 and newsCatId in ( {$ids})";
		$this->setOrder("newsIndex Desc, newsId Desc");
		$this->setLimit(array(0,$size));
		return $this->getObjectsByCondition();
	}

	function getLastest($limit=1){
		$this->condition .= " newsStatus > 0";
		$this->setOrder("newsId Desc");
		$this->setLimit(array(0, $limit));
		return $this->getObjectsByCondition();
	}
	
	function __destruct(){
		unset($this);
	}

	function deleteObjInCategory($catIds = 0){
		global $vsStd;
		
		$query = "SELECT newsId, newsImage from vsf_news where newsCatId in(".$catIds.")";
		$list = $this->executeQuery($query, 0);

		if(!count($list)) return false;
		
		$this->condition = "newsCatId IN (".$catIds.")";
		if(!$this->deleteObjectByCondition()) return false;
		
		foreach ($list as $news)
			$this->vsFile->deleteFile($news['newsImage']);

		return true;
	}
}
?>