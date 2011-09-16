<?php

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}
global $vsStd;
$vsStd->requireFile ( CORE_PATH . "feed/rss.php" );

class feed_public {
	public $output = "";
	public $html = "";
	private $module;
	
	function __construct() {
		global $vsTemplate, $vsStd;
		$this->module = new rss ();
		$this->html = $vsTemplate->load_template ( 'skin_rss' );
	}

	function auto_run() {
		global $bw, $vsTemplate;
		
		switch ($bw->input [1]) {
			case 'fd' :
				$this->output = $this->module->getFeedCatId ( 0,$bw->input [2] );
				break;
			default :
				$this->loadDefault ();
		}
	}

	function loadDefault() {
		global $bw, $vsPrint, $vsStd;
		
		$this->output = $this->html->loadDefault ();
	}

	/**
	 * @return the $output
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * @param field_type $output
	 */
	public function setOutput($output) {
		$this->output = $output;
	}

}
?>