<?php
/**
 *
 * @author Sanh Nguyen
 * @version 1.0 RC
 */
require_once (CORE_PATH . "gallerys/Gallery.class.php");
class gallerys extends VSFObject {
	public $obj;
	protected $categories = array ();
	protected $relTableName;

	function __construct() {
		global $DB;
		parent::__construct ();
		$this->primaryField = 'galleryId';
		$this->basicClassName = 'Gallery';
		$this->tableName = 'gallery';
		$this->obj = $this->createBasicObject ();
		$this->relTableName = "rel_gallery_file";
		$this->categories = $this->vsMenu->getCategoryGroup ("gallerys" );
		if (! $DB->field_exists ( 'galleryPassWord', $this->tableName ))
			$DB->sql_add_field ( $this->tableName, 'galleryPassWord', 'varchar(32)' );
		if (! $DB->field_exists ( 'galleryImage', $this->tableName ))
			$DB->sql_add_field ( $this->tableName, 'galleryImage', 'int(10)' );
	}

	function getRelTableName() {
		return $this->relTableName;
	}

	function setRelTableName($relTableName) {
		$this->relTableName = $relTableName;
	}

	function getCategories() {
		return $this->categories;
	}

	function setCategories($categories) {
		$this->categories = $categories;
	}

	function getAlbumByCode($code = null) {
		if (! $code)
			return;
		$strIds = $this->vsMenu->getChildrenIdInTree ( $this->getCategories () );
		$this->condition = "galleryCatId in ({$strIds}) and galleryCode = '{$code}'";
		$this->getOneObjectsByCondition ();
		
		return $this->getFileByAlbumId ( $this->obj->getId () );
	}

	function getFileByAlbumId($albumId, $groupFile = 0, $groupName = NULL) {
		$this->vsRelation->setRelId ( $albumId );
		$this->vsRelation->setTableName ( $this->getRelTableName () );
		$fileId = $this->vsRelation->getObjectByRel ( true );
		if ($fileId) {
			$this->vsFile->setOrder ( "fileIndex asc" );
			$this->vsFile->setCondition ( "fileId in({$fileId})" );
			$this->vsFile->getObjectsByCondition ();
			$arrayFile = $this->vsFile->getArrayObj ();
		}
		$array = $this->vsRelation->arrval;
		if ($groupFile) {
			$groupFile = array ();
			foreach ( $this->vsRelation->arrval as $group ) {
				if ($arrayFile [$group ['objectId']])
					$groupFile [is_array ( $groupName ) ? $groupName [$group ['relId']]->getCode () : $group ['relId']] [$group ['objectId']] = $arrayFile [$group ['objectId']];
			}
			return $this->arrayObj = $groupFile;
		}
		return $this->arrayObj = $arrayFile;
	}

	function getListFileWithPage($albumId, $url = "gallerys", $objIndex = 3, $size = 2) {
		$this->vsRelation->setRelId ( $albumId );
		$this->vsRelation->setTableName ( $this->getRelTableName () );
		$fileId = $this->vsRelation->getObjectByRel ();
		if ($fileId) {
			$this->vsFile->setCondition ( "fileId in({$fileId})" );
			$this->vsFile->setOrder ( "fileId DESC, fileIndex DESC" );
			return $this->vsFile->getPageList ( $url, $objIndex, $size );
		}
	}

	function getNewList($size = 9) {
		$option = $this->getListWithPage ();
		$listIds = implode ( ',', array_keys ( $option ['pageList'] ) );
		$this->vsRelation->setRelId ( $listIds );
		$this->vsRelation->setTableName ( $this->getRelTableName () );
		$fileId = $this->vsRelation->getObjectByRel ();
		if ($fileId) {
			$this->vsFile->setCondition ( "fileId in({$fileId})" );
			$this->vsFile->setOrder ( "fileId DESC, fileIndex DESC" );
			$this->vsFile->setLimit ( array (0, $size ) );
			return $this->vsFile->getObjectsByCondition ();
		}
	}

	function getAlbumById($albumId = 0, $tableName = "", $groupFile = 0) {
		if (intval ( $albumId ) or ! $tableName)
			return;
		$this->vsRelation->setRelId ( $albumId );
		$this->vsRelation->setTableName ( $tableName );
		$strId = $this->vsRelation->getObjectByRel ();
		return $this->getFileByAlbumId ( $strId, $groupFile );
	}

	function getListWithPage($url = "gallerys", $objIndex = 2, $size = 5) {
		$strIds = $this->vsMenu->getChildrenIdInTree ( $this->getCategories () );
		$this->setCondition ( "galleryStatus>0" );
		$this->setOrder ( "galleryId DESC, galleryIndex DESC" );
		return $this->getPageList ( $url, $objIndex, $size );
	}

	function getListWithCat($treeCat,$url = NULL,$objIndex = 20,$size = 5) {
		global $vsSettings,$bw;
		if (! is_object ( $treeCat ))
			return false;
		$strIds = $this->vsMenu->getChildrenIdInTree ( $treeCat );
		$this->setCondition ( "galleryStatus>0 and galleryCatId in($strIds)" );
		$this->setOrder ( "galleryId DESC, galleryIndex DESC" );
		$this->setLimit(array(0,$size));
		$gallery = $this->getPageList($url,$objIndex,$vsSettings->getSystemKey ( 'gallerys_category_capability', 10,'gallerys'));
		
		foreach ($gallery['pageList'] as $key=>$value){
			$this->vsFile->setLimit (array(0,$vsSettings->getSystemKey ( 'gallerys_file_capability', 4,'gallerys')));
			$file = $this->getFileByAlbumId($key,1);
			$gallery['pageList'][$key]->file = $file[$key];
		}
		return $gallery;
	}

	function __destruct() {
		unset ( $this );
	}
}

