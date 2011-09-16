<?php
class Skin extends BasicObject {
	private $folder = NULL;
	private $default = NULL;
	private $isAdmin = NULL;
	private $authorName = NULL;
	private $authorEmail = NULL;
	private $authorWebsite = NULL;

	function __construct() {
		parent::__construct ();
	}

	function __destruct() {
		parent::__destruct ();
		unset ( $this->folder );
		unset ( $this->default );
		unset ( $this->authorName );
		unset ( $this->authorEmail );
		unset ( $this->authorWebsite );
	}

	function getAuthorEmail() {
		return $this->authorEmail;
	}
	/**
	 * @param $isAdmin the $isAdmin to set
	 */
	function setIsAdmin($isAdmin) {
		$this->isAdmin = $isAdmin;
	}

	/**
	 * @return the $isAdmin
	 */
	function getIsAdmin($option=null) {
		if($option=='text')
		{	$array= array('User','Admin');
		return $array[$this->isAdmin];
		}
		return $this->isAdmin;
	}
	function __set_state($array=array()) {
		$menu = new Skin();
		foreach ($array as $key => $value) {
			$menu->$key = $value;
		}
		return $menu;
	}
	function getAuthorName() {
		return $this->authorName;
	}

	function getAuthorWebsite() {
		return $this->authorWebsite;
	}

	function getDefault() {
		return $this->default;
	}

	function getFolder() {
		return $this->folder;
	}
	function setAuthorEmail($authorEmail) {
		$this->authorEmail = $authorEmail;
	}

	function setAuthorName($authorName) {
		$this->authorName = $authorName;
	}

	function setAuthorWebsite($authorWebsite) {
		$this->authorWebsite = $authorWebsite;
	}

	function setDefault($default) {
		$this->default = $default;
	}

	function setFolder($folder) {
		$this->folder = $folder;
	}

	function convertToDB() {
		isset ( $this->id ) ? ($dbobj ['skinId'] = $this->id) : '';
		isset ( $this->title ) ? ($dbobj ['skinTitle'] = $this->title) : '';
		isset ( $this->isAdmin ) ? ($dbobj ['skinIsAdmin'] = $this->isAdmin) : '';
		isset ( $this->folder ) ? ($dbobj ['skinFolder'] = $this->folder) : '';
		isset ( $this->status ) ? ($dbobj ['skinStatus'] = $this->status) : '';
		isset ( $this->default ) ? ($dbobj ['skinDefault'] = $this->default) : '';
		isset ( $this->authorName ) ? ($dbobj ['skinAuthorName'] = $this->authorName) : '';
		isset ( $this->authorEmail ) ? ($dbobj ['skinAuthorEmail'] = $this->authorEmail) : '';
		isset ( $this->authorWebsite ) ? ($dbobj ['skinAuthorWebsite'] = $this->authorWebsite) : '';
		return $dbobj;
	}

	function convertToObject($object) {
		global $vsMenu;
		isset ( $object ['skinId'] ) ? $this->setId ( $object ['skinId'] ) : '';
		isset ( $object ['skinTitle'] ) ? $this->setTitle ( $object ['skinTitle'] ) : '';
		isset ( $object ['skinIsAdmin'] ) ? $this->setIsAdmin( $object ['skinIsAdmin'] ) : '';
		isset ( $object ['skinFolder'] ) ? $this->setFolder ( $object ['skinFolder'] ) : '';
		isset ( $object ['skinStatus'] ) ? $this->setStatus ( $object ['skinStatus'] ) : '';
		isset ( $object ['skinDefault'] ) ? $this->setDefault ( $object ['skinDefault'] ) : '';
		isset ( $object ['skinAuthorName'] ) ? $this->setAuthorName ( $object ['skinAuthorName'] ) : '';
		isset ( $object ['skinAuthorEmail'] ) ? $this->setAuthorEmail ( $object ['skinAuthorEmail'] ) : '';
		isset ( $object ['skinAuthorWebsite'] ) ? $this->setAuthorWebsite ( $object ['skinAuthorWebsite'] ) : '';
	}

	function validate() {
		global $vsLang;
		$status = true;
		if ($this->getTitle () == "") {
			$status = false;
			$this->message = $vsLang->getWords ( 'skin_err_name_blank', "Skin name cannot be blank!<br />" );
		}
		if ($this->getFolder () == "") {
			$status = false;
			$this->message .= $vsLang->getWords ( 'skin_err_folder_blank', "Folder name cannot be blank!" );
		}
		return $status;
	}
}
?>