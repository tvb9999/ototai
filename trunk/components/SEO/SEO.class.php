<?php
class SEO extends BasicObject {
	
	private $aliasUrl = NULL;
	private $realUrl = NULL;
	private $keyword = NULL;
	public $title = NULL;
	public $type = NULL;

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param $type the $type to set
	 */
	public function setType($type) {
		$this->type = $type;
	}

	function __construct() {
		parent::__construct ();
	}

	function __destruct() {
		parent::__destruct ();
		unset ( $this->aliasUrl );
		unset ( $this->realUrl );
		unset ( $this->keyword );
	
	}

	public function convertToDB() {
		isset ( $this->aliasUrl ) ? ($dbobj ['seoAliasUrl'] = $this->aliasUrl) : '';
		isset ( $this->realUrl ) ? ($dbobj ['seoRealUrl'] = $this->realUrl) : '';
		isset ( $this->keyword ) ? ($dbobj ['seoKeyword'] = $this->keyword) : '';
		isset ( $this->intro ) ? ($dbobj ['seoIntro'] = $this->intro) : '';
		isset ( $this->title ) ? ($dbobj ['seoTitle'] = $this->title) : '';
		isset ( $this->type ) ? ($dbobj ['seoType'] = $this->type) : '';
		return $dbobj;
	}

	function convertToObject($object) {
		isset ( $object ['seoId'] ) ? $this->setId ( $object ['seoId'] ) : '';
		isset ( $object ['seoAliasUrl'] ) ? $this->setAliasUrl ( $object ['seoAliasUrl'] ) : '';
		isset ( $object ['seoRealUrl'] ) ? $this->setRealUrl ( $object ['seoRealUrl'] ) : '';
		isset ( $object ['seoKeyword'] ) ? $this->setKeyword ( $object ['seoKeyword'] ) : '';
		isset ( $object ['seoIntro'] ) ? $this->setIntro ( $object ['seoIntro'] ) : '';
		isset ( $object ['seoTitle'] ) ? $this->setTitle ( $object ['seoTitle'] ) : '';
		isset ( $object ['seoType'] ) ? $this->setType ( $object ['seoType'] ) : '';
	
	}

	public function getAliasUrl() {
		return $this->aliasUrl;
	}

	public function getKeyword() {
		return $this->keyword;
	}

	public function getRealUrl() {
		return $this->realUrl;
	}

	public function setAliasUrl($aliasUrl) {
		$this->aliasUrl = $aliasUrl;
	}

	public function setKeyword($keyword) {
		$this->keyword = $keyword;
	}

	public function setRealUrl($realUrl) {
		$this->realUrl = $realUrl;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}
}
?>