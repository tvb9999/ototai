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

class advisorys_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";

	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."advisorys`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."advisorys` (
			  	`advisoryId` smallint(4) NOT NULL AUTO_INCREMENT,
			  	`advisoryTitle` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			  	`advisoryAddress` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			  	`advisoryEmail` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			  	`advisoryName` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			  	`advisoryIntro` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			  	`advisoryContent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			  	`advisoryPostDate` int(10) DEFAULT NULL,
			  	`advisoryPhone` int(10) NOT NULL,
				`advisoryCatId` int(10) NOT NULL,
			  	`advisoryStatus` tinyint(1) NOT NULL default '0',
			  	
			  PRIMARY KEY (`advisoryId`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			('advisory','".$this->version."',1,1,'This is a system module for management all advisory for VS Framework.','advisorys');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."advisorys`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}
?>