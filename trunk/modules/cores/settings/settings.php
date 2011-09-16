<?php
require_once (CORE_PATH . 'settings/Setting.class.php');
class settings extends VSFObject {
	public $obj;
	public $builcachesetting;
	protected $categoryField = NULL;
	public $cache_path = NULL;
	
	function __construct() {
		global $vsModule;
		parent::__construct ();
		$this->categoryField = "settingCatId";
		$this->primaryField = 'settingId';
		$this->basicClassName = 'Setting';
		$this->tableName = 'setting';
		$this->obj = $this->createBasicObject ();
		$this->cache_path = "./cache/" . APPLICATION_TYPE . ".setting.php";
		
		$this->categories = $this->vsMenu->getCategoryGroup ( "settings" );
		
		if ($vsModule->obj->getClass () != 'settings')
			$this->importCache ();
	}
	
	function convertToCatId($module) {
		global $vsLang;
		
		if ($module == $vsLang->getWords ( 'group_global_module', 'global' ))
			return 0;
		$module = strtolower ( $module );
		$categories = $this->categories->getChildren ();
		
		foreach ( $categories as $child )
			if ($child->getUrl () == $module)
				return $child->getId ();
		
		$menus = new menus ();
		$menus->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
		$menus->obj->setAlt ( $module );
		$menus->obj->setTitle ( $module );
		$menus->obj->setParentId ( $this->categories->getId () );
		$menus->obj->setIsLink ( 1 );
		$menus->obj->setIsAdmin ( - 1 );
		$menus->obj->setUrl ( $module );
		$menus->obj->setStatus ( 1 );
		$menus->obj->setLevel ( $this->categories->getLevel () + 1 );
		$menus->obj->setType ( 1 );
		$menus->insertObject ();
		$this->categories->children [$menus->obj->getId ()] = $menus->obj;
		return $menus->obj->getId ();
	
	}
	
	/*
	type: 0: global, 1: chạy theo APPLICATION_TYPE 
*/
	function getSystemKey($key, $val, $module = "", $type = 1, $root = 0, $input = "text", $intro = "") {
		global $bw, $vsMenu, $vsLang;
		$catId = 0;
		
		if (isset ( $bw->vars [$key] ))
			return $bw->vars [$key];
		
		$bw->vars [$key] = $val;
		
		$module = $module ? $module : ($bw->input ['module'] ? $bw->input ['module'] : $vsLang->getWords ( 'group_global_module', 'global' ));
		
		if ($module != $vsLang->getWords ( 'group_global_module', 'global' ))
			$catId = $this->convertToCatId ( $module );
		
		$settingType = 0;
		if ($type) {
			$settingType = 2;
			if (APPLICATION_TYPE == "admin")
				$settingType = 1;
		}
		
		$arra ['settingIntro'] = $intro;
		$arra ['settingKey'] = $key;
		$arra ['settingValue'] = $val;
		$arra ['settingCatId'] = $catId;
		$arra ['settingTitle'] = ucwords ( str_replace ( "_", " ", $key ) );
		$arra ['settingIndex'] = 0;
		$arra ['settingRoot'] = $root;
		$arra ['settingModule'] = $module;
		$arra ['settingType'] = $settingType;
		
		$this->temp_arr_setting [] = $arra;
		$this->builcachesetting = 1;
		unset ( $arra );
		
		return $bw->vars [$key];
	}
	
	function addSetting() {
		if ($this->builcachesetting == 2) {
			$this->buildCache ( "admin" );
			$this->buildCache ( "user" );
			
			return true;
		}
		
		if (is_array ( $this->temp_arr_setting ))
			foreach ( $this->temp_arr_setting as $arra ) {
				$this->obj->convertToObject ( $arra );
				$this->insertObject ();
				$this->obj->__destruct ();
			}
		
		$this->buildCache ();
		$this->getSystemKey ( 'public_menu_cache', 1, 'global', 0, 1 );
	}
	
	function buildCache($type = APPLICATION_TYPE) {
		global $bw, $DB;
		
		$settingType = ($type == "user") ? 2 : 1;
		$condition = "settingType=0 OR settingType=" . $settingType;
		$this->setCondition ( $condition );
		$array = $this->getArrayByCondition ();
		
		$vars = array ();
		foreach ( $array as $element )
			//			$vars[strtolower($element['settingKey'])] = $element['settingValue'];
			$vars [$element ['settingKey']] = $element ['settingValue'];
		
		$cachepath = "./cache/" . $type . ".setting.php";
		;
		
		$bw->vars = array_merge ( $bw->vars, $vars );
		$cache_content = "<?php\n";
		$cache_content .= "\$arraySetting = " . var_export ( $vars, true ) . ";\n";
		$cache_content .= "?>";
		$file = fopen ( $cachepath, "w" );
		fwrite ( $file, $cache_content );
		fclose ( $file );
		unset ( $vars );
	}
	
	function importCache() {
		global $bw;
		
		if (file_exists ( $this->cache_path ) && SETTINGS_CACHE) {
			require_once ($this->cache_path);
			
			if (count ( $arraySetting ))
				return $bw->vars = array_merge ( $bw->vars, $arraySetting );
		}
		
		$this->buildCache ();
	
	}
	
	function deleteByModule($module = "") {
		if (! $module)
			return false;
		$this->condition = "settingModule in (" . $module . ")";
		$this->deleteObjectByCondition ();
		
		$vsSettings->builcachesetting = 2;
		return $this->result ['status'];
	}
	
	function deleteByCatId($catId = "") {
		if (! $catId)
			return false;
		$this->condition = "settingCatId in (" . $catId . ")";
		$this->deleteObjectByCondition ();
		
		$vsSettings->builcachesetting = 2;
		return $this->result ['status'];
	}

	function setCategoryField($categoryField) {
		$this->categoryField = $categoryField;
	}
	
	function getCategoryField() {
		return $this->categoryField;
	}
	
	function getCategories() {
		return $this->categories;
	}
	
	function __destruct() {
		unset ( $this->arrayObj );
		unset ( $this->objSource );
	}
}
?>