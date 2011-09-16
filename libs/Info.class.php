<?php
//===========================================================================
// INFO CLASS
//===========================================================================
class infos {
	public $input = array ();
	public $base_url = "";
	public $vars = "";
	public $lang_id = "en";
	public $skin = "";
	public $server_load = 0;
	public $version = "v3.0.0";
	public $lastclick = "";
	public $location = "";
	public $debug_html = "";
	public $perm_id = "";
	public $skin_global = "";
	public $loaded_templates = array ();
	public $user = "";

	function infos() {
		global $INFO;
		
		$this->vars = &$INFO;
		$this->vars ['mime_img'] = 'style/images/<#IMG_DIR#>';
	}
}