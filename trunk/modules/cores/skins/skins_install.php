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

class skins_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";
	public $tableName = "skin";
	public $moduleTitle = "skins";
	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS`".SQL_PREFIX."{$this->tableName}`";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."{$this->tableName}` (
			  `{$this->tableName}Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `{$this->tableName}Title` varchar(255) NOT NULL,
			  `{$this->tableName}IsAdmin` smallint(1) NOT NULL,
			  `{$this->tableName}Status` smallint(1) NOT NULL,
			  `{$this->tableName}AuthorName` varchar(255) NOT NULL,
			  `{$this->tableName}Default` varchar(255) NOT NULL,
			  `{$this->tableName}Folder` varchar(155) NOT NULL,
 			  `{$this->tableName}AuthorEmail` varchar(255) NOT NULL,
 			  `{$this->tableName}AuthorWebsite` varchar(255) NOT NULL,
			  PRIMARY KEY (`{$this->tableName}Id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) VALUES 
			('{$this->moduleTitle} manager','".$this->version."',1,1,'This is a system module for management all {$this->moduleTitle}  for VS Framework.','{$this->moduleTitle}');
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."skin` (`skinId`, `skinTitle`, `skinFolder`, `skinIsAdmin`, `skinStatus`, `skinDefault`, `skinAuthorEmail`, `skinAuthorName`, `skinAuthorWebsite`) VALUES
			(1, 'VS Default', 'finance', 0, 1, 1, 'info@vietsol.net', 'designer', 'http://www.vietsol.net'),
			(2, 'VS lightgray', 'blue', 1, 0, 1, 'info@vietsol.net', 'designer', 'http://www.vietsol.net');";

	}

	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleClass`='{$this->moduleTitle}'";
		$this->query[] = "DROP TABLE `".SQL_PREFIX."{$this->tableName}`";
	}
}
?>