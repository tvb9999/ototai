<?php
	private $time;
	public function __construct(){
	public function getId() {
		return $this->id;
	}

	public function getModule() {
		return $this->module;
	}

	public function getAction() {
		return $this->action;
	}

	public function getRelUrl() {
		return $this->relUrl;
	}

	public function getAliasUrl() {
		return $this->aliasUrl;
	}

	public function getHits() {
		return $this->hits;
	}

	public function getTime() {
		return $this->time;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setModule($module) {
		$this->module = $module;
	}

	public function setAction($action) {
		$this->action = $action;
	}

	public function setRelUrl($relUrl) {
		$this->relUrl = $relUrl;
	}

	public function setAliasUrl($aliasUrl) {
		$this->aliasUrl = $aliasUrl;
	}

	public function setHits($hits) {
		$this->hits = $hits;
	}

	public function setTime($time) {
		$this->time = $time;
	}

}
?>