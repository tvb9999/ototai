<?php

require_once(CORE_PATH."weblinks/Weblink.class.php");
class weblinks extends VSFObject {
	public $obj;

	protected $categoryField 	="";
	protected $relTableName 	="";
	protected $categories 		= array();

	function __construct(){
		parent::__construct();
		$this->categoryField 	= "weblinkCatId";
		$this->primaryField 	= 'weblinkId';
		$this->basicClassName 	= 'weblink';
		$this->tableName 		= 'weblink';
		$this->relTableName 	= "rel_weblink_file";
		$this->obj = $this->createBasicObject();
		$this->categories = $this->vsMenu->getCategoryGroup(strtolower($this->tableName."s"));
	}

	/**
	 * @return the $relTableName
	 */
	public function getweblinksForUser() {
		global $vsStd,$bw;
		$vsStd->requireFile(CORE_PATH.'access/access.php');
		$access= new accesses();
		$cacheArray= $access->getArrayObj();
		$ids="-1";
		if($cacheArray[$bw->input['vs']]){
			$ids.=",".$cacheArray[$bw->input['vs']]->getId();
		}
		if($cacheArray[$bw->input[0]]){
			$ids.=",".$cacheArray[$bw->input[0]]->getId();
		}
		$ids;
		if($ids){
			$this->vsRelation->setRelId($ids);
			$this->vsRelation->setTableName('weblink_link');
			$fileId=$this->vsRelation->getObjectByRel();
			$fileId?$condition="weblinkId in( {$fileId} )":'';
		}
		return $this->arrayObj	=	$this->getweblinkList($condition);
	}
	/**
	 * @return the $relTableName
	 */
	public function getRelTableName() {
		return $this->relTableName;
	}

	/**
	 * @param $relTableName the $relTableName to set
	 */
	public function setRelTableName($relTableName) {
		$this->relTableName = $relTableName;
	}

	public function setCategories($categories) {
		$this->categories = $categories;
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
		$this->condition = "weblinkCatId in ( {$ids})";
		$this->limit=array(0,8);
		return $this->getObjectsByCondition();
	}
	/**
	 * @return the $categoryField
	 */
	public function getOtherList($obj) {
		global $vsMenu;
		$cat=$vsMenu->getCategoryById($obj->getCatId());
		$ids=$vsMenu->getChildrenIdInTree($cat);
		$this->condition = "weblinkId <> {$obj->getId()}";
		if($ids)
		$this->condition .= " and weblinkCatId in ( {$ids})";
		return $this->getObjectsByCondition();
	}

	/**
	 * @return the $categoryField
	 */
	public function getweblinkList($condition="") {
		global $vsMenu;
		$ids=$vsMenu->getChildrenIdInTree($this->getCategories());
		$this->condition = "weblinkStatus > 0 and weblinkCatId in ( {$ids})";
		if($condition)
			$this->condition .= " and {$condition}";
		return $this->getObjectsByCondition();
	}

	function __destruct(){
		unset($this);
	}

	function delete($ids = 0) {
		global $vsStd;
		$this->createMessageSuccess($this->vsLang->getWords('weblink_delete_by_id_success', "Deleted weblink successfully!"));
		// Get objects information
		$this->fieldsString = "weblinkFileId";
		$this->condition = "weblinkId IN (".$ids .")";
		$list = $this->getObjectsByCondition();
		if(!count($list)) return false;
		// Delete news data
		$this->condition = "weblinkId IN (".$ids .")";
		if(!$this->deleteObjectByCondition()) return false;
		foreach ($list as $news){
			$this->vsFile->deleteFile($news->getFileId());
		}
		unset($news);
		unset($list);
		return true;
	}

	public function updateStatus($ids, $status){
		$this->setCondition("weblinkId IN (". $ids.")");
		return $this->updateObjectByCondition($status);
	}
}
?>