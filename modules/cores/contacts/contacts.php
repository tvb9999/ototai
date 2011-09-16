<?php
require_once(CORE_PATH."contacts/Contact.class.php");
class contacts extends VSFObject{

	public $obj;


	function __construct(){
		parent::__construct();

		$this->primaryField 	= 'contactId';
		$this->basicClassName 	= 'Contact';
		$this->tableName 		= 'contact';
		$this->obj = $this->createBasicObject();
		$this->obj	=&$this->basicObject;
		$this->fields = $this->obj->convertToDB();
	}

	function __destruct(){
		unset($this);
	}

//	function validator(){
//		global $vsLang;
//		$this->result ['status'] = true;
//		$this->result ['message'] = "";
//		if($this->contact->getContactName() == ""){
//			$this->result['status'] = false;
//			$this->result['message'] .= $vsLang("contacts_ErrorNameEmpty","* Name cannot be blank!");
//		}
//		if($this->contact->getContactTitle() == ""){
//			$this->result['status'] = false;
//			$this->result['message'] .= $vsLang("contacts_ErrorTitleEmpty","* Title cannot be blank!");
//		}
//		if($this->contact->getContactMesseage() == ""){
//			$this->result['status'] = false;
//			$this->result['message'] .= $vsLang("contacts_ErrorMessageEmpty","* Message cannot be blank!");
//		}
//		if($this->contact->getContactEmail() == ""){
//			$this->result['status'] = false;
//			$this->result['message'] .= $vsLang("contacts_ErrorEmailEmpty","* Email cannot be blank!");
//		}
//		
//	}



}
?>