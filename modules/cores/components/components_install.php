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

class components_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";
	
	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."components`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."components` (
			  `comId` smallint(4) NOT NULL AUTO_INCREMENT,
			  `comName` varchar(64) NOT NULL DEFAULT '',
			  `comPackage` varchar(32) NOT NULL DEFAULT '',
			  `comInstalled` tinyint(1) NOT NULL,
			  `comDescription` varchar(255) NOT NULL DEFAULT '',
			  PRIMARY KEY (`comId`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";
		
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleId`,`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			(17,'Component','".$this->version."',1,0,'This is a system module for management all Component for VS Framework.','components');
		";
	}
	
	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."components`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}
?>