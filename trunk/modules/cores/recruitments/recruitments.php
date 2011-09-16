<?php
require_once(CORE_PATH."recruitments/Recruitment.class.php");
class recruitments extends VSFObject {
	public $obj;
	protected $categoryField 	="";
	protected $categories 		= array();

	function __construct(){
		$this->requireFileUseFull();
		parent::__construct();
		$this->categoryField 	= "recruitmentCatId";
		$this->primaryField 	= 'recruitmentId';
		$this->basicClassName 	= 'Recruitment';
		$this->tableName 		= 'recruitment';
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

	/**
	 * @param $categoryField the $categoryField to set
	 */
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
		$this->condition = "recruitmentCatId in ( {$ids})";
		$this->setOrder("recruitmentIndex Desc, recruitmentId Desc");
		$this->limit=array(0,30);
		return $this->getObjectsByCondition();
	}

	/**
	 * @return the $categoryField
	 */
	public function getOtherList($obj) {
		global  $vsSettings;
		$cat=$this->vsMenu->getCategoryById($obj->getCatId());
		$ids=$this->vsMenu->getChildrenIdInTree($cat);
		$this->condition = "recruitmentId < {$obj->getId()} and recruitmentStatus <>0";
		$this->setOrder("recruitmentIndex Desc, recruitmentId Desc");
		$size =  $vsSettings->getSystemKey("user_{$bw->input[0]}_list_number_other",10);
		$this->setLimit(array(0,$size));
		if($ids)
		$this->condition .= " and recruitmentCatId in ( {$ids})";
		return $this->getObjectsByCondition();
	}

	/**
	 * @return the $categoryField
	 */
	public function getHotList() {
		$ids=$this->vsMenu->getChildrenIdInTree($this->getCategories());
		$this->condition .= " recruitmentStatus > 0 and recruitmentCatId in ( {$ids})";
		$this->setOrder("recruitmentIndex Desc, recruitmentId Desc");
		$this->setLimit(array(0,10));
		return $this->getObjectsByCondition();
	}

	function getLastest($limit=1){
		$this->condition .= " recruitmentStatus > 0";
		$this->setOrder("recruitmentId Desc");
		$this->setLimit(array(0, $limit));
		return $this->getObjectsByCondition();
	}
	
	function __destruct(){
		unset($this);
	}

	function delete($ids = 0) {
		global $vsStd;
		$this->createMessageSuccess($this->vsLang->getWords('recruitment_delete_by_id_success', "Deleted recruitment successfully!"));
		// Get objects information
		$this->fieldsString = "recruitmentImage";
		$this->condition = "recruitmentId IN (".$ids .")";
		$list = $this->getObjectsByCondition();
		if(!count($list)) return false;
		// Delete recruitment data
		$this->condition = "recruitmentId IN (".$ids .")";
		if(!$this->deleteObjectByCondition()) return false;
		foreach ($list as $recruitment){
			$this->vsFile->deleteFile($recruitment->getImage());
		}
		unset($recruitment);
		unset($list);
		return true;
	}

	public function updateStatus($ids, $status){
		$this->setCondition("recruitmentId IN (". $ids.")");
		return $this->updateObjectByCondition($status);
	}
}
?>