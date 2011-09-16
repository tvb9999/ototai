<?php
require_once (CORE_PATH . "pages/pages.php");

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}

class pages_admin {
	protected 	$module;
	protected 	$output = "";
	private 	$html = "";
	
	function __construct() {
		global $bw, $vsTemplate, $vsPrint, $vsStd;
		$vsPrint->addJavaScriptFile ( "tiny_mce/tiny_mce" );
		$this->html = $vsTemplate->load_template ( 'skin_pages' );
		$vsStd->requireFile ( LIBS_PATH . "Pagination.class.php" );
		$vsStd->requireFile ( JAVASCRIPT_PATH . "/tiny_mce/tinyMCE.php" );
		$vsPrint->addCSSFile ( 'pages' );
		$this->module = new pages ();
	}

	function auto_run() {
		global $bw;
		
		switch ($bw->input [1]) {
			case 'displayPagesTab' :
				$this->displayPagesTab ();
				break;
			case 'getObjList' :
				$this->getObjList ();
				break;
			case 'pageCode' :
				$this->getObjListWithCode ();
				break;
			case 'getEventStream' :
				$this->getEvenStream ($bw->input[2], $bw->input[3]);
				break;
			case 'displayEditForm' :
				$this->displayEditForm ( $bw->input [2] );
				break;
			case 'editPageProcess' :
				$this->editPageProcess ();
				break;
			case 'deletePage' :
				$this->deletePage ();
				break;
			case 'displayCatPageList' :
				$this->displayCatPageList ( $bw->input [2] );
				break;
			case 'updateStatus' :
				$this->updateStatus ( $bw->input [2], $bw->input [3] );
				break;
			
			case 'module' :
				$this->displayModulePageTab ( $bw->input [2] );
				break;
			case 'displayMPages' :
				$this->displayModulePages ( $bw->input [2] );
				break;
			case 'getMPageList' :
				$this->getModulePageList ( $bw->input [2] );
				break;
			case 'displayCatMPageList' :
				$this->displayCatMPageList ( $bw->input [3], $bw->input [2] );
				break;
			case 'displayVirtualTab' :
				$this->displayVirtualTab ();
				break;			
			case 'displayVirtualForm' :
				$this->displayVirtualForm ( $bw->input [2] );
				break;
			case 'editVirtualProcess' :
				$this->editVirtualProcess ();
				break;
			case 'deleteVirtual' :
				$this->deleteVirtual ();
				break;
			default :
				$this->defaultView ();
		}
	}

	/* 
	 * Author   	: khamdb@redsunic.com
	 * Param  		: Unknown
	 * Return  		: Unknown
	 * Description  : Unknown
	 * 
	 */
	function getEvenStream($module = '',$keyword = ''){
		global $bw;
		$result = $this->module->searchTitle($keyword, $module);
		
		return $this->output = $this->html->displayEvenStream ($result);
	}
	
	function deleteVirtual() {
		global $bw, $vsLang, $vsStd;
		
		//remove in table modules
		$vsStd->requireFile ( CORE_PATH . 'modules/modules.php' );
		$module = new modules ();
		$Omodule = $module->getModuleByIds ( $bw->input [2] );
		$module->setCondition ( "moduleId in ({$bw->input [2]})" );
		$module->deleteObjectByCondition ();
		
		if ($Omodule) {
			//remove in table pages
			

			$this->module->vsRelation->setTableName ( "page_category" );
			$pageIds = $this->module->vsRelation->getRelationByOption ( true, array ("where" => "module='$Omodule'" ) );
			
			$this->module->vsRelation->setCondition ( "module='$Omodule'" );
			$this->module->vsRelation->deleteObjectByCondition ();
			
			if ($pageIds) {
				$this->module->setCondition ( "pageId in({$pageIds})" );
				$this->module->getObjectsByCondition ();
				
				foreach ( $this->module->getArrayObj () as $page )
					$this->module->vsFile->deleteFile ( $page->getImage ( 0 ) );
				
				require_once (CORE_PATH . "gallerys/gallerys.php");
				$gallery = new gallerys ();
				$this->module->vsRelation->setRelId ( implode ( array_keys ( $this->module->getArrayObj () ), ',' ) );
				$this->module->vsRelation->setTableName ( "gallery_pages" );
				$strId = $this->module->vsRelation->getObjectByRel ();
				$this->module->vsRelation->setCondition ( "relId in(" . implode ( array_keys ( $this->module->getArrayObj () ), ',' ) . ")" );
				$this->module->vsRelation->deleteObjectByCondition ();
				
				if ($strId) {
					$gall = $gallery->getFileByAlbumId ( $strId );
					$this->module->vsFile->deleteFile ( implode ( array_keys ( $gall ), ',' ) );
					$gallery->vsRelation->setTableName ( $gallery->getRelTableName () );
					$gallery->vsRelation->setCondition ( "relId in(" . $strId . ")" );
					$gallery->vsRelation->deleteObjectByCondition ();
					
					$gallery->setCondition ( "galleryId in(" . $strId . ")" );
					$gallery->deleteObjectByCondition ();
					
					foreach ( explode ( ",", $strId ) as $value )
						$this->module->deleteDirectory ( "./uploads/gallery/image/Album-" . $value );
				}
				$this->module->deleteObjectByCondition ();
			}
			
			//remove in table systemsettings
			$vsStd->requireFile ( CORE_PATH . 'settings/settings.php' );
			$settings = new settings ();
			$settings->setCondition ( "settingModule in ('{$Omodule}')" );
			$settings->deleteObjectByCondition ();
			//remove int table menu
			$vsStd->requireFile ( CORE_PATH . 'menus/menus.php' );
			$menus = new menus ();
			$menus->setCondition ( "menuUrl in ('{$Omodule}')" );
			$menus->deleteObjectByCondition ();
		}
		return $this->output = $this->displayVirtualTab ();
	}

	function editVirtualProcess() {
		global $bw, $vsLang, $vsStd, $vsMenu;
		
		$vsStd->requireFile ( CORE_PATH . 'modules/modules.php' );
		$module = new modules ();
		$bw->input ['moduleIsUser'] = $bw->input ['moduleIsUser'] ? $bw->input ['moduleIsUser'] : 0;
		$bw->input ['moduleIsAdmin'] = $bw->input ['moduleIsAdmin'] ? $bw->input ['moduleIsAdmin'] : 0;
		$module->obj->convertToObject ( $bw->input );
		$module->obj->setClass ( $bw->input ['moduleTitle'] );
		$module->obj->setVirtual ( 1 );
		if (empty ( $bw->input ['moduleId'] )) {
			$module->insertObject ( $module->obj );
			$vsMenu->getCategoryGroup ( $bw->input ['moduleTitle'] );
			
			if ($module->result ['status']) {
				$alert = $vsLang->getWords ( 'pages_addVirtualItem_Successful', 'you have successfully add a virtual module!' );
				$javascript = <<<EOF
						<script type='text/javascript'>
							jAlert(
								"{$alert}",							
								"{$bw->vars['global_websitename']} Dialog"
							);
						</script>
EOF;
			}
		} else {
			$module->updateObjectById ( $module->obj );
			
			$this->module->vsRelation->setCondition ( "module = '" . $bw->input ['oldModuleTitle'] . "'" );
			$array = array ('module' => $bw->input ['moduleTitle'] );
			$this->module->vsRelation->setTableName ( "page_category" );
			$this->module->vsRelation->updateObjectByCondition ( $array );
			
			if ($module->result ['status']) {
				$alert = $vsLang->getWords ( 'pages_editVirtualItem_Successful', 'you have successfully edit a virtual module!' );
				$javascript = <<<EOF
						<script type='text/javascript'>
							jAlert(
								"{$alert}",							
								"{$bw->vars['global_websitename']} Dialog"
							);
						</script>
EOF;
			}
		}
		
		return $this->output = $javascript . $this->displayVirtualTab ();
	}

	function displayVirtualTab() {
		global $vsLang, $vsStd;
		$vsStd->requireFile ( CORE_PATH . 'modules/modules_admin.php' );
		$module = new modules_admin ();
		$option ['list'] = $this->html->displayVirtualItemContainer ( $module->getVirtualModuleList () );
		$option ['form'] = $this->displayVirtualForm ();
		return $this->output = $this->html->displayVirtualTab ( $option );
	}

	function displayVirtualForm($moduleId = 0) {
		global $bw, $vsLang, $vsStd;
		$vsStd->requireFile ( CORE_PATH . 'modules/modules.php' );
		$option ['submitValue'] = $vsLang->getWords ( 'bt_add', 'Add' );
		$option ['formTitle'] = $vsLang->getWords ( 'pages_addVirtual', 'Add Virtual Module' );
		
		$module = new modules ();
		if (! empty ( $moduleId )) {
			$option ['submitValue'] = $vsLang->getWords ( 'bt_edit', 'Edit' );
			$option ['formTitle'] = $vsLang->getWords ( 'pages_editVirtual', 'Edit Virtual Module' );
			$module->getObjectById ( $bw->input [2] );
		}
		
		return $this->output = $this->html->displayEditVirtualForm ( $module->obj, $option );
	}

	function displayModulePageTab($module = "abouts") {
		global $vsPrint;
		
		$vsPrint->addJavaScriptString ( 'init_tab', '$(document).ready(function(){$("#page_tabs").tabs({fx: { opacity: "toggle" },cache: false});});' );
		$this->setOutput ( $this->html->displayModulePagesTab ( $module ) );
	}

	function displayModulePages($module = "abouts") {
		global $bw, $vsLang, $vsMenu, $vsStd, $vsSettings;
		
		$vsStd->requireFile ( CORE_PATH . 'modules/modules_admin.php' );
		$module_admin = new modules_admin ();
		
		foreach ( $module_admin->getVirtualModuleList () as $moduleObj ) {
			$option ['rootId'] = $this->module->vsMenu->getCategoryGroup ( strtolower ( $module ) )->getId ();
			if (strtolower ( $moduleObj->getTitle () ) == $module) {
				if ($vsSettings->getSystemKey ( $module . '_category_list', 1, $module,1,1 ))
					$option ['cat'] = $this->module->getVirtualCategoryList ( $module );
				$option ['list'] = $this->getModulePageList ( $module );
				$option ['virtual'] = $module;
				
				return $this->output = $this->html->displayModulePages ( $option );
			}
		}
		
		$option ['error'] = $vsLang->getWords ( 'pages_virtual_notExists', 'This virtual module does not exists!' );
		return $this->output = $this->html->displayModulePages ( $option );
	
	}

	function displayCatMPageList($module = "abouts", $catIds = "") {
		$url = "pages/displayCatMPageList/" . $catIds . "/";
		$this->getModulePageList ( $module, $url, $catIds, 4 );
	}

	function getModulePageList($module = "abouts", $url = "pages/getMPageList/", $strIds = null, $pageIndex = 3) {
		global $bw, $vsSettings;
		$count = 0;
		$style_class = array ("odd", "even" );
		
		if (! $strIds) {
			$categories = $this->module->vsMenu->getCategoryGroup ( $module );
			$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
		}
		
		$this->module->vsRelation->setRelId ( $strIds );
		$this->module->vsRelation->setTableName ( "page_category" );
		$pageIds = $this->module->vsRelation->getObjectByRel ();
		$size = $vsSettings->getSystemKey ( "page_capability", 10 ,$temp,1,1);
		
		$option = $this->module->getByPageIds ( $pageIds, $url . $module, $size, $pageIndex, 1, "mainPageContainer" );
		$option ['virtual'] = $module;
		$temp = $option ['virtual'] ? $option ['virtual'] : "pages";
		$option ['upload'] = $vsSettings->getSystemKey ( $temp . '_file', 0, $temp,1,1 );
		return $this->output = $this->html->objListHtml ( $option );
	}

	function getObjListWithCode($url = "pages/pageCode", $pageIndex = 2) {
		global $bw, $vsSettings;
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10,$bw->input[0],1,1 );
		$catId = $this->module->vsMenu->getChildrenIdInTree ( $this->module->getCategories ()->getId () );
		
		if (! intval ( $bw->input [2] ) && $bw->input [2]) {
			$this->module->setCondition ( "pageCode='{$bw->input[2]}' and pageCatId in (" . $catId . ")" );
			$option = $this->module->getPageList ( $url, $pageIndex, $size, 1, 'mainPageContainer' );
			$option ['modePageCode'] = $bw->input [2];
			$pageIndex = 3;
		} else {
			$this->module->setCondition ( 'pageCode<>"" and pageCatId in (' . $catId . ')' );
			$option = $this->module->getPageList ( $url, $pageIndex, $size, 1, 'mainPageContainer' );
			$option ['modePageCode'] = '1';
		}
		
		return $this->output = $this->html->objListHtmlWithCode ( $option );
	}

	function displayPagesTab() {
		global $bw, $vsLang, $vsMenu;
		$option ['menu'] = $this->module->getMenuList ();
		$option ['cat'] = $this->module->getCategoryList ();
		$option ['list'] = $this->getObjList ();
		return $this->output = $this->html->displayPageTab ( $option );
	}

	function displayCatPageList($catIds = "") {
		$url = "pages/displayCatMPageList/" . $catIds . "/";
		$this->getObjList ( $url, 3, $catIds );
	}

	function getObjList($url = "pages/getObjList", $pageIndex = 2, $strIds = "") {
		global $bw, $vsSettings, $vsStd, $vsMenu;
		$size = $vsSettings->getSystemKey ( "admin_{$bw->input[0]}_list_number", 10 ,$bw->input[0],1,1 );
		
		if (! $strIds) {
			$vsStd->requireFile ( CORE_PATH . 'modules/modules_admin.php' );
			$module_admin = new modules_admin ();
			foreach ( $module_admin->getVirtualModuleList () as $moduleObj ) {
				$categories = $this->module->vsMenu->getCategoryGroup ( $moduleObj->getClass () );
				$strIds .= $this->module->vsMenu->getChildrenIdInTree ( $categories ) . ",";
			}
			
			$vsMenu->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
			$menus = $vsMenu->filterMenu ( array ('langId' => true ), $vsMenu->arrayTreeMenu );
			
			$strIds .= implode ( ",", array_keys ( $menus ) );
		}
		
		$this->module->vsRelation->setRelId ( $strIds );
		$this->module->vsRelation->setTableName ( "page_category" );
		$pageIds = $this->module->vsRelation->getObjectByRel ();
		
		$option = $this->module->getByPageIds ( $pageIds, $url, $size, $pageIndex, 1, "mainPageContainer" );
		
		return $this->output = $this->html->objListHtml ( $option );
	}

	function displayEditForm($pageId = '', $module = "pages") {
		global $bw, $vsLang, $vsStd, $vsSettings;
		$option ['virtual'] = $bw->input ['virtual'] != 'pages' ? $bw->input ['virtual'] : '';
		$option ['module'] = $option ['virtual'] ? $option ['virtual'] : $module;
		$option ['submitValue'] = $vsLang->getWords ( 'bt_add', 'Add' );
		$option ['formTitle'] = $vsLang->getWords ( 'add_page', 'Add Page' );
		
		if ($pageId) {
			$option ['submitValue'] = $vsLang->getWords ( 'bt_edit', 'Edit' );
			$option ['formTitle'] = $vsLang->getWords ( 'edit_page', 'Edit Page' );
			
			$this->module->getObjectById ( $bw->input [2] );
			$this->module->vsRelation->setObjectId ( $this->module->obj->getId () );
			
			$this->module->vsRelation->setTableName ( "page_category" );
			$conIds = $this->module->vsRelation->getRelByObject ();
			$this->module->obj->setGroupdIds ( $conIds );
		}
		
		$option ['key'] = "pages";
		if (! empty ( $bw->input ['modePageCode'] )) {
			if ($bw->input ['modePageCode'] == 1)
				$option ['key'] = "pageCode";
			else
				$option ['key'] = $bw->input ['modePageCode'];
		}
		if (! empty ( $bw->input ['virtual'] ))
			$option ['key'] = $bw->input ['virtual'];
		
		$editor = new tinyMCE ();
		$editor->setUrl ( $bw->vars ['board_url'] );
		if ($vsSettings->getSystemKey ( $option ['key'] . '_intro_editor', 0, $option ['key'],1,1 )) {
			
			$editor->setWidth ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_width", '100%', $bw->input [0], 1, 1 ) );
			$editor->setHeight ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_height", '150px', $bw->input [0], 1, 1 ) );
			$editor->setToolbar ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_toolbar", 'narrow', $bw->input [0], 1, 1 ) );
			$editor->setTheme ( $vsSettings->getSystemKey ( $bw->input [0] . "_intro_editor_theme", "advanced", $bw->input [0], 1, 1 ) );
			$editor->setInstanceName ( 'pageIntro' );
			$editor->setValue ( $this->module->obj->getIntro ( -1 ) );
			$this->module->obj->setIntro ( $editor->createHtml () );
		} else
			$this->module->obj->setIntro ( strip_tags ( $this->module->obj->getIntro () ) );
		
		$editor->setWidth ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_width", '100%', $bw->input [0], 1, 1 ) );
		$editor->setHeight ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_height", '350px', $bw->input [0], 1, 1 ) );
		$editor->setToolbar ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_toolbar", 'full', $bw->input [0], 1, 1 ) );
		$editor->setTheme ( $vsSettings->getSystemKey ( $bw->input [0] . "_content_editor_theme", "advanced", $bw->input [0], 1, 1 ) );
		$editor->setInstanceName ( 'pageContent' );
		$editor->setValue ( $this->module->obj->getContent (-1) );
		$this->module->obj->setContent ( $editor->createHtml () );
		
		$status = $this->module->obj->getStatus ();
		
		if (! isset ( $status ))
			$this->module->obj->setStatus ( 1 );
		return $this->output = $this->html->displayEditPageForm ( $this->module->obj, $option );
	}

	function addEditProcess() {
		global $bw, $vsLang, $vsStd,$vsModule, $vsRelation;
		
		$bw->input ['pagePostDate'] = $bw->input ['pagePostDate'] ? $bw->input ['pagePostDate'] : time ();
		if ($bw->input ['pageAddGoogle']) {
			$vsStd->requireFile ( UTILS_PATH . "googleMap.class.php" );
			$googleMap = new googleMap ();
			$googleMap->setAddress ( $bw->input ['pageAddGoogle'] );
			$googleMap->getCoordinate ();
			$bw->input ['pageLatitude'] = $googleMap->getLatitude ();
			$bw->input ['pageLongitude'] = $googleMap->getLongitude ();
		
		}
		if ($bw->input ['pageUpdatedAction'] >= 0)
			$bw->input ['pageUpdated'] = $bw->input ['pageUpdatedAction'];
		
		if ($bw->input ['fileId'])
			$bw->input ['pageImage'] = $bw->input ['fileId'];
		elseif ($bw->input ['txtlink'])
			$bw->input ['pageImage'] = $this->module->vsFile->copyFile ( $bw->input ['txtlink'], "pages" );
		
		$this->module->obj->convertToObject ( $bw->input );
		$this->module->obj->setCatId ( $this->module->getCategories ()->getId () );
		$this->module->obj->setModule ( $bw->input ['virtualModule'] );
		$this->module->vsRelation->setArrayField ( array ('module' => $bw->input ['virtualModule'] ) );
		if (empty ( $bw->input ['pageId'] )) {
			$this->module->insertObject ( $this->module->obj );
			if ($this->module->result ['status']) {
				if ($bw->input ['virtualModule'] && ! $bw->input ['pageGroupIds'])
					$bw->input ['pageGroupIds'] = $this->module->vsMenu->getCategoryGroup ( $bw->input ['virtualModule'] )->id;
				$this->module->vsRelation->setObjectId ( $this->module->obj->getId () );
				$this->module->vsRelation->setRelId ( $bw->input ['pageGroupIds'] );
				$this->module->vsRelation->setTableName ( "page_category" );
				$this->module->vsRelation->insertRel ();
				if($bw->input ['pageEvent']){
					$arrayObj = explode (',', $bw->input ['checkedObj']);
					if(is_array($arrayObj)){
						foreach($arrayObj as $value){
							$this->module->vsRelation->setObjectId ( $value );
							$this->module->vsRelation->setRelId ( $this->module->obj->getId () );
							$this->module->vsRelation->setTableName ( "page_event" );
							$this->module->vsRelation->insertRel ();
						}
					}
				}
			}
			if ($this->module->result ['status']) {
				$confirmContent = $vsLang->getWords ( 'pages_addPageItem_Successful', 'you have successfully add a page!' ) . '\n' . $vsLang->getWords ( 'pages_addPageItem_AddMore', 'Do you want to add another page?' );
				
				$function = "displayEditForm";
				
				if ($bw->input ['virtualModule'])
					$function = "displayEditForm/&virtual={$bw->input['virtualModule']}";
				if ($bw->input ['modePageCode'])
					$function = "displayEditForm/0/&modePageCode=1";
				$javascript = <<<EOF
					<script type='text/javascript'>
						jConfirm(
							"{$confirmContent}",							
							'{$bw->vars['global_websitename']} Dialog', 
							function(r){
								if(r){
									vsf.get('{$bw->input['module']}/{$function}','mainPageContainer');
								}
							}
						);
					</script>
EOF;
			}
		} else {
			
			$this->module->updateObjectById ( $this->module->obj );
			
			if ($this->module->result ['status']) {
				if ($bw->input ['pageGroupIds']) {
					$this->module->vsRelation->setObjectId ( $this->module->obj->getId () );
					$this->module->vsRelation->setRelId ( $bw->input ['pageGroupIds'] );
					$this->module->vsRelation->setTableName ( "page_category" );
					$this->module->vsRelation->insertRel ( $bw->input ['pageOldgroupIds'] );
				}
				if ($bw->input ['pageDeleteImage'] || $bw->input ['pageImage']) {
					$this->module->vsFile->deleteFile ( $bw->input ['pageOldFileId'] );
				}
			}
			
			if ($this->module->result ['status']) {
				$alert = $vsLang->getWords ( 'pages_editPageItem_Successful', 'you have successfully edit a page!' );
				$javascript = <<<EOF
						<script type='text/javascript'>
							jAlert(
								"{$alert}",							
								"{$bw->vars['global_websitename']} Dialog"
							);
						</script>
EOF;
			}
		}
		
		if ($bw->input ['virtualModule']){
			if($vsModule->obj->getIntro()){
				$vsStd->requireFile(CORE_PATH.'feed/rss.php');
				$rss = new rss();
				$rss->getFeedModule($bw->input ['virtualModule'],VSFTextCode::removeAccent($vsModule->obj->getIntro(),'_'));
			}
			return $this->output = $javascript . $this->getModulePageList ( $bw->input ['virtualModule'], "pages/getMPageList/", $bw->input ['pageGroupIds'] );
		}
		if ($bw->input ['modePageCode']) {
			if ($bw->input ['modePageCode'] != 1)
				$bw->input [2] = $bw->input ['modePageCode'];
			return $this->output = $javascript . $this->getObjListWithCode ();
		}
		
		return $this->output = $javascript . $this->getObjList ();
	}

	function editPageProcess() {
		global $bw;
		
		$this->addEditProcess ();
		if ($bw->input ['pageUpdatedAction'])
			$this->updateURL ( $bw->input ['pageGroupIds'] );
	}

	function deletePage() {
		global $bw;
	
		$this->module->setCondition ( "pageId in (" . $bw->input [2] . ")" );
		$this->module->getObjectsByCondition ();
		
		foreach ( $this->module->getArrayObj () as $page )
			$this->module->vsFile->deleteFile ( $page->getImage ( 0 ) );
		
		$this->module->setCondition ( "pageId in (" . $bw->input [2] . ")" );
		$this->module->deleteObjectByCondition ();
		
		$this->module->vsRelation->setObjectId ( $bw->input [2] );
		$this->module->vsRelation->setTableName ( "page_category" );
		$catId = $this->module->vsRelation->delRelByObject ();
		unset ( $bw->input [2] );
		if ($bw->input ['virtual'])
			return $this->output = $this->getModulePageList ( $bw->input ['virtual'] ,"pages/getMPageList/",$catId);
		if ($bw->input ['modePageCode'])
			return $this->output = $this->getObjListWithCode ();
		
		return $this->output = $this->getObjList ();
	}

	function updateURL($menuIds = "", $subURL = "detail") {
		$url = "pages/" . $subURL . "/" . VSFTextCode::removeAccent ( $this->module->obj->getTitle () ) . '-' . $this->module->obj->getId ();
		
		foreach ( explode ( ",", $menuIds ) as $menuId )
			$this->module->vsMenu->updateURL ( $url, $menuId );
	}

	function restoreURL($menuIds = "") {
		$this->module->vsMenu->restoreURL ( $menuIds );
	}

	function updateStatus($pageIds = "", $status = 0) {
		global $bw;
		$this->module->setCondition ( 'pageId in (' . $pageIds . ")" );
		$array = array ('pageStatus' => $status );
		$this->module->updateObjectByCondition ( $array );
		unset ( $bw->input [2] );
		if ($bw->input ['virtual'])
			return $this->output = $this->getModulePageList ( $bw->input ['virtual'] );
		if ($bw->input ['modePageCode'])
			return $this->output = $this->getObjListWithCode ();
		$this->output = $this->getObjList ();
	}

	function defaultView() {
		global $vsPrint, $bw;
		$vsPrint->addJavaScriptString ( 'init_tab', 
										'$(document).ready(function(){
											$("#page_tabs").tabs({fx: { 
												opacity: "toggle" 
											},cache: false});
										});'
									);
		if ($bw->input [0] == 'pages'){
			$this->setOutput ( $this->html->pageMainLayout () );
		}else{
			$this->setOutput ( $this->html->displayModulePagesTab ( $bw->input [0] ) );
		}
	}
	
	public function getHtml() {
		return $this->html;
	}

	public function getOutput() {
		return $this->output;
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	public function setOutput($output) {
		$this->output = $output;
	}
}
?>