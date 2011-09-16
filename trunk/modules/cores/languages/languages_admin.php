<?php
/*
 +-----------------------------------------------------------------------------
 |   VIET SOLUTION SJC  base on IPB Code version 2.0.0
 |	Author: BabyWolf
 |	Homepage: http://khkt.net
 |	If you use this code, please don't delete these comment line!
 |	Start Date: 21/09/2004
 |	Finish Date: 22/09/2004
 |	Modified author: tongnguyen
 |	Modified Start Date: 5/04/2009
 |	Modified Finish Date: 11/04/2009
 |	moduleName Description: This module is for management all languages in system.
 +-----------------------------------------------------------------------------
 */
if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}
global $vsStd;
$vsStd->requireFile ( CORE_PATH . "languages/languages.php", true );

class languages_admin extends languages {
	private $output = "";
	private $html = "";

	/*-------------------------------------------------------------------------*/
	// CONSTRUCT
	/*-------------------------------------------------------------------------*/

	function __construct() {
		global $vsTemplate;
		parent::__construct ();
		// Get more words for this invocation!
		$this->html = $vsTemplate->load_template ( 'skin_languages' );
	}
	/**
	 * @return unknown
	 */
	public function getHtml() {
		return $this->html;
	}

	/**
	 * @return unknown
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * @param unknown_type $html
	 */
	public function setHtml($html) {
		$this->html = $html;
	}

	/**
	 * @param unknown_type $output
	 */
	public function setOutput($output) {
		$this->output = $output;
	}

	function auto_run() {
		global $bw;
		//-------------------------------------------
		// What to do?
		//-------------------------------------------
		switch ($bw->input [1]) {
			// User langauge zone
			case 'switch':
					$this->switchUserLanguage();
				break;

				// Word zone
			case 'deleteWord' :
				$this->deleteWord ();
				break;
			case 'addMore' :
				$this->addMoreWord ();
				break;
				// Item File Lang Module zone
			case 'saveLangItem' :
				$this->saveLanguages ();
				break;
			case 'viewItem' :
				$this->viewItem ();
				break;
			case 'addLangItem' :
				$this->addLangItem ();
				break;
			case 'deleteItem' :
				$this->deleteItem ();
				break;
					
				// Language Lang zone
				
			case 'viewLangWith' :
				$this->viewLangWith ();
				break;
			case 'viewLangModule' :
				$this->viewLangModule ();
				break;
			case 'deleteLang' :
				$this->deleteLang ();
				break;
			case 'editLang' ://displayLangForm
				$this->addEditLangForm('edit');
				break;
			case 'addLangForm' ://displayLangForm
				$this->addEditLangForm ();
				break;
			case 'addEditLang' :
				$this->addEditLang ();
				break;
			case 'displayLangForm':
				$this->displayLanguagesForm ();
				break;
			case 'languageList':
				$this->getLangsList ();//ok
				break;
			case 'additemform':
				$this->additemform ();//
				break;
			case 'relation':
				$this->relation();
				break;
			case 'create-delete-folder':
				$this->createDeleteFolder();
				break;
			default :
				$this->loadDefault ();
				break;
		}
	}
	function createDeleteFolder(){

	}


	function additemform() {
		//		global $bw, $vsTemplate;
		return $this->output = $this->html->addLangItemForm ();
	}

	function switchUserLanguage() {
		global $bw, $vsPrint;

		$language = new Lang();
		$language = $this->getLangById($bw->input[2]);
		$_SESSION[APPLICATION_TYPE]['language']['currentLang'] = $language->convertToDB();
		$vsPrint->boink_it($_SERVER['HTTP_REFERER']);
	}

	function defaultLang() {
		global $bw;
		$this->language = $this->getLangByID ($bw->input [2]);
		if ($this->result ['status']) {
			$this->language->setDefault ( 1 );
			$this->updateLang ();
		}
		$this->getLangsList ( $this->Langobj->result ['message'] );
	}

	function deleteLang() {

		global $bw;
		$this->deleteLanguges ( $bw->input [2] );
		$this->getLangsList ( $this->result ['message'] );
	}

	function getWordArray() {
		global $bw;
		if ($bw->input [4]) {
			$this->language->setId ( $bw->input [2] );
			$this->langtype=$bw->input [3];
			$this->language->setModule ( $bw->input [4] );
				
			$this->setItemFilePath ();
				
			require ($this->itempath);
			$_SESSION ['moduleName'] = $bw->input [3];
		} else
		require ($_SESSION ['pathLang']);
		return $lang;
	}

	function updateModuleWord($valuearr = array()) {
		global $vsLang;

		$content = "<?php\n";
		$content .= "\$lang = ";
		$content .= var_export ( $valuearr, true );
		$content .= ";\n";
		$content .= "?>";
		@chmod ( $_SESSION ['pathLang'], 0777 );
		$wf = fopen ( $_SESSION ['pathLang'], "w" );
		fwrite ( $wf, $content );
		fclose ( $wf );
		@chmod ( $_SESSION ['pathLang'], 0644 );
	}

	function deleteWord() {
		global $bw;
		$valuearr = $this->getWordArray ();
		unset ( $valuearr [$bw->input [3]] );
		$this->updateModuleWord ( $valuearr );
		$this->viewItem ( "The word key <b>[" . $bw->input [3] . "]</b> has been deleted." );
	}

	function addMoreWord() {
		global $bw;
		$valuearr = $this->getWordArray ();
		$valuearr += array ($bw->input ['wkey'] => $bw->input ['wdisplay'] );

		$this->updateModuleWord ( $valuearr );
		$this->viewItem ( "The word key <b>[" . $bw->input ['wkey'] . "]</b> has been added with value <b>[" . $bw->input ['wdisplay'] . "]." );
	}

	function saveLanguages() {
		global $bw;
		$InputArray = array ();

		foreach ( $bw->input as $key => $value ) {
			if (substr ( $key, 0, 4 ) == 'key_')
			$InputArray [substr ( $key, 4 )] = $value;
		}

		$this->updateModuleWord ( $InputArray );
		$this->viewItem ( "All language in " . $bw->input [0] . " have been saved." );
	}

	function viewItem($message = "") {
		global $vsTemplate;
		$valuearr = $this->getWordArray ();
		$langitem ['moduleName'] = $_SESSION ['moduleName'];

		foreach ( $valuearr as $key => $value ) {
			$var_array['key']=$key;
			$var_array['value']=$value;
			$show[]=$var_array;
			//			$vsTemplate->assign_block_vars($var_array,'WORD_ITEM');
		}
		$showall['WORD_ITEM'] = $show;
		unset($var_array);
		unset($valuearr);
		$showall['message'] = $message;
		$showall['moduleName'] = $langitem ['moduleName'];
		return $this->output = $this->html->wordList($showall);
		//		$vsTemplate->assign_var('message',$message);
		//		$vsTemplate->assign_var('moduleName',$langitem ['moduleName']);
		//		$vsTemplate->assign_vars_form_string( $this->html->wordList(),'moduleHandle',0);

	}

	/**
	 * This function is for add a language item to file or database
	 * @name AddLangItem
	 * @author BabyWolf
	 * @param String $bw->input['LangItemName'] posted from add form
	 * @param Int $bw->input['langId'] posted from add form
	 * @return The result message
	 */
	function addLangItem() {
		global $bw,$langtype;

		// Set language item object values
		$this->language->setValue ( array ('pageTitle' => $bw->input ['moduleName'], 'main_title' => $bw->input ['moduleName'] ) );
		$this->language->setId ( $bw->input ['langId'] );
		$this->language->setModule ( $bw->input ['moduleName'] );
		$bw->input [3] = $bw->input ['langtype'];
		$langtype = $bw->input ['langtype'];
		$this->langtype=$bw->input [3];

		if($this->language->getModule() == "")
		$this->result['message'] .= "Module can't be blank!<br>";
		elseif($this->langtype=='')
		$this->result['message'] .= "Folder Type lang is not define!<br>";
		else $this->writeItemToFile ();

		$this->getLangItemList ( $this->result ['message'] );
	}
	/**
	 * This function is for deleting an items in the list
	 * @name DeleteItem
	 * @author BabyWolf
	 * @param Int $bw->input['itemId'] got from address
	 * @return The message after process
	 */
	function deleteItem() {
		global $bw;

		$this->language->setId ( $bw->input [2] );
		$this->langtype=$bw->input [3];
		$this->language->setModule ( $bw->input [4] );
		$this->deleteItemFile ();
		$this->getLangItemList ( $this->result ['message'] );
		//		$this->viewLangModule ( $this->result ['message'] );

	}
	function viewLangModule($message='') {
		global $bw,$vsTemplate;

		$_SESSION['url_href'] = $bw->input ['vs'];

		$form['CURRENT_LANG_ITEM'] = $this->getLangItemList($message);
		$form['CURRENT_LANG_ADD_ITEM'] = $this->html->addLangItemForm();
		$this->output = $this->html->FileLangMainAjax($form);
	}
	/**
	 * This function is for get all items in a Lang
	 * @name GetLangItemList
	 * @author BabyWolf
	 * @param Int $bw->input['langId'] got from address
	 * @return html of item list in specified Lang
	 */
	function getLangItemList($message = "") {
		global $bw,$vsTemplate,$langtype;

		$bw->input [2] = $bw->input [2] ? $bw->input [2] : $this->language->getId ();
		$type = $bw->input [3] ? $bw->input [3] : 'user';
		// Then get all items it that Lang
		$items = $this->getAllItemInLang ( $bw->input [2] ,$type);

		if (! $this->result ['status']) {
			$message = $message . $this->result ['message'];
			$langhtml = $this->html->langItem ( $langitemhtml, $message );
			return $langhtml;
		}
		$format_class = 1;
		$count = 0;
		foreach ( $items as $key => $value ) {
			$values=$this->language->convertToView();
			$values['langModule']=$value;
			$values['langType']=$type;
			$values['format_class'] = $count % 2 ? 'even' : 'odd';
			//			$vsTemplate->assign_block_vars($values,"VAR_LANG_ITEM");
			$show[$count] = $values;
			$count ++;
				
		}
		$showall['VAR_LANG_ITEM'] = $show;
		$showall['type']=$type;
		$langtype = $type;
		$showall['message']=$message;

		$this->output = $this->html->langItem($showall);
		return $this->output;
		//		$vsTemplate->assign_var('langtype',$type);
		//		$vsTemplate->assign_var('message',$message);
		//		$vsTemplate->assign_vars_form_string( $this->html->langItem(),'CURRENT_LANG_ITEM');
	}
	//==========================================
	// Lang ZONE
	//==========================================
	/**
	* This function is for viewing the items in a Lang
	* @name ViewLang
	* @author BabyWolf
	* @param Int $bw->input['langId'] got from address
	* @return html of add form and item list
	*/
	function viewLangWith() {
		global $bw,$vsTemplate;

		$_SESSION ['url_href'] = $bw->input ['vs'];

		$show['CURRENT_LANG_ITEM']=$this->getLangItemList ();

		$show['CURRENT_LANG_ADD_ITEM']=$this->html->addLangItemForm();

		$this->output = $this->html->FileLangMain($show);

	}

	/**
	 * This function is for outputing add/edit Lang form
	 * @name AddEditPacakgeForm
	 * @author BabyWolf
	 * @param String $message the result message
	 * @param Bit $formtype control the type of form
	 * @param langpacakge_obj $this->language the object for editing
	 * @return html of add/edit Lang form
	 */
	function addEditLangForm($formtype ='add',$message = "") {
		global $vsLang ,$vsTemplate,$bw;

		$form ['type'] = $formtype;
		$form ['message'] = $message;
		$form ['title'] = $vsLang->getWords ( 'creat_new_lang' ,' Create Language set  ');
		$form['submit'] = $vsLang->getWords('creat_new_lang_bt','Add');
		if($formtype=='edit'){
			$this->language = $this->getLangById ( $bw->input [2] );
			$form['submit'] = $vsLang->getWords('edit_selected_lang_bt','Edit');
			$form ['title'] = $vsLang->getWords ( 'edit_selected_lang' ,' Edit Language set  ');
			$form['switchform'] = <<<EOF
				<input class="button" type="button" value="Switch to Add Form" name="switch" onclick="javascript:vsf.get('languages/addLangForm/','langmodule');" />
EOF;
			$form['language'] = $this->language;
		}
		$flags = $this->getLanguageSymbol();

		$form['flags'] = $flags;
		if(!$form['language'])$form['language'] = new Lang();

		return $this->output = $this->html->addEditLangForm ($form);
	}

	/**
	 * This function is for process add/edit a Lang
	 * @name AddEditLang
	 * @author BabyWolf
	 * @param The parameters is posted from add/edit form
	 * @return Out put the result message
	 */
	function addEditLang() {
		global $bw;
		// Set posted parameters to Lang object
		$this->language->convertToObject ( $bw->input );
		// Check the form type,
		// if form type is 1 => edit
		// else form type is not 1 => add
		if ($bw->input ['FormType']=='edit') {
			$this->language->setId ( $bw->input ['langId'] ); // Get posted id to update
			if($bw->input ['langFolder']!=$bw->input ['oldFolder']){
				if($this->makeLangfolder($bw->input ['langFolder'])){
					$this->deleteDirectory(ROOT_PATH."langs/".$bw->input ['oldFolder']);

				}
			}
			$this->updateLang ();
			$this->result ['message']="update folder[".$bw->input ['langFolder']."]success";
			//			$this->updateLang ();
		} else {
			if($bw->input ['langFolder']&&$this->makeLangfolder($bw->input ['langFolder'])){
				$this->insertLang ();
				$this->result ['message']="create folder[".$bw->input ['langFolder']."]success";
			}
		}
		$this->getLangsList ( $this->result ['message'] );

	}

	function makeLangfolder($name){
		$linkname = ROOT_PATH."langs/".$name;
		if(!is_dir($linkname))
		{
			mkdir($linkname, 0777, true);
			mkdir($linkname."/admin", 0777, true);
			mkdir($linkname."/user", 0777, true);
			return 1;
		}else {
			$this->result ['message'].=" folder[".$name."] has been exits";
			return;
		}
			
	}

	function removeFolder($name){
		$linkname = ROOT_PATH."langs/".$name;
		if(is_dir($linkname))
		{
			Rmdir($linkname);
			return 1;
		}
	}
	function deleteDirectory($dir) {
		if (!file_exists($dir)) return true;
		if (!is_dir($dir) || is_link($dir)) return unlink($dir);
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			if (!$this->deleteDirectory($dir . "/" . $item)) {
				chmod($dir . "/" . $item, 0777);
				if (!$this->deleteDirectory($dir . "/" . $item)) return false;
			};
		}
		return rmdir($dir);
	}



	/**
	 * This function is for get all Lang list
	 * @name GetLangLangsList
	 * @author BabyWolf
	 * @param String $message the message of result
	 * @return html of language Lang list
	 */
	function getLangsList($message = "") {
		global $vsTemplate,$bw;
		$showval="";
		// Get all Langs
		$this->getAllLang ();
		// Init Lang object
		$count = 0; // Using for css of row
		$format_class = 1;
		foreach ( $this->arrayLang as $Lang ) {
			// Calculate even or odd row
			$values=$Lang->convertToView();
				
			$values['format_class'] = $count % 2 ? 'even' : 'odd';
			$values['imageactive'] = $Lang->getStatus () ? "enabled" : "disabled";
			$values['defimg']= <<<EOF
			<img src="{$bw->vars['img_url']}/skin_visible.gif" border="0" alt="">
EOF;
			if($values['langDefault'])
			$values['defimg']= <<<EOF
			<a href="javascript:vsf.get('Languages/defaultLang/{$values['langId']}/','langlist');"><img src="{$bw->vars['img_url']}/skin_invisible.gif" border="0" alt=""></a>
EOF;
			$values['langType']= $values['langType']?"User":"Admin";
			$values['langDefault']= $values['langDefault']?"Yes":"No";
			//			$vsTemplate->assign_block_vars($values,'LANG_ITEM');
			$show[$count]=$values;
			$count ++;
		}
		$showval['values']=$show;

		$showval['mess'] = $message;

		return $this->output = $this->html->getLangList ($showval);
	}
	function loadDefault() {
		global $vsPrint,$vsTemplate;
		$vsPrint->addJavaScriptString('init_tab','
		var loadcategory=0;
		var loadproducts=0;
			$(document).ready(function(){
    			$("#page_tabs").tabs(
    				{
    					cache: false
    				}
    			);
  			});
		');

		$list = $this->arrayLang;


		$this->output = $this->html->MainPage($list);
	}
	/**
	 * This function is show all language Lang and add Lang form
	 * @name ShowLanguages
	 * @author BabyWolf
	 * @return Out put module html
	 */
	function displayLanguagesForm() {
		$show['form'] = $this->addEditLangForm ();
		$show['list'] = $this->getLangsList ();

		return $this->output = $this->html->languagesMain($show);
	}
}
?>