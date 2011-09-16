<?php
require_once (CORE_PATH . "/menus/Menu.class.php");

class menus extends VSFObject {
	public $arrayTreeMenu = array ();
	public $arrayMenu = array ();
	public $arrayTreeCategory = array ();
	public $arrayCategory = array ();
	public $arraySelected = array ();
	public $obj;
	public $filtercondition = array ();
	public $objsource = "menu";
	public $result = array ();
	public $displayType = "text";

	function __construct() {
		parent::__construct ();
		$this->primaryField = 'menuId';
		$this->basicClassName = 'Menu';
		$this->tableName = 'menu';
		$this->obj = $this->createBasicObject ();
		
		$this->getAllMenu ();
	}

	function __destruct() {
		unset ( $this->arrayTreeMenu );
	}

	function copyArrayTreeMenu() {
		return $this->arrayTreeMenu;
	}

	function getCategoryById($categoryId = 0) {
		$this->result ['status'] = true;
		$categoryId = intval ( $categoryId ) ? $categoryId : 0;
		
		if (! isset ( $this->arrayCategory [$categoryId] )) {
			$this->result ['status'] = false;
			$this->result ['message'] = "There is no item with specified ID!";
			return;
		}
		$obj = $this->arrayCategory [$categoryId];
		$this->obj = $obj;
		return $obj;
	}

	function getMenuById($objId) {
		$this->result ['status'] = true;
		$objId = intval ( $objId ) ? $objId : 0;
		if (! isset ( $this->arrayMenu [$objId] )) {
			$this->result ['status'] = false;
			$this->result ['message'] = "There is no item with specified ID!";
			return false;
		}
		
		return $this->obj = $this->arrayMenu [$objId];
	}

	function addEditCategory() {
		global $bw, $vsSettings;
		$categoryObj = new Menu ();
		$categoryObj->setTitle ( $bw->input ['categoryName'] );
		$categoryObj->setStatus ( $bw->input ['categoryStatus'] );
		$categoryObj->setType ( 0 );
		isset ( $bw->input ['categoryDesc'] ) ? $categoryObj->setAlt ( $bw->input ['categoryDesc'] ) : '';
		$categoryObj->setStatus ( $bw->input ['categoryIsVisible'] );
		
		if ($vsSettings->getSystemKey ( $bw->input ['categoryGroup'] . '_status', 1 ) == 0)
			$categoryObj->setStatus ( 1 );
		
		if ($vsSettings->getSystemKey ( $bw->input ['categoryGroup'] . '_value', 1 ))
			$categoryObj->setIsLink ( $bw->input ['categoryValue'] );
		
		$categoryObj->setIsDropdown ( $bw->input ['categoryIsDropdown'] );
		$categoryObj->setIndex ( $bw->input ['categoryIndex'] );
		$categoryObj->setIsAdmin ( - 1 );
		if ($bw->input ['fileId'])
			$categoryObj->setFileId ( $bw->input ['fileId'] );
		
		// Set the parent Id
		// If choose root, the parent will be the root category of this module and its level is 2
		if ($bw->input ['categoryParentId'] == 0) {
			$parentCategory = $this->getCategoryGroup ( $bw->input ['categoryGroup'] );
			$categoryObj->setParentId ( $parentCategory->getId () );
			$categoryObj->setLevel ( 2 );
		} else {
			$parentCategory = $this->getCategoryById ( $bw->input ['categoryParentId'] );
			// Set the parent Id
			$categoryObj->setParentId ( $parentCategory->getId () );
			// The level of the category will be its parent's + 1
			$categoryObj->setLevel ( $parentCategory->getLevel () + 1 );
			$categoryObj->setUrl ( $parentCategory->getUrl () );
		}
		
		if (! empty ( $bw->input ['categoryId'] )) {
			$menus = $this->getCategoryById ( $bw->input ['categoryId'] );
			if ($bw->input ['fileId'])
				$this->vsFile->deleteFile ( $menus->getFileId () );
			$categoryObj->setId ( $bw->input ['categoryId'] );
			$this->obj = $categoryObj;
			$this->obj->setUrl ( $menus->getBackup () );
			$this->obj->setBackup ( "" );
			$this->updateMenu ();
		} else {
			$this->obj = $categoryObj;
			$this->insertMenu ();
		}
		$this->getAllMenu ();
	}

	//Modify by Luu Quang Vu
	function deleteCategoryById($categoryIds = "") {
		global $DB, $bw;
		$cat = $this->getObjectById ( $categoryIds );
		if (! is_object ( $cat )) {
			$this->result ['message'] = "There is an error when deleted!";
			return $this->result ['status'] = false;
		}
		$categoryIds;
		$this->deleteObjectById ( $categoryIds );
		$this->condition = "{$this->primaryField} IN (" . $categoryIds . ")";
		$this->vsFile->deleteFile ( $cat->getFileId () );
		if ($this->deleteObjectByCondition ()) {
			unset ( $this->arrayTreeCategory );
			unset ( $this->arrayCategory );
			$this->getAllMenu ();
			$this->buildCacheMenu ();
		}
	}

	function deleteMenuById($objId = 0) {
		global $DB;
		$cat = $this->getObjectById ( $objId );
		$this->condition = "{$this->primaryField} IN (" . $objId . ")";
		$this->deleteObjectByCondition ();
		$this->vsFile->deleteFile ( $cat->getFileId () );
	}

	function changeIndex($arrayCat = null, $property = "id") {
		if (! $arrayCat && count ( $arrayCat ))
			return;
		$array = array ();
		foreach ( $arrayCat as $cat )
			$array [$cat->$property] = $cat;
		return $array;
	}

	function updateMenu() {
		global $DB, $vsLang;
		
		$this->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
		$this->updateObjectById ( $this->obj );
	}

	function insertMenu() {
		global $vsLang, $DB;
		$this->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
		$this->insertObject ( $this->obj );
	}

	function getCategoryGroup($categoryGroup = '', $extent = '') {
		global $vsStd;
		
		if (! $categoryGroup)
			return false;
		if (count ( $this->arrayTreeCategory ) < 1)
			return false;
		$this->createBasicObject ();
		$this->obj->setUrl ( $categoryGroup );
		
		reset ( $this->arrayTreeCategory );
		$categories = $this->arrayTreeCategory [15];
		if ($categoryGroup != 'systemsettings') {
			$this->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
			$option = array ('url' => true, 'langId' => true );
		} else {
			$option = array ('url' => true );
		}
		
		if ($extent) {
			$option = array_merge ( $option, $extent );
			$result = $this->filterMenu ( $option, $categories->getChildren () );
		}
		else $result = $this->filterMenu ( $option, $categories->getChildren (),1 );
		reset ( $result );
		
		$thisCategory = current ( $result );
		
		if ($extent ['no-create'])
			return $thisCategory;
		
		if (! is_object ( $thisCategory )) {
			$this->setCondition ( "langId =" . $this->obj->langId . " and menuIsAdmin=-1 and menuUrl = '{$this->obj->url}'" );
			$thisCategory = $this->getOneObjectsByCondition ();
			if (! is_object ( $thisCategory ))
				$thisCategory = $this->createRootCategory ( $categoryGroup );
		}
		
		return $thisCategory;
	}

	function getSubCategory($categoryGroup = '') {
		$query = explode ( '/', $categoryGroup );
		$categoryGroup = $query [0];
		return $this->getCategoryGroup ( $categoryGroup, array ('no-create' => 1 ) );
	}

	public function createRootCategory($categoryGroup = "",$parrent = 15, $level = 1, $type = 0 , $alt = 'menu') {
		$this->obj->__destruct ();
		$this->obj->setTitle ( $categoryGroup );
		$this->obj->setUrl ( $categoryGroup ); // Category url will be the modules url
		$this->obj->setType ( $type ); // Menu type of category is internal
		$this->obj->setAlt ( $alt );
		$this->obj->setStatus ( 1 );
		$this->obj->setIsDropdown ( 1 );
		$this->obj->setIndex ( 0 );
		$this->obj->setIsLink ( 1 );
		$this->obj->setIsAdmin ( - 1 ); // Category will have menu type -1
		$this->obj->setParentId ( $parrent );
		$this->obj->setLevel ( $level );
		$this->insertMenu ();
		return $this->obj;
	}

	function getAllMenu($user = 'admin') {
		global $DB, $bw;
		$query = array ('select' => '*', 'from' => $this->objsource, 'order' => 'menuLevel DESC, menuIndex' );
		
		if (APPLICATION_TYPE == 'user' || $user == 'user') {
			$query ['where'] = "menuIsAdmin<>1 AND menuStatus=1";
			$arrayTreeMenu = array ();
			$arrayMenu = array ();
			$arrayTreeCategory = array ();
			$arrayCategory = array ();
			if (file_exists ( CACHE_PATH . "menus.cache" )) {
				require_once (CACHE_PATH . "menus.cache");
				$this->arrayTreeMenu = $arrayTreeMenu;
				$this->arrayMenu = $arrayMenu;
				$this->arrayTreeCategory = $arrayTreeCategory;
				$this->arrayCategory = $arrayCategory;
				return;
			}
		}
		$DB->simple_construct ( $query );
		$DB->simple_exec ();
		
		while ( $obj = $DB->fetch_row () ) {
			$createMenu = new Menu ();
			$createMenu->convertToObject ( $obj );
			// If element is Category type, put them in array of Category
			if ($createMenu->getIsAdmin () == - 1) {
				$this->arrayCategory [$createMenu->getId ()] = $createMenu;
				if($createMenu->getStatus() == 1 ) $this->arrayTreeCategorys [$createMenu->getId ()] = $createMenu;
				$createMenu = new Menu ();
				$createMenu->convertToObject ( $obj );
				$this->arrayTreeCategory [$createMenu->getId ()] = $createMenu;
				
			} else {
				$this->arrayMenu [$createMenu->getId ()] = $createMenu;
				$createMenu = new Menu ();
				$createMenu->convertToObject ( $obj );
				$this->arrayTreeMenu [$createMenu->getId ()] = $createMenu;
			}
			unset ( $createMenu );
		}
		// build tree structure for menus
		$this->buildTreeStructure ( &$this->arrayTreeMenu );
		// build tree structure for category
		$this->buildTreeStructure ( &$this->arrayTreeCategory );
		$this->buildTreeStructure ( &$this->arrayTreeCategorys );
		$this->buildCacheMenu ();
	}

	function buildTreeStructure($array = array()) {
		foreach ( $array as $element ) {
			if ($element->getLevel ()) {
				if (isset ( $array [$element->getParentId ()] )) {
					$array [$element->getParentId ()]->setChild ( $element );
					unset ( $array [$element->getId ()] );
				}
			}
		}
	}

	/**
	 * Filter array tree menu
	 * @param array $properties (property_name => true/false)
	 * @param array $objs array of tree menu
	 * @param int $recursiveLevel for limit the recursive loop
	 * @return array $objs that have filtered
	 */
	function filterMenu($properties = array(), $objs, $recursiveLevel = 0, $currentLevel = 0) {
		$currentLevel ++;
		if ($recursiveLevel >= 1 && $recursiveLevel < $currentLevel)
			return;
		foreach ( $properties as $property => $value ) {
			foreach ( $objs as $obj ) {
				if (! isset ( $obj->$property ))
					continue;
				if (is_bool ( $value ))
					$this->boolFilter ( $value, $property, $properties, $obj, &$objs, $recursiveLevel, $currentLevel );
				else
					$this->arrayFilter ( $value, $property, $properties, $obj, &$objs, $recursiveLevel, $currentLevel );
			}
		}
		return $objs;
	}

	function arrayFilter($value, $property, $properties, $obj, $objs, $recursiveLevel, $currentLevel) {
		if ($value [0]) {
			if (! in_array ( $obj->$property, $value [1] )) {
				unset ( $objs [$obj->id] );
			} elseif (count ( $obj->getChildren () )) {
				$this->filterMenu ( $properties, &$objs [$obj->getId ()]->children, $recursiveLevel, $currentLevel );
			}
		} else {
			if (in_array ( $obj->$property, $value [1] )) {
				unset ( $objs [$obj->id] );
			} elseif (count ( $obj->getChildren () )) {
				$this->filterMenu ( $properties, &$objs [$obj->getId ()]->children, $recursiveLevel, $currentLevel );
			}
		}
	}

	function boolFilter($value, $property, $properties, $obj, $objs, $recursiveLevel, $currentLevel) {
		if ($value) {
			if ($obj->$property != $this->obj->$property)
				unset ( $objs [$obj->id] );
			elseif (count ( $obj->getChildren () )) {
				$this->filterMenu ( $properties, &$objs [$obj->getId ()]->children, $recursiveLevel, $currentLevel );
			}
		} else {
			if ($obj->$property == $this->obj->$property)
				unset ( $objs [$obj->id] );
			elseif (count ( $obj->getChildren () )) {
				$this->filterMenu ( $properties, &$objs [$obj->getId ()]->children, $recursiveLevel, $currentLevel );
			}
		}
	}

	function buildOptionMenuTree($oMenus = array(), $html = "", $option = array(), $count = 0) {
		global $vsLang;
		if (! is_array ( $oMenus ))
			return;
		if ($count > 0)
			$option ['listStyle'] = " &nbsp; &nbsp; &nbsp;" . $option ['listStyle'];
		
		foreach ( $oMenus as $oMenu ) {
			if (isset ( $option ['selected'] ) && $oMenu->id == $option ['selected'])
				$selected = "selected ";
			if ($option ['index'])
				$html .= '<option ' . $selected . 'value="' . $oMenu->id . '" >' . $option ['listStyle'] . ' ' . $oMenu->title . ' (' . $oMenu->getIndex () . ' - ' . $oMenu->id . ')</option>';
			else
				$html .= '<option ' . $selected . 'value="' . $oMenu->id . '" >' . $option ['listStyle'] . ' ' . $oMenu->title . '</option>';
			
			if (is_array ( $oMenu->getChildren () )) {
				$count ++;
				$this->buildOptionMenuTree ( $oMenu->getChildren (), &$html, $option, $count );
			}
		}
	}

	/**
	 * Build select box html by BabyWolf
	 * @param array $objs of Menu object
	 * @param array $option[id,size,class,multiple,listStyle] for select tag
	 * @return $html of select box
	 */
	function displaySelectBox($objs = array(), $option = array()) {
		global $vsLang;
		
		// Build select tag
		$html = '<select id="' . $option ['id'] . '" style="width: 100%;"';
		$html .= $option ['size'] ? ' size="' . $option ['size'] . '"' : '';
		$html .= $option ['class'] ? ' class="' . $option ['class'] . '"' : '';
		$html .= $option ['multiple'] ? ' multiple' : '';
		$html .= $option ['name'] ? ' name="' . $option ['name'] . '"' : '';
		$html .= $option ['onclick'] ? ' onclick="' . $option ['onclick'] . '()"' : '';
		$html .= '><option value="0">' . $vsLang->getWords ( 'menus_option_root', "Root" ) . '</option>';
		isset ( $option ['index'] ) ? "" : $option ['index'] = 1;
		$this->buildOptionMenuTree ( $objs, &$html, $option );
		$html .= "</select>";
		return $html;
	}

	function getMenuForUser() {
		global $vsLang;
		$this->obj->setLangId ( $vsLang->currentLang->getId () );
		$this->obj->setIsAdmin ( 0 );
		$this->obj->setStatus ( 1 );
		$this->obj->setTitle ( 'Categories' );
		$result = $this->filterMenu ( array ('isAdmin' => true, 'langId' => true, 'status' => true, 'title' => false ), $this->arrayTreeMenu, 1 );
		
		$this->obj->__destruct ();
		
		return $result;
	}

	/**
	 * extract a branch from a tree structure of category
	 * @author BabyWolf
	 * @param interger $categoryId the id of the node you want to extract
	 * @param $categories tree structure of category objects
	 * @return $result[category,ids] list of path from root and tree category structure
	 */
	function extractNodeInTree($categoryId = 0, $categories = array()) {
		foreach ( $categories as $category ) {
			if ($category->getId () == $categoryId) {
				$result ['ids'] [] = $category->getId ();
				$result ['category'] = $category;
				return $result;
			}
			if (count ( $category->getChildren () ) > 0) {
				$result = $this->extractNodeInTree ( $categoryId, $category->getChildren () );
				
				if (is_object ( $result ['category'] )) {
					$result ['ids'] [] = $category->getId ();
					return $result;
				}
			}
		}
		return false;
	}

	function getUtilitys($categoryId = 0) {
		$obj = $this->getCategoryById ( $categoryId );
		if (! $obj)
			return false;
		return $obj->getUtilitys ();
	}

	function getChildrenIdInTree($category) {
		if (! is_object ( $category )) {
			$arraycate = $this->extractNodeInTree ( $category, $this->arrayTreeCategory );
			$category = $arraycate ['category'];
		}
		if (! is_object ( $category ))
			return;
		
		$strIds = $category->getId ();
		if ($category->getChildren ()) {
			$arrayIds = array_keys ( $category->getChildren () );
			$arrayIds ? $strIds .= ',' . implode ( ",", $arrayIds ) : $strIds .= implode ( ",", $arrayIds );
			foreach ( $category->getChildren () as $thisCategory )
				if (count ( $thisCategory->getChildren () ))
					$strIds .= "," . $this->getChildrenIdInTree ( $thisCategory );
		}
		return $strIds;
	}

	function buildCacheMenu() {
		// Only build cache for user menus
		$this->obj->setStatus ( 1 );
		$arrayTreeMenu = $this->filterMenu ( array ('status', 'isAdmin' => array (true, array (0, - 1 ) ) ), $this->arrayTreeMenu );
		$arrayMenu = $this->filterMenu ( array ('status', 'isAdmin' => array (true, array (0, - 1 ) ) ), $this->arrayMenu );
		$arrayCategory = $this->filterMenu ( array ('status', 'isAdmin' => array (true, array (0, - 1 ) ) ), $this->arrayCategory );
		
		$cache_content = "<?php\n";
		$cache_content .= "\$arrayTreeMenu = " . var_export ( $arrayTreeMenu, true ) . ";\n";
		$cache_content .= "\$arrayMenu = " . var_export ( $arrayMenu, true ) . ";\n";
		$cache_content .= "\$arrayTreeCategory = " . var_export ( $this->arrayTreeCategorys, true ) . ";\n";
		$cache_content .= "\$arrayCategory = " . var_export ( $arrayCategory, true ) . ";\n";
		$cache_content .= "?>";
		$cache_path = CACHE_PATH . "menus.cache";
		$cache_content = preg_replace ( '/\s\s+/', '', $cache_content );
		$file = fopen ( $cache_path, "w" );
		fwrite ( $file, $cache_content );
		fclose ( $file );
	}

	function updateURL($url = "", $menuIds = "") {
		$arrayId = explode ( ',', $menuIds );
		$trueIds = '-11';
		foreach ( $arrayId as $value ) {
			$arrMenu [$value] = $this->arrayCategory [$value];
			if (! is_object ( $arrMenu [$value] ))
				$arrMenu [$value] = $this->arrayMenu [$value];
			if ($arrMenu [$value]->getBackup ())
				$error .= "{$arrMenu[$value]->getTitle()}, ";
			else {
				$trueIds .= ", " . $value;
				$success .= "{$arrMenu[$value]->getTitle()}, ";
			}
			$success = substr ( $success, 0, - 2 );
			$error = substr ( $error, 0, - 2 );
		}
		if ($error || $success) {
			
			$message = $success ? "cập nhật Link Thành công Menu [ {$success} ] " : "";
			$message .= $error ? " Chú ý Menu [ {$error} ] đã được cập nhật Trước đó vui lòng vào menus khôi phục lại" : "";
			print <<<EOF
						<script type='text/javascript'>
							alert('{$message}');
						</script>
EOF;
		}
		$this->setCondition ( 'menuId in (' . $trueIds . ")" );
		$query = "UPDATE " . SQL_PREFIX . "menu SET menuBackup = menuUrl, menuUrl = '" . $url . "' WHERE menuId in (" . $trueIds . ")";
		$this->executeQuery ( $query );
		$this->getAllMenu ();
		$this->buildCacheMenu ();
	}

	function restoreURL($menuIds = "") {
		$this->setCondition ( 'menuId in (' . $menuIds . ")" );
		$query = "UPDATE " . SQL_PREFIX . "menu SET menuUrl = menuBackup WHERE menuId in (" . $menuIds . "); ";
		$query = "UPDATE " . SQL_PREFIX . "menu SET menuBackup = '' WHERE menuId in (" . $menuIds . "); ";
		$this->executeQuery ( $query );
		$this->getAllMenu ();
		$this->buildCacheMenu ();
	}

	function getMenuForModule($module = "",$status=1) {
		global $vsLang;
		
		$this->obj->setLangId ( $vsLang->currentLang->getId () );
		$this->obj->setStatus ($status );
		$this->obj->setUrl ( $module );
	
		if($status==1){
			$result = $this->filterMenu ( array ( 'langId' => true, 'status' => true, 'url' => true ), $this->arrayTreeCategory[15]->getChildren(), 1 );
			$result = $result?reset($result):array();
		}
		else 
			$result = $this->filterMenu ( array ( 'langId' => true, 'status' => true, 'url' => true ), $this->arrayCategory, 1 );
		$this->obj->__destruct ();
	
		return $result;
	}
}
?>
