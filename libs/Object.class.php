<?php
require_once LIBS_PATH . "boards/Object.board.php";
class VSFObject extends Object {

	function __construct() {
		parent::__construct ();
	}

	function reportError() {
		print '<script type="text/javascript">window.parent.alertError("' . $this->result ['message'] . '");</script>';
		return;
	}

	function getBasicObject() {
		return $this->basicObject;
	}

	function getBasicClassName() {
		return $this->basicClassName;
	}

	function setBasicObject($basicObject) {
		$this->basicObject = $basicObject;
	}

	function setBasicClassName($basicClassName) {
		$this->basicClassName = $basicClassName;
	}

	function setPrimaryField($primaryField) {
		$this->primaryField = $primaryField;
	}

	function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	function getPrimaryField() {
		return $this->primaryField;
	}

	function getTableName() {
		return $this->tableName;
	}

	function setFieldsString($fieldsString) {
		$this->fieldsString = $fieldsString ? $fieldsString : "*";
	}

	function getFieldsString() {
		return $this->fieldsString;
	}

	function setHaving($value) {
		$this->having = $value;
	}

	function getHaving() {
		return $this->having;
	}

	function setResult($result) {
		$this->result = $result;
	}

	function setFields($fields) {
		$this->fields = $fields;
	}

	function getResult() {
		return $this->result;
	}

	function getFields() {
		return $this->fields;
	}

	function setLimit($limit) {
		$this->limit = $limit;
	}

	function setOrder($order) {
		$this->order = $order;
	}

	function setGroupby($groupby) {
		$this->groupby = $groupby;
	}

	function setCondition($condition) {
		$this->condition = $condition;
	}

	function getLimit() {
		return $this->limit;
	}

	function getGroupby() {
		return $this->groupby;
	}

	function getOrder() {
		return $this->order;
	}

	function getCondition() {
		return $this->condition;
	}

	function setPrefixField($prefixField) {
		$this->prefixField = $prefixField;
	}

	function getPrefixField() {
		return $this->prefixField;
	}

	function setArrayObj($arrayObj) {
		$this->arrayObj = $arrayObj;
	}

	function getArrayObj() {
		return $this->arrayObj;
	}

	function getListIds() {
		return implode ( ',', array_keys ( $this->arrayObj ) );
	}

	function getSearchStrings() {
		global $DB, $vsLang;
		
		$this->resetResult ();
		$this->createMessageSuccess ( $this->vsLang->getWords ( 'count_table_success', "count table successfully!" ) );
		$query = array ('select' => 'DISTINCT ' . $this->tableName . 'Title,' . $this->tableName . 'Id', 'from' => $this->tableName, 'where' => $this->condition, 'groupby' => $this->tableName . 'Title' );
		
		$DB->simple_construct ( $query );
		$array = array ();
		if (! $DB->simple_exec ()) {
			$this->createMessageError ( $this->vsLang->getWords ( 'count_table_condition_fail', "There is no item in table!" ) );
			return $array;
		}
		
		while ( $row = mysql_fetch_array ( $DB->query_id ) )
			$result [] = $row;
		
		$arrayStrings = null;
		if (count ( $result ))
			for($i = 0; $i < count ( $result ); $i ++) {
				$arrayStrings ['id'] [$i] = $result [$i] [$this->tableName . 'Id'];
				$arrayStrings ['title'] [$i] = $result [$i] [$this->tableName . 'Title'];
			}
		
		if ($arrayStrings) {
			$arrayStrings ['id'] = '"' . implode ( '","', $arrayStrings ['id'] ) . '"';
			$arrayStrings ['title'] = '"' . implode ( '","', $arrayStrings ['title'] ) . '"';
		}
		
		return $arrayStrings;
	}

	function countTable() {
		global $DB, $vsLang;
		$this->resetResult ();
		$this->fieldsString = "count({$this->primaryField}) as total";
		$this->createMessageSuccess ( $this->vsLang->getWords ( 'count_table_success', "count table successfully!" ) );
		$query = array ('select' => $this->fieldsString, 'from' => $this->tableName, 'where' => $this->condition );
		$totalfeild = 1;
		if ($this->groupby) {
			$query ['select'] = "{$this->groupby},$this->fieldsString";
			$query ['groupby'] = $this->groupby;
			$totalfeild = count ( explode ( ',', $this->groupby ) );
		}
		$DB->simple_construct ( $query );
		$this->resetQuery ();
		$array = array ();
		if (! $DB->simple_exec ()) {
			$this->createMessageError ( $this->vsLang->getWords ( 'count_table_condition_fail', "There is no item in table!" ) );
			return $array;
		}
		$result = mysql_fetch_row ( $DB->query_id );
		if (! is_array ( $result )) {
			$this->createMessageError ( $this->vsLang->getWords ( 'count_table_condition_fail', "There is no item in table!" ) );
			return array ();
		}
		try {
			while ( $result ) {
				$eval = "\$array";
				
				for($i = 0; $i < $totalfeild; $i ++)
					$eval = $eval . "[$result[$i]]";
				
				eval ( $eval . "=$result[$totalfeild] ;" );
				$result = mysql_fetch_row ( $DB->query_id );
			}
		} catch ( Exception $e ) {
			$this->createMessageError ( $this->vsLang->getWords ( 'count_table_condition_fail', "There is no item in table!" ) );
			Throw new Exception ( $e );
		}
		return $array;
	}

	function getPageList($url = "", $objIndex = 3, $size = 10, $ajax = 0, $callack = "",$releaseUrl='',$style="Number") {
		global $vsStd, $bw;
		$vsStd->requireFile ( LIBS_PATH . "/Pagination.class.php" );
		$total = $this->getNumberOfObject ();
		if($releaseUrl==false) $url = ltrim($url,'/')."/";
		if ($size < $total) {
			$pagination = new VSFPagination ();
			$pagination->ajax = $ajax;
			$pagination->callbackobjectId = $callack;
			$pagination->p_Style = $style;
			$pagination->url 				= $ajax?$url:$bw->base_url.$url;
			
			$pagination->p_Size = $size;
			$pagination->p_TotalRow = $total;
			$pagination->SetCurrentPage ( $objIndex );
			$pagination->BuildPageLinks ();
			$this->setLimit ( array ($pagination->p_StartRow, $pagination->p_Size ) );
		}
		$option ['paging'] = $pagination->p_Links;
		
		$option ['pageList'] = $this->getObjectsByCondition ();
		$option ['total'] = $total;
		return $option;
	}

	function showStatusInfo($home = 0) {
		global $bw, $vsLang;
		if ($home)
			return '
                <table cellspacing="1" cellpadding="1" id="objListInfo" width="100%">
                                             <tbody>
                                                    <tr align="left">
                <span style="padding-left: 10px;line-height:16px;"><img src="' . $bw->vars ['img_url'] . '/enable.png" /> ' . $vsLang->getWords ( 'global_status_enable', 'Enable' ) . '</span>
                <span style="padding-left: 10px;line-height:16px;"><img src="' . $bw->vars ['img_url'] . '/disabled.png" /> ' . $vsLang->getWords ( 'global_status_disabled', 'Disable' ) . '</span>
                <span style="padding-left: 10px;line-height:16px;"><img src="' . $bw->vars ['img_url'] . '/home.png" /> ' . $vsLang->getWords ( 'global_status_ishome', 'Show on home page' ) . '</span>
                                                    </tr>
                                             </tbody>
                                        </table>
                ';
		else
			return '
                <table cellspacing="1" cellpadding="1" id="objListInfo" width="100%">
                                             <tbody>
                                                    <tr align="left">
                <span style="padding-left: 10px;line-height:16px;"><img src="' . $bw->vars ['img_url'] . '/enable.png" /> ' . $vsLang->getWords ( 'global_status_enable', 'Enable' ) . '</span>
                <span style="padding-left: 10px;line-height:16px;"><img src="' . $bw->vars ['img_url'] . '/disabled.png" /> ' . $vsLang->getWords ( 'global_status_disabled', 'Disable' ) . '</span>

                                                    </tr>
                                             </tbody>
                                        </table>
                ';
	}

	function createSearchCondition($searchContent, $searchType, $moduleName) {
		$result = null;
		$moduleName = current ( str_split ( $moduleName, strlen ( $moduleName ) - 1 ) );
		if ($searchType == 1)
			$result = $moduleName . 'Id=' . $searchContent;
		else
			$result = $moduleName . 'ClearSearch LIKE ' . '"%' . $searchContent . '%"';
		return $result;
	}

	protected function __reset() {
		$this->tableName = null;
		$this->prefixField = null;
		$this->basicClassName = null;
		$this->basicObject = null;
		
		$this->primaryField = null;
		$this->fieldsString = null;
		$this->fields = array ();
		$this->arrayObj = array ();
		
		$this->resetQuery ();
		$this->resetResult ();
	}

	function createBasicObject() {
		if ($this->basicClassName) {
			$this->basicObject = new $this->basicClassName ();
			return $this->basicObject;
		}
		return false;
	}

	function resetResult() {
		$this->arrayObj = array ();
		$this->result ['status'] = true;
		$this->result ['developer'] = "";
	}

	function resetQuery() {
		$this->fieldsString = "*";
		$this->condition = "";
		$this->order = "";
		$this->groupby = "";
		$this->limit = array ();
	}

	function createMessageError($message = "Error") {
		$this->result ['status'] = false;
		$this->result ['developer'] .= $message;
	}

	function createMessageSuccess($message = "Success") {
		$this->result ['status'] = true;
		$this->result ['developer'] .= $message;
	}

	function validateObject($isUpdate = false) {
		if (! method_exists ( $this->basicObject, 'validate' ))
			return true;
		
		if ($this->basicObject->validate ( $isUpdate )) {
			$this->createMessageSuccess ( $this->basicObject->message );
			return true;
		}
		
		$this->createMessageError ( $this->basicObject->message );
		return false;
	}

	function getNumberOfObject() {
		global $DB;
		$DB->simple_construct ( array ('select' => "COUNT(" . $this->prefixField . $this->primaryField . ") as total", 'from' => $this->tableName, 'where' => $this->condition ) );
		$DB->simple_exec ();
		$result = $DB->fetch_row ();
		return $result ['total'];
	}

	function getObjectById($id) {
		global $DB;
		$this->resetResult ();
		$id = intval ( $id );
		$DB->simple_select ( $this->fieldsString, $this->tableName, $this->prefixField . $this->primaryField . " = " . $id );
		$DB->simple_exec ();
		$objDB = $DB->fetch_row ();
		if (is_array ( $objDB )) {
			$this->basicObject->convertToObject ( $objDB );
			$this->createMessageSuccess ( $this->vsLang->getWords ( 'develop_get_obj_success', "Execute successful" ) );
			return $this->basicObject;
		}
		//if record not exit, create a message error and set status is false
		$this->createMessageError ( $this->vsLang->getWords ( 'get_id_fail', "There is no item with specified ID!" ) );
		unset ( $objDB );
		$this->resetQuery ();
		return false;
	}

	function getOneObjectsByCondition($method = 'getId') {
		global $DB;
		
		$this->limit = array (0, 1 );
		$this->getObjectsByCondition ( $method );
		
		if ($this->arrayObj)
			return $this->obj = $this->basicObject = current ( $this->arrayObj );
		return false;
	}

	function getObjectsByCondition($method = 'getId', $group = 0) {
		global $DB, $vsLang;
		$this->resetResult ();
		$this->createMessageSuccess ( $this->vsLang->getWords ( 'develope_get_obj_success', "Execute successful" ) );
		
		$this->autokill ();
		$query = array ('select' => $this->fieldsString, 'from' => $this->tableName, 'where' => $this->condition );
		
		if (count ( $this->limit ))
			$query ['limit'] = $this->limit;
		
		$query ['order'] = $this->order ? $this->order : $this->getPrimaryField () . " desc";
		
		if ($this->groupby) {
			$query ['groupby'] = $this->groupby;
			$this->having ? $query ['having'] = $this->having : "";
		}
		$DB->simple_construct ( $query );
		$this->resetQuery ();
		
		if (! $DB->simple_exec ()) {
			$this->createMessageError ( $this->vsLang->getWords ( 'develope_connect_db_fail', "Cannot connect to database" ) );
			return array ();
		}
		
		$result = $DB->fetch_row ();
		if (! is_array ( $result )) {
			$this->createMessageError ( $this->vsLang->getWords ( 'develope_get_obj_fail', "No object was found" ) );
			return array ();
		}
		
		$count = 0;
		while ( $result ) {
			$obj = $this->createBasicObject ();
			$obj->convertToObject ( $result );
			$obj->stt = ++ $count;
			if ($group)
				$this->arrayObj [$obj->$method ()] [$obj->getId ()] = $obj;
			else
				$this->arrayObj [$obj->$method ()] = $obj;
			$result = $DB->fetch_row ();
		}
		
		$this->resetQuery ();
		return $this->arrayObj;
	}

	function getArrayByCondition($method = 'Id', $group = 0) {
		global $DB, $vsLang;
		$this->resetResult ();
		$this->createMessageSuccess ( $this->vsLang->getWords ( 'develope_get_obj_success', "Execute successful" ) );
		
		$this->autokill ();
		$query = array ('select' => $this->fieldsString, 'from' => $this->tableName, 'where' => $this->condition );
		
		if (count ( $this->limit ))
			$query ['limit'] = $this->limit;
		
		$query ['order'] = $this->order ? $this->order : $this->getPrimaryField () . " desc";
		
		if ($this->groupby) {
			$query ['groupby'] = $this->groupby;
			$this->having ? $query ['having'] = $this->having : "";
		}
		$DB->simple_construct ( $query );
		$this->resetQuery ();
		
		if (! $DB->simple_exec ()) {
			$this->createMessageError ( $this->vsLang->getWords ( 'develope_connect_db_fail', "Cannot connect to database" ) );
			return array ();
		}
		
		$result = $DB->fetch_row ();
		if (! is_array ( $result )) {
			$this->createMessageError ( $this->vsLang->getWords ( 'develope_get_obj_fail', "No object was found" ) );
			return array ();
		}
		
		$count = 0;
		while ( $result ) {
			if ($group)
				$return [$this->tableName . "s" . $method] = $obj;
			else
				$return [] = $result;
			
			$result = $DB->fetch_row ();
		}
		
		$this->resetQuery ();
		return $return;
	}

	function deleteObjectByCondition() {
		global $DB;
		$this->resetResult ();
		
		$this->createMessageSuccess ( $this->vsLang->getWords ( 'develop_delete_object_success', "Delete object successfully!" ) );
		$DB->simple_delete ( $this->tableName, $this->condition );
		if (! $DB->simple_exec ()) {
			$this->createMessageError ( $this->vsLang->getWords ( 'develope_connect_db_fail', "Cannot connect to database" ) );
		}
		
		$this->resetQuery ();
		return $this->result ['status'];
	}

	function deleteObjectById($id) {
		$this->condition = $this->prefixField . $this->primaryField . "=" . intval ( $id );
		return $this->deleteObjectByCondition ();
	}

	function updateObjectByCondition($updateFields = array()) {
		global $DB;
		$this->resetResult ();
		$this->createMessageSuccess ( $this->vsLang->getWords ( 'develop_update_object_success', "Updated object successfully!" ) );
		
		$updateFields = $updateFields ? $updateFields : $this->fields;
		if (! $DB->do_update ( $this->tableName, $updateFields, $this->condition )) {
			$this->createMessageError ( $this->vsLang->getWords ( 'develope_connect_db_fail', "Cannot connect to database" ) );
		}
		$this->resetQuery ();
		return $this->result ['status'];
	}

	function updateObjectById($obj = null) {
		if ($obj)
			$this->basicObject = $obj;
		if (! $this->validateObject ( true ))
			return false;
		$this->condition = $this->prefixField . $this->primaryField . "=" . intval ( $this->basicObject->getId () );
		
		return $this->updateObjectByCondition ( $this->basicObject->convertToDB () );
	}

	function updateObject($obj = null) {
		if ($obj)
			$this->basicObject = $obj;
		if (! $this->validateObject ( true ))
			return false;
		$this->condition = $this->prefixField . $this->primaryField . "=" . intval ( $this->basicObject->getId () );
		return $this->updateObjectByCondition ( $this->basicObject->convertToDB () );
	}

	function insertObject($object = null) {
		global $DB;
		$this->resetResult ();
		
		if ($object instanceof $this->basicClassName && is_object ( $object ) && $object)
			$this->basicObject = $object;
		
		if (! $this->validateObject ())
			return false;
		
		$dbObj = $this->basicObject->convertToDB ();
		
		if ($DB->do_insert ( $this->tableName, $dbObj )) {
			$this->createMessageSuccess ( $this->vsLang->getWords ( 'insert_success', 'Insert Object success' ) );
			$this->basicObject->setId ( $DB->get_insert_id () );
			return $this->result ['status'];
		}
		
		$this->createMessageError ( $this->vsLang->getWords ( 'develope_connect_db_fail', "Cannot connect to database" ) );
		unset ( $dbObj );
		return $this->result ['status'];
	}

	function executeQuery($query = "", $obj = 1, $method = "Id") {
		if (! $query)
			return false;
		global $DB;
		$DB->cur_query = $query;
		$DB->simple_exec ();
		
		$count = 0;
		$record = $DB->fetch_row ();
		$this->resetQuery ();
		while ( $record ) {
			if ($obj) {
				$obj = $this->createBasicObject ();
				$obj->convertToObject ( $record );
				$obj->stt = ++ $count;
				$func = "get" . $method;
				$result [$obj->$func ()] = $obj;
			} else {
				$result [] = $record;
			}
			$record = $DB->fetch_row ();
		}
		
		$this->resetQuery ();
		return $result;
	}

	function executeNoneQuery($query = "") {
		if (! $query)
			return false;
		global $DB;
		$DB->cur_query = $query;
		$DB->simple_exec ();
		return true;
	}

	function deleteDirectory($dir) {
		if (! file_exists ( $dir ))
			return true;
		if (! is_dir ( $dir ) || is_link ( $dir ))
			return unlink ( $dir );
		foreach ( scandir ( $dir ) as $item ) {
			if ($item == '.' || $item == '..')
				continue;
			if (! $this->deleteDirectory ( $dir . "/" . $item )) {
				chmod ( $dir . "/" . $item, 0775 );
				if (! $this->deleteDirectory ( $dir . "/" . $item ))
					return false;
			}
			;
		}
		return rmdir ( $dir );
	}

}