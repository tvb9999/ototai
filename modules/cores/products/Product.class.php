<?php
class Product extends BasicObject {
	private $code = NULL;
	private $price = NULL;
	private $image = NULL;
	private $state = NULL;
	
	private $clearSearch = NULL;

	function __construct() {
		parent::__construct ();
	}

	function __destruct() {
		parent::__destruct ();
		unset ( $this->code );
		unset ( $this->price );
		unset ( $this->image );
		unset ( $this->hits );
		unset ( $this->cleanTitle );
		unset ( $this->cleanContent );
	}

	public function convertToDB() {
		$dbobj ['productId'] = $this->id ? $this->id : 0;
		$dbobj ['productCatId'] = $this->getCatId() ? $this->getCatId() : 0;
		$dbobj ['productTitle'] = $this->title ? $this->title : '';
		$dbobj ['productIntro'] = $this->intro ? $this->intro : '';
		$dbobj ['productContent'] = $this->content ? $this->content : '';
		$dbobj ['productCode'] = $this->code ? $this->code : '';
		$dbobj ['productImage'] = $this->image <> '' ? $this->image : '';
		$dbobj ['productPostDate'] = $this->postdate ? $this->postdate : 0;
		$dbobj ['productPrice'] = $this->price ? $this->price : '';
		$dbobj ['productIndex'] = $this->index ? $this->index : 0;
		$dbobj ['productStatus'] = $this->status ? $this->status : '';
		
		if (isset ( $this->intro ) || isset ( $this->content ) || isset ( $this->title )) {
			$allstring = $this->title . ' ' . $this->code . ' ' . strip_tags ( $this->getIntro () ) . ' ' . strip_tags ( $this->getContent () );
			$dbobj ['productClearSearch'] = strtolower ( VSFTextCode::removeAccent ( trim ( $allstring ) ) );
		}
		return $dbobj;
	}

	function convertToObject($object) {
		global $vsMenu;
		$this->setId ( $object ['productId'] ? $object ['productId'] : 0);
		$this->setCatId ( $object ['productCatId'] ? $object ['productCatId'] : 0);
		$this->setCategory ( $object ['productCatId'] ? $object ['productCatId'] : '');
		$this->setTitle ( $object ['productTitle'] ? $object ['productTitle'] : '');
		$this->setIntro ( $object ['productIntro'] ? $object ['productIntro'] : '');
		$this->setContent ( $object ['productContent'] ? $object ['productContent'] : '');
		$this->setCode ( $object ['productCode'] ? $object ['productCode'] : '');
		$this->setPrice ( $object ['productPrice'] ? $object ['productPrice'] : '');
		isset ( $object ['productImage'] )	? $this->setImage 	( $object ['productImage'] ) 		: '';
		$this->setPostdate ( $object ['productPostDate'] ? $object ['productPostDate'] : '');
		$this->setIndex ( $object ['productIndex'] ? $object ['productIndex'] : '');
		$this->setStatus ( $object ['productStatus'] ? $object ['productStatus'] : '');
	}

	function validate() {
		$status = true;
		
		if ($this->title == "") {
			$this->message .= "Product title can not be blank!";
			$status = false;
		}
		return $status;
	}

	public function getCleanSearch() {
		return $this->cleanSearch;
	}

	public function setCleanSearch($cleanSearch) {
		$this->cleanSearch = $cleanSearch;
	}

	public function getState() {
		return $this->state;
	}

	public function setState($state) {
		$this->state = $state;
	}

	public function getCode() {
		return $this->code;
	}

	public function getPrice($number = true) {
		global $vsLang;
		if (APPLICATION_TYPE == 'user' && $number) {
			if ($this->price > 0) {
				return number_format ( $this->price, 0, "", "." );
			}
			return $vsLang->getWords ( 'callprice', 'Call' );
		}
		return $this->price;
	}

	public function getImage() {
		return $this->image;
	}

	public function getHits() {
		return $this->hits;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function setPrice($price) {
		$this->price = $price;
	}

	public function setImage($image) {
		$this->image = $image;
	}

	public function setHits($hits) {
		$this->hits = $hits;
	}

	function getStatus($type = null) {
		global $bw;
		if (! $type)
			return $this->status;
		if ($type == "image") {
			$imgArray = array ('disabled.png', 'enable.png', 'home.png', 'best.png', 'new.png');
			return $this->status = "<img src='{$bw->vars ['img_url']}/{$imgArray[$this->getStatus()]}' alt='{$this->getStatus()}' />";
		}
		if ($type == "text")
			return $this->status ? "Hiển thị" : "Ẩn";
	}

}