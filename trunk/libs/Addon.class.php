<?php
class Addon {
	public $html;

	function __construct() {
		
		global $vsTemplate;
		$this->html = $vsTemplate->load_template ( 'skin_addon' );
		
		if (APPLICATION_TYPE == 'user')
			$this->runUserAddOn ();
		else
			$this->runAdminAddOn ();
	}

	function runUserAddOn() {
		global $vsStd, $vsTemplate, $vsCounter, $vsMenu;
		
		$vsCounter->visitCounter ();
		
		$this->managerPortlet ();
	}

	function runAdminAddOn() {
		global $bw;
		
		if ($bw->vars ['user_multi_lang'] == "Yes")
			$this->displayChooseLanguage ();
		$this->displayAdminMenus ();
	}

	/*
	 * get ACP Help: Hien thi thong tin ho tro nguoi dung theo tung module va chuc nang
	 * @param $curr_action	=	$bw->input['module']?$bw->input['module']:$bw->input['module']."::".$bw->input['action']
	 * @return void
	 */
	function displayAcpHelp() {
		global $bw, $vsSkin, $DB;
		$curr_action = $bw->input ['module'];
		if ($bw->input ['action'] != "") {
			$curr_action .= "::" . $bw->input ['action'];
		}
		
		$curr_LangId = $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'];
		$DB->simple_construct ( array ('select' => '*', 'from' => 'acp_help', 'where' => 'langId=' . $curr_LangId . ' AND `module_key`="' . $curr_action . '"', 'order' => 'id' ) );
		$DB->simple_exec ();
		if ($acp_help = $DB->fetch_row ()) {
			$vsSkin->ACP_HELP_SYSTEM = $this->html->acpHelpHTML ( $acp_help );
		}
	}

	function displayChooseLanguage($langType = 1, $display = '<!--USER LANGUAGE LIST-->') {
		global $vsStd, $vsTemplate;
		
		if (! isset ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] )) {
			$oLanguages = new languages ();
			$oLanguages->language->setAdminDefault ( 1 );
			$langResult = $oLanguages->getLangByObject ( array ('getAdminDefault' ), $oLanguages->arrayLang );
			
			reset ( $langResult );
			$language = current ( $langResult );
			$_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] = $language->convertToDB ();
		}
		
		$currentUserLanguage = new Lang ();
		$currentUserLanguage->convertToObject ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] );
		
		$vsStd->requireFile ( CORE_PATH . "languages/languages.php" );
		$languages = new languages ();
		$vsTemplate->global_template->LANGUAGE_LIST = $this->html->userLanguages ( $languages->arrayLang, $title );
	}

	function displayAdminMenus() {
		global $vsTemplate, $vsMenu, $vsSettings;
		$vsMenu->obj->setIsAdmin ( 1 );
		$vsMenu->obj->setStatus ( 1 );
		$vsMenu->obj->setPosition ( 'top' );
		$vsMenu->obj->setTitle ( 'Categories' );
		
		if (! $vsSettings->getSystemKey ( 'admin_multi_lang', 0, 'global', 1, 1 ))
			$menus = $vsMenu->filterMenu ( array ('isAdmin' => true, 'status' => true ), $vsMenu->arrayTreeMenu );
		else {
			$vsMenu->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
			$menus = $vsMenu->filterMenu ( array ('isAdmin' => true, 'langId' => true, 'status' => true ), $vsMenu->arrayTreeMenu );
		}
		
		$vsMenu->obj->setLangId ( $_SESSION [APPLICATION_TYPE] ['language'] ['currentLang'] ['langId'] );
		$vsTemplate->global_template->ADMIN_TOP_MENU = $menus;
	}

	function managerPortlet() {
		global $vsTemplate, $vsStd, $bw, $vsSettings, $vsMenu, $vsLang;
				
		$vsStd->requireFile ( CORE_PATH . 'supports/supports.php' );
		$support = new supports ();
		$vsTemplate->global_template->support = $support->getSupportWithCatId ();
		
		$vsStd->requireFile ( CORE_PATH . 'partners/partners.php' );
		$partner = new partners ();
		$partnerID = $partner->vsMenu->getChildrenIdInTree ( $partner->getCategories() );
		$partner->setCondition("partnerStatus > 0 and partnerCatId in ($partnerID)");
		$vsTemplate->global_template->partner = $partner->getObjectsByCondition();

		$vsStd->requireFile ( CORE_PATH . 'products/products.php' );
		$product = new products ();
		$productID = $product->vsMenu->getChildrenIdInTree ( $product->getCategories() );
		$listBestPro = $product->getSpecialList(5,3,"and productCatId in($productID)");
		
		$vsStd->requireFile ( CORE_PATH . 'weblinks/weblinks.php' );
		$weblink = new weblinks();
		$link = $weblink->getweblinkList();
		
		$vsStd->requireFile ( CORE_PATH . 'news/news.php' );
		$news = new newses();
		$listHotNews= $news->getHotList(10);
		
		$vsStd->requireFile ( CORE_PATH . 'pages/pages.php' );
		$page = new pages();
		$listHelp = $page->getHostListByModule('helpbuy');
		//
		$menu = $vsMenu->getMenuForUser();
		//
		$category = $vsMenu->getMenuForModule("products")->getChildren();

		$vsTemplate->global_template->bestpro = $listBestPro;
		$vsTemplate->global_template->hotNews = $listHotNews;
		$vsTemplate->global_template->menu = $menu;
		$vsTemplate->global_template->category = $category;
		$vsTemplate->global_template->link = $link;
		$vsTemplate->global_template->help = $listHelp;
	}
}
?>