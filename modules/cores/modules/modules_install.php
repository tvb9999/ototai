<?php
class modules_install {
	public $query = "";
	public $version = "3.3.0";
	public $build = "533";
	public $tableName = "module";
	public $moduleTitle = "modules";
	function Install() {

		$this->query[] = "
		CREATE TABLE `".SQL_PREFIX."{$this->moduleTitle}` (
		  `{$this->tableName}Id` smallint(4) unsigned NOT NULL auto_increment,
		  `{$this->tableName}Title` varchar(32) NOT NULL,
		  `{$this->tableName}Version` varchar(8) NOT NULL,
		  `{$this->tableName}IsAdmin` tinyint(1) NOT NULL default '0',
		  `{$this->tableName}IsUser` tinyint(1) NOT NULL default '0',
		  `{$this->tableName}Virtual` tinyint(1) NOT NULL default '0',
		  `{$this->tableName}Intro` varchar(255) NOT NULL,
		  `{$this->tableName}Class` varchar(32) NOT NULL,
		  PRIMARY KEY  (`{$this->tableName}Id`),
		  KEY `{$this->tableName}Class` (`{$this->tableName}Class`)
		) ENGINE=MyISAM AUTO_INCREMENT=1;
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."{$this->moduleTitle}`(`{$this->tableName}Name`,`{$this->tableName}Version`,`{$this->tableName}IsAdmin`,`{$this->tableName}IsUser`,`{$this->tableName}Intro`,`{$this->tableName}Class`) values 
			('{$this->moduleTitle} Management','".$this->version."',1,0,'This is a system module for management all {$this->moduleTitle} in VS Framework.','{$this->moduleTitle}');
		";
	}

	function Uninstall() {
		$this->query[] = "";
	}
}