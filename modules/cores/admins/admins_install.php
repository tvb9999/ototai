<?php
class admins_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";

	function Install(){
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."admin`;";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."admin` (
			  `adminId` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
			  `adminName` varchar(32) NOT NULL,
			  `adminPassword` varchar(32) NOT NULL,
			  `adminLastLogin` int(10) DEFAULT '0' COMMENT 'Last login time',
			  `adminJoinDate` int(10) DEFAULT '0' COMMENT 'Created time',
			  `adminStatus` tinyint(1) DEFAULT '1' COMMENT 'Lock or not',
			  PRIMARY KEY (`adminId`),
			  KEY `Name` (`adminName`)
			) ENGINE=MyISAM  AUTO_INCREMENT=1 ;
		";
		$this->query[] ="INSERT INTO `".SQL_PREFIX."admin` (`adminId`, `adminName`, `adminPassword`, `adminLastLogin`, `adminJoinDate`, `adminStatus`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1273028229, 1241184503, 0)";

		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."admin_sessions`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."admin_session` (
			  `sessionId`  int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `adminId` smallint(4),
			  `sessionTime` int(10) NOT NULL,
			  `sessionCode` varchar(32) NOT NULL DEFAULT '',
			  PRIMARY KEY (`sessionId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."admingroup`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."admingroup` (
			  `groupId` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
			  `groupParentId` smallint(4),
			  `groupName` varchar(64) NOT NULL DEFAULT '',
			  `groupIntro` text NOT NULL,
			  `groupPermission` text NOT NULL,
			  PRIMARY KEY (`groupId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$this->query[] = "INSERT INTO `".SQL_PREFIX."admingroup` (`groupId`, `groupParentId`, `groupName`, `groupIntro`, `groupPermission`) VALUES
			(1, 0, 'Quản trị', 'root', 'b:0;'),
			(2, 0, 'Nhân viên', 'normal', 'b:0;');";

		$this->query[] = "INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values
			('Admin','".$this->version."',1,1,'This is a system module for management all simple page for VS Framework.','admins');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."admin`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
		$this->query[] = "DELETE FROM `".SQL_PREFIX."menus` WHERE `menuTitle`='admins'";
	}
}
?>