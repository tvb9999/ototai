<?php

require_once(CORE_PATH."products/products.php");

class products_public{
	
	protected $html;
	protected $module;
	protected $output;
	
	function __construct() {
		global $vsTemplate, $vsPrint;
		$this->module = new products();
        $this->html = $vsTemplate->load_template('skin_products');
	}
	
	function auto_run() {
		global $bw, $vsTemplate;
	
		switch ($bw->input[1]) {
			case 'search':
				$this->loadSearch();
				break;
			case 'detail':
				$this->loadDetail($bw->input[2]);
				break;
			case 'category':
				$this->loadCategory($bw->input[2]);
				break;
			default:
				$this->loadDefault();
				break;
		}
	}
	
	function loadSearch(){
		global $bw,$vsMenu,$vsLang,$vsPrint,$vsStd, $DB;
		
		$vsPrint->mainTitle = $vsPrint->pageTitle = $vsLang->getWords('product_search_result','Kết quả tìm kiếm');
		$url = "products/search";
		$keywords = $bw->input['productName'];
		$keywords = strtolower ( VSFTextCode::removeAccent ( trim ( $keywords ) ) );		
		$where = "productStatus>0 and productClearSearch LIKE '%".$keywords."%'";
		
		$this->module->setCondition($where);
		$option['pageList'] = $this->module->getObjectsByCondition();
		
		if(!count($option['pageList']))
			$option['message'] = $vsLang->getWords('pro_no_search','Không tìm thấy sản phẩm. Hãy nhập từ khóa khác xem sao!');
		$this->output = $this->html->loadDefault($option);
	}
	
	function loadDefault(){
		$objList['pageList'] = $this->module->getListWithCat($this->module->getCategories());
		
		return $this->output = $this->html->loadDefault($objList);
	}
	
	public function loadDetail($str) {
		global $bw, $vsLang, $vsPrint, $vsMenu;
		
		$query = explode('-',$str);
		$productId = abs(intval($query[count($query)-1]));
		$obj = $this->module->getObjectById($productId);
		if(!$obj) 
			return $vsPrint->redirect_screen('Không có dữ liệu theo yêu cầu');
		$vsPrint->mainTitle = $vsPrint->pageTitle = $obj->getTitle();
		$option['category'] =  $this->module->vsMenu->getCategoryById($obj->getCatId());
		$option['other'] =  $this->module->getOtherList($obj); 
		return $this->output = $this->html->loadDetail($obj, $option);
	}
	
	public function loadCategory($catId=0) {
		global $vsPrint,$bw,$vsSettings,$vsMenu;
				
		$category = $vsMenu->getCategoryById($catId);
		$ids = $this->module->vsMenu->getChildrenIdInTree($catId);
		$this->module->setCondition("productCatId in ( {$ids}) and productStatus >0");
//		$this->module->setOrder("productIndex DESC, productId DESC");
		$size = $vsSettings->getSystemKey('product_listCat_Quality', 10, 'products');
		$listObjectAll = $this->module->getPageList("products/category/$catId",3,$size);
		$vsPrint->mainTitle = $vsPrint->pageTitle = $category->getTitle();
		
		return $this->output = $this->html->loadDefault($listObjectAll,$this->module);
	}
	
	public function getOutput() {
		return $this->output;
	}

	public function setOutput($output) {
		$this->output = $output;
	}
}
?>