<?php
class menus_install {
	public $query = "";
	public $version = "3.4.1";
	public $build = "634";

	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."menu`";
		$this->query[] = "
			CREATE TABLE ".SQL_PREFIX."menu (
			  `menuId` int(10) NOT NULL auto_increment,
			  `langId` smallint(4) unsigned NOT NULL,
			  `menuTitle` varchar(64) NOT NULL,
			  `menuUrl` varchar(255) NOT NULL,
			  `menuIndex` smallint(4) NOT NULL default '0',
			  `menuIsVisible` tinyint(1) NOT NULL default '0',
			  `menuAlt` varchar(255) NOT NULL,
			  `parentId` int(10) NOT NULL default '0',
			  `menuIsLink` tinyint(1) NOT NULL default '0',
			  `menuIsDropDown` tinyint(1) NOT NULL default '0',
			  `menuType` tinyint(1) NOT NULL default '0',
			  `menuLevel` tinyint(1) NOT NULL default '0',
			  `menuPosition` varchar(5) NOT NULL,
			  `menuFileId` varchar(50) NOT NULL,
			  `menuIsAdmin` tinyint(1) default '1',
			  PRIMARY KEY  (`menuId`)
			) ENGINE=MyISAM AUTO_INCREMENT=19
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."menu`(`menuId`,`langId`,`menuTitle`,`menuUrl`,`menuIndex`,`menuIsVisible`,`menuAlt`,`parentId`,`menuIsLink`,`menuIsDropDown`,`menuType`,`menuLevel`,`menuPosition`,`menuIsAdmin`) VALUES 
			(1,2,'Home','home',1,1,'Admin homepage',0,1,0,0,0,'@1000',1),
			(2,2,'Site','',5,1,'',0,0,1,0,0,'@1000',1),
			(3,2,'Content','',10,1,'',0,0,1,0,0,'@1000',1),
			(4,2,'Component','',15,1,'',0,0,0,0,0,'@1000',1),
			(5,2,'Modules','Modules',20,1,'',0,1,1,0,0,'@1000',1),
			(6,2,'System','',25,1,'',0,0,1,0,0,'@1000',1),
			(7,2,'Help','',30,1,'',0,0,0,0,0,'@1000',1),
			(8,2,'Logout','users/logout/',35,1,'',0,1,0,0,0,'@1000',1),
			(9,2,'Menus','menus',5,1,'Manage menu of system',2,1,0,0,2,'@1000',1),
			(10,2,'Users manager','users',10,1,'',2,1,0,0,1,'@0000',1),
			(11,2,'Cron tasks','crontasks',15,1,'',2,1,0,0,2,'@0000',1),
			(12,2,'Simple pages','pages',1,1,'',3,1,0,0,2,'@1000',1),
			(13,2,'Url alias manager','urlalias',5,1,'',3,1,0,0,2,'@1000',1),
			(14,2,'Backup database','backup',1,1,'',5,1,1,0,2,'@1000',1),
			(15,2,'System Configuration','SystemSettings',1,1,'System vars configuration',6,1,1,0,2,'@1000',1),
			(16,2,'Language Manager','Languages',5,1,'Manage languagues',6,1,1,0,2,'@1000',1),
			(17,2,'Skin manager','wrapper',10,1,'Manage skin system',6,1,1,0,2,'@1000',1),
			(18,0,'Categories','0',0,0,'System categories',0,0,0,0,0,'@0000',-1);
		";
		$this->query[] = "
			INSERT INTO ".SQL_PREFIX."module(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			('Menus Manager','".$this->version."',1,1,'This is a system module for management all menu links in VS Framework.','menus')
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."menu`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}