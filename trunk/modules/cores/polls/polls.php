<?php

require_once (CORE_PATH . "polls/Poll.class.php");
class polls extends VSFObject {
	public $obj;
	
	protected $categoryField = "";
	protected $categories = array ();
	
	function __construct() {
		$this->requireFileUseFull ();
		parent::__construct ();
		global $DB;
		$this->categoryField = "pollCatId";
		$this->primaryField = 'pollId';
		$this->basicClassName = 'Poll';
		$this->tableName = 'poll';
		$this->obj = $this->createBasicObject ();
		$this->categories = $this->vsMenu
			->getCategoryGroup ( strtolower ( $this->tableName . "s" ) );
	}
	
	public function setCategories($categories) {
		$this->categories = $categories;
	}
	
	function requireFileUseFull() {
		global $vsStd;
		$vsStd->requireFile ( UTILS_PATH . "PostParser.class.php" );
		$vsStd->requireFile ( UTILS_PATH . "TextCode.class.php" );
	}
	
	public function setCategoryField($categoryField) {
		$this->categoryField = $categoryField;
	}
	
	public function getCategories() {
		return $this->categories;
	}
	
	public function getCategoryField() {
		return $this->categoryField;
	}
	public function getListWithCat($treeCat) {
		if (! is_object ( $treeCat ))
			return false;
		$ids = $this->vsMenu
			->getChildrenIdInTree ( $treeCat );
		$this->condition = "pollStatus > 0";
		if ($ids)
			$this->condition .= " and pollCatId in ( {$ids})";
		$this->setOrder ( "pollIndex DESC, pollId DESC" );
		$this->limit = array (0, 8 );
		
		return $this->getObjectsByCondition ();
	}
	function delete($ids = 0) {
		global $vsStd;
		$this->createMessageSuccess ( $this->vsLang
			->getWords ( 'news_delete_by_id_success', "Deleted News successfully!" ) );
		// Get objects information
		$this->fieldsString = "pollImage";
		$this->condition = "{$this->getPrimaryField()} IN (" . $ids . ")";
		$list = $this->getObjectsByCondition ();
		if (! count ( $list ))
			return false;
		
		// Delete news data
		$this->condition = "{$this->getPrimaryField()} IN (" . $ids . ")";
		if (! $this->deleteObjectByCondition ())
			return false;
		foreach ( $list as $news ) {
			$this->vsFile
				->deleteFile ( $news->getImage () );
		}
		unset ( $news );
		unset ( $list );
		return true;
	}
	function __destruct() {
		unset ( $this );
	}
	
	public function updateStatus($ids, $status) {
		$this->setCondition ( "pollId IN (" . $ids . ")" );
		return $this->updateObjectByCondition ( $status );
	}
	
	function getTotalClick($catId=0) {
		global $DB, $bw;
		
		$DB->cur_query = "select sum(pollClick) as totalClick from {$bw->vars['sql_tbl_prefix']}" . $this->getTableName ()." where pollCatId=".$catId;
		$DB->simple_exec ();
		$tl = $DB->fetch_row ();
		
		return $tl ['totalClick'];
	}
}
?>