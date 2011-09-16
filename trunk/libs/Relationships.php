<?php
require_once (LIBS_PATH . "Relationship.class.php");
class VSFRelationship extends VSFObject {
	
	public $tableName = NULL;
	public $primaryField = NULL;
	public $basicClassName = 'Relationship';
	
	public $result = array ();
	public $arrayField = array ();
	
	private $obj = NULL;
	private $relId = "";
	private $objectId = "";
	
	public $arrval = array ();
	private $arrObj = array ();

	function resetParameter() {
		$this->result = array ();
		$this->arrayField = array ();
		$this->arrval = array ();
	}

	function resetObject() {
		$this->relId = "";
		$this->objectId = "";
		$this->resetParameter ();
	}

	function createBasicObject() {
		if ($this->basicClassName) {
			$this->obj = new $this->basicClassName ();
			return true;
		}
		return false;
	}

	function __construct() {
		parent::__construct ();
		$this->createBasicObject ();
	}

	function __destruct() {
		unset ( $this->tableName );
		unset ( $this->primaryField );
		//		unset($this->basicClassName);
		unset ( $this->result );
		unset ( $this->arrayField );
		unset ( $this->arrval );
		unset ( $this->obj );
		unset ( $this->arrObj );
		unset ( $this->relId );
		unset ( $this->objectId );
	}

	//fill $tableName $objectId $relId  and delete if we have full infomation about it

	function delRelByObject() {
		global $DB;
		$this->result ['status'] == true;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] == false;
			$this->result ['message'] .= "Table [" . SQL_PREFIX . $this->tableName . "] not exits";
			return false;
		}
		if ($this->objectId) {
			$condition = "objectId in ($this->objectId)";
			$this->setCondition ( $condition );
			$DB->simple_construct ( array("select"=> "relId" ,"from"=> $this->tableName,"where"=> $condition) );
			$DB->simple_exec ();
			$rel = $DB->fetch_row ();
			$this->deleteObjectByCondition ();
			return $rel['relId'];
		}
		return true;
	}

	function delObjectByRel() {
		global $DB;
		$this->result ['status'] == true;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] == false;
			$this->result ['message'] .= "Table [" . SQL_PREFIX . $this->tableName . "] not exits";
			return false;
		}
		if ($this->relId) {
			$condition = "relId =" . $this->relId;
			$this->setCondition ( $condition );
			$this->deleteObjectByCondition ();
		}
		return true;
	}

	function delRelationByOption($condition) {
		global $DB;
		$this->result ['status'] == true;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] == false;
			$this->result ['message'] .= "Table [" . SQL_PREFIX . $this->tableName . "] not exits";
			return false;
		}
		$this->setCondition ( $condition );
		$this->deleteObjectByCondition ();
		return true;
	}

	// return $arrval if has field and $val
	function getObjectObjByRel($group = 0) {
		global $DB, $vsLang;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_noTable", "This table is not exits" ) . "<br>";
			return false;
		}
		
		if ($this->relId) {
			$DB->simple_construct ( array ('select' => '*', 'from' => $this->tableName, 'where' => "relId in ({$this->relId})" ) );
			$DB->simple_exec ();
			$this->resetParameter ();
			$count = count ( $this->primaryField );
			
			$rel = $DB->fetch_row ();
			while ( $rel ) {
				$this->obj = clone $this->obj;
				$this->obj->convertToObject ( $rel );
				
				if ($group) {
					if ($count) {
						$eval = "\$this->arrObj";
						foreach ( $this->primaryField as $field )
							$eval = $eval . "[$rel[$field]]";
						eval ( $eval . "=\$this->obj ;" );
					} else
						$this->arrObj [$rel ['objectId']] [$rel ['relId']] = $this->obj;
				
				} else
					$this->arrObj [$rel ['objectId']] = $this->obj;
				
				$rel = $DB->fetch_row ();
			}
			return true;
		} else {
			$this->result ['status'] = false;
			$this->result ['message'] .= "relId not exits<br>";
			return false;
		}
	}

	function getObjectByRel($advance = false) {
		global $DB, $vsLang;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_noTable", "This table is not exits" ) . "<br>";
			return "";
		}
		if ($this->relId) {
			$DB->simple_construct ( array ('select' => '*', 'from' => $this->tableName, 'where' => "relId in ({$this->relId})" ) );
			$DB->simple_exec ();
			$this->resetParameter ();
			
			$rel = $DB->fetch_row ();
			while ( $rel ) {
				$objectId .= $rel ['objectId'] . ',';
				if ($advance)
					$this->arrval [$rel ['objectId']] = $rel;
				$rel = $DB->fetch_row ();
			}
			return trim ( $objectId, ',' );
		} else {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_relIdNotExist", "relId is not exist" ) . "<br>";
			return "";
		}
	}

	// return $arrval if has field and $val
	function getRelObjByObject($group = 0) {
		global $DB, $vsLang;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_noTable", "This table is not exists" ) . "<br>";
			return false;
		}
		if ($this->objectId) {
			$arr = array ('select' => '*', 'from' => $this->tableName, 'where' => "objectId in ({$this->objectId})" );
			
			if ($this->relId)
				$arr ['where'] .= " and relId in ($this->relId)";
			$DB->simple_construct ( $arr );
			$DB->simple_exec ();
			
			$this->resetParameter ();
			
			$count = count ( $this->primaryField );
			
			$rel = $DB->fetch_row ();
			
			while ( $rel ) {
				$this->obj = clone $this->obj;
				$this->obj->convertToObject ( $rel );
				
				if ($group) {
					if ($count) {
						$eval = "\$this->arrObj";
						foreach ( $this->primaryField as $field )
							$eval = $eval . "[$rel[$field]]";
						eval ( $eval . "=\$this->obj ;" );
					} else
						$this->arrObj [$rel ['relId']] [$rel ['objectId']] = $this->obj;
				
				} else
					$this->arrObj [$rel ['relId']] = $this->obj;
				
				$rel = $DB->fetch_row ();
			}
			return true;
		} else {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_objIdNotExist", "objectId is not exist" ) . "<br>";
			return false;
		}
	}

	function getRelByObject($advance = 0) {
		global $DB, $vsLang;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_noTable", "This table is not exists" ) . "<br>";
			return "";
		}
		if ($this->objectId) {
			$arr = array ('select' => '*', 'from' => $this->tableName, 'where' => "objectId in ({$this->objectId})" );
			
			if ($this->relId)
				$arr ['where'] .= " and relId in ($this->relId)";
			$DB->simple_construct ( $arr );
			$DB->simple_exec ();
			
			$this->resetParameter ();
			$rel = $DB->fetch_row ();
			
			while ( $rel ) {
				$relId .= $rel ['relId'] . ',';
				if ($advance)
					$this->arrval [$rel ['relId']] = $rel;
				$rel = $DB->fetch_row ();
			}
			return trim ( $relId, ',' );
		
		} else {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_objIdNotExist", "objectId is not exist" ) . "<br>";
			return "";
		}
	}

	function getRelationObjByOption($option = array(), $group = 0, $advance = 1) {
		global $DB, $vsLang;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_noTable", "This table is not exits" ) . "<br>";
			return "";
		}
		$arr = array ('select' => ($option ['select'] ? $option ['select'] : '*'), 'from' => ($option ['from'] ? $option ['from'] : $this->tableName), 'where' => ($option ['where'] ? $option ['where'] : 1), 'groupby' => ($option ['group'] ? $option ['group'] : NULL), 'having' => ($option ['having'] ? $option ['having'] : NULL) );
		
		$DB->simple_construct ( $arr );
		$DB->simple_exec ();
		$this->resetParameter ();
		
		$count = count ( $this->primaryField );
		$rel = $DB->fetch_row ();
		while ( $rel ) {
			$this->obj = clone $this->obj;
			$this->obj->convertToObject ( $rel );
			$relId .= $rel ['relId'] . ',';
			if ($group) {
				if ($count) {
					$eval = "\$this->arrObj";
					foreach ( $this->primaryField as $field )
						$eval = $eval . "[$rel[$field]]";
					eval ( $eval . "=\$this->obj ;" );
				} else {
					$this->arrObj [reset ( $rel )] = $this->obj;
					if ($advance)
						$this->arrval [$rel ['relId']] = $rel;
				}
			} else {
				if ($advance)
					$this->arrval [$rel ['relId']] = $rel;
				$this->arrObj [$this->obj->getRelId ()] = $this->obj;
			}
			
			$rel = $DB->fetch_row ();
		}
		return trim ( $relId, ',' );
	}

	function getRelationByOption($advance = false, $option = array()) {
		global $DB, $vsLang;
		if (! $this->check_exitTable ()) {
			$this->result ['status'] = false;
			$this->result ['message'] .= $vsLang->getWords ( "global_relationship_err_noTable", "This table is not exits" ) . "<br>";
			return "";
		}
		$arr = array ('select' => ($option ['select'] ? $option ['select'] : '*'), 'from' => ($option ['from'] ? $option ['from'] : $this->tableName), 'where' => ($option ['where'] ? $option ['where'] : 1) );
		$DB->simple_construct ( $arr );
		$DB->simple_exec ();
		$this->resetParameter ();
		
		$resultKey = $option ['key'] ? $option ['key'] : "objectId";
		$rel = $DB->fetch_row ();
		while ( $rel ) {
			$relationId .= $rel [$resultKey] . ',';
			if ($advance)
				$this->arrval [$rel [$resultKey]] = $rel;
			$rel = $DB->fetch_row ();
		}
		return trim ( $relationId, ',' );
	}

	//tao 1 table relationship  khi co $this->tableName 
	function createTable() {
		global $DB;
		$this->result ['status'] = true;
		if ($this->tableName == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= "Name table  can't be left blank!<br>";
			return false;
		}
		if ($this->check_exitTable ()) {
			$this->result ['message'] .= "Table name[" . SQL_PREFIX . $this->tableName . "] has been exits";
			return false;
		}
		$sql = $this->getSQLString ();
		$DB->query ( $sql );
		if ($DB->query_id)
			$this->result ['message'] .= "Table name[" . SQL_PREFIX . $this->tableName . "] has been created success";
		return true;
	}

	function insertRel($groupDelete = NULL, $advance = NULL) {
		global $DB, $bw;
		$this->result ['status'] == true;
		$this->validateRelation ();
		
		if (! $this->result ['status'])
			return false;
		
		if (! $this->check_exitTable ())
			$this->createTable ();
		
		if ($advance)
			$condition = $advance;
		else {
			if (is_string ( $this->objectId ) || is_numeric ( $this->objectId ))
				$condition = $groupDelete ? "objectId in ({$this->objectId}) and relId in ({$groupDelete})" : "objectId in ({$this->objectId})";
			else
				$this->result ['message'] .= "objectId is not a numberic";
		}
		
		$DB->simple_delete ( $this->tableName, $condition );
		$DB->simple_exec ();
		
		$array = explode ( ',', $this->relId );
		
		foreach ( $array as $value ) {
			$array = array ('relId' => $value, 'objectId' => $this->objectId );
			if (count ( $this->arrayField ))
				$array = array_merge ( ( array ) $array, ( array ) $this->arrayField );
			
			$this->obj->convertToObject ( $array );
			
			$dbObj = $this->obj->convertToDB ();
			
			if ($DB->do_insert ( $this->tableName, $dbObj )) {
				if ($DB->query_id)
					$this->result ['message'] .= "Insert value to table [" . SQL_PREFIX . $this->tableName . "] succcess";
			}
		}
	}

	function initRelationship() {
		if ($this->tableName == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= "Name table  can't be left blank!<br>";
			return false;
		}
		if (! $this->check_exitTable ())
			$this->createTable ();
	}

	function getSQLString() {
		$advance = " , ";
		foreach ( $this->arrayField as $key => $value )
			$advance .= $key . " " . $value ['value'] . " " . $value ['default'] . ", ";
		
		return "
			CREATE TABLE `" . SQL_PREFIX . $this->tableName . "` (			  
			  	objectId varchar(56) NOT NULL,
			  	relId varchar(56) NOT NULL" . substr ( $advance, 0, - 2 ) . ") ENGINE=MyISAM ;
		";
	}

	function validateRelation() {
		global $DB;
		$this->result ['status'] = true;
		$this->result ['message'] = "";
		if ($this->tableName == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= "Name table  can't be left blank!<br>";
		}
		if (! intval ( $this->objectId )) {
			$this->result ['status'] = false;
			$this->result ['message'] .= "Name objectId  can't be left blank or type is int!<br>";
		}
		if ($this->relId == "") {
			$this->result ['status'] = false;
			$this->result ['message'] .= "Name relId  can't be left blank or type is int!<br>";
		}
	}

	function check_exitTable() {
		global $DB, $bw;
		$listTB = $DB->get_table_names ();
		$nameTB = SQL_PREFIX . $this->tableName;
		
		foreach ( $listTB as $val ) {
			if ($val == $nameTB)
				return 1;
		}
		return 0;
	}

	function setArrayField($array) {
		if (is_array ( $array )) {
			$this->arrayField = $array;
			$extendField = array_keys ( $this->arrayField );
			
			$this->obj->extendObjectFields ( $extendField );
		}
	}

	function setPrimaryField($array) {
		if (is_array ( $array ))
			$this->primaryField = $array;
	}

	function setRelId($relId) {
		$this->relId = $relId;
	}

	function setObjectId($objectId) {
		$this->objectId = $objectId;
	}

	function getArrObj() {
		return $this->arrObj;
	}
	/**
	 * @return the $relId
	 */
	public function getRelId() {
		return $this->relId;
	}

}