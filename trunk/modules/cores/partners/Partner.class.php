<?php
class Partner extends BasicObject {
	private $address = NULL;
	private $fileId = NULL;
	private $expTime = NULL;
	private $begTime = NULL;
	private $price = NULL;
	private $website = NULL;
	private $hits = NULL;
	private $position = NULL;
	public $message = NULL;
	public $clearsearch = NULL;
	
	public function convertToDB() {
		$dbobj ['partnerCatId'] = $this->getCatId () ? $this->getCatId () : 0;
		$dbobj ['partnerId'] = $this->id ? $this->id : 0;
		$dbobj ['partnerTitle'] = $this->title ? $this->title : '';
		$dbobj ['partnerWebsite'] = $this->website ? $this->website : '';
		$dbobj ['partnerExpTime'] = $this->expTime ? $this->expTime : 0;
		$dbobj ['partnerBeginTime'] = $this->begTime ? $this->begTime : 0;
		$dbobj ['partnerAddress'] = $this->address ? $this->address : '';
		$dbobj ['partnerContent'] = $this->content ? $this->content : '';
		isset ( $this->fileId ) 	? ($dbobj ['partnerFileId'] 		= $this->fileId) 		: 0;
		$dbobj ['partnerIndex'] = $this->index ? $this->index : 0;
		$dbobj ['partnerHits'] = $this->hits ? $this->hits : 0;
		$dbobj ['partnerStatus'] = $this->status ? $this->status : 0;
		$dbobj ['partnerPrice'] = $this->price ? $this->price : 0;
		$dbobj ['partnerPosition'] = $this->position ? $this->position : 0;
		if (isset ( $this->intro ) || isset ( $this->content ) || isset ( $this->title )) {
			$cleanContent = VSFTextCode::removeAccent ( $this->title ) . " ";
			$cleanContent .= VSFTextCode::removeAccent ( strip_tags ( $this->getIntro () )) ;
			$dbobj ['partnerClearSearch'] = $cleanContent;
		}
		return $dbobj;
	}
	
	function convertToObject($object) {
		
		$this->setId ( $object ['partnerId'] ? $object ['partnerId'] : 0);
		$this->setCatId ( $object ['partnerCatId'] ? $object ['partnerCatId'] : 0);
		$this->setTitle ( $object ['partnerTitle'] ? $object ['partnerTitle'] : '');
		$this->setIntro ( $object ['partnerIntro'] ? $object ['partnerIntro'] : '');
		$this->setWebsite ( $object ['partnerWebsite'] ? $object ['partnerWebsite'] : '');
		$this->setAddress ( $object ['partnerAddress'] ? $object ['partnerAddress'] : '');
		$this->setPrice ( $object ['partnerPrice'] ? $object ['partnerPrice'] : '');
		$this->setExpTime ( $object ['partnerExpTime'] ? $object ['partnerExpTime'] : '');
		$this->setBeginTime ( $object ['partnerBeginTime'] ? $object ['partnerBeginTime'] : '');
		isset ( $object ['partnerFileId'] )	? $this->setFileId 	( $object ['partnerFileId'] ) 		: 0;
		$this->setIndex ( $object ['partnerIndex'] ? $object ['partnerIndex'] : '');
		$this->setContent ( $object ['partnerContent'] ? $object ['partnerContent'] : '');
		$this->setHits ( $object ['partnerHits'] ? $object ['partnerHits'] : '');
		$this->setStatus ( $object ['partnerStatus'] ? $object ['partnerStatus'] : 0);
		$this->setPosition ( $object ['partnerPosition'] ? $object ['partnerPosition'] : '');
		$this->setClearSearch ( $object ['partnerClearSearch'] ? $object ['partnerClearSearch'] : '');
	
	}
	/**
	 * @return the $latitude
	 */
	
	/**
	 * @return unknown
	 */
	public function getPosition() {
		return $this->position;
	}
	
	/**
	 * @param unknown_type $position
	 */
	public function setPosition($position) {
		$this->position = $position;
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	/**
	 * @param $address the $address to set
	 */
	public function setAddress($address) {
		$this->address = $address;
	}
	
	function __construct() {
		parent::__construct ();
	}
	
	function __destruct() {
		unset ( $this );
	}
	
	/**
	 * @param $hits the $hits to set
	 */
	public function setHits($hits) {
		$this->hits = $hits;
	}
	
	public function setClearSearch($clear) {
		$this->clearsearch = $clear;
	}
	
	/**
	 * @param $fileId the $fileId to set
	 */
	public function setFileId($fileId) {
		$this->fileId = $fileId;
	}
	
	/**
	 * @return the $url
	 */
	public function getUrl() {
		global $bw;
		return $bw->base_url . 'partners/detail/' . $this->getTitle ( true ) . '-' . $this->getId () . '/';
	}
	
	/**
	 * @return the $hits
	 */
	public function getHits() {
		return $this->hits;
	}
	
	/**
	 * @return the $fileId
	 */
	public function getFileId() {
		return $this->fileId;
	}
	
	/**
	 * @return the $expTime
	 */
	public function getExpTime($format = NULL) {
		if ($format && $this->expTime) {
			$datetime = new VSFDateTime ();
			return $datetime->getDate ( $this->expTime, $format );
		}
	}
	
	public function getBeginTime($format = NULL) {
		if ($format && $this->begTime) {
			$datetime = new VSFDateTime ();
			return $datetime->getDate ( $this->begTime, $format );
		}
	}
	
	/**
	 * @return the $price
	 */
	public function getPrice() {
		return $this->price;
	}
	
	/**
	 * @return the $website
	 */
	public function getWebsite() {
		return "http://" . str_replace ( "http://", "", $this->website );
	}
	
	/**
	 * @param $expTime the $expTime to set
	 */
	public function setExpTime($expTime) {
		$this->expTime = $expTime;
	}
	public function setBeginTime($begTime) {
		$this->begTime = $begTime;
	}
	
	/**
	 * @param $price the $price to set
	 */
	public function setPrice($price) {
		$this->price = $price;
	}
	
	/**
	 * @param $website the $website to set
	 */
	public function setWebsite($website) {
		$this->website = $website;
	}
	
	public function createNoImage() {
		return '<img src="utils/timthumb.php?src=images/noimage.jpg&amp;w=250&amp;h=150&amp;zc=0" alt="no-image">';
	}
	
	function validate() {
		$status = true;
		if ($this->title == "") {
			$this->message .= "partner title can not be blank!";
			$status = false;
		}
		return $status;
	}
	function getStatus($type = null) {
		global $bw;
		if (! $type)
			return $this->status;
		if ($type == "image") {
			$imgArray = array ('disabled.png', 'enable.png', 'home.png' );
			return $this->status = "<img src='{$bw->vars ['img_url']}/{$imgArray[$this->getStatus()]}' alt='{$this->getStatus()}' />";
		}
		if ($type == "text")
			return $this->status ? "Hiển thị" : "Ẩn";
	}

}