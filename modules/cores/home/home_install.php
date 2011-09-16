<?php
class home_install {
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "627";

	function Install() {
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."modules(`moduleId`,`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			(1,'Home','".$this->version."',1,1,'Home.','home');
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}