<?php

/*
 +-----------------------------------------------------------------------------
 |   VIET SOLTION SJC base on IPB Code version 2.0.0
 |	Author: tongnguyen
 |	Homepage: http://vietsol.net
 |	If you use this code, please don't delete these comment line!
 |	Start Date: 21/11/2008
 |	Finish Date: 21/11/2008
 +-----------------------------------------------------------------------------
 */
class class_editor {
	private $editorID = "";
	private $basePath;
	private $language = "";
	private $value = "";
	private $height = "";
	private $width = "";
	private $toolbarSet = "";
	private $theme;

	function __construct() {
		global $bw;
		$this->basePath = $bw->vars ['board_url'];
		$this->theme = "advanced";
	}

	function __destruct() {
		unset ( $this->editorID );
		unset ( $this->basePath );
		unset ( $this->language );
		unset ( $this->value );
		unset ( $this->height );
		unset ( $this->width );
		unset ( $this->simple );
		unset ( $this->theme );
	}

	/**
	 * @return unknown
	 */
	public function getBasePath() {
		return $this->basePath;
	}

	/**
	 * @return unknown
	 */
	public function getEditorID() {
		return $this->editorID;
	}

	/**
	 * @return unknown
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return unknown
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @return unknown
	 */
	public function getSimple() {
		return $this->simple;
	}

	/**
	 * @return unknown
	 */
	public function getTheme() {
		return $this->theme;
	}

	/**
	 * @return unknown
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @return unknown
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param unknown_type $basePath
	 */
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}

	/**
	 * @param unknown_type $editorID
	 */
	public function setEditorID($editorID) {
		$this->editorID = $editorID;
	}

	/**
	 * @param unknown_type $height
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * @param unknown_type $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	/**
	 * @param unknown_type $simple
	 */
	public function setToolbarSet($toolbarSet = "") {
		$this->toolbarSet = $toolbarSet;
	}

	/**
	 * @param unknown_type $theme
	 */
	public function setTheme($theme) {
		$this->theme = $theme;
	}

	/**
	 * @param unknown_type $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @param unknown_type $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * create editor
	 * @param unknown_type $editor
	 */
	function createEditor($id, $editor = array()) {
		require_once (JAVASCRIPT_PATH . "tiny_mce/tinyMCE.php");
		$mce = new tinyMCE ();
		
		$mce->setHeight ( $editor ['height'] );
		$mce->setWidth ( $editor ['width'] );
		$mce->setUrl ( $this->basePath );
		$mce->setValue ( $this->value );
		$mce->setToolbar ( $this->toolbarSet );
		$mce->setTheme ( $this->theme );
		$mce->setInstanceName ( $id );
		return $mce->createHtml ();
	}
}
?>