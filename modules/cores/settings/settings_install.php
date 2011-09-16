<?php
class settings_install {
	public $query 		= "";
	public $version 	= "3.2.1";
	public $build 		= "436";
	public $tableName 	= "setting";
	public $moduleTitle = "settings";

	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."setting`;";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."setting` (
			  `settingId` int(10) NOT NULL AUTO_INCREMENT,
			  `settingCatId` int(10) NOT NULL,
			  `settingTitle` varchar(255) NOT NULL,
			  `settingIntro` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `settingValue` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `settingInputType` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `settingKey` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `settingRoot` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `settingType` tinyint(1) NOT NULL DEFAULT '0',
			  `settingModule` varchar(50) NOT NULL DEFAULT 'global',
			  `settingIndex` int(10) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`settingId`),
			  KEY `SKey` (`settingKey`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."module`(`moduleTitle`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleIntro`,`moduleClass`) 
			VALUES
			('Settings','".$this->version."',1,0,'This is a system module for management all system settings for VS Framework configuration.','settings');
		";

		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."setting` (`settingCatId`, `settingTitle`, `settingIntro`, `settingValue`, `settingInputType`, `settingKey`, `settingRoot`, `settingType`, `settingModule`, `settingIndex`) values
				('0','Website name','Example: Viet Solution','Viet Solution','text','global_websitename','0','0','global','0'),
				('0','Website address','Example: www.vietsol.net','www.vietsol.net','text','global_websiteaddress','0','0','global','0'),
				('0','System Email','Example: admin@vietsol.net','admin@vietsol.net','text','global_systememail','0','0','global','0'),
				('0','Cache for menus','Enable this for better performance. But everytime you add new menu for user, you have to build cache again.','Yes','radio','public_menu_cache','1','1','global','0'),
				('0','SMTP user','Example: admin@vietsol.net','admin@vietsol.net','text','email_smtp_user','0','1','global','0'),
				('0','SMTP Password','Example: 123456','123456','password','email_smtp_password','0','1','global','0'),
				('0','Mail method','Use local PHP method or SMTP','Yes','radio','email_method','1','1','global','0'),
				('0','SMTP host','Example: smtp.vietsol.net','smtp.valleytech.net','text','email_smtp_host','1','1','global','0'),
				('0','SMTP port','Example: 25','25','text','email_smtp_port','1','1','global','0'),
				('0','Email Wrap Brackets','Email Wrap Brackets','Yes','radio','mail_wrap_brackets','1','1','global','0'),
				('0','Lifetime of Admin session','Number of minutes for admin time out (Example: 30 minutes)','30','text','admin_timeout','1','1','global','0'),
				('0','Type of redirect','Use for different OS when you got problem with redirect feature','normal','text','header_redirect','1','0','global','0'),
				('0','Admin default page','The first page when load admin system','products','text','admin_frontpage','1','1','global','0'),
				('0','User default page','The first page when load the public user page','home','text','public_frontpage','1','2','global','0'),
				('0','Server time zone','Time zone of the server','-7','text','global_server_timezone','1','1','global','0'),
				('0','Use clean url','Rewrite url more friendly','Yes','radio','public_cleanurl','1','1','global','0'),
				('0','Multi Languages for User','Multi Languages for User','Yes','radio','user_multi_lang','1','1','global','0'),
				('0','Multi Languages for Admin','Multi Languages for Admin','Yes','radio','admin_multi_lang','1','1','global','0'),
				('0','Cache skin wrapper','Cache skin wrapper','1','text','use_cache_skin_wrapper','1','1','global','0'),
				('0','Public menu cache','Public menu cache','1','text','public_menu_cache','1','2','global','0')
		";
	}

	function Uninstall($moduleId) {
		//		$this->query[] = "DROP TABLE `".SQL_PREFIX."system_settings`;";
		//		$this->query[] = "DELETE FROM `".SQL_PREFIX."module` WHERE `moduleClass`='{$this->moduleTitle}'";
	}
}