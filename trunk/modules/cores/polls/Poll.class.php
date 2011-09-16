<?php
class Poll extends BasicObject{
	private $image 		= NULL;
	private $click	 	= NULL;
	
	function __destruct() {
		parent::__destruct();
		unset($this->image);
		unset($this->click);
	}
	
	public function getImage() {
		return $this->image;
	}

	public function getClick() {
		return $this->click;
	}

	public function setImage($image) {
		$this->image = $image;
	}

	public function setClick($click) {
		$this->click = $click;
	}

	function convertToDB() {
		isset ( $this->catId ) 			? ($dbobj ['pollCatId'] 		= $this->getCatId()) 		: '';
		isset ( $this->id ) 			? ($dbobj ['pollId'] 		= $this->id) 					: '';
		isset ( $this->title ) 			? ($dbobj ['pollTitle'] 		= $this->title) 			: '';
		isset ( $this->intro ) 			? ($dbobj ['pollIntro'] 		= $this->intro) 			: '';
		isset ( $this->content ) 		? ($dbobj ['pollContent'] 	= $this->content) 				: '';
		isset ( $this->index ) 			? ($dbobj ['pollIndex'] 		= $this->index) 			: '';
		isset ( $this->click ) 			? ($dbobj ['pollClick'] 		= $this->click) 			: '';
		isset ( $this->image ) 			? ($dbobj ['pollImage'] 		= $this->image) 			: '';
		isset ( $this->status ) 		? ($dbobj ['pollStatus'] 	= $this->status) 				: '';
		return $dbobj;
	}

	function convertToObject($object) {
		isset ( $object ['pollId'] ) 		? $this->setId ( $object ['pollId'] ) 				: '';
		isset ( $object ['pollCatId'] ) 		? $this->setCatId ( $object ['pollCatId'] ) 			: '';
		isset ( $object ['pollTitle'] ) 		? $this->setTitle ( $object ['pollTitle'] ) 			: '';
		isset ( $object ['pollIntro'] ) 		? $this->setIntro ( $object ['pollIntro'] ) 			: '';
		isset ( $object ['pollImage'] ) 		? $this->setImage ( $object ['pollImage'] ) 			: '';
		isset ( $object ['pollIndex'] ) 		? $this->setIndex ( $object ['pollIndex'] ) 			: '';
		isset ( $object ['pollClick'] ) 		? $this->setClick ( $object ['pollClick'] ) 			: '';
		isset ( $object ['pollContent'] )	? $this->setContent ( $object ['pollContent'] ) 		: '';
		isset ( $object ['pollStatus'] ) 	? $this->setStatus ( $object ['pollStatus'] ) 		: '';
	}

	function validate() {
		$status = true;
		if ($this->title == "") {
			$this->message .= "poll title can not be blank!";
			$status = false;
		}
		return $status;
	}	
}