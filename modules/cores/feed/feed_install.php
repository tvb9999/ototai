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

class feed_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";

	function Install(){
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`,`moduleVirtual`) values 
			('Rss Manager','".$this->version."',0,1,'This is a system module for management all page for VS Framework.','feed',0);
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
		$this->query[] = "DELETE FROM `".SQL_PREFIX."menus` WHERE `menuTitle`='feed' and `parentId`=15";
	}
}
?>