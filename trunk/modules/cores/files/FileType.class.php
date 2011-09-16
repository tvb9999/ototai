<?php

class FileType {
	private $id = NULL;
	private $mime = NULL;
	private $extension = NULL;
	private $displayHTML = NULL;


	public function setId($id=0) {
		$this->id = intval($id);
	}

	public function setMime($mime="") {
		$this->mime = $mime;
	}

	public function setExtension($extension="") {
		$this->extension = $extension;
	}

	public function setDisplayHTML($html="") {
		$this->html = $html;
	}

	public function getId() {
		return $this->id;
	}

	public function getMime() {
		return $this->mime;
	}

	public function getExtension() {
		return $this->extension;
	}

	public function getDisplayHTML() {
		return $this->displayHTML;
	}

	public function convertToDB() {
		isset ( $this->id ) 		? ($dbobj ['fileTypeId'] = $this->id) : 0;
		isset ( $this->mime ) 		? ($dbobj ['fileTypeMime'] = $this->mime) : '';
		isset ( $this->extension ) 	? ($dbobj ['fileExtension'] = $this->extension) : '';
		isset ( $this->displayHTML )		? ($dbobj ['fileShowHTML'] = $this->displayHTML) : '';
		return $dbobj;
	}

	public function convertToObject($object = array()) {
		isset ( $object ['fileTypeId'] ) ? ($this->id = $object ['fileTypeId']) : 0;
		isset ( $object ['fileTypeMime'] ) ? ($this->mime = $object ['fileTypeMime']) : '';
		isset ( $object ['fileExtension'] ) ? ($this->extension = $object ['fileExtension']) : '';
		isset ( $object ['fileShowHTML'] ) ? ($this->displayHTML = $object ['fileShowHTML']) : '';
	}
}
?>