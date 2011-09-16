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

class products_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";
	public $tableName = "product";
	public $moduleTitle = "products";
	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."{$this->tableName}`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."{$this->tableName}` (
			  `{$this->tableName}Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `{$this->tableName}CatId` int(10) NOT NULL,
			  `{$this->tableName}Title` varchar(256) NOT NULL,
			  `{$this->tableName}Intro` text,
			  `{$this->tableName}Content` text,
			  `{$this->tableName}Code` varchar(255),
			  `{$this->tableName}Price` double,
			  `{$this->tableName}Image` varchar(32) NOT NULL,			  			  
			  `{$this->tableName}PostDate` int(10) NOT NULL,
			  `{$this->tableName}Hits` int(11),
			  `{$this->tableName}Index` int(4) NOT NULL,
			  `{$this->tableName}Status` tinyint(4) NOT NULL,
			  `{$this->tableName}CleanTitle` varchar(256) NOT NULL,
  			  `{$this->tableName}CleanContent` text NOT NULL,
			  
			  PRIMARY KEY (`{$this->tableName}Id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";
		
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) VALUES 
			('{$this->moduleTitle} manager','".$this->version."',1,1,'This is a system module for management all {$this->moduleTitle}  for VS Framework.','{$this->moduleTitle}');
		";
	}
	
	
	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."{$this->tableName}`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleClass`='{$this->moduleTitle}'";
	}
}
?>