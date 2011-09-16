<?php
class languages_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "627";

	function Install() {
		$this->query[] = "
			CREATE TABLE `".SQL_PREFIX."langs` (
			  `langId` smallint(4) NOT NULL auto_increment,
			  `langName` varchar(32) NOT NULL,
			  `userDefault` tinyint(1) NOT NULL default '0',
			  `adminDefault` tinyint(1) NOT NULL default '0',
			  `langFolder` varchar(32) NOT NULL,
			  `langStatus` tinyint(1) NOT NULL default '1',
			  `langSymbol` varchar(32) NOT NULL,
			  PRIMARY KEY  (`langId`),
			  KEY `langDefault` (`langDefault`,`langType`)
			) ENGINE=MyISAM AUTO_INCREMENT=1;
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."langs`(`langName`,`userDefault`,`adminDefault`,`langFolder`,`langStatus`,`langSymbol`) VALUES 
			('Vietnamese',1,1,'vi',1,'vietnam.png'),
			('English',0,0,'en',1,'england.png'),
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleId`,`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			(4,'Languages','".$this->version."',1,1,'This is a system module for management all languages for VS Framework.','languages');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."langs`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}