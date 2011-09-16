<?php
global $vsStd;
$vsStd->requireFile(CORE_PATH.'weblinks/weblinks.php');
class weblinks_public{
	protected $html;
	protected $module;
	protected $output;
	protected $arrayObj;
	function __construct() {
		global $vsTemplate,$vsStd;
		$this->module = new weblinks();
		$this->html = $vsTemplate->load_template('skin_weblinks');
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