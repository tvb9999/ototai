<?php
class Page extends BasicObject {
	private $image 		= NULL;
	private $code 		= NULL;
	private $latitude 	= NULL;
	private $longitude 	= NULL;
	private $addGoogle 	= NULL;
	private $info     	= NULL;
	private $module 	= NULL;
	
	function __construct() {
		parent::__construct ();
	}
	/**
	 * @param $longitude the $longitude to set
	 */
	
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}
	
	/**
	 * @param $latitude the $latitude to set
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}
	
	/**
	 * @return the $longitude
	 */
	public function getLongitude() {
		return $this->longitude;
	}
	
	/**
	 * @return the $latitude
	 */
	public function getLatitude() {
		return $this->latitude;
	}
	
	function __destruct() {
		parent::__destruct ();
		unset ( $this->image );
		unset ( $this->postdate );
		unset ( $this->code );
	}
	
	public function convertToDB() {
		
		$dbobj ['pageLatitude'] = $this->latitude ? $this->latitude : 0;
		$dbobj ['pageLongitude'] = $this->longitude ? $this->longitude : 0;
		$dbobj ['pageCatId'] = $this->catId ? $this->catId : '';
		$dbobj ['pageTitle'] = $this->title ? $this->title : '';
		$dbobj ['pageIntro'] = $this->intro ? $this->intro : '';
		isset ( $this->image ) 	? ($dbobj ['pageImage'] 		= $this->image) 		: '';
		$dbobj ['pageContent'] = $this->content ? $this->content : '';
		$dbobj ['pageIndex'] = $this->index ? $this->index : 0;
		$dbobj ['pagePostDate'] = $this->postdate ? $this->postdate : '';
		$dbobj ['pageStatus'] = $this->status ? $this->status : '';
		$dbobj ['pageCode'] = $this->code ? $this->code : '';
		$dbobj ['pageAddGoogle'] = $this->addGoogle ? $this->addGoogle : '';
		$dbobj ['pageInfo'] = $this->info ? serialize($this->info) : '';
		isset ($this->module) ? $dbobj ['pageModule'] = $this->module : '';
		$allstring = $this->title . ' ' . $this->code . ' ' . strip_tags ( $this->getIntro () ) . ' ' . strip_tags ( $this->getContent () );
		$dbobj ['clearTitle'] = strtolower ( VSFTextCode::removeAccent ( trim ( $allstring ),' ' ) );
		return $dbobj;
	}
	
	function convertToObject($object) {
		global $vsMenu;
		
		$this->setLatitude ( $object ['pageLatitude'] != ''? $object ['pageLatitude'] : 0);
		$this->setLongitude ( $object ['pageLongitude']!= '' ? $object ['pageLongitude'] : 0);
		$this->setId ( $object ['pageId'] ? $object ['pageId'] : '');
		$this->setCatId ( $object ['pageCatId'] ? $object ['pageCatId'] : '');
		$this->setTitle ( $object ['pageTitle'] ? $object ['pageTitle'] : '');
		$this->setIntro ( $object ['pageIntro'] ? $object ['pageIntro'] : '');
		$this->setIndex ( $object ['pageIndex']!= '' ? $object ['pageIndex'] : 0);
		isset ( $object ['pageImage'] )	? $this->setImage 	( $object ['pageImage'] ) 		: 0;
		$this->setContent ( $object ['pageContent'] ? $object ['pageContent'] : '');
		$this->setPostdate ( $object ['pagePostDate'] ? $object ['pagePostDate'] : 0);
		$this->setCode ( $object ['pageCode'] ? $object ['pageCode'] : '');
		$this->setStatus ( $object ['pageStatus'] ? $object ['pageStatus'] : 0);
		$this->setAddGoogle ( $object ['pageAddGoogle'] ? $object ['pageAddGoogle'] : '');
		$this->setInfo ($object ['pageInfo'] ? $object ['pageInfo'] : '');
		$this->setModule ($object ['pageModule'] ? $object ['pageModule'] : '');
	}
	
	public function getUrl($module = "pages") {
		return parent::getUrl ( $module );
	}
	
	public function getImage() {
		return $this->image;
	}
	
	public function getAddGoogle() {
		return $this->addGoogle;
	}

	public function setAddGoogle($addGoogle) {
		$this->addGoogle = $addGoogle;
	}

	public function setImage($image) {
		$this->image = $image;
	}
	
	public function getGroupdIds() {
		return $this->groupdIds;
	}
	
	public function setGroupdIds($groupdIds) {
		$this->groupdIds = $groupdIds;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @return the $module
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * @param field_type $module
	 */
	public function setModule($module) {
		$this->module = $module;
	}

	public function getInfo() {
		return unserialize($this->info);
	}

	public function setInfo($info) {
		$this->info = $info;
	}

	public function setCode($code) {
		$this->code = $code;
	}

}
?>