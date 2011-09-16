<?php
global $vsStd;
$vsStd->requireFile(CORE_PATH."files/FileType.class.php");

class filetypes extends VSFObject{
	public $arrayType = array();
	public $type;

	private $objsource 	= "";
	public $result 	= array();

	public $condition = "";

	function __construct() {
		parent::__construct();
		$this->primaryField = 'fileTypeId';
		$this->basicClassName = 'FileType';
		$this->tableName = 'file_type';
		$this->type = $this->createBasicObject ();

		$this->fields = $this->type->convertToDB ();
		$this->getAllType();
	}

	function __destruct() {
		unset($this);
	}

	function validateType($checkExisted = false) {
		global $vsLang, $DB;

		$this->result['status'] = true;
		$this->result['message'] = $vsLang->getWords('filetype_validate_success',"Validate file type successfully!");

		// Check if the mime is blank
		if($this->type->getMime()=="") {
			$this->result['status'] = false;
			$this->result['message'] = $vsLang->getWords('filetype_err_mime_blank',"File mime cannot be blank!");
		}

		if($checkExisted) {
			$mime = $DB->simple_exec_query(array('select'=>'*','from'=>$this->objsource,'where'=>"fileTypeId='".$this->type->getMime()."'"));

			if($mime) {
				$this->result['status'] = false;
				$this->result['message'] = $vsLang->getWords('filetype_err_duplicated','This mime is already existed!');
			}
		}
	}


	function getAllType() {
		global $DB;
		$this->getObjectsByCondition();
		$this->arrayType = $this->getArrayObj();

	}
}
?>