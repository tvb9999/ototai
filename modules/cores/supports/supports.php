<?php
require_once (CORE_PATH . "supports/Support.class.php");
class supports extends VSFObject{
	public $obj;
	protected $categoryField 	="";
	protected $relTableName 	="";
	protected $categories 		= array();

	function __construct(){
		parent::__construct();
		$this->categoryField 	= "supportCatId";
		$this->primaryField 	= 'supportId';
		$this->basicClassName 	= 'Support';
		$this->tableName 		= 'support';
		$this->relTableName 	= "support_file";
		$this->obj = $this->createBasicObject();
		$this->categories = array();
		$this->categories = $this->vsMenu->getCategoryGroup("supports");
	}

	function __destruct() {
	}

	public function portlet(){
		//		$listObj = $this->getListWithCat();   // chia theo danh mục
		$this->setCondition("supportStatus >0 ");
		$listObj= $this->getSupportWithCatId(); // lấy theo 1 danh mục hoặc lấy tất cả
		
		return $listObj;
	}
	
	function getSupportWithCatId($catId=0 ) {
		
		$treeCat = $this->vsMenu->getCategoryById($catId);
		
		if(is_object($treeCat))
			$listcate =$this->vsMenu->getChildrenIdInTree ( $treeCat );
		else
			$listcate = 8;//$this->vsMenu->getChildrenIdInTree ( $this->categories );//$this->categories
		
		$this->getCondition()?$this->setCondition($this->getCondition()." and supportCatId in (" . $listcate . ")"):$this->setCondition("supportStatus >0 and supportCatId in (" . $listcate . ")");
		$this->setLimit(array(0,20));
		$this->setOrder("supportIndex");
		return $this->getObjectsByCondition ();

	}
	
	function delete($ids = 0) {
		global $bw;
		
		// Get objects information
		$this->condition = "{$this->getPrimaryField()} IN (".$ids .")";
		if(!$this->deleteObjectByCondition()) return false;
		return true;
	}
	
	function getListWithCat() {
		$listcate =$this->vsMenu->getChildrenIdInTree ( $this->getCategories() );
		$this->setCondition("supportCatId in (" . $listcate . ")");
		$this->setLimit(array(0,20));
		$this->setOrder("supportIndex");
		return $this->getObjectsByCondition("getCatId",1);
	}

	function updateStatus($ids, $status){
		$this->setCondition("supportId IN (". $ids.")");
		return $this->updateObjectByCondition($status);

	}
	/**
	 * @return the $relTableName
	 */
	function getRelTableName() {
		return $this->relTableName;
	}

	/**
	 * @param $relTableName the $relTableName to set
	 */
	function setRelTableName($relTableName) {
		$this->relTableName = $relTableName;
	}

	function setCategories($categories) {
		$this->categories = $categories;
	}

	/**
	 * @return the $categories
	 */
	function getCategories() {
		return $this->categories;
	}

	/**
	 * @return the $categoryField
	 */
	public function getCategoryField() {
		return $this->categoryField;
	}
}
?>