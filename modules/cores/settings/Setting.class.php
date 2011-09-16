<?php
if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (REDSUN FW is powered by <a href=\"http://www.redsunic.com\">REDSUNIC Webdesign Company</a>)";
	exit ();
}
class Setting extends BasicObject {
	
	private $value = NULL;
	private $inputType = NULL;
	private $key = NULL;
	private $root = NULL;
	private $type = NULL;
	private $module = NULL;

	public function __construct() {
		parent::__construct ();
	}

	public function __destruct() {
		parent::__destruct ();
		unset ( $this->module );
		unset ( $this->value );
		unset ( $this->inputType );
		unset ( $this->key );
		unset ( $this->index );
		unset ( $this->root );
		unset ( $this->type );
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setModule($module) {
		$this->module = $module;
	}

	public function getModule() {
		return $this->module;
	}

	public function setRoot($root) {
		$this->root = $root;
	}

	public function setKey($key) {
		return $this->key = $key;
		$this->key = strtolower ( $key );
	}

	public function setInputType($inputType) {
		$this->inputType = $inputType;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getType() {
		return $this->type ? $this->type : 0;
	}

	public function getRoot() {
		return $this->root;
	}

	public function getKey() {
		return $this->key;
	}

	public function getInputType() {
		return $this->inputType;
	}

	public function getValue() {
		return $this->value;
	}

	public function getListNameFunctionGet() {
		return array ('settingId' => 'getId', 'settingCatId' => 'getCatId', 'settingTitle' => 'getTitle', 'settingIntro' => 'getIntro', 'settingValue' => 'getValue', 'settingInputType' => 'getInputType', 'settingKey' => 'getKey', 'settingIndex' => 'getIndex', 'settingRoot' => 'getRoot', 'settingType' => 'getType', 'settingModule' => 'getModule' );
	}

	/**
	 * use to get value of $fieldName
	 * @param string $fieldName, $fieldName: name of field in class and database
	 * @return string , value of field name
	 */
	public function getNameFunctionGetByFieldName($fieldName) {
		$list = $this->getListNameFunctionGet ();
		foreach ( $list as $key => $value )
			if ($fieldName == $key)
				return $value;
		return false;
	}

	/*
	 * change object SystemSetting to array contain element SystemSetting to insert database
	 * @return array element SystemSetting
	 */
	public function convertToDB() {
		
		isset ( $this->catId ) ? ($dbobj ['settingCatId'] = $this->catId) : '';
		isset ( $this->title ) ? ($dbobj ['settingTitle'] = $this->title) : '';
		isset ( $this->intro ) ? ($dbobj ['settingIntro'] = $this->intro) : '';
		isset ( $this->value ) ? ($dbobj ['settingValue'] = $this->value) : '';
		isset ( $this->inputType ) ? ($dbobj ['settingInputType'] = $this->inputType) : '';
		isset ( $this->key ) ? ($dbobj ['settingKey'] = $this->key) : '';
		isset ( $this->index ) ? ($dbobj ['settingIndex'] = $this->index) : '';
		isset ( $this->root ) ? ($dbobj ['settingRoot'] = $this->root) : '';
		isset ( $this->type ) ? ($dbobj ['settingType'] = $this->type) : '';
		isset ( $this->module ) ? ($dbobj ['settingModule'] = $this->module) : '';
		return $dbobj;
	}

	/*
	 * change array element SystemSetting  to object SystemSetting
	 * @param $arrayElement is a array contain element SystemSetting
	 * @return void
	 */
	function convertToObject($object) {
		global $vsMenu;
		isset ( $object ['settingId'] ) ? $this->setId ( $object ['settingId'] ) : '';
		isset ( $object ['settingCatId'] ) ? $this->setCatId ( $object ['settingCatId'] ) : '';
		isset ( $object ['settingTitle'] ) ? $this->setTitle ( $object ['settingTitle'] ) : '';
		isset ( $object ['settingIntro'] ) ? $this->setIntro ( $object ['settingIntro'] ) : '';
		isset ( $object ['settingValue'] ) ? $this->setValue ( $object ['settingValue'] ) : '';
		isset ( $object ['settingInputType'] ) ? $this->setInputType ( $object ['settingInputType'] ) : '';
		isset ( $object ['settingKey'] ) ? $this->setKey ( $object ['settingKey'] ) : '';
		$object ['settingIndex'] <> '' ? $this->setIndex ( $object ['settingIndex'] ) : '';
		isset ( $object ['settingRoot'] ) ? $this->setRoot ( $object ['settingRoot'] ) : '';
		isset ( $object ['settingType'] ) ? $this->setType ( $object ['settingType'] ) : '';
		isset ( $object ['settingModule'] ) ? $this->setModule ( $object ['settingModule'] ) : '';
	}

	function validate() {
		global $vsLang;
		$status = true;
		if ($this->title == "") {
			$this->message .= $vsLang->getWords ( 'title_empty', "Systemsetting title can not be blank!" );
			$status = false;
		}
		return $status;
	}

	public function buildElementForm($fieldName = '', $textType = '', $readonly = false, $disabled = false, $attr = array()) {
		global $vsTemplate;
		
		$readonly = $readonly ? 'readonly' : '';
		$disabled = $disabled ? 'disabled' : '';
		
		foreach ( $attr as $key => $value ) {
			$sAttr .= $key . '="' . $value . '" ';
		}
		
		$getProperty = $this->getNameFunctionGetByFieldName ( $fieldName );
		$value = $this->$getProperty ();
		
		if (! $textType)
			$textType = $this->inputType;
		
		switch ($textType) {
			case 'radio' :
				if ($this->value == 'Yes')
					$checkedYes = 'checked';
				else
					$checkedNo = 'checked';
				return $vsTemplate->global_template->buildRadioButtonHTML ( $fieldName . $this->id, $checkedYes, $checkedNo, $readonly, $disabled, $sAttr );
			
			case 'checkbox' :
				return $vsTemplate->global_template->buildCheckBoxHTML ( $fieldName . $this->id, $fieldName . $this->id, $this->value?'checked':'', $readonly, $disabled, $sAttr );
			
			default :
				return $vsTemplate->global_template->buildTextTypeHTML ( $textType, $fieldName . $this->id, $fieldName . $this->id, $value, $readonly, $disabled, $sAttr );
		}
	}
}
?>