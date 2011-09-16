<?php

class VSFSession {

	function __construct() {
		global $DB;
		if (APPLICATION_TYPE == 'admin')
			$this->createAdminSession ();
		else
			$this->createUserSession ();
	}

	function __destruct() {
	}

	function createAdminSession() {
		global $vsUser;
		$thisTime = time ();
		if (! isset ( $_SESSION [APPLICATION_TYPE] ['session'] )) {
			$vsUser->sessions->deleteSession ();
			$vsUser->sessions->obj->setTime ( $thisTime );
			$vsUser->sessions->obj->setCode ( $thisTime );
			$vsUser->sessions->obj->setAdminId ( 0 );
			$vsUser->sessions->insertObject ();
			$_SESSION [APPLICATION_TYPE] ['session'] = $vsUser->sessions->obj->convertToDB ();
		} else {
			
			$vsUser->sessions->setCondition ( 'sessionId=' . $_SESSION [APPLICATION_TYPE] ['session'] ['sessionId'] );
			$vsUser->sessions->getOneObjectsByCondition ();
			$vsUser->sessions->obj->setTime ( $thisTime );
			$vsUser->sessions->obj->setCode ( $thisTime );
			$vsUser->sessions->obj->setAdminId ( $_SESSION [APPLICATION_TYPE] ['session'] ['userId'] );
			$vsUser->sessions->setCondition ( "sessionId={$vsUser->sessions->obj->getId()}" );
			if ($vsUser->sessions->result ['status'])
				$vsUser->sessions->updateObjectById ( $vsUser->sessions->obj );
			else {
				$vsUser->sessions->insertObject ();
				$_SESSION [APPLICATION_TYPE] ['session'] = $vsUser->sessions->obj->convertToDB ();
			}
			$vsUser->obj->convertToObject ( $_SESSION [APPLICATION_TYPE] ['obj'] );
			if (count ( $_SESSION [APPLICATION_TYPE] ['groups'] ) > 0)
				foreach ( $_SESSION [APPLICATION_TYPE] ['groups'] as $groupId ) {
					$vsUser->obj->addGroup ( $vsUser->groupadmins->getGroupById ( $groupId ) );
				}
		}
	}

	function createUserSession() {
		global $vsUser, $vsCounter;
		$thisTime = time ();
		
		if (! isset ( $_SESSION [APPLICATION_TYPE] ['session'] )) {
			$vsUser->sessions->deleteSession ();
			$vsUser->sessions->obj->setTime ( $thisTime );
			$vsUser->sessions->obj->setCode ( $thisTime );
			$vsUser->sessions->obj->setUserId ( 0 );
			$vsUser->sessions->insertObject ();
			$vsCounter->insertCounter ( 1, 1, 0 );
			$_SESSION [APPLICATION_TYPE] ['session'] = $vsUser->sessions->obj->convertToDB ();
		} else {
			$vsUser->sessions->setCondition ( 'sessionId=' . $_SESSION [APPLICATION_TYPE] ['session'] ['sessionId'] );
			$vsUser->sessions->getOneObjectsByCondition ();
			$vsUser->sessions->obj->setTime ( $thisTime );
			$vsUser->sessions->obj->setCode ( $thisTime );
			$vsUser->sessions->obj->setUserId ( $_SESSION [APPLICATION_TYPE] ['session'] ['userId'] );
			$vsUser->sessions->setCondition ( "sessionId={$vsUser->sessions->obj->getId()}" );
			if ($vsUser->sessions->result ['status'])
				$vsUser->sessions->updateObjectById ( $vsUser->sessions->obj );
			else {
				$vsUser->sessions->insertObject ();
				$_SESSION [APPLICATION_TYPE] ['session'] = $vsUser->sessions->obj->convertToDB ();
				$vsCounter->insertCounter ( 1, 0, 1 );
			}
			$vsUser->obj->convertToObject ( $_SESSION [APPLICATION_TYPE] ['obj'] );
			if (count ( $_SESSION [APPLICATION_TYPE] ['groups'] ) > 0)
				foreach ( $_SESSION [APPLICATION_TYPE] ['groups'] as $groupId ) {
					$vsUser->obj->addGroup ( $vsUser->groupusers->getGroupById ( $groupId ) );
				}
		}
	}

}
?>