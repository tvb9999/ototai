<?php
/*
+-----------------------------------------------------------------------------
|   VIET SOLUTION SJC  base on IPB Code version 3.3.4.1
|	Author: tongnguyen
|	Start Date: 19/05/2009
|	Finish Date: 20/05/2009
|	moduleName Description: This module is for management all component in system.
+-----------------------------------------------------------------------------
*/

global $vsStd;
$vsStd->requireFile(CORE_PATH."components/components.php");

class components_admin extends components {
	private $html;
	private $output;
	
	/**
	 * @return unknown
	 */
	public function getOutput() {
		return $this->output;
	}
	
	/**
	 * @param unknown_type $output
	 */
	public function setOutput($output) {
		$this->output = $output;
	}
	
	/**
	 * CONSTRUCT
	 */
	function __construct(){
		global $vsTemplate;
		parent::__construct();
		$this->html = $vsTemplate->load_template("skin_components");
	}
	
	/**
	 * DESTRUCT
	 */
	function __destruct(){
		unset($this->html);
		unset($this->output);
	}
	
	function auto_run(){
		global $bw;
		
		switch ($bw->input[1]) {
			case 'editComponent':
					$this->output =	$this->addEditForm('edit');
				break;
			case 'addComponent':
					$this->output = $this->addEditForm();
				break;
			case 'addEditComponent':
					$this->addEditComponent();
				break;
			case 'un_install':
					$this->un_installComponent();
				break;
			default:
					$this->loadDefault();
				break;
		}
	}

	function addEditForm($formType="add"){
		global $bw,$vsLang;
		
		$form['formType'] = $formType;
		$form['submit'] = $vsLang->getWords("component_{$formType}_bt",ucfirst($formType));
		$form['title'] = $vsLang->getWords("component_{$formType}_title",ucfirst($formType)." Component");
		if($formType=="edit")
		{
			$form['switchform'] = "<button class=\"ui-state-default ui-corner-all\" onclick=\"javascript:vsf.get('components/addComponent/','comForm');\">{$vsLang->getWords('component_switch_bt','Switch to Add Form')}</button>";
			$this->components = $this->getComponentId($bw->input[2]);
		}
		return $this->html->addEditForm($this->components,$form);
	}
	
	function addEditComponent(){
		global $bw;
		
		$this->components->convertToObject($bw->input);
		if($bw->input['formType'] == "edit")
			$this->updateComponent();
		else $this->insertComponent();
		
		$this->output = $this->getComponentList($this->result['message']);
	}
	
	function getComponentList($message = ""){
		$count = 0;
		$this->getAllComponent();

		foreach ($this->arrayCom as $com){
			$format_class = $count++ % 2 ? 'even' : 'odd';
			$un_install = $com->getComInstalled()?$this->html->uninstallLink($com):$this->html->installLink($com);
			$result .= $this->html->addComList($com,$format_class,$un_install);
		}
		return $this->html->comListHTML($result,$message);
	}
	
	function un_installComponent(){
		global $bw;
		
		$this->components = $this->getComponentId($bw->input[3]);
		$this->components->setComId($bw->input[3]);
		$bw->input[2] = $bw->input[2]=="un"?0:1;
		$this->components->setComInstalled($bw->input[2]);
		$this->updateComponent();
		$this->output = $this->getComponentList($this->result['message']);
	}
	
	function loadDefault(){
		
		$comList = $this->getComponentList();
		$comForm = $this->addEditForm();
		$this->output = $this->html->mainCom();
		$this->output = str_replace('<!--COM LIST-->',$comList,$this->getOutput());
		$this->output = str_replace('<!--COM FORM-->',$comForm,$this->getOutput());
	}
 
}
?>