<?php
require_once(CORE_PATH."skins/Skin.class.php");
class skins extends VSFObject {
	public $obj			= NULL;
	private $cache_path	=	"./cache/cache_skins.php";
	function __construct() {
		global $vsModule;
		parent::__construct ();
		$this->primaryField = 'skinId';
		$this->tableName = 'skin';
		$this->basicClassName = 'Skin';
		$this->obj 	= $this->createBasicObject();
		$this->obj	= &$this->basicObject;
		$this->cache_path="./cache/cache_skins.php";
		if($vsModule->obj->getClass()=='skins')
			$this->getObjectsByCondition();
		else
			$this->createSkinCache();
	}

	function __destruct() {
	}

	function createSkinCache(){
		global $vsSettings;
		if( file_exists($this->cache_path) && $vsSettings->getSystemKey('use_cache_skin_wrapper', 1, 'global', 1)){
			require_once ($this->cache_path);
			return $this->arrayObj = $arraySkin;
		}
		$this->writeCache();
	}
	
	function writeCache() {
		$this->condition = 'skinDefault=1';
		$this->getObjectsByCondition("getIsAdmin");
		$cache_content  = "<?php\n";
		$cache_content .= "\$arraySkin = ".var_export($this->arrayObj,true).";\n";
		$cache_content .= "?>";
		$file = fopen($this->cache_path, "w");
		fwrite($file, $cache_content);
		fclose($file);
	}
	function getDefaultSkin() {
		global $DB;
		$this->obj=$this->arrayObj[0];
		if (APPLICATION_TYPE == 'admin') {
			$this->obj=$this->arrayObj[1];
		}
		$this->obj->setFolder (SKIN_PATH . $this->obj->getFolder ());
	}

	function updateSkin() {
		// If the new skin is set to default, set all others to not default
		if ($this->obj->getDefault ()) {
			$this->fields = array ('skinDefault' => 0 );
			$this->setCondition("skinIsAdmin={$this->obj->getIsAdmin()}");
			$this->updateObjectByCondition ();
		}
		$objObj = clone ($this->obj);
		$this->getObjectById ( $this->obj->getId () );
		// If the name of folder have been changed, change it name
		if ($objObj->getFolder () != $this->obj->getFolder ()) {
			$objRootPath = "skins/user";
			$objNewPath = $objRootPath . "/" . $objObj->getFolder ();
			$objOldPath = $objRootPath . "/" . $this->obj->getFolder ();
			rename ( $objOldPath, $objNewPath );
		}
		$this->updateObjectById ($objObj);
		$this->writeCache();
	}

	function insertSkin($inheritId = 0) {
		global $vsStd;
		// If the new skin is set to default, set all others to not default
		if ($this->obj->getDefault ()) {
			$this->fields = array ('skinDefault' => 0 );
			$this->setCondition("skinIsAdmin={$this->obj->getIsAdmin()}");
			$this->updateObjectByCondition ();
		}
		// Starting insert database
		$this->insertObject ();
		$this->writeCache();
	}
}
?>