<?php

if ( ! defined( 'IN_VSF' ) )
{
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit();
}
global $vsStd;
$vsStd->requireFile(COM_PATH.'SEO/SEO.php');


class urlalias_admin {
	public $output		= "";
	private $html       = "";
	public $module      ="";

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

	/*-------------------------------------------------------------------------*/
	// INIT
	/*-------------------------------------------------------------------------*/

	function __construct()
	{
		global $vsStd,$vsTemplate;
		// Get more words for this invocation!
		$this->html = $vsTemplate->load_template('skin_urlalias');
		$this->module = new COM_SEO();
	}
	 
	function auto_run()
	{
		global $bw;

			
		//-------------------------------------------
		// What to do?
		//-------------------------------------------
		switch($bw->input[1])
		{
			// Alias zone
			case 'addAlias':
				$this->output = $this->addEditObjForm('add');
				break;

			case 'editAlias':
				$this->output = $this->addEditObjForm('edit');
				break;

			case 'addEditObj':
				$this->addEditObj();
				break;
					
			case 'deleteObj':
				$this->deleteObj();
				break;
			case 'alias':
				$this->output = $this->getObjList();
				break;
			case 'showAddEditForm':
				$this->showAddEditForm($bw->input[2]);
				break;
			case 'addEditObjPublic':
				$this->addEditObjPublic();
				break;
			default:
				$this->displayDefault();
				break;
		}
	}
	function addEditObjPublic(){
		global $bw;
			if($bw->input['seoId']){//edit
				$this->module->obj= $this->module->getObjectById($bw->input['seoId']);
				$this->module->obj->convertToObject($bw->input);
				$this->module->updateObjectById($this->module->obj);
				if($this->module->result['status']) $this->module->result['id']=$this->module->obj->getId();
			}else{//add new seo
				$this->module->obj->convertToObject($bw->input);
				$this->module->insertObject($this->module->obj);
				if($this->module->result['status']) $this->module->result['id']=$this->module->obj->getId();
				
			}
			
	//		$a=array("messager"=>"Khong thanh cong","b"=>"this is b value","status"=>false);
			return $this->output=json_encode($this->module->result);
	}
	//===================================================
	// ALIAS ZONE
	//===================================================
	function showAddEditForm($seoId=NULL){
		global $bw,$vsLang;
		
		if($seoId)
		{
			$form['submit'] = $vsLang->getWords("alias_edit_bt_","Edit");
			$form['title'] = $vsLang->getWords("alias_edit_title_","Edit Alias");
			$this->module->obj = $this->module->getObjectById($seoId);
		}else{
			$form['submit'] = $vsLang->getWords("alias_add_bt_","Add");
			$form['title'] = $vsLang->getWords("alias_add_title_","Add Alias");
		}
		if(!$this->module->obj){
			$this->module->obj=new SEO();
		}
		$addeditformhtml = $this->html->showAddEditForm($form, $this->module->obj);
		return	$this->output =$addeditformhtml;
	}
	function deleteObj() {
		global $bw;
		$list = $bw->input[2];
		//		$this->module->deleteSEOObject($bw->input[2]);
		$this->module->setCondition("seoId in ({$bw->input[2]})");
		$this->module->deleteObjectByCondition();
		$bw->input[2]=$bw->input[3];
		$this->module->getAllSEOObject();
		$this->module->result['message']="Delete obj id ({$list}) success";
		return	$this->output = $this->getObjList($this->module->result['message']);
	}

	function addEditObj() {
		global $bw;
		$this->module->obj->convertToObject($bw->input);
		if($bw->input['formType'] == 'edit') {
			$this->module->updateObjectById($this->module->obj);
		}
		else
		$this->module->insertObject($this->module->obj);
		$this->module->getAllSEOObject();
			
		$this->output = $this->getObjList($this->module->result['message']);
	}

	function displayDefault() {
		$this->output = $this->html->managerObjHtml($this->addEditObjForm(),$this->getObjList());
	}

	function getObjList($message = "") {
		global $bw,$vsStd,$vsSettings,$vsLang;
		$this->module->getAllSEOObject();
		$vsStd->requireFile(LIBS_PATH ."Pagination.class.php");
		$size =  $vsSettings->getSystemKey("admin_{$bw->input[0]}_list_number",10);
		$pagination = new VSFPagination();
		$pagination->ajax=1;
		$pagination->callbackobjectId='urlCurrent';
		$pagination->url = "urlalias/alias/";
		$pagination->p_Size = $size;
		$pagination->p_TotalRow=count($this->module->getArrayObj());
		$pagination->SetCurrentPage(2);
		$pagination->BuildPageLinks();

		$option['list']   = array_slice($this->module->getArrayObj(),$pagination->p_StartRow,$size);
		foreach ($option['list'] as $alia) {
			$alia->type?$alia->typeText=$vsLang->getWords("alias_detail","Detail"):$alia->typeText=$vsLang->getWords("alias_global","Global");
		}
		$option['paging'] = $pagination->p_Links;
		$option['message']=$message;

		return $this->html->objListHtml($option);
	}

	function addEditObjForm($formtype='add') {
		global $bw,$vsLang;
		$form['formType'] = $formtype;
		$form['submit'] = $vsLang->getWords("alias_{$formtype}_bt",ucfirst($formtype));
		$form['title'] = $vsLang->getWords("alias_add_title","Add More Alias");
		if($formtype=="edit")
		{
			$form['title'] = $vsLang->getWords("alias_edit_title","Edit A Alias");
			$form['switchform'] = "<button  onclick=\"javascript:vsf.get('urlalias/addAlias/','urlForm');\">{$vsLang->getWords('component_switch_bt','Switch to Add Form')}</button>";
			$this->module->obj = $this->module->getObjectById($bw->input[2]);
				
		}
		$addeditformhtml = $this->html->addEditObjForm($form, $this->module->obj);

		return $addeditformhtml;
	}
}
?>