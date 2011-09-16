<?php
/*
 +-----------------------------------------------------------------------------
 |   VIETSOL CMS version 1.0.0.0
 |	Author: BabyWolf
 |	Homepage: http://www.vietsol.net
 |	If you use this code, please don't delete these comment lines!
 |	Start Date: 10/21/2007
 |	Finish Date: 10/21/2007
 |	Modified Start Date: 10/27/2007
 |	Modified Finish Date: 10/28/2007
 |	Module Description: This module is for management all modules in system.
 +-----------------------------------------------------------------------------
 */

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}

class modules_admin {
	private $output = "";
	private $html = "";
	public $module;
	private $systemchoice = array ();

	/*-------------------------------------------------------------------------*/
	// construct
	/*-------------------------------------------------------------------------*/
	public function __construct(){
		global $vsTemplate;

		$this->module = new modules();
		$this->html = $vsTemplate->load_template('skin_modules');
		$this->SetSystemChoice ();
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
	 * @return unknown
	 */
	public function getSystemchoice() {
		return $this->systemchoice;
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
			case 'uninstall' :
				$this->uninstallModules ();
				break;
					
			case 'install' :
				$this->installModules ();
				break;
					
			case 'addForm' :
				$this->addModuleForm ();
				break;
					
			case 'edit' :
				$this->editModule ();
				break;
					
			case 'delete' :
				$this->deletedModule ();
				break;
					
			case 'addEdit' :
				$this->addEditModule ();
				break;
					
			case 'changeStatus' :
				$this->changeModuleStatus ();
				break;
					
			default :
				$this->showModules ();
				break;
		}
	}

	function uninstallModules() {
		global $bw, $DB;

		$this->module->getObjectById ( $bw->input [2] );
		$classname = $this->module->obj->getClass() . "_install";
		$filepath = CORE_PATH. $this->module->obj->getClass() . "/" . $this->module->obj->getClass() . "_install.php";
		if (file_exists ( $filepath )) {
			require_once ($filepath);
			if (class_exists ( $classname )) {

				$module_install = new $classname ( );

				if (method_exists ( $module_install, "Uninstall" )) {
					$module_install->Uninstall ($bw->input [2]);
					$message = "You have successfully uninstalled modules [" . $this->module->obj->getClass() . "]";
					foreach ( $module_install->query as $value ) {
						$DB->query_id = mysql_query ( $value, $DB->connection_id );
						if (! $DB->query_id) {
							$message = mysql_error ();
							break;
						}
					}
				} else {
					$message = "The install method is not existed!";
				}
			} else {
				$message = "The install object is not existed!";
			}
		} else
		$message = "The install file is not existed!";

		$this->output = $this->getModuleList ( $message );
	}

	function installModules() {
		global $bw, $DB, $vsLang;
		$this->module->obj->setClass($bw->input[2]);
		$classname = $this->module->obj->getClass() . "_install";
		$filepath = CORE_PATH. $this->module->obj->getClass() . "/" . $this->module->obj->getClass() . "_install.php";
		if (file_exists ( $filepath )) {
			require_once ($filepath);
			if (class_exists ( $classname )) {

				$module_install = new $classname ( );

				if (method_exists ( $module_install, "Install" )) {
					$module_install->Install ();
					$message = sprintf($vsLang->getWords('modules_install_success',"You have successfully installed modules [%s]"), $this->module->obj->getClass());
					foreach ( $module_install->query as $value ) {
						$DB->query_id = mysql_query ( $value, $DB->connection_id );
						if (! $DB->query_id) {
							$message = mysql_error ();
							break;
						}
					}
				} else {
					$message = $vsLang->getWords('modules_install_method_not_exist',"The install method is not existed!");
				}
			} else {
				$message = $vsLang->getWords('modules_install_object_not_exist',"The install object is not existed!");
			}
		} else
		$message = $vsLang->getWords('modules_install_file_not_exist',"The install file is not existed!");

		$this->output = $this->getModuleList ( $message );
	}

	function addModuleForm() {
		$this->output = $this->addEditForm ();
	}

	function editModule() {
		global $bw;
		$this->module->getObjectById ( $bw->input [2] );
		$this->output = $this->addEditForm ( 1 );
	}

	function deletedModule() {
		global $bw;

		$this->deleteModule ( $bw->input [2] );

		$this->output = $this->getModuleList ( $this->result ['message'] );
	}

	function addEditModule() {
		global $bw;

		if ($bw->input ['moduleIsAdmin'])
		$bw->input ['moduleIsAdmin'] = 1;
		if ($bw->input ['moduleIsUser'])
		$bw->input ['moduleIsUser'] = 1;

		$this->module->obj->convertToObject ( $bw->input );

		if ($bw->input ['FormType']) {
			$this->module->obj->setId ( $bw->input ['moduleId'] );
			$this->module->updateObjectById($this->module->obj);
		} else{
			$this->module->validateModule(true);
			if($this->module->result['status'])
			$this->module->insertObject($this->module->obj);
		}

		$this->output = $this->getModuleList ( $this->result ['message'] );
	}
	/**
	 * Function name: SetSystemChoice
	 * Author: BabyWolf
	 * Written date: 10/10/2007
	 * Modified date: 12/16/2007
	 * Description: Set system class list so that user can't modify its status.
	 *
	 * @return void
	 */
	function setSystemChoice() {
		$this->systemchoice = array ('modules' => 'modules', 'languages' => 'languages', 'home' => 'home', 'systemsetting' => 'systemsetting', 'generalsetting' => 'generalsetting', 'login' => 'login', 'logout' => 'logout' );
	}

	/**
	 * Function name: UpdateSkin
	 * Author: BabyWolf
	 * Written date: 12/30/2007
	 * Modified date:
	 * Description: Update Skin.
	 * Check if enable module already have skin, do nothing.
	 * If enable module don't have skin, create skin file for it.
	 *
	 * @param $modulename: name of enable module.
	 * @return void
	 */
	function updateSkin($modulename = "") {
		global $bw, $DB, $vsStd, $vsPrint;
		// init skin path for type of module
		if ($bw->input ['Type']) {
			$skinpath = "../skin_cache";
		} else {
			$skinpath = "./skin_cache";
		}

		$DB->simple_construct ( array ('select' => '*', 'from' => 'skin_sets' ) );
		$l = $DB->simple_exec ();

		while ( $skin = $DB->fetch_row ( $l ) ) {
			if (file_exists ( $skinpath . "/cacheid_" . $skin ['set_skin_set_id'] . "/skin_" . $modulename . ".php" ))
			break;

			// Build file content
			$content = "<?php\n\n";
			$content .= "class skin_" . $modulename . " {\n\n";
			$content .= "function " . ucfirst ( $modulename ) . "Main() {\n";
			$content .= "global \$bw;\n";
			$content .= "\$BWHTML = \"\";\n";
			$content .= "//--starthtml--//\n\n";
			$content .= "\$BWHTML .= <<<EOF\n";
			$content .= "<div class=\"maintitle\">{\$bw->lang['main_title']}</div>\n";
			$content .= "<div align=\"right\" class=\"normaltext\"><!--PAGE LINKS--></div>\n";
			$content .= "<!--PAGE CONTENT-->\n";
			$content .= "<div align=\"right\" class=\"normaltext\"><!--PAGE LINKS--></div>\n";
			$content .= "EOF;\n";
			$content .= "//--endhtml--//\n\n";
			$content .= "return \$BWHTML;\n";
			$content .= "}\n}\n?>";
				
			$wf = fopen ( $skinpath . "/cacheid_" . $skin ['set_skin_set_id'] . "/skin_" . $modulename . ".php", "w" );
			fwrite ( $wf, $content );
			fclose ( $wf );
		}
	}
	/**
	 * Function name: UpdateLanguage
	 * Author: BabyWolf
	 * Written date: 12/16/2007
	 * Modified date:
	 * Description: Update language.
	 * Check if enable module already have language, do nothing.
	 * If enable module don't have language, create language for it.
	 *
	 * @param $modulename: name of enable module.
	 * @return void
	 */
	function updateLanguage($modulename = "") {
		global $bw, $DB, $vsStd, $vsPrint;
		// init lang path for type of module
		if ($bw->input ['Type']) {
			$langtype = 1;
			$langpath = "../lang";
		} else {
			$langtype = 0;
			$langpath = "./lang";
		}

		$DB->simple_construct ( array ('select' => '*', 'from' => 'langs', 'where' => 'LangType=' . $langtype ) );
		$l = $DB->simple_exec ();

		while ( $lang = $DB->fetch_row ( $l ) ) {
			// if languages store in file, write file, else update database
			if ($bw->vars ['storelangtype'])
			$this->updateLangFile ( $lang, $langpath, $modulename );
			else
			$this->updateLangDB ( $lang ['LangID'], $modulename );
		}
	}

	/**
	 * Function name: UpdateLangFile
	 * Author: BabyWolf
	 * Written date: 12/16/2007
	 * Modified date:
	 * Description: Check if language of module is already exist in file. If not, create it.
	 *
	 * @param  integer 	$langid
	 * @param  string	$langpath
	 * @param  string	$modulename
	 * @return void
	 */
	function updateLangFile($lang = array(), $langpath = "", $modulename = "") {

		if (file_exists ( $langpath . "/" . $lang ['FolderName'] . "/" . $modulename . ".lang" ))
		return;
			
		// Build file content
		$content = "<?php\n";
		$content .= "\$lang = array(\n";
		$content .= "'pageTitle'	=> '" . $modulename . "',\n";
		$content .= "'main_title'	=> '" . $modulename . "',\n";
		$content .= ");\n";
		$content .= "?>";

		$wf = fopen ( $langpath . "/" . $lang ['FolderName'] . "/" . $modulename . ".lang", "w" );
		fwrite ( $wf, $content );
		fclose ( $wf );
	}

	/**
	 * Function name: UpdateLangDB
	 * Author: BabyWolf
	 * Written date: 12/16/2007
	 * Modified date:
	 * Description: Check if language of module is already exist in database. If not, create it.
	 *
	 * @param  integer $langid
	 * @param  string $modulename
	 * @return void
	 */
	function updateLangDB($langid = 1, $modulename = "") {
		global $bw, $DB, $vsStd, $vsPrint;

		$DB->simple_construct ( array ('select' => '*', 'from' => 'lang_items', 'where' => "Module='" . $modulename . "' AND LangID=" . $langid ) );
		$li = $DB->simple_exec ();

		if ($DB->fetch_row ( $li ))
		return;

		$langvalue = array ('pageTitle' => $modulename, 'main_title' => $modulename );
		$langitem = serialize ( $langvalue );
		$lang = array ('LangID' => $langid, 'Module' => $modulename, 'Value' => $langitem );
		$DB->do_insert ( 'lang_items', $lang );
	}

	function getModuleList($message = "") {
		global $vsStd,$vsFile;
		$files = $this->module->vsFile->readDir(CORE_PATH,array('.svn'));

		$this->module->getObjectsByCondition ('getClass');

		$arrayModule=$this->module->getArrayObj();

		$moduleListHTML = "";
		$count = 0;
		$files=array_reverse($files);
		foreach ( $files as $file) {
			$count++;
			$rowStyle = $count%2?"odd":"even";
			$installLink = "";
			$editLink = "";
			if(array_key_exists($file,$arrayModule)) {
				$moduleObject = $arrayModule[$file];
				$installLink = $this->html->uninstallLink($moduleObject);
				$editLink = $this->html->editLink($moduleObject);
			}
			else {
				$moduleObject = new Module();
				$moduleObject->setTitle($file);
				$moduleObject->setClass($file);
				$installLink = $this->html->installLink($moduleObject);
			}
				
			if(!file_exists(CORE_PATH.$file."/".$file."_install.php"))
			$installLink = "";

			$moduleListHTML .= $this->html->addModuleList ( $moduleObject , $editLink, $installLink, $rowStyle);
		}

		$modulehtml = $this->html->moduleList ( $message, $moduleListHTML );

		return $modulehtml;
	}

	function addEditForm($formtype = 0) {

		$formtitle = "Add More Module";
		if ($formtype)
		$formtitle = "Edit A Module";
		$addeditformhtml = $this->html->addEditModuleForm ( $formtitle, $formtype, $this->module->obj );

		return $addeditformhtml;
	}

	/**
	 * Function name: ShowModules
	 * Author: BabyWolf
	 * Written date: 10/10/2007
	 * Modified date: 12/16/2007
	 * Description: Show enabled/disabled module list.
	 *
	 * @return void
	 */
	function showModules() {
		$modulehtml = $this->getModuleList ();
		$addformhtml = $this->addEditForm ();
		$this->output = $this->html->modulesMain ($addformhtml,$modulehtml);
	}

	function getVirtualModuleList($message = "") {
		global $vsStd;

		$this->module->setCondition("moduleVirtual=1");
		$this->module->getObjectsByCondition();

		$arrayModule = $this->module->getArrayObj();

		return $arrayModule;
	}
}
?>