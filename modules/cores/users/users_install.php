<?php
class users_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";

	function Install(){
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."user`;";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."user` (
			  `userId` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `userName` varchar(64) NOT NULL DEFAULT '',
			  `userAddress` varchar(255) NOT NULL DEFAULT '',
			  `userPassword` varchar(64) NOT NULL DEFAULT '',
			  `userEmail` varchar(255) NOT NULL DEFAULT '',
			  `userInfo` text NOT NULL,
			  `userLastLogin` int(10) NOT NULL DEFAULT '0',
			  `userJoinDate` int(10) NOT NULL DEFAULT '0',
			  `userStatus` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`userId`)
			) ENGINE=MyISAM  AUTO_INCREMENT=1 ;
		";

		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."user_session`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."user_session` (
			  `sessionCode` varchar(64) NOT NULL DEFAULT '',
			  `sessionTime` int(10) NOT NULL,
			  `sessionId` int(10) NOT NULL,
			  `userId` int(10) NOT NULL,
			  PRIMARY KEY (`sessionId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."usergroup`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."usergroup` (
			  `groupId` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
			  `groupTitle` varchar(64) NOT NULL DEFAULT '',
			  `groupIntro` text NOT NULL,
			  `groupPermission` text NOT NULL,
			  PRIMARY KEY (`groupId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleId`,`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			(13,'User Manager','".$this->version."',1,1,'This is a system module for management all simple page for VS Framework.','users');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
		$this->query[] = "DELETE FROM `".SQL_PREFIX."menu` WHERE `menuTitle`='users'";
		$this->query[] = "DROP TABLE `".SQL_PREFIX."user`";
		$this->query[] = "DROP TABLE `".SQL_PREFIX."usergroup`";
		$this->query[] = "DROP TABLE `".SQL_PREFIX."user_session`";
	}
}
?>