<?php
class Relationship {
	
	private $objectId = NULL;
	private $relId = NULL;
	private $arrayField = array ();

	function __construct() {
	}

	function __destruct() {
		unset ( $this->objectId );
		unset ( $this->relId );
		unset ( $this->arrayField );
	}

	function setRelId($relId) {
		$this->relId = $relId;
	}

	function setObjectId($objectId) {
		$this->objectId = $objectId;
	}

	function getRelId() {
		return $this->relId;
	}

	function getObjectId() {
		return $this->objectId;
	}

	function setArrayField($array) {
		if (is_array ( $array ))
			$this->arrayField = $array;
	}

	function extendObjectFields($array) {
		if (is_array ( $array )) {
			$this->arrayField = $array;
			foreach ( $array as $field )
				$this->$field = NULL;
		}
	}

	function convertFieldToObject($object = array()) {
		foreach ( $this->arrayField as $value ) {
			if (isset ( $object [$value] ))
				$this->$value = $object [$value];
		}
	}

	function convertToObject($object = array()) {
		isset ( $object ['objectId'] ) ? $this->setObjectId ( $object ['objectId'] ) : '';
		isset ( $object ['relId'] ) ? $this->setRelId ( $object ['relId'] ) : '';
		
		if (is_array ( $this->arrayField ))
			$this->convertFieldToObject ( $object );
	}

	function convertFieldToDB($dbobj) {
		foreach ( $this->arrayField as $value )
			isset ( $this->$value ) ? $dbobj [$value] = $this->$value : '';
	}

	function convertToDB() {
		isset ( $this->objectId ) ? ($dbobj ['objectId'] = $this->objectId) : '';
		isset ( $this->relId ) ? ($dbobj ['relId'] = $this->relId) : '';
		if (is_array ( $this->arrayField ))
			$this->convertFieldToDB ( &$dbobj );
		return $dbobj;
	}
}	