<?php
/*
+-----------------------------------------------------------------------------
|   VIET SOLUTION SJC  base on IPB Code version 3.3.4.1
|	Author: tongnguyen
|	Start Date: 19/05/2009
|	Finish Date: 20/05/2009
|	moduleName Description: This module is for management all component in system.
+-----------------------------------------------------------------------------
*/

global $vsStd;
$vsStd->requireFile ( CORE_PATH . "components/Component.class.php");

class components {
	public $components = "";
	private $objsource = "";
	public $result = array ();
	public $arrayCom = array();
	
	function __construct() {
		$this->components = new Component();
		$this->objsource = "components";
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		$this->getAllComponent();
	}
	
	function __destruct() {
		unset ( $this->components );
		unset ( $this->result );
	}
	
	public function getComponentId($comId) {
		global $vsLang;
		
		$this->components->setComId($comId);
		if (!isset($this->arrayCom[$this->components->getComId()])) {
			$this->result ['status'] = false;
			$this->result ['message'] = $vsLang->getWords ( 'component_no_id', 'There is no item with specified ID!' );
			return false;
		}
		
		return $this->arrayCom[$this->components->getComId()];
	}
	
	public function getAllComponent() {
		global $DB;
		
		$this->arrayCom = array ();
		
		$DB->simple_construct ( array ('select' => '*', 'from' => $this->objsource, 'order' => 'comId' ) );
		$DB->simple_exec ();
		
		while ( $com = $DB->fetch_row () ) {
			$comO = new Component();
			$comO->convertToObject ( $com );
			$this->arrayCom [$comO->getComId ()] = $comO;
		}
		unset ( $com );
		unset ( $comO );
	}
	
	function validateComponent() {
		
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		
		if ($this->components->getComName () == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= "Components name can't be blank";
		}
	}
	
	public function insertComponent() {
		global $DB, $vsLang;
		
		$this->validateComponent ();
		
		if (! $this->result ['status'])
			return;
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		
		$dbObj = $this->components->convertToDB ();
		if (! $DB->do_insert ( $this->objsource, $dbObj )) {
			$this->result ['status'] = false;
			$this->result ['message'] = $vsLang->getWords ( 'component_insert_fail', 'There is an error when insert field!' );
		} else
			$this->result ['message'] .= $vsLang->getWords ( 'component_insert_success', 'You have successfully insert the field!' );
	}
	
	public function updateComponent() {
		global $DB, $vsLang;
		
		$this->validateComponent ();
		
		if (! $this->result ['status'])
			return;
		
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		
		$dbObj = $this->components->convertToDB ();

		if (! $DB->do_update ( $this->objsource, $dbObj, 'comId=' . $this->components->getComId () )) {
			$this->result ['status'] = false;
			$this->result ['message'] = $vsLang->getWords ( 'component_update_fail', 'There is an error when update field!' );
		} else
			$this->result ['message'] .= $vsLang->getWords ( 'component_update_success', 'You have successfully update the field!' );
	}
	
	public function deteleComponent($comId){
		global $DB, $vsLang;
		
		$order = $this->getComponentId($comId);
		if(!$this->result['status']) return ;
		
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		
		$DB->simple_delete($this->objsource,'comId='.$comId);
		if($DB->simple_exec()) $this->result['message'] .= $vsLang->getWords('order_delete','You have successfully detele order ')."[".$order->getOrderName()."]";
		else{
			$this->result['status'] = false;
			$this->result['message'] = "There is an error when deleted a order!";
		}
	}
}
?>