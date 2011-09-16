<?php
require_once(CORE_PATH."/users/Session.class.php");

class usersessions extends VSFObject {
	public $obj;
	/**
	 * @return the $relTableName
	 */
	function __construct(){
		parent::__construct();
		$this->primaryField 	= 'sessionId';
		$this->basicClassName 	= 'UserSession';
		$this->tableName 		= 'user_session';
		$this->obj = $this->createBasicObject();
	}
	function deleteSession(){
		global $bw;
		$this->setCondition('sessionTime < ' . (time() -(60 * 60)));
		$this->deleteObjectByCondition();
	}
	function updateLoginSession($obj=null){
		global $vsUser;
		if(!$obj)
		$obj=$vsUser->obj;
		$thisTime= time();
		$vsUser->sessions->obj->setTime($thisTime);
		$vsUser->sessions->obj->setCode($thisTime);
		$vsUser->sessions->obj->setUserId($obj->getId());
		$vsUser->sessions->obj->setId($_SESSION[APPLICATION_TYPE]['session']['sessionId']);
		$vsUser->sessions->updateObjectById($vsUser->sessions->obj);
		$_SESSION[APPLICATION_TYPE]['session'] = $vsUser->sessions->obj->convertToDB();

		if(count($obj->getGroups())>0)
		foreach ($obj->getGroups() as $group)
		{
			$_SESSION[APPLICATION_TYPE]['groups'][$group->getId()] = $group->getId();
		}
	}
}
?>
