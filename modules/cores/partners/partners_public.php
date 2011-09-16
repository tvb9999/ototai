<?php
if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}
global $vsStd;
$vsStd->requireFile(CORE_PATH.'partners/partners.php');
class partners_public{
	protected $html;
	protected $module;
	protected $output;
	protected $arrayObj;
	function __construct() {
		global $vsTemplate,$vsStd;
		$this->module = new partners();
		$this->html = $vsTemplate->load_template('skin_partners');
	}

	function auto_run() {
		global $bw;
		switch ($bw->input[1]) {
			case 'detail':
				$this->loadDetail($bw->input[2]);
				break;
			case 'category':
				$this->loadCategory($bw->input[2]);
				break;
			default:{
				$this->loadDefault();
				break;
			}
		}
	}

	public function showBottomGlobal(){
		return  $this->output =$this->html->showBottomGlobal($this->arrayObj);
	}

	public function showCenterGlobal(){
		return  $this->output =$this->html->showCenterGlobal($this->arrayObj);
	}

	function loadDefault(){
		$hostObject=$this->module->getHotList();
		$htmlListCatProject=$this->getListWithCat();
		return $this->output = $this->html->loadDefault($hostObject,$htmlListCatProject);
	}

	public function getOutput() {
		return $this->output;
	}

	public function setOutput($output) {
		$this->output = $output;
	}
}
?>