<?php
/*
 +-----------------------------------------------------------------------------
 |   VIET SOLUTION SJC  base on IPB Code version 3.3.4.1
 |	Author: tongnguyen
 |	Start Date: 19/05/2009
 |	Finish Date: 20/05/2009
 |	moduleName Description: This module is for management all component in system.
 +-----------------------------------------------------------------------------
 */

class supports_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";
	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."support`;";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."support` (
			  `supportId` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `supportCatId` smallint(4) NOT NULL DEFAULT '0',
			  `supportType` smallint(4) NOT NULL DEFAULT '0',
			  `supportIndex` smallint(4) NOT NULL DEFAULT '0',
			  `supportNick` varchar(564) NOT NULL DEFAULT '',
			  `supportImageOffline` varchar(100) NOT NULL DEFAULT '',
			  `supportImageOnline` varchar(100) NOT NULL DEFAULT '',
			  `supportStatus` tinyint(1) NOT NULL DEFAULT '0',
			  `supportProfile` varchar(700) NOT NULL DEFAULT '',
			  `supportAvatar` varchar(50) NOT NULL DEFAULT '',
			  PRIMARY KEY (`supportId`)
			) ENGINE=MyISAM  AUTO_INCREMENT=1 ;
		";
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			('support Module','".$this->version."',1,1,'This is a system module for management all simple support for VS Framework.','supports');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
		$this->query[] = "DROP TABLE `".SQL_PREFIX."support`";
	}
}

?>