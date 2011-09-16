<?php
if ( ! defined( 'IN_VSF' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

global $vsStd;
$vsStd->requireFile(CORE_PATH."gallerys/gallerys.php");
class gallerys_public{
	private  $output;
	private  $html;
	private $module;


	function __construct() {
		global $bw, $vsTemplate,$vsMenu;
		global $vsPrint;
		$this->module = new gallerys();
		 
		$this->html = $vsTemplate->load_template('skin_gallerys');
		
		$vsTemplate->global_template = $this->html;
		$vsTemplate->global_template->gallery = $this->module;
		
	}

	public function getOutput() {
		return $this->output;
	}

	public function setOutput($output) {
		$this->output = $output;
	}

	public function auto_run(){
		global $bw, $vsLang;

		switch ($bw->input[1]){
			case 'slideshow':
					$this->slideshow();
				break;

			case 'detail':
					$this->loadDetail($bw->input[2]);
				break;

			case 'display-album-tab':
					$this->displayGalleryTab();
				break;
				
			case 'add-edit-gallery-file':
					$this->addEditGalleryFile();
				break;
				
			case 'edit-form-file':
					$this->displayFileForm('edit',$bw->input[2]);
				break;
				
			case 'add-form-file':
					$this->displayFileForm('add',$bw->input[2]);
				break;
				
			case 'delete-file':
					$this->displayDeleteFile();
				break;

			default:
					$this->loadDefault();
				break;
		}
	}
	
	public function displayGalleryTab(){
		$galad = new gallerys_admin();
		return $this->output=$galad->displayGalleryTab();
	}

	function addEditGalleryFile(){
		global $bw,$vsStd;
		if($bw->input['oldFileId']){
			$vsStd->requireFile ( UTILS_PATH . "TextCode.class.php" );
			$objName = VSFTextCode::removeAccent ( trim ( $bw->input['fileTitle'] ), "_" );
			$this->module->vsFile->getObjectById($bw->input['oldFileId']);
			$pathOld = $this->module->vsFile->obj->getPathView();
			if($bw->input['fileId']) $this->module->vsFile->deleteFile($bw->input['oldFileId']);
			else{
				if($this->module->vsFile->obj->getTitle()!=$objName)
				{
					$this->module->vsFile->obj->setTitle($objName);
					$this->module->vsFile->updateObjectById($this->module->vsFile->obj);
				}
			}
		}
		if($bw->input['fileId']){
			$this->module->vsRelation->setObjectId($bw->input['fileId']);
			$this->module->vsRelation->setRelId($bw->input['albumId']);
			$this->module->vsRelation->setTableName($this->module->getRelTableName());
			$this->module->vsRelation->insertRel();
		}
		return $this->output = $this->displayGalleryFileList($bw->input['albumId']);
	}

	function displayGalleryFileList($albumId=0){
		$this->module->getFileByAlbumId($albumId);
		return $this->output = $this->html->displayGalleryFileList($this->module->getArrayObj(),$albumId);
	}

	function displayDeleteFile(){
		global $bw;
		$this->module->vsFile->deleteFile($bw->input[2]);
		$this->module->vsRelation->setObjectId($bw->input[2]);
		$this->module->vsRelation->setTableName($this->module->getRelTableName());
		$this->module->vsRelation->delRelByObject();
		$this->displayGalleryFileList($bw->input[3]);
	}


	public function loadDetail($objId) {
		global $bw, $vsLang, $vsStd,$vsSettings,$vsTemplate;
		
		$objId = $objId?$objId:$bw->input['fileId'];
		 
		$obj = $this->module->getObjectById($objId);
		$pass = $bw->input['pass']?md5($bw->input['pass']):'';
		if(!is_object($obj)) return $this->output = $this->html->error();
		
		$listIds=$obj->getId();
		$gallery['file'] = $this->module->getFileByAlbumId($listIds);

		$gallery['cat'] = $obj;
		$this->output = $this->html->loadVideo($gallery);
	}

	
	function loadDefault($object=null, $url="gallerys", $objIndex=1, $size=9){
		global $bw, $vsPrint, $vsLang;
		
		$url1 = "gallerys"; $index1 = 1; $size1 = 1;
		$album = $this->module->getListWithPage($url1, $index1, $size1);
		$listIds = implode(',',array_keys($album['pageList']));
		if($object){
			$listIds = $object->getId();
			$categories = $this->module->vsMenu->getCategoryById($object->getCatId());
			$url = "gallerys/detail/{$object->getId()}";
			$objIndex = 3;
		}

		$arrayFile = array();
		foreach(explode(",", $listIds) as $galleryId){
			$objIndex = 3; $size = 5;
			$url = "gallerys/detail/{$galleryId}";
			$gallery[$galleryId] = $this->module->getListFileWithPage($galleryId, $url, $objIndex, $size);
		}
		
		$this->output = $this->html->loadDefault($album, $gallery, $object);
	}

	function displayFileForm($formtype="add",$album){
		global $bw,$vsLang;
		if(is_numeric($album))
		$album= $this->module->getObjectById($album);
		if(!$album){
			$this->alertMessage($vsLang->getWords("global_none_album",'Bạn phải tạo Album trước khi sử dụng'));
			return false;
		}
		$form['type'] = $formtype;
		$form['albumId'] = $album->getId();
		$form ['formSubmit'] = $this->module->vsLang->getWords ( "file_type_{$formtype}_bt", ucwords ( $formtype ) );
		$form ['title'] = $this->module->vsLang->getWords ( "file_type_{$formtype}_title", ucwords ( $formtype ) . " File" );
		if ($form ['type']=="edit") {
			$this->module->vsFile->getObjectById($bw->input[3]);
			$form['switchform'] = '<input type="button" class="ui-state-default ui-corner-all" value="Chuyển qua form thêm mới" name="switch" id="switch-add-file-bt" />';
		}
		return $this->output = $this->html->addEditFileForm($form,$this->module->vsFile->obj,$album);
	}

}
?>