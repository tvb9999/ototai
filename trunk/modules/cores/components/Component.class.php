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

class Component{
	private $comId = 0;
	private $comName = "";
	private $comPackage = "";
	private $comInstalled = 0;
	private $comDescription = "";
	
	/**
	 * @return unknown
	 */
	public function getComDescription() {
		return $this->comDescription;
	}
	
	/**
	 * @return unknown
	 */
	public function getComId() {
		return $this->comId;
	}
	
	/**
	 * @return unknown
	 */
	public function getComInstalled() {
		return $this->comInstalled;
	}
	
	/**
	 * @return unknown
	 */
	public function getComName() {
		return $this->comName;
	}
	
	/**
	 * @return unknown
	 */
	public function getComPackage() {
		return $this->comPackage;
	}
	
	/**
	 * @param unknown_type $comDescription
	 */
	public function setComDescription($comDescription) {
		$this->comDescription = $comDescription;
	}
	
	/**
	 * @param unknown_type $comId
	 */
	public function setComId($comId) {
		$this->comId = $comId;
	}
	
	/**
	 * @param unknown_type $comInstalled
	 */
	public function setComInstalled($comInstalled) {
		$this->comInstalled = $comInstalled;
	}
	
	/**
	 * @param unknown_type $comName
	 */
	public function setComName($comName) {
		$this->comName = $comName;
	}
	
	/**
	 * @param unknown_type $comPackage
	 */
	public function setComPackage($comPackage) {
		$this->comPackage = $comPackage;
	}
	
	function __destruct(){
		unset($this->comId);
		unset($this->comName);
		unset($this->comPackage);
		unset($this->comInstalled);
		unset($this->comDescription);
	}
	
	public function convertToDB(){
		return array(   'comName' 		=> $this->comName,
						'comPackage'	=> $this->comPackage,
						'comInstalled' 	=> $this->comInstalled,
						'comDescription'=> $this->comDescription);
	}
	
	public function convertToObject($object){
		$this->comId 			= $object['comId'];
		$this->comName 			= $object['comName'];
		$this->comPackage 		= $object['comPackage'];
		$this->comInstalled 	= $object['comInstalled'];
		$this->comDescription 	= $object['comDescription']; 
	}
}
?>