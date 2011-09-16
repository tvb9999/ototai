<?php

require_once(CORE_PATH."modules/Module.class.php");

class modules extends VSFObject {
	public $obj;

	function __construct(){
		parent::__construct();
		$this->primaryField 	= 'moduleId';
		$this->basicClassName 	= 'Module';
		$this->tableName 		= 'module';
		$this->obj = $this->createBasicObject();
		$this->fields = $this->obj->convertToDB();
	}

	function __destruct() {

	}

	function getEnabledModule($moduleType="admin") {
		if($moduleType=="admin") {
			$this->condition = "moduleIsAdmin=1";
		}
		elseif($moduleType=="User") {
			$this->condition = "moduleIsUser=1";
		}
		$this->getAllModules();
	}

	function getModuleByClass($class="") {
		global $DB, $vsLang;
		$this->obj->setClass($class);
		$this->result['message'] = $vsLang->getWords('module_get_class_success',"Get action successfully!");
		$this->condition = "moduleClass='".$class."'";
		$this->getOneObjectsByCondition();
		if(!$this->result['status']) {
			$this->result['message'] = $vsLang->getWords('module_get_class_fail',"There is no item with specified action!");
		}

	}

	function validateModule($checkexist = false) {
		global $DB, $vsLang;
		if($checkexist) {
			$this->getModuleByClass($this->obj->getClass());
			if($this->result['status']) {
				$this->result['status'] = false;
				$this->result['message'] .= $vsLang->getWords('err_module_existed',"This module is already existed!<br>");
			}
			else $this->result['status'] = true;
		}
		else {
			$this->condition = "(moduleName='".$this->obj->getTitle()."' OR moduleClass='".$this->obj->getClass()."') AND moduleId !=".$this->obj->getId();
			$list = $this->getObjectsByCondition();
			if($this->result['status']) {
				$this->result['status'] = false;
				$this->result['message']  = "This module is duplicated with another module!<br>";
			}
		}
	}

	function getAllModules() {
		global $DB, $vsLang;
			
		$this->result['status'] = true;
		$this->result['message'] = $vsLang->getWords('get_all_success',"Get all modules success!");
			
		$DB->simple_construct(array('select'	=> '*',
										'from'		=> $this->tableName,
										'where'		=> $this->condition
		)
		);
		$DB->simple_exec();
		$module = $DB->fetch_row();
		while($module) {
			$oModule = new Module();
			$oModule->convertToObject($module);
			$this->arrayModule[$oModule->getClass()] = $oModule;
			$module = $DB->fetch_row();
		}
	}

	function getModuleById($id=0) {
		global $DB, $vsLang;

		$id = intval($id);
		$this->result['status'] = true;

		$DB->simple_construct(array('select'	=> '*',
									'from'		=> $this->tableName,
									'where'		=> 'moduleId='.$id
		)
		);
		$DB->simple_exec();
		$module = $DB->fetch_row();
		if($module) {
			$this->obj->convertToObject($module);
		}
		else {
			$this->result['status'] = false;
			$this->result['message'] = $vsLang->getWords('module_get_id_fail',"There is no item with specified ID!");
		}
	}
	
	function getModuleByIds($ids = 0){
		$lisname= "";
		if($ids){
			$this->setCondition("moduleId in ({$ids})");
			$list =  $this->getObjectsByCondition();
	
			if(count($list))
				foreach($list as $obj)
					$lisname .= $obj->getClass().",";
		}
		
		return rtrim($lisname,',');
	}

	
	/*
	 * Author 	 : khamdb@redsunic.com
	 * parameter : $condition
	 * type 	 : String
	 * return 	 : array Object
	 */
	function getVirtualList($condition = ''){
		$this->setCondition("moduleVirtual = 1");
		if($condition) 
			$this->setCondition( $this->getCondition () ." and moduleClass not in ( $condition )");
		$this->setOrder("moduleId Asc");
		return $this->getObjectsByCondition();
	}
}
?>