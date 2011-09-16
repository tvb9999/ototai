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

class urlalias_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";
	
	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."seo`;";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."seo` (
			  `seoId` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `seoType` tinyint(1) NOT NULL,
			  `seoAliasUrl` varchar(255) NOT NULL,
			  `seoRealUrl` varchar(255) NOT NULL,
			  `seoTitle` varchar(255) NOT NULL,
			  `seoKeyword` varchar(255) NOT NULL,
			  `seoIntro` text NOT NULL,
			  `seoStatus` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`seoId`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					
		";
		
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 			
			('Url alias manager', '".$this->version."', 1, 1, 'This module is for manage alias of url.', 'urlalias')
		";
	}
	
	function Uninstall($moduleId) {
		$this->query[] = "DROP TABLE `".SQL_PREFIX."seo`";
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}
?>