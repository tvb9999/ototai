<?php
/*
 +-----------------------------------------------------------------------------
 |   VIET SOLUTION JSC  base on IPB Code version 2.0.0
 |	Author: BabyWolf
 |	Homepage: http://khkt.net
 |	If you use this code, please don't delete these comment line!
 |	Start Date: 21/09/2004
 |	Finish Date: 22/09/2004
 |	Modified Start Date: 07/02/2007
 |	Modified Finish Date: 10/02/2007
 +-----------------------------------------------------------------------------
 */

if ( ! defined( 'IN_VSF' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

class files_admin
{
	private $output		= "";
	private $html       = "";
	private $module     = NULL;
	function __construct() {
		global $vsTemplate;
		$this->module = new files();
		$this->base_url = $bw->base_url;
		$this->html = $vsTemplate->load_template('skin_files');
	}

	/*-------------------------------------------------------------------------*/
	// INIT
	/*-------------------------------------------------------------------------*/
	public function getOutput() {
		return $this->output;
	}

	public function setOutput($output="") {
		$this->output = $output;
	}

	function auto_run()
	{
		global $bw,$vsPrint;

		//-------------------------------------------
		// What to do?
		//-------------------------------------------
		switch($bw->input['action'])
		{
			// Mime zone
			case 'add-type':
				$this->setOutput($this->addEditTypeForm());
				break;

			case 'edit-type':
				$this->editType();
				break;

			case 'add-edit-type':
				$this->addEditType();
				break;

			case 'display-type':
				$this->displayTypePage();
				break;
			case 'delete-type':
				$this->deleteObjectById($bw->input[2]);
				$this->output = $this->GetTypeList($this->result['message']);
				break;

				// Folder zone
			case 'getfolder':
				$this->viewFolder();
				break;

			case 'addfolder':
				$this->addFolder();
				break;
					
			case 'deletefolder':
				$this->deleteFolder();
				break;
				// File zone
			case 'deletefile':
				$this->doDeleteFile();
				break;

			case 'addfile':
				$this->output = $this->AddEditFileForm();
				break;

			case 'editfile':
				$this->editFile();
				break;

			case 'addeditfile':
				$this->addEditFile();
				break;

			case 'getfilelist':
				$this->viewFile();
				break;
			case 'uploadfile':
				$this->module->uploadFile($bw->input['uploadName'],$bw->input['fileFolder']);
				break;
			case 'displayfiles':
				global $vsStd;
				 return header("location: {$bw->vars['board_url']}/javascripts/tiny_mce/plugins/imagemanager/index.php");
//				$vsPrint->boink_it("{$bw->vars['board_url']}/javascripts/tiny_mce/plugins/imagemanager/index.php");
//				die;
//				$this->displayFilePage();
				break;
			default:
				$this->loadDefault();
				break;
		}
	}

	function viewFile() {

		$this->currentPath = $this->getPath();
		$this->output = $this->GetFilesList();
	}

	function getPath() {
		global $bw;

		$thisPath = "";
		for($i=2; $i <= count(explode("/",$bw->input['vs']));$i++)
		if($bw->input[$i]) $thisPath .= $bw->input[$i]."/";
		return $thisPath;
	}

	function displayFilePage() {

		$createFormHTML = $this->html->FolderForm();
		$folderListHTML = $this->getDirectoryList();

		$output .= $this->html->FolderList($folderListHTML,$createFormHTML);

		$output .= $this->html->MainFiles($this->addEditFileForm(), $this->getFilesList());

		$this->setOutput($output);
	}

	function loadDefault() {
		global $vsPrint;
		$vsPrint->addJavaScriptString('init_tab',
			'$(document).ready(function(){$("#page_tabs").tabs({fx: { opacity: "toggle" },cache: true});});'
			);
			$this->output = $this->html->MainPage();
	}

	//===================================================
	// TYPE ZONE
	//===================================================
	function editType() {
		global $bw;

		$this->type = $this->module->getObjectById($bw->input[2]);
		$this->output = $this->addEditTypeForm('edit');
	}

	function addEditType() {
		global $bw;

		$this->type->convertToObject($bw->input);

		if($bw->input['FormType']=="edit") {
			$this->type->setId($bw->input['fileTypeID']);
			$this->updateObjectById($this->type);
		}
		else {
			$this->insertObject($this->type);
		}

		$this->output = $this->GetTypeList($this->result['message']);
	}

	function addEditTypeForm($formtype='add', $message = "") {

		$form['message'] = $message;
		$form['type'] = $formtype;
		$form ['formSubmit'] = $this->module->vsLang->getWords ( "file_type_{$formtype}_bt", ucwords ( $formtype ) );
		$form ['title'] = $this->module->vsLang->getWords ( "file_type_{$formtype}_title", ucwords ( $formtype ) . " File Type" );
		if ($form ['type']=="edit") {
			$form['switchform'] = <<<EOF
<input type="button" class="ui-state-default ui-corner-all" value="Chuyển qua form thêm mới" name="switch" onclick="vsf.get('files/add-type/','addeditform');" />
EOF;
		}
		$addEditFormHTML = $this->html->addEditTypeForm($form, $this->module->objType->type);

		return $addEditFormHTML;
	}

	function getTypeList($message = "") {
		$this->module->objType->getAllType();
		// Get main html of mime list
		return $typeHTML = $this->html->TypeList($this->module->objType->arrayType, $message);
	}

	function displayTypePage() {

		$typeFormHTML = $this->addEditTypeForm();
		$typeListHTML = $this->getTypeList();

		$returnHTML = $this->html->MainType($typeFormHTML, $typeListHTML);

		$this->setOutput($returnHTML);
	}

	//===================================================
	// FOLDER ZONE
	//===================================================
	function viewFolder() {

		$thisPath = $this->getPath();
		$this->output = $this->getDirectoryList($thisPath);
	}

	function deleteFolder() {
		global $vsLang;

		$folderPath = $this->rootPath.$this->getPath();
		if(file_exists($folderPath) && @rmdir($folderPath))
		$returnHTML = $this->html->showMessage($vsLang->getWords('folder_delete_success','Delete folder successfully!'));
		else
		$returnHTML = $this->html->showMessage($vsLang->getWords('folder_delete_fail', "Delete fail! Please check for permission or the folder have to be empty."));

		$parent_dir = substr($this->getPath(),0,strrpos(rtrim($this->getPath(),"/"),"/"));

		$returnHTML .= $this->getDirectoryList($parent_dir);

		$this->setOutput($returnHTML);
	}

	function addFolder() {
		global $bw, $vsLang;

		$dirpath = $this->rootPath.$bw->input['folderPath'].$bw->input['folderName'];

		if(!file_exists($dirpath)) {
			if(mkdir($dirpath))
			$returnHTML = $this->html->showMessage($vsLang->getWords('folder_create_success',"Created folder successfully!"));
			else
			$returnHTML = $this->html->showMessage($vsLang->getWords('folder_create_fail',"Could not create folder, please check for permission!"));
		}
		else
		$returnHTML = $this->html->showMessage($vsLang->getWords('folder_create_existed',"The folder you want to create have already existed!"));

		$returnHTML .= $this->getDirectoryList($bw->input['folderPath']);

		$this->setOutput($returnHTML);
	}

	function getDirectoryList($dir = "") {
		global $bw;
		$files = $this->module->readDir(UPLOAD_PATH. $dir."/", array('.svn'));

		$filelist = "";
		if($dir != "") {
			$thisfile['name'] = "..";
			$thisfile['path'] = substr($dir,0,strrpos(rtrim($dir,"/"),"/"))."/";
			$thisfile['icon'] = "up.png";
			$thisfile['delete'] = "<br />";
			$filelist []= $thisfile;
		}
		foreach ($files as $key=>$file) {
			$thisfile['name'] = $file;
			$thisfile['path'] = $dir.$file."/";
			$thisfile['icon'] = "folder.png";
			$thisfile['delete'] = "<a href=\"javascript:vsf.get('files/deletefolder/{$thisfile['path']}','folder-list')\"><img src='{$bw->vars['img_url']}/del.png' /></a><br />";
			$filelist[$key+1]= $thisfile;
		}
		return $this->html->FolderLink($filelist);
	}

	//===================================================
	// FILES ZONE
	//===================================================
	function doDeleteFile() {
		global $bw;

		$this->module->deleteFile($bw->input[2]);

		$this->output = $this->getFilesList($this->result['message']);
	}

	function editFile() {
		global $bw;

		$this->module->obj->setId($bw->input[2]);
		$this->module->getObjectById($this->module->obj->getId());
		$this->output = $this->AddEditFileForm('edit');
	}

	function addEditFile() {
		global $bw;
		if($bw->input['fileId']) {
			$arrayId=explode(',',$bw->input['fileId']);
			$count=0;
			foreach ($arrayId as $id) {
				$bw->input['fileId']=$id;
				$this->module->getObjectById($bw->input['fileId']);
				if($this->module->result['status']) {
					$this->module->obj->convertToObject($bw->input);
					$this->module->updateObjectById($this->module->obj);
					if($bw->input['oldFileId']&&$count==0) {
						$count++;
						$this->module->deleteFile($id);
						$this->module->setCondition("fileId={$bw->input['fileId']}");
						$this->module->updateObjectByCondition(array('fileId'=>$id));
						$sql="ALTER TABLE `".SQL_PREFIX.$this->module->getTableName()."` auto_increment = 1";
						$this->module->executeQuery($sql);
					}
				}
			}
		}
		else {
			$this->module->obj->convertToObject($bw->input);
			if($bw->input['oldFileId']) {
				$this->module->obj->setId($bw->input['oldFileId']);
				$this->module->updateObjectById($this->module->obj);
			}
			else{
				print "
					<script>
						vsf.alert('{$this->module->vsLang->getWords ( 'global_file_upload_err_no_file', 'No file was uploaded!' )}');
					</script>
				";
			}
		}

		$this->module->currentPath = $this->module->obj->getPath();
		unset($this->module->obj);

		$this->output = $this->GetFilesList($this->result['message']);
	}

	function getFilesList($message = "") {
		global $bw,$vsStd,$vsSettings;
		require_once(UTILS_PATH."TextCode.class.php");
		$textcode = new VSFTextCode();

		if(!$this->module->currentPath)
		$this->module->currentPath = $this->getPath();

		$this->module->setCondition("filePath='{$this->module->currentPath}'");
		$this->module->setOrder('fileId desc');
		$end = $this->module->getNumberOfObject();
		$size = $vsSettings->getSystemKey("admin_file_list_number",10);
		$limit = array();
		if($end > $size){
			// Build page link for product list
			$vsStd->requireFile(LIBS_PATH.'Pagination.class.php');
			$pagination = new VSFPagination();
			$pagination->ajax 				= 1;
			$pagination->callbackobjectId 	= 'area-panel';
			$pagination->url 				= "files/getfilelist/$this->module->currentPath/";
			$pagination->p_Size 			= $size;
			$pagination->p_TotalRow 		= $end ;
			$pagination->SetCurrentPage(4);
			$pagination->BuildPageLinks();
			$limit = array($pagination->p_StartRow,$pagination->p_Size);
		}
		$this->module->setLimit($limit);
		$this->module->getObjectsByCondition();
		$option['paging'] = $pagination->p_Links;
		$option['mainTitle'] = $this->module->vsLang->getWords('discover_main_title','Discover Item List');
		$option['message'] = $message;

		return $this->html->fileList($this->module->getArrayObj(), $message);
	}

	function addEditFileForm($formtype="add", $message = "") {
		$form['message'] = $message;
		$form['type'] = $formtype;
		$form ['formSubmit'] = $this->module->vsLang->getWords ( "file_type_{$formtype}_bt", ucwords ( $formtype ) );
		$form ['title'] = $this->module->vsLang->getWords ( "file_type_{$formtype}_title", ucwords ( $formtype ) . "Thêm một file mới" );
		if ($form ['type'])
		$form['switchform'] = '<input type="button" class="ui-state-default ui-corner-all" value="Chuyển qua form thêm mới" name="switch" id="switch-add-file-bt" />';
			
		$form['filepath'] = $this->module->obj->getPath();

		$addeditformhtml = $this->html->addEditFileForm($form, $this->module->obj);

		return $addeditformhtml;
	}
}
?>