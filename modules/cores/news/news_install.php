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

class news_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";
	public $tableName = "news";
	public $moduleTitle = "news";
	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."{$this->tableName}`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."{$this->tableName}` (
			  `{$this->tableName}Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `{$this->tableName}CatId` int(11) NOT NULL,
			  `{$this->tableName}Title` varchar(255) NOT NULL,
			  `{$this->tableName}Intro` text NOT NULL,
			  `{$this->tableName}Content` text NOT NULL,
			  `{$this->tableName}Image` varchar(50) NOT NULL,
			  `{$this->tableName}Author` varchar(150) NOT NULL,
			  `{$this->tableName}Index` int(11) NOT NULL,
			  `{$this->tableName}PostDate` int(11) NOT NULL,
			  `{$this->tableName}Hits` int(11) NOT NULL,
			  `{$this->tableName}Status` tinyint(1) NOT NULL,
			  PRIMARY KEY (`{$this->tableName}Id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."{$this->tableName}_relation` (
			  `{$this->tableName}Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `menuId` int(10) NOT NULL,
			  `relType` tinyint(1) NOT NULL,
			  PRIMARY KEY (`{$this->tableName}Id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) VALUES 
			('{$this->moduleTitle} manager','".$this->version."',1,1,'This is a system module for management all {$this->moduleTitle}  for VS Framework.','{$this->moduleTitle}');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."{$this->tableName}`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleClass`='{$this->moduleTitle}'";
	}
}
?>