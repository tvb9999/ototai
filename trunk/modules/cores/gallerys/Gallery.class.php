<?php
/**
 *
 * @author Sanh Nguyen
 * @version 
 */
class Gallery extends BasicObject {
	private $code = NULL;
	private $image = NULL;
	private $passWord = NULL;
	/**
	 * @return the $code
	 */
	/**
	 * @return the $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param $image the $image to set
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @param $code the $code to set
	 */
	/**
	 * @return the $pass
	 */
	public function getPassWord() {
		return $this->passWord;
	}

	/**
	 * @param $pass the $pass to set
	 */
	public function setPassWord($pass) {
		$this->passWord = $pass;
	}

	public function setCode($code) {
		$this->code = $code;
	}
	
	function __construct() {
		parent::__construct ();
	}
	
	function __destruct() {
		unset ( $this );
	}
	public function getUrl($module = "pages", $char = "-") {
		global $bw;
		$this->title = str_replace ( "/", $char, $this->title );
		return $bw->vars ['board_url'] . '/gallerys/detail/' . $this->id;
	}
	
	function validate() {
		global $vsLang;
		$status = true;
		if ($this->title == "") {
			$this->message .= $vsLang->getWords ( 'gallery_err_name_blank', "Album name cannot be blank!" );
			$status = false;
		}
		return $status;
	}
	public function convertToDB() {
		isset ( $this->catId ) ? ($dbobj ['galleryCatId'] = $this->catId) : '';
		
		$dbobj ['galleryAlbum'] = $this->title !='' ? $this->title : '';
		$dbobj ['galleryIntro'] = $this->intro ? $this->intro : '';
		$dbobj ['galleryIndex'] = $this->index ? $this->index : 0;
		isset ( $this->status ) ? ($dbobj ['galleryStatus'] = $this->status) : '';
		$dbobj ['galleryCode'] = $this->code ? $this->code : '';
		isset ( $this->image ) ? ($dbobj ['galleryImage'] = $this->image) : '';
		isset ( $this->passWord ) ? ($dbobj ['galleryPassWord'] = $this->passWord) : '';
		return $dbobj;
	}
	
	public function convertToObject($object = array()) {
		isset ( $object ['galleryId'] ) ? $this->setId ( $object ['galleryId'] ) : '';
		isset ( $object ['galleryCatId'] ) ? $this->setCatId ( $object ['galleryCatId'] ) : '';
		isset ( $object ['galleryAlbum'] ) ? $this->setTitle ( $object ['galleryAlbum'] ) : '';
		isset ( $object ['galleryIntro'] ) ? $this->setIntro ( $object ['galleryIntro'] ) : '';
		isset ( $object ['galleryIndex'] ) ? $this->setIndex ( $object ['galleryIndex'] ) : '';
		isset ( $object ['galleryStatus'] ) ? $this->setStatus ( $object ['galleryStatus'] ) : '';
		isset ( $object ['galleryCode'] ) ? $this->setCode ( $object ['galleryCode'] ) : '';
		isset ( $object ['galleryImage'] ) ? $this->setImage ( $object ['galleryImage'] ) : '';
		isset ( $object ['galleryPassWord'] ) ? $this->setPassWord ( $object ['galleryPassWord'] ) : '';
	}
}