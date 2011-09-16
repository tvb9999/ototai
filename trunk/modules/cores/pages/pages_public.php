 <?php

	if (! defined ( 'IN_VSF' )) {
		print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
		exit ();
	}

	require_once (CORE_PATH . "pages/pages.php");
	class pages_public {
		protected $html;
		protected $module;
		protected $output;

		function __construct() {
			global $vsTemplate, $bw, $vsModule, $vsSkin;
			if ($vsModule->obj->getVirtual () and file_exists ( $vsSkin->obj->getFolder () . "/skin_{$bw->input['module']}.php" )) {
				$this->html = $vsTemplate->load_template ( "skin_{$bw->input['module']}" );
			} else
				$this->html = $vsTemplate->load_template ( 'skin_pages' );
			$this->module = new pages ();
		}

		function auto_run() {
			global $bw;
			
			switch ($bw->input [1]) {
				case 'detail' :
					$this->loadDetail ( $bw->input [2] );
					break;
				
				case 'code' :
					$this->loadCode ( $bw->input [2] );
					break;
				case 'category' :
					$this->loadCategory ( $bw->input [2] );
					break;
				
				default :
					$this->loadDefault ();
					break;
			}
		}

		function loadCode($pageCode) {
			global $vsPrint, $vsLang, $bw, $vsSettings;
			if ($bw->input ['module'] != "pages") {
				$categories = $this->module->vsMenu->getCategoryGroup ( $bw->input ['module'] );
				$strIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
			} else
				$categories = $this->module->getCategories ();
			if (! $pageCode)
				return $vsPrint->redirect_screen ( $vsLang->getWords ( 'page_noPageItem', 'Không có dữ liệu theo yêu cầu' ) );
			
			$catIds = $this->module->vsMenu->getChildrenIdInTree ( $categories );
			$this->module->setCondition ( "pageCode='{$pageCode}'" );
			$this->module->getOneObjectsByCondition ( $pageCode );
			$bw->input ['catUrl'] = $categories->getCatUrl ( $bw->input [0] );
			$vsPrint->pageTitle = $vsPrint->mainTitle = $this->module->obj->getTitle ();
			$this->output = $this->html->loadDetail ( $this->module->obj );
		}

		function loadDefault() {
			global $bw, $vsPrint, $vsLang;
			$result = $this->module->getByMouduleName ( $bw->input ['module'], 1 );
			$this->output = $this->html->loadDefault ($result);
		}

		function loadDetail($pageId) {
			global $vsPrint, $vsLang, $bw, $vsMenu;
			
			$query = explode('-',$pageId);
			$pageId = abs(intval($query[count($query)-1]));
			if($pageId == 0) return $vsPrint->redirect_screen($vsLang->getWords('page_noPageItem','Không có dữ liệu theo yêu cầu'));
			
			$result = $this->module->getObjectById ( $pageId );
			
			$this->module->vsRelation->setTableName ( "page_category" );
			$this->module->vsRelation->getRelationByOption ( true, array ("where" => "objectId=$pageId" ) );
			
			$price_cate = $this->module->vsRelation->arrval;
			
			$resultOther = $this->module->getListWithCatModule ($vsMenu->arrayCategory[$price_cate[$pageId]['relId']], $bw->input ['module'], 10 );
			unset ( $resultOther ['list'][$result->getId ()] );
			
			$vsPrint->mainTitle = $vsMenu->pageTitle = $vsMenu->arrayCategory[$price_cate[$pageId]['relId']]->getTitle();
			
			$this->output = $this->html->loadDetail ( $result, $resultOther,$vsMenu->arrayCategory[$price_cate[$pageId]['relId']] );
		}

		function download($fileId = "") {
			if (! $fileId)
				return;
			global $vsFile;
			
			$vsFile->downloadFile ( $fileId );
		}

		function loadCategory($catId) {
			global $bw, $vsPrint, $vsSettings;
			$catId = abs ( intval ( $catId ) );
			if ($catId == 0)
				return $vsPrint->redirect_screen ( 'Không có dữ liệu theo yêu cầu' );
			$result = $this->module->vsMenu->extractNodeInTree ( $catId, $this->module->vsMenu->arrayTreeCategory );
			$current = $result ['category'];
			$bw->input ['catUrl'] = $current->getCatUrl ( $bw->input [0] );
			$vsPrint->pageTitle = $vsPrint->mainTitle = $current->getTitle ();
			$catIds = $this->module->vsMenu->getChildrenIdInTree ( $current );
			$this->module->vsRelation->setRelId ( $catIds );
			$this->module->vsRelation->setTableName ( "page_category" );
			$pageIds = $this->module->vsRelation->getObjectByRel ();
			$size = $vsSettings->getSystemKey ( 'page_' . $bw->input [0] . '_cate_capability', 10, $bw->input [0], 2 );
			$option = $this->module->getByPageIds ( $pageIds, $bw->input [0] . "/category/" . $catId . "/" . strtolower ( VSFTextCode::removeAccent ( str_replace ( "/", '-', trim ( $current->getTitle () ) ), '-' ) ), $size, 4 );
			$this->output = $this->html->htmlListObject ( $option );
		}

		function getListWithCat($module) {
			$category = $this->module->vsMenu->getCategoryGroup ( $module );
			
			if (count ( $category->getChildren () )) {
				foreach ( $category->getChildren () as $key => $cat ) {
					$cat = $this->module->vsMenu->getCategoryById ( $key );
					$listObject = $this->module->getListWithCat ( $cat );
					$html .= $this->html->htmlListObject ( $cat, $listObject );
				}
			} else {
				$listObject = $this->module->getListWithCat ( $category );
				$html = $this->html->htmlListObject ( $category, $listObject );
			}
			return $html;
		}

		/*Not Necessory*/
		function getModulePageList($url = "", $pageIndex = 1) {
			global $bw;
			$module = $bw->input ['module'];
			return $this->module->getByMouduleName ( $bw->input ['module'], $pageIndex );
		}

		function setOutput($out) {
			return $this->output = $out;
		}

		function getOutput() {
			return $this->output;
		}
	}
	?>
