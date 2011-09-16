<?php
require_once(CORE_PATH."orders/OrderItem.class.php");
class orderItems extends VSFObject {
	public $obj;
	
	function __construct(){
		parent::__construct();
		$this->primaryField 	= 'itemId';
		$this->basicClassName 	= 'OrderItem';
		$this->tableName 		= 'order_item';
		
		$this->obj = $this->createBasicObject();
		$this->fields = $this->obj->convertToDB();
	}
	
	function __destruct(){	
		unset($this);
	}	
	
	function getListByProductId($productId=0){
		$this->setCondition("productId IN (". $productId.")");
		return $this->getObjectsByCondition();
	}
}
?>