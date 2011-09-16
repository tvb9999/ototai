<?php
class orders_install{
	public $query = "";
	public $version = "3.3.4.1";
	public $build = "628";
	
	function Install() {
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."order`;";
		$this->query[] = "DROP TABLE IF EXISTS `".SQL_PREFIX."order_items`;";
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."order` (
			  `orderId` int(10) NOT NULL AUTO_INCREMENT,
			  `orderName` varchar(32) NOT NULL,
			  `orderAddress` varchar(100) NOT NULL,
			  `orderPhone` varchar(11) NOT NULL,
			  `orderFax` varchar(11) NOT NULL,
			  `orderEmail` varchar(32) NOT NULL,
			  `orderTime` int(10) NOT NULL,
			  `orderStatus` text NOT NULL,
			  `orderNote` text NOT NULL,
			  `orderMessage` text NOT NULL,
			  PRIMARY KEY (`orderId`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 ;
		";
		
		$this->query[] = "
			CREATE TABLE IF NOT EXISTS `".SQL_PREFIX."order_items` (
			  `itemId` int(10) NOT NULL AUTO_INCREMENT,
			  `itemOrderId` int(10) NOT NULL,
			  `itemProductName` varchar(100) NOT NULL,
			  `itemProductCode` varchar(32) NOT NULL,
			  `itemProductId` int(10) NOT NULL,
			  `itemQuantity` smallint(5) NOT NULL,
			  `itemPrice` int(10) NOT NULL,
			  `itemSaleOff` varchar(20) NOT NULL,
			  PRIMARY KEY (`itemId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		";
		$this->query[] = "
			INSERT INTO `".SQL_PREFIX."modules`(`moduleId`,`moduleName`,`moduleVersion`,`moduleIsAdmin`,`moduleIsUser`,`moduleDescription`,`moduleClass`) values 
			(15,'Order','".$this->version."',1,1,'This is a system module for management all Order for VS Framework.','orders');
		";
	}
	
	function Uninstall($moduleId) {
		$this->query[] = "DELETE FROM `".SQL_PREFIX."modules` WHERE `moduleId`=".$moduleId;
		$this->query[] = "DROP TABLE `".SQL_PREFIX."order`";
		$this->query[] = "DROP TABLE `".SQL_PREFIX."order_items`";
	}
}
?>