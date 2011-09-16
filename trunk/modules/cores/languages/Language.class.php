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

class Lang {
	private $name 			= null; // name of language package
	private $admindefault 	= null; // 0 for admin and 1 for user
	private $userdefault 	= null; // 0 for not default, 1 for default
	private $foldername 	= null; // folder name of language when store language in files
	private $status 		= null; // 0 is instatus, 1 is status
	private $symbol 		= null; // the symbol of language, e.g: vietnamese flag for vietnamese language

	public $langpath 		= null;

	private $id 	= null;
	private $module 	= ""; 	// module that own this item
	private $value 		= array();	// language value of this item


	function __construct(){
		// Set path of Lang
		$this->langpath = ROOT_PATH."langs/";
	}

	function __destruct(){
		unset($this->itemId);
		unset($this->status);
		unset($this->foldername);
		unset($this->userdefault);
		unset($this->admindefault);
		unset($this->name);
		unset($this->userDefault);
		unset($this->id);
		unset($this->module);
		unset($this->value);
	}
	public function getPrimary() {
		return 'langId';
	}
	/**
	 * @return unknown
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return unknown
	 */
	public function getUserDefault() {
		return $this->userdefault;
	}

	/**
	 * @return unknown
	 */
	public function getFolderName() {
		return $this->foldername;
	}

	/**
	 * @return unknown
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return unknown
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * @return unknown
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return unknown
	 */
	public function getSymbol() {
		return $this->symbol;
	}

	/**
	 * @return unknown
	 */
	public function getAdminDefault() {
		return $this->admindefault;
	}

	/**
	 * @return unknown
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param unknown_type $type
	 */
	public function getLangPath() {
		return $this->langpath;
	}

	/**
	 * @param unknown_type $status
	 */
	public function setStatus($Status) {
		$this->status = $Status;
	}

	/**
	 * @param unknown_type $default
	 */
	public function setUserDefault($default) {
		$this->userdefault = intval($default);
	}

	/**
	 * @param unknown_type $foldername
	 */
	public function setFolderName($foldername) {
		$this->foldername = $foldername;
	}

	/**
	 * @param unknown_type $id
	 */
	public function setId($langId) {
		$this->id = intval($langId);
	}

	/**
	 * @param unknown_type $module
	 */
	public function setModule($module) {
		$this->module = $module;
	}

	/**
	 * @param unknown_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param unknown_type $symbol
	 */
	public function setSymbol($symbol) {
		$this->symbol = $symbol;
	}

	/**
	 * @param unknown_type $type
	 */
	public function setAdminDefault($adminDefault) {
		$this->admindefault = intval($adminDefault);
	}

	/**
	 * @param unknown_type $type
	 */
	public function setLangPath($langpath) {
		$this->langpath = $langpath;
	}

	/**
	 * @param unknown_type $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	public function convertToDB() {
		isset($this->id)        	? ($dbobj['langId'] 		= $this->id) 			: '';
		isset($this->name)        	? ($dbobj['langName'] 		= $this->name) 			: '';
		isset($this->userdefault)   ? ($dbobj['userDefault'] 	= $this->userdefault) 	: '';
		isset($this->admindefault)  ? ($dbobj['adminDefault'] 	= $this->admindefault) 	: '';
		isset($this->foldername )   ? ($dbobj['langFolder'] 	= $this->foldername) 	: '';
		isset($this->status)     	? ($dbobj['langStatus'] 	= $this->status) 		: '';
		isset($this->symbol )      	? ($dbobj['langSymbol'] 	= $this->symbol) 		: '';

		return $dbobj;
	}
	/**
	 * change new from database object to Module object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	public function convertToObject($object=array(),$time_method = "SHORT") {
		isset($object['langId'])		? $this->setId($object['langId'])							:'';
		isset($object['langName'])		? $this->setName($object['langName'])						:'';
		isset($object['userDefault'])	? $this->setUserDefault($object['userDefault'])				:'';
		isset($object['adminDefault'])	? $this->setAdminDefault($object['adminDefault'])			:'';
		isset($object['langFolder'])	? $this->setFolderName($object['langFolder'])				:'';
		isset($object['langStatus'])	? $this->setStatus($object['langStatus'])					:'';
		isset($object['langSymbol'])	? $this->setSymbol($object['langSymbol'])					:'';
		isset($object['langFolder'])	? $this->setLangPath($this->langpath.$object['langFolder'])	:'';
	}
	/**
	 * change object to template use
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	public function convertToView() {
		isset($this->id)   			? ($template['langId'] = $this->getId() ) 					: '';
		isset($this->name)        	? ($template['langName'] = $this->getName()) 				: '';
		isset($this->userdefault)   ? ($template['userDefault'] = $this->getUserDefault()) 		: '';
		isset($this->admindefault)  ? ($template['adminDefault'] = $this->getAdminDefault()) 	: '';
		isset($this->foldername )   ? ($template['langFolder'] = $this->getFolderName()) 		: '';
		isset($this->status)     	? ($template['langStatus'] = $this->getStatus()) 			: '';
		isset($this->symbol )       ? ($template['langSymbol'] = $this->getSymbol()) 			: '';
		isset($this->langpath )     ? ($template['langPath'] = $this->getLangPath()) 			: '';
		return $template;
	}

}
?>