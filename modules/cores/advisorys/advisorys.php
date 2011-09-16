<?php
require_once (CORE_PATH . "advisorys/Advisory.class.php");
class advisorys extends VSFObject {
	
	public $obj;
	protected $categoryField = "";
	public $categories = array ();

	function __construct() {
		global $DB;
		parent::__construct ();
		$this->primaryField = 'advisoryId';
		$this->basicClassName = 'advisory';
		$this->tableName = 'advisorys';
		$this->categoryField = 'advisorysCatId';
		$this->obj = $this->createBasicObject ();
		$this->fields = $this->obj->convertToDB ();
		$this->categories = array ();
		$this->categories = $this->vsMenu->getCategoryGroup ( strtolower ( $this->tableName ) );
	}

	/**
	 * @param $categories the $categories to set
	 */
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

	function validate($checkPassword = true) {
		global $vsLang;
		
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		
		if ($this->obj->getTitle () == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->currentArrayWords ['err_admin_name_blank'];
		}
	}

	function __destruct() {
	}

	function validator() {
		global $vsLang;
		
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		if ($this->obj->getName () == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang ( "advisorys_ErrorNameEmpty", "* Name cannot be blank!" );
		}
//		if ($this->advisory->getadvisoryTitle () == "") {
//			$this->result ['status'] = false;
//			$this->result ['message'] .= $vsLang ( "advisorys_ErrorTitleEmpty", "* Title cannot be blank!" );
//		}
		if ($this->obj->getIntro () == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang ( "advisorys_ErrorMessageEmpty", "* Question cannot be blank!" );
		}
		if ($this->obj->getEmail () == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang ( "advisorys_ErrorEmailEmpty", "* Email cannot be blank!" );
		}
	}

	public function getOtherList($obj, $url = "") {
		global $vsSettings;
		$cat = $this->vsMenu->getCategoryById ( $obj->getCatId () );
		$ids = $this->vsMenu->getChildrenIdInTree ( $cat );
		$this->condition = "advisoryId <> {$obj->getId()} and advisoryStatus>0";
		if ($ids)
			$this->condition .= " and advisoryCatId in ( {$ids})";
		$size = $vsSettings->getSystemKey ( 'advisory_other_Quality', 10, 'advisorys' );
		return $this->getPageList ( $url, 3, $size );
	}

	public function getListShowHome() {
		$ids = $this->vsMenu->getChildrenIdInTree ( $this->getCategories () );
		$this->setCondition ( "advisoryStatus >0 and advisoryCatId in ($ids)" );
		$obj = $this->getOneObjectsByCondition ();
		
		return $obj;
	}

}
?>