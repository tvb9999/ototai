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

class files_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";

	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS ".SQL_PREFIX."file";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS ".SQL_PREFIX."file (
			  `fileId` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `fileModule` varchar(100) DEFAULT NULL,
			  `fileTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  `fileIntro` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `fileType` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  `fileSize` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `fileUploadTime` int(10) unsigned NOT NULL DEFAULT '0',
			  `filePath` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			  `fileStatus` tinyint(1) DEFAULT NULL,
			  `fileIndex` smallint(4) DEFAULT NULL,
			  `filePass` varchar(32) DEFAULT NULL,
			  `fileAliasTitle` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`fileId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS ".SQL_PREFIX."file_type (
			  `fileTypeId` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
			  `fileTypeMime` varchar(64) NOT NULL DEFAULT '',
			  `fileExtension` varchar(32) NOT NULL DEFAULT '',
			  `fileShowHTML` text NOT NULL,
			  PRIMARY KEY (`fileTypeId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;
		";
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			('File manager','".$this->version."',1,1,'This is a system module for management all News for VS Framework.','files');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}
?>