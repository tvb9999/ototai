<?php
/*
 +-----------------------------------------------------------------------------
 |   VIET SOLUTION SJC  base on IPB Code version 3.0.0
 |	Author: tongnguyen
 |	Start Date: 5/04/2009
 |	Finish Date: 11/04/2009
 |	moduleName Description: This module is for management all languages in system.
 +-----------------------------------------------------------------------------
 */

class languages {
	public $arrayLang = array();
	public $language;
	public $condition = "";
	public $itempath = "";
	public $langtype = "user";
	public $result = array();
	public $objsource = "";

	function __construct() {
		$this->language = new Lang();
		$this->objsource = "langs";
		$this->getAllLang();
	}

	public function getLangByObject($methods=array(),$langArry=array()) {
		foreach ($methods as $method)
			foreach ($langArry as $lang){
				if($lang->$method() != $this->language->$method())
					unset($langArry[$lang->getId()]);
			}
		return $langArry;
	}

	// Get a Lang by specific ID
	function getLangById($langId=0) {
		global $vsLang;

		$this->result['status'] = true;
		$this->language->setId($langId);

		if(!isset($this->arrayLang[$this->language->getId()])) {
			$this->result['status'] = false;
			$this->result['message'] = $vsLang->getWords('lang_no_id','There is no item with specified ID!');
			return;
		}
//		print "<pre>";
//		print_r($this->language->getId());
//		print "</pre>";
//print "<pre>";
//print_r($this->arrayLang);
//print "</pre>";
		return $this->arrayLang[$this->language->getId()];
	}

	/**
	 * Delete a Lang with specific ID
	 * @name Delete language Lang
	 * @author BabyWolf
	 * @param int $this->id
	 * @return array $this->result with status and message
	 */
	function deleteLanguges($langId=0) {
		global $DB;

		$this->result['status'] = true;

		// Check if the Lang want to delete exist
		$this->language = $this->getLangById($langId);
		if(!$this->result['status']) return; // return if the Lang does not exist

		// Check if this language is default
		if($this->default) {
			$this->result['status'] = false;
			$this->result['message'] = "The language Lang [".$this->language->getName()."] is using as default, you can not delete it. There must be one default language.";
			return; // return if it is default
		}

		// Start delete when Lang exist
		$DB->simple_delete($this->objsource, "langId=".$this->language->getId());
		if($DB->simple_exec()) {
			$this->result['message'] = "You have successfully deleted language Lang [".$this->language->getName()."].";
		}
		else {
			// If the delete query is fail
			$this->result['status'] = false;
			$this->result['message'] = "There is an error when deleted a Lang!";
		}
	}

	function validateLang($duplicate = false) {
		global $DB;

		// Init status and message
		$this->result['status'] = true;
		$this->result['message'] = "";

		// Check if name of Lang is blank
		if($this->language->getName() == "") {
			$this->result['status'] = false;
			$this->result['message'] .= "Lang name can't be left blank!<br>";
		}

		// Check if folder name of Lang is blank
		if($this->language->getFolderName() == "") {
			$this->result['status'] = false;
			$this->result['message'] .= "Folder name can't be left blank!<br>";
		}

		// Check if folder name has already exist with same type
		$where = "langFolder='".$this->language->getFolderName()."'";
		if($duplicate) $where .= " AND langId!=".$this->language->getId();

		$DB->simple_construct(array('select'	=> '*',
									'from'		=> 'langs',
									'where'		=> $where
		)
		);
		//		print 'toidayu'.$DB->cur_query;exit;
		$DB->simple_exec();

		if($DB->fetch_row()) {
			$this->result['status'] = false;
			$this->result['message'] .= "With this type, folder name has already existed!<br>";
		}
			
		// Check if Lang name has already existed with same type
		$where = "langName='".$this->language->getName()."'";
		if($duplicate) $where .= " AND langId!=".$this->language->getId();

		$DB->simple_construct(array('select'	=> '*',
									'from'		=> 'langs',
									'where'		=> $where
		)
		);
		$DB->simple_exec();
		if($DB->fetch_row()) {
			$this->result['status'] = false;
			$this->result['message'] .= "With this type, the Lang name has already existed!<br>";
		}
	}

	// Get flag file name from flags directory
	function getLanguageSymbol() {
		global $vsSkin;

		$flagspath = ROOT_PATH.$vsSkin->obj->getFolder()."/images/flags";
		$dh = opendir($flagspath);

		$flags = array();
		while($file=readdir($dh)) {
			if($file=="." || $file==".." || $file==".svn" || is_dir($flagspath.$file)) continue;
				$flags[] = array('name'=>substr($file,0,-4),'value' => $file);
		}

		return $flags;
	}

	function updateLang() {
		global $vsLang, $DB;

		// Validate object's properties
		//		$this->validateLang(true);

		// If the result of validation is fail => return
		//		if(!$this->result['status']) return;

		// Init the result
		$this->result['status'] = true;
		$this->result['message'] = "";

		// Set database values
		$dbojb = $this->language->convertToDB();

		// Update all rest Lang to not default if this one is default
		if($this->language->getUserDefault())
		$DB->do_update($this->objsource,array('userDefault'=>0));
		if($this->language->getAdminDefault())
		$DB->do_update($this->objsource,array('adminDefault'=>0));
		// Update the object with specific ID

		if($DB->do_update($this->objsource,$dbojb,"langId=".$this->language->getId())){
			$this->result['message'] .= $vsLang->getWords('language_update_success','You have successfully update the field!');
		}
		else {
			$this->result['status'] = false;
			$this->result['message'] .= $vsLang->getWords('languafe_update_fail','There is an error when update field!');
		}
	}

	function insertLang() {
		global $vsLang, $DB;

		// Validate the object's properties
		$this->validateLang();

		// If the result of validation is fail => return
		if(!$this->result['status']) return;

		// Init the result
		$this->result['status'] = true;
		$this->result['message'] = "";

		// Set database values
		$dbojb = $this->language->convertToDB();

		// Insert the object to database
		if($DB->do_insert($this->objsource,$dbojb))
		$this->result['message'] = $vsLang->getWords('language_insert_success','You have successfully insert the field!');
		else {
			$this->result['status'] = false;
			$this->result['message'] = $vsLang->getWords('languafe_insert_fail','There is an error when insert field!');
		}
	}

	function getAllLang() {
		global $DB;

		$this->arrayLang = array();
		$DB->simple_construct(
				array(
									'select'	=> '*',
									'from'		=> $this->objsource,
									'order'		=> 'langId',
									'where'		=> $this->condition
				)
		);
		$DB->simple_exec();
		while($lang = $DB->fetch_row()) {
			$oLang = new Lang();
			$oLang->convertToObject($lang);
			$this->arrayLang[$oLang->getId()] = $oLang;
		}
	}


	function setItemFilePath() {
		$lang = $this->getLangById($this->language->getId());
		$this->langpath = $lang->getLangPath();
		if($this->result['status'])
		$this->itempath = $lang->getLangPath().'/'.$this->langtype."/".$this->language->getModule().".lang";
		$_SESSION['pathLang'] = $this->itempath;
	}

	function deleteItemFile() {
		$this->setItemFilePath();
		if(!$this->result['status']) return;
		// Start delete the item file
		if(!@unlink($this->itempath)) {
			$this->result['status'] = false;
			$this->result['message'] = "There was an error when delete item file! Please check for permission or if the file exist.";
			return;
		}

		$this->result['status'] = true;
		$this->result['message'] = "You have successfully deleted item file [".$this->language->getModule()."]";
	}

	function writeItemToFile() {
		global $bw, $DB, $std, $print;

		$this->setItemFilePath();

		if(!$this->result['status']) return;
		if(!file_exists($this->langpath)) {
			mkdir($this->langpath,0777);
		}

		// Build file content
		$content = "<?php\n";
		$content .= "\$lang = ";
		$content .= var_export($this->language->getValue(),true);
		$content .= ";\n";
		$content .= "?>";

		if(!$wf = @fopen($this->itempath,"w")) {
			$this->result['status'] = false;
			$this->result['message'] = "The system can't open streaming for writing! Please check folder permission.";
			return;
		}

		if(!@fwrite($wf,$content)) {
			$this->result['status'] = false;
			$this->result['message'] = "There was an error when writing file! Please check file permission.";
			return;
		}

		if(!@fclose($wf)) {
			$this->result['status'] = false;
			$this->result['message'] = "There was an error when close file streaming! Please check file permission.";
			return;
		}
		$this->result['status'] = true;
		$this->result['message'] = "You have successfully write lang item file.";
	}

	function getAllItemInLang($langId=0,$type='user') {
		$this->result['status'] = true;
		$this->result['message'] = "";

		$this->language = $this->getLangById($langId);

		if(!$this->result['status']) return;
		if(!$dh = @opendir($this->language->getLangPath()."/{$type}")) {
			$this->result['status'] = false;
			$this->result['message'] = "Can't open the directory. for Folder [ {$type} ]Please check for permission or if it exist.";
			return;
		}

		$items = array();
		while($file=readdir($dh)) {
				
			if(	$file=="."
			|| $file==".."
			|| is_dir($this->language->getLangPath().$file)
			|| substr($file,strpos($file,"."))!=".lang")
			continue;
				
			$items[] = substr($file,0,strpos($file,"."));
		}
		return $items;
	}

}
?>