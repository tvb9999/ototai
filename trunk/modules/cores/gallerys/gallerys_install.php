<?php
/**
 *
 * @author Sanh Nguyen
 * @version
 */


class gallerys_install {
	public $query = "";
	public $version = "3.4.1";
	public $build = "634";

	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS ".SQL_PREFIX."gallery";
		$this->query[] = "
			INSERT INTO ".SQL_PREFIX."module(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) values 
			('Manage Gallerys','".$this->version."',1,1,'This is a system module for management all menu links in VS Framework.','gallerys')
		";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS ".SQL_PREFIX."gallery (
			 `galleryId` smallint(5) NOT NULL AUTO_INCREMENT,
			  `galleryCatId` smallint(5) DEFAULT NULL,
			  `galleryAlbum` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `galleryCode` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `galleryIntro` text COLLATE utf8_unicode_ci,
			  `galleryIndex` smallint(4) DEFAULT NULL,
			  `galleryStatus` tinyint(1) DEFAULT NULL,
			  PRIMARY KEY (`galleryId`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
	}

	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleId`=".$moduleId;
	}
}
