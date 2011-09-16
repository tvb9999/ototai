<?php
global $vsStd;
$vsStd->requireFile ( CORE_PATH . "orders/Order.class.php");
$vsStd->requireFile ( CORE_PATH . "orders/orderitem.php" );
class orders extends VSFObject {
	public $obj;
	public $arrayObj = array();
	public $className;
	public $orderitem;
	
	function __construct() {
		global $DB;
		parent::__construct ();
		$this->primaryField = 'orderId';
		$this->basicClassName = 'Order';
		$this->tableName = 'order';
		$this->obj	=$this->createBasicObject ();
		$this->fields = $this->obj->convertToDB();
		$this->orderitem = new orderItems();
	}
	
	function __destruct() {
		unset ( $this->obj );
		unset ( $this->className );
		unset ( $this->objsource );
	}
	
}
?>