<?php
/*
+-----------------------------------------------------------------------------
|   VIET SOLUTION SJC  base on IPB Code version 3.0.0
|	Author: tongnguyen
|	Start Date: 5/04/2009
|	Finish Date: 11/04/2009
|	moduleName Description: This module is for management all languages in system.
+-----------------------------------------------------------------------------
*/

global $vsStd;
$vsStd->requireFile(CORE_PATH."orders/orders.php");


class orders_admin {
	private $output = "";
	private $html = "";
	private $module;
	
	function __construct(){
		global $vsStd,$vsPrint;
		global $vsTemplate;
		$this->html = $vsTemplate->load_template ( 'skin_orders' );
		$this->module = new orders ( );
	}
	
	function __destruct(){
		unset($this->output);
		unset($this->html);
	}
	
	/**
	 * @return unknown
	 */
	public function getOutput() {
		return $this->output;
	}
	
	function auto_run(){
		global $bw;
		switch ($bw->input[1]){
			case 'displayOrder':
					$this->showOrder();
				break;
			case 'viewCart':
					$this->viewCart();
				break;
			case 'update':
					$this->orderUpdate();
				break;
			case 'getListObj':
					$this->getListObj();
				break;
			case 'editOrderItem':
					$this->editOrderItem();
				break;
			case 'sendMailForm':
					$this->sendMailForm();
				break;
			case 'sendMail':
					$this->sendMail();
				break;
			case 'printOrder':
					$this->orderView(1);
				break;
			case 'deleteOrder':
					$this->deletedOrder();
				break;
			case 'success-status':
					$this->updateStatus(1);
				break;
			case 'pending-status':
					$this->updateStatus(0);
				break;
			default:
				$this->showOrder();
		}
	}
	
	function showOrder($message = ""){
		global $vsTemplate;	
		$current = $this->getListObj($message);
		$this->output = $this->html->mainLayout($current);
	}
	
	function getListObj($message = ""){
		global $vsTemplate,$vsStd;
		$option = $this->module->getPageList("orders",1,10,1,"page");
		return $this->output = $this->html->getListObj($option);
	}
	
	function editOrderItem(){
		global $bw,$vsPrint,$vsLang,$vsTemplate,$vsStd;
		$this->getObjById($bw->input[2]);
		
		$vsTemplate->assign_vars_form_string($this->html->editOrderItemForm());
	}
	
	function orderView($print=0){
		global $bw,$vsPrint,$vsLang,$vsTemplate,$vsStd;
		
		$this->module->getObjectById($bw->input[2]);
		$cart = $this->viewCart();
		$this->output = $this->html->printOrderItem($this->module->obj,$cart);
		
	}
	
	function orderUpdate(){
		global $bw;
		$this->obj->convertToObject($bw->input);
		$this->updateObjToDB();
		$this->getListObj($this->result['message']);
	}
	
	function sendMailForm(){
		global $vsStd,$bw, $vsTemplate;
		$vsStd->requireFile(UTILS_PATH."class_editor.php");
		$editor = new class_editor();
		$editorHtml=$editor->createEditor('Content',array('width'=>'100%','height'=>'350px'));
		
		$this->getObjById($bw->input[2]);
		$vsTemplate->convertObject($this->obj);
		$vsTemplate->assign_vars_form_string($editorHtml,'EDITOR');
		$vsTemplate->assign_vars_form_string($this->html->sendMail());
	}
	
	function sendMail(){
		global $bw, $vsLang, $vsStd;
		$vsStd->requireFile ( LIBS_PATH . "Email.class.php" );
		$email = new Emailer ( );
		$message = $email->clean_message ( $bw->input ['Content'] );
		$email->setTo ( $bw->input ['email'] );
		$email->setSubject ($bw->vars ['global_systememail'] );
		$email->setBody ( $message );
		$email->sendMail ();
		if($email->error)
			$this->getListObj($vsLang->getWords ( 'order_send_success', 'You have successfully send mail' ));
		else $this->getListObj($vsLang->getWords ( 'order_send_success', 'Send fail mail' ));
	}
	
	function viewCart(){
		global $bw,$DB,$vsTemplate,$vsRelation;
		
		$this->module->orderitem->setCondition("orderId in ({$bw->input[2]})");
		$this->module->orderitem->getObjectsByCondition();
		$option['cart'] = $this->module->orderitem->getArrayObj();
		
		$vsRelation->setTableName("order_gift");
		$vsRelation->setObjectId($bw->input[2]);
		$vsRelation->getRelByObject(1);
		
		$option['gift'] = $vsRelation->arrval;
		
		if(count($vsRelation->arrval)){
			$vsRelation->__destruct();
			$vsRelation->__construct();
			$vsRelation->setTableName("gift_product");
			$vsRelation->setArrayField(array("productImage"=>"productImage","optionTitle"=>"optionTitle","productTitle"=>"productTitle"));
			$vsRelation->setPrimaryField(array("objectId"=>"objectId","relId"=>"relId"));
			$vsRelation->getRelationObjByOption(array("group"=>"objectId, relId"),1);
			
			$option['gProduct'] = $vsRelation->getArrObj();
			
			
			$vsRelation->__destruct();
			$vsRelation->__construct();
			$vsRelation->setTableName("gift_product_replate");
			$vsRelation->setArrayField(array("productId"=>"productId","productImage"=>"productImage","price"=>"price","optionTitle"=>"optionTitle","productTitle"=>"productTitle"));
			$vsRelation->setPrimaryField(array("objectId"=>"objectId","productId"=>"productId","relId"=>"relId"));
			$vsRelation->getRelationObjByOption(array("group"=>"objectId,productId, relId"),1);
			$option['rProduct'] = $vsRelation->getArrObj();
		}
		
		$this->output = $this->html->viewCart($option);
		return $option;
	}
	
	function deletedOrder(){
		global $bw,$vsRelation;
		
		$this->module->orderitem->setCondition("orderId in({$bw->input[2]})");
		$this->module->orderitem->deleteObjectByCondition();
		$this->module->setCondition("orderId in({$bw->input[2]})");
		$this->module->deleteObjectByCondition();
		
		return $this->getListObj();
	}

	function updateStatus($index){
		global $bw,$vsRelation;
		if($bw->input[3]){
			$this->module->orderitem->setCondition("itemId in ({$bw->input[3]})");
			$this->module->orderitem->updateObjectByCondition(array("itemStatus"=>$index));
		}
		if($bw->input[4]){
			$vsRelation->setCondition("relId in({$bw->input[4]})");
			$vsRelation->setTableName("order_gift");
			$vsRelation->updateObjectByCondition(array("status"=>$index));
		}
		$this->viewCart();
	}
}
?>