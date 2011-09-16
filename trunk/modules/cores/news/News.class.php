<?php
class News extends BasicObject {
	private $image = NULL;
	private $author = NULL;
	private $hits = NULL;
	
	public $parser = NULL;
	public $message = NULL;

	function __construct() {
		parent::__construct ();
	}

	function __destruct() {
		parent::__destruct ();
		unset ( $this->image );
		unset ( $this->author );
		unset ( $this->hits );
	}

	public function convertToDB() {
		$dbobj ['newsCatId'] = $this->getCatId () ? $this->getCatId () : '';
		$dbobj ['newsId'] = $this->id ? $this->id : 0;
		$dbobj ['newsTitle'] = $this->title ? $this->title : '';
		$dbobj ['newsIntro'] = $this->intro ? $this->intro : '';
		isset ( $this->image ) 	? ($dbobj ['newsImage'] 		= $this->image) 		: '';
		$dbobj ['newsContent'] = $this->content ? $this->content : '';
		$dbobj ['newsIndex'] = $this->index ? $this->index : 0;
		$dbobj ['newsPostDate'] = $this->postdate ? $this->postdate : '';
		$dbobj ['newsHits'] = $this->hits ? $this->hits : 0;
		$dbobj ['newsStatus'] = $this->status ? $this->status : '';
		$dbobj ['newsAuthor'] = $this->author ? $this->author : '';
		return $dbobj;
	}

	function convertToObject($object) {
		global $vsMenu;
		$this->setId ( $object ['newsId'] ? $object ['newsId'] : 0 );
		$this->setCatId ( $object ['newsCatId'] ? $object ['newsCatId'] : '' );
		$this->setCategory ( $object ['newsCatId'] ? $object ['newsCatId'] : '' );
		$this->setTitle ( $object ['newsTitle'] ? $object ['newsTitle'] : '' );
		$this->setIntro ( $object ['newsIntro'] ? $object ['newsIntro'] : '' );
		$this->setAuthor ( $object ['newsAuthor'] ? $object ['newsAuthor'] : '' );
		$this->setIndex ( $object ['newsIndex'] ? $object ['newsIndex'] : '' );
		isset ( $object ['newsImage'] )	? $this->setImage 	( $object ['newsImage'] ) 		: 0;
		$this->setContent ( $object ['newsContent'] ? $object ['newsContent'] : '' );
		$this->setPostdate ( $object ['newsPostDate'] ? $object ['newsPostDate'] : '' );
		$this->setHits ( $object ['newsHits'] ? $object ['newsHits'] : '' );
		$this->setStatus ( $object ['newsStatus'] ? $object ['newsStatus'] : '' );
	}

	function validate() {
		$status = true;
		if ($this->content == "") {
			$this->message .= "News content can not be blank!<br />";
			$status = false;
		}
		
		if ($this->title == "") {
			$this->message .= "News title can not be blank!";
			$status = false;
		}
		return $status;
	}

	/**
	 * @param $hits the $hits to set
	 */
	public function setHits($hits) {
		$this->hits = $hits;
	}

	/**
	 * @param $author the $author to set
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * @param $image the $image to set
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * @param $category the $category to set
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	 * @return the $url
	 */
	public function getUrl() {
		global $bw;
		return parent::getUrl ( "news" );
	}

	/**
	 * @return the $hits
	 */
	public function getHits() {
		return $this->hits;
	}

	/**
	 * @return the $author
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * @return the $image
	 */
	public function getImage() {
		return $this->image;
	}

}