<?php
require_once (CORE_PATH . "partners/Partner.class.php");
class partners extends VSFObject {
	public $obj;
	
	protected $categoryField = "";
	protected $relTableName = "";
	protected $categories = array ();

	function __construct() {
		parent::__construct ();
		$this->categoryField = "partnerCatId";
		$this->primaryField = 'partnerId';
		$this->basicClassName = 'Partner';
		$this->tableName = 'partner';
		$this->relTableName = "rel_partner_file";
		$this->obj = $this ->createBasicObject ();
		$this->categories = $this->vsMenu ->getCategoryGroup ( strtolower ( $this->tableName . "s" ) );
	}

	function convertToCatId($module) {
		global $vsLang;
		
		if ($module == $vsLang ->getWords ( 'group_global_module', 'global' ))
			return 0;
		
		$module = strtolower ( $module );
		$categories = $this->categories ->getChildren ();
		foreach ( $categories as $child )
			if ($child ->getUrl () == $module)
				return $child ->getId ();
		
		$menus = new menus ();
		$menus->obj ->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
		$menus->obj ->setAlt ( $module );
		$menus->obj ->setTitle ( $module );
		$menus->obj ->setParentId ( $this->categories ->getId () );
		$menus->obj ->setIsLink ( 1 );
		$menus->obj ->setIsAdmin ( - 1 );
		$menus->obj ->setUrl ( $module );
		$menus->obj ->setStatus ( 1 );
		$menus->obj ->setLevel ( $this->categories ->getLevel () + 1 );
		$menus->obj ->setType ( 1 );
		$menus ->insertObject ();
		$this->categories->children [$menus->obj ->getId ()] = $menus->obj;
		return $menus->obj ->getId ();
	
	}

	public function getPartnersForUser() {
		global $vsStd, $bw;
		return $this->arrayObj = $this ->getPartnerList ( $condition );
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
		if (! is_object ( $treeCat ))
			return false;
		$ids = $this->vsMenu ->getChildrenIdInTree ( $treeCat );
		if ($ids)
			$this->condition = "partnerCatId in ( {$ids})";
		$this->limit = array (0, 8 );
		return $this ->getObjectsByCondition ();
	}

	/**
	 * @return the $categoryField
	 */
	public function getOtherList($obj) {
		global $vsMenu;
		$cat = $vsMenu ->getCategoryById ( $obj ->getCatId () );
		$ids = $vsMenu ->getChildrenIdInTree ( $cat );
		$this->condition = "partnerId <> {$obj->getId()}";
		if ($ids)
			$this->condition .= " and partnerCatId in ( {$ids})";
		return $this ->getObjectsByCondition ();
	}

	/**
	 * @return the $categoryField
	 */
	public function getPartnerList($condition = "") {
		global $vsMenu;
		$ids = $vsMenu ->getChildrenIdInTree ( $this ->getCategories () );
		$this->condition = "partnerStatus > 0 and partnerCatId in ( {$ids})";
		$this->order = "partnerIndex";
		if ($condition)
			$this->condition .= " and {$condition}";
		return $this ->getObjectsByCondition ();
	}

	function __destruct() {
		unset ( $this );
	}

	function delete($ids = 0) {
		global $vsStd;
		// Get objects information
		$this->fieldsString = "partnerFileId";
		$this->condition = "partnerId IN (" . $ids . ")";
		$list = $this ->getObjectsByCondition ();
		if (! count ( $list ))
			return false;
		
		// Delete news data
		$this->condition = "partnerId IN (" . $ids . ")";
		if (! $this ->deleteObjectByCondition ())
			return false;
		foreach ( $list as $news ) {
			$this->vsFile ->deleteFile ( $news ->getFileId () );
		}
		unset ( $news );
		unset ( $list );
		return true;
	}

	public function updateStatus($ids, $status) {
		$this ->setCondition ( "partnerId IN (" . $ids . ")" );
		return $this ->updateObjectByCondition ( $status );
	}

}
?>