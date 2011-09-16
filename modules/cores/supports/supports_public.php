<?php
/*
 +-----------------------------------------------------------------------------
 |   VS FRAMEWORK 3.0.0
 |	Author: BabyWolf
 |	Homepage: http://vietsol.net
 |	If you use this code, please don't delete these comment line!
 |	Start Date: 21/09/2004
 |	Finish Date: 22/09/2004
 |	Version 2.0.0 Start Date: 07/02/2007
 |	Version 3.0.0 Start Date: 03/29/2009
 +-----------------------------------------------------------------------------
 */
if(!defined('IN_VSF')){
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}
require_once(CORE_PATH."supports/supports.php");
class supports_public{
	protected $html = "";
	protected $module;
	protected $output = "";

	function __construct(){
		global $vsTemplate;
		$this->html = $vsTemplate->load_template('skin_supports');
		$this->module = new supports();
	}

	function auto_run(){
		global $bw;

		switch($bw->input[1]){

			case 'detail':
				$this->loadDetail($bw->input[2]);
				break;
		}
	}

	public function getHtml() {
		return $this->html;
	}

	public function getOutput() {
		return $this->output;
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	public function setOutput($output) {
		$this->output = $output;
	}
}
?>
