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

class contacts_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";

	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."contacts`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."contacts` (
			  	`contactId` smallint(4) NOT NULL AUTO_INCREMENT,
			  	`contactTitle` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			  	`contactProfile` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			  	`contactEmail` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  	`contactName` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  	`contactContent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  	`contactPostDate` int(10) NOT NULL,
			   	`contactStatus` tinyint(1) NOT NULL default '0',
  				`contactIsReply` smallint(1) NOT NULL default '0',
			  	`contactType` smallint(1) default '0',
			  PRIMARY KEY (`contactId`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			('Contact','".$this->version."',1,1,'This is a system module for management all Contact for VS Framework.','contacts');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."contacts`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}
?>