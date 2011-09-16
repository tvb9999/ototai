<?php

if ( ! defined( 'IN_VSF' ) ){
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit();
}
global $vsStd;
$vsStd->requireFile(CORE_PATH . "pages/pages_public.php");	

class helpbuy_public extends pages_public {
	public  $output = "";
	public $html="";
	function __construct(){
		global $vsTemplate,$vsStd;		
		parent::__construct();			
		$this->html = $vsTemplate->load_template('skin_helpbuy');
	}
		
	function auto_run(){
		global $bw, $vsTemplate;
		
		$bw->input['module']="helpbuy";
		switch ($bw->input[1]){
			default:
				$this->loadDefault();
		}
	}
	
	function loadDefault(){
		global $bw, $vsPrint, $vsLang;
		
		$result=$this->module->getByMouduleName($bw->input['module']);
		
		if($result['pageList']){
			$currentItem = current($result['pageList']);
		}
		
		$this->output = $this->html->loadDefault($currentItem);
	}	
}
?>