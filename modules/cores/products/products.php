<?php
require_once(CORE_PATH."products/Product.class.php");

class products extends VSFObject {
	
	public $obj;
	public $optProduct;
	protected $categoryField 	="";
	protected $relTableName 	="";
	protected $categories 		= array();
	
	function __construct(){
		$this->requireFileUseFull();
		parent::__construct();
		$this->categoryField 	= "productCatId";
		$this->primaryField 	= 'productId';
		$this->basicClassName 	= 'Product';
		$this->tableName 		= 'product';
		
		$this->relTableName 	= "product_category";
		$this->obj = $this->createBasicObject();
		
		$this->categories = array();
        $this->categories = $this->vsMenu->getCategoryGroup(strtolower($this->tableName."s"));

	}	
	
	function getListWithCat($treeCat) {
		global $vsSettings;
		if(!is_object($treeCat)) 
			return false;
		$ids = $this->vsMenu->getChildrenIdInTree($treeCat);
		if($ids) 
			$this->condition = "productCatId in ( {$ids})";
		$this->limit = array(0, $vsSettings->getSystemKey('list_cat_quality', 10, 'products'));
		return $this->getObjectsByCondition();
	}
	
	function getSpecialList($limit = 0, $type=null,$condition = null){
		global $bw, $vsSettings;
		
		$this->setCondition("productStatus = {$type} ".$condition);
		$this->setOrder("productIndex DESC, productId DESC");
		$array = $this->getCondition();
		
		if(!$limit)
			$limit = $vsSettings->getSystemKey('product_special_quality', 12, 'products');
		$this->setLimit(array(0, $limit));
		return $this->getObjectsByCondition();
	}
	
	function getLastedtList($limit){
		global $vsSettings;
		$ids=$this->vsMenu->getChildrenIdInTree($this->getCategories());
		$this->condition .= " productStatus > 0 and productCatId in ( {$ids})";
		$this->setOrder("productIndex DESC, productId DESC");
		
		if(!$limit)
		$limit = $vsSettings->getSystemKey('product_lastest_quality', 12, 'products');
		$this->setLimit(array(0, $limit));
		return $this->getObjectsByCondition();
	}

	function getOtherList($obj, $url="") {
		global $vsSettings;
		$cat = $this->vsMenu->getCategoryById($obj->getCatId());
		$ids = $this->vsMenu->getChildrenIdInTree($cat);
		$this->condition = "productId <> {$obj->getId()} and productStatus>0";
		if($ids)
			$this->condition .= " and productCatId in ( {$ids})";
		$size = $vsSettings->getSystemKey('product_other_Quality', 10, 'products');
		return $this->getPageList($url, 3, $size);
	}
	
	function delete($ids = 0) {
		global $vsStd;
		$this->result['message'] = $this->vsLang->getWords('product_delete_by_id_success', "Deleted successfully!");
		// Get objects information
		$this->fieldsString = "productImage";
		$this->condition = "productId IN (".$ids .")";
		$list = $this->getObjectsByCondition();
		if(!count($list)) return false;
		// Delete product data
		$this->condition = "productId IN (".$ids .")";
		
		if(!$this->deleteObjectByCondition()) return false;
		$this->optProduct->setCondition("productId IN (".$ids .")");
		$this->optProduct->deleteObjectByCondition();
		foreach ($list as $product){
			$this->vsFile->deleteFile($product->getImage());
		}	
		unset($product);
		unset($list);
		return true;
	}

	function deleteObjInCategory($catIds = 0){
		global $bw, $vsStd, $vsSettings;

		if($vsSettings->getSystemKey("products_multi_category", 0, "products", 0, 1)){
			$vsStd->requireFile(LIBS_PATH."Relationships.php");
			$rel = new VSFRelationship();
			
			$query = "SELECT *, count(*) as count from vsf_product_category  group by objectId having relId in({$catIds})";
			$list = $this->executeQuery($query, 0);
			if(!count($list)) return false;
			
			$ids = "";
			foreach($list as $element)
				if($element['count'] < 2)
					$ids .= $element['objectId'].",";
				else $where .= 'relId = '.$element['relId']." AND objectId = ".$element['objectId']." AND ";
				
			$ids = trim($ids, ",");
			$where = trim($where, " AND ");

			$condition = " objectId in (".$ids.") ";
			$rel->setTableName($this->getRelTableName());
			$rel->delRelationByOption($condition);
			if($where)
				$rel->delRelationByOption($where);
				
		
			$query = "SELECT productId, productImage from vsf_product where productCatId in(".$ids.")";
			$list = $this->executeQuery($query, 0);
			
			foreach($list as $product)
				$this->vsFile->deleteFile($product['productImage']);

			$this->condition = "productId IN (".$ids.")";
			if(!$this->deleteObjectByCondition()) return false;
			
			return true;
		}
		
		$temp = explode(",", $catIds);
		foreach($temp as $element){
			$cat = $this->vsMenu->getCategoryById($element);
			$ids.= $this->vsMenu->getChildrenIdInTree($cat).",";
		}
		
		$ids = trim($ids, ",");
		
		$query = "SELECT productId, productImage from vsf_product where productCatId in(".$ids.")";
		$list = $this->executeQuery($query, 0);

		if(!count($list)) return false;
		
		foreach ( $list as $product )
			$this->vsFile->deleteFile($product['productImage']);

		$this->condition = "productCatId IN (".$ids.")";
		if(!$this->deleteObjectByCondition()) return false;
			
		return true;
	}
	
	function getRelTableName() {
		return $this->relTableName;
	}

	function setRelTableName($relTableName) {
		$this->relTableName = $relTableName;
	}

	function setCategories($categories) {
		$this->categories = $categories;
	}
	
	function requireFileUseFull() {
		global $vsStd;
		$vsStd->requireFile(UTILS_PATH."TextCode.class.php");
	}

	function setCategoryField($categoryField) {
		$this->categoryField = $categoryField;
	}
	
	function getCategories() {
		return $this->categories;
	}

	function getCategoryField() {
		return $this->categoryField;
	}

	function __destruct(){	
		unset($this);
	}	
	
}
?>