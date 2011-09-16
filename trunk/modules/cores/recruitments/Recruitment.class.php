<?php
class Recruitment extends BasicObject {
	private $image = NULL;
	private $author = NULL;
	private $begin = NULL;
	private $end = NULL;

	public $parser = NULL;
	public $message = NULL;

	function __construct() {
		parent::__construct ();
	}

	function __destruct() {
		parent::__destruct ();
		unset ( $this->image );
		unset($this->begin);
		unset($this->end);
	}
	
	function convertToDB() {
		isset ( $this->id ) ? ($dbobj ['recruitmentId'] = $this->id) : '';
		isset ( $this->catId ) ? ($dbobj ['recruitmentCatId'] = $this->getCatId ()) : '';
		isset ( $this->title ) ? ($dbobj ['recruitmentTitle'] = $this->title) : '';
		isset ( $this->intro ) ? ($dbobj ['recruitmentIntro'] = $this->intro) : '';
		isset ( $this->image ) ? ($dbobj ['recruitmentImage'] = $this->image) : '';
		isset ( $this->content ) ? ($dbobj ['recruitmentContent'] = $this->content) : '';
		isset ( $this->index ) ? ($dbobj ['recruitmentIndex'] = $this->index) : '';
		isset ( $this->postdate ) ? ($dbobj ['recruitmentPostDate'] = $this->postdate) : '';
		isset ( $this->begin) ? ($dbobj ['recruitmentBegin'] = $this->begin) : '';
		isset ( $this->end) ? ($dbobj ['recruitmentEnd'] = $this->end) : '';
		isset ( $this->status ) ? ($dbobj ['recruitmentStatus'] = $this->status) : '';
		return $dbobj;
	}
	function convertToObject($object) {
		global $vsMenu;
		isset ( $object ['recruitmentId'] ) ? $this->setId ( $object ['recruitmentId'] ) : '';
		isset ( $object ['recruitmentCatId'] ) ? $this->setCatId ( $object ['recruitmentCatId'] ) : '';
		isset ( $object ['recruitmentTitle'] ) ? $this->setTitle ( $object ['recruitmentTitle'] ) : '';
		isset ( $object ['recruitmentIntro'] ) ? $this->setIntro ( $object ['recruitmentIntro'] ) : '';
		isset ( $object ['recruitmentIndex'] ) ? $this->setIndex ( $object ['recruitmentIndex'] ) : '';
		isset ( $object ['recruitmentImage'] ) ? $this->setImage ( $object ['recruitmentImage'] ) : '';
		isset ( $object ['recruitmentContent'] ) ? $this->setContent ( $object ['recruitmentContent'] ) : '';
		isset ( $object ['recruitmentPostDate'] ) ? $this->setPostdate ( $object ['recruitmentPostDate'] ) : '';
		isset ( $object ['recruitmentBegin'] ) ? $this->setBegin($object ['recruitmentBegin'] ) : '';
		isset ( $object ['recruitmentEnd'] ) ? $this->setEnd($object ['recruitmentEnd'] ) : '';
		isset ( $object ['recruitmentStatus'] ) ? $this->setStatus ( $object ['recruitmentStatus'] ) : '';
		
		isset ( $object ['recruitmentCatId'] ) ? $this->setCategory ( $object ['recruitmentCatId'] ) : '';
	}

	function validate() {
		$status = true;
		if ($this->content == "") {
			$this->message .= "Recruitment content can not be blank!<br />";
			$status = false;
		}

		if ($this->title == "") {
			$this->message .= "Recruitment title can not be blank!";
			$status = false;
		}
		return $status;
	}

	function setCategory($category) {
		$this->category = $category;
	}


	function getUrl() {
		global $bw;
		return parent::getUrl ( "recruitments" );
	}
	
	function getImage() {
		return $this->image;
	}

	function getIntro($size=0) {
		$parser = new PostParser ();
		$parser->pp_do_html = 1;
		$parser->pp_nl2br = 0;
		$intro = $parser->post_db_parse($this->intro);
		if($size){
			$intro = strip_tags($intro);
			return VSFTextCode::cutString($intro,$size);
		}
		return $parser->post_db_parse($this->intro);
	}
	
	function getContent($size=0) {
		$parser = new PostParser ();
		$parser->pp_do_html = 1;
		$parser->pp_nl2br = 0;
		$content = $parser->post_db_parse($this->content);
		if($size){
			$content = strip_tags($content);
			return VSFTextCode::cutString($content,$size);
		}
		return $parser->post_db_parse($this->content);
	}
	
	function getAuthor() {
		return $this->author;
	}

	
//	function getPostDate($format=null){
//		if($format) {
//			$datetime= new VSFDateTime();
//			return $datetime->getDate($this->postdate, $format);
//		}
//		return $this->postdate;
//	}
	
	function getBegin($format = NULL){
		if($format&& $this->begin){
			$datetime= new VSFDateTime();
			return $datetime->getDate($this->begin, $format);
		}
		return $this->begin;
	}

	function getEnd($format = NULL) {
		if($format && $this->end) {
			$datetime = new VSFDateTime();
			return $datetime->getDate($this->end, $format);
		}
		return $this->end;
	}

	function getParser() {
		return $this->parser;
	}

	function getMessage() {
		return $this->message;
	}

	function setImage($image) {
		$this->image = $image;
	}

	function setAuthor($author) {
		$this->author = $author;
	}

	function setBegin($begin) {
		$this->begin = $begin;
	}

	function setEnd($end) {
		$this->end = $end;
	}

	function setParser($parser) {
		$this->parser = $parser;
	}

	function setMessage($message) {
		$this->message = $message;
	}

}