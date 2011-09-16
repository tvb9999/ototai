<?php
require_once(CORE_PATH."admins/Session.class.php");
class sessions extends VSFObject {
	public $obj;
	/**
	 * @return the $relTableName
	 */
	function __construct(){
		parent::__construct();
		$this->primaryField 	= 'sessionId';
		$this->basicClassName 	= 'Session';
		$this->tableName 		= 'admin_session';
		$this->obj = $this->createBasicObject();
	}

	function deleteSession(){
		global $bw;
		$this->setCondition('sessionTime < ' . (time()-$bw->vars['admin_timeout']*4*60));
		$this->deleteObjectByCondition();
	}
	function updateLoginSession(){
		global $vsUser;
		$thisTime= time();
		$vsUser->sessions->obj->setTime($thisTime);
		$vsUser->sessions->obj->setCode($thisTime);
		$vsUser->sessions->obj->setAdminId($vsUser->obj->getId());
		$vsUser->sessions->obj->setId($_SESSION[APPLICATION_TYPE]['session']['sessionId']);
		$vsUser->sessions->updateObjectById($vsUser->sessions->obj);
		$_SESSION[APPLICATION_TYPE]['session'] = $vsUser->sessions->obj->convertToDB();
		$_SESSION[APPLICATION_TYPE]['obj'] = $vsUser->obj->convertToDB();
		if(count($vsUser->obj->getGroups())>0)
		foreach ($vsUser->obj->getGroups() as $group)
		{
			$_SESSION[APPLICATION_TYPE]['groups'][$group->getId()] = $group->getId();
		}

	}
}