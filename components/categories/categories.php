<?php

class categories {
	
	private $name;
	
	private $categories = array ();
	
	private $view;
	
	private $num;

	public function __construct($name, $num, $crateCategory = true) {
		
		global $vsMenu, $vsStd;
		
		define ( "ADD_CATEGORY", "ADD_CATEGORY" );
		
		define ( "ADD_EDIT_CATEGORY", "ADD_EDIT_CATEGORY" );
		
		define ( "EDIT_CATEGORY", "EDIT_CATEGORY" );
		
		define ( "DELETE_CATEGORY", "DELETE_CATEGORY" );
		
		define ( "DISPLAY_ADD_CATEGORY", "DISPLAY_ADD_CATEGORY" );
		
		define ( "DISPLAY_EDIT_CATEGORY", "DISPLAY_EDIT_CATEGORY" );
		
		define ( "DISPLAY_LIST_CATEGORY", "DISPLAY_LIST_CATEGORY" );
		
		define ( "DISPLAY_FORM_CATEGORY", "DISPLAY_FORM_CATEGORY" );
		
		define ( "DISPLAY_MAIN_CATEGORY", "DISPLAY_MAIN_CATEGORY" );
		
		$this->num = $num;
		
		$this->name = $name;
		
		$vsStd->requireFile ( COM_PATH . "categories/skin_categories.php" );
		
		$this->view = new skin_categories ( $this->name, $this->num );
		
		if ($crateCategory)
			
			$this->categories = $vsMenu->getCategoryGroup ( $this->name );
	
	}

	public function __destruct() {
		
		unset ( $this->name );
		
		unset ( $this->view );
		
		unset ( $this->categories );
	
	}

	public function displayMainPage($form = array('type'=>'Add')) {
		
		$buttons = $this->view->displayButtonEditAndDelete ();
		
		$left = $this->displayListCategory ( $buttons );
		
		$right = $this->displayCategoryForm ( $form );
		
		return $this->view->displayMainPage ( $left, $right );
	
	}

	public function displayListCategory($buttons, $message = "") {
		
		global $vsMenu;
		
		$listCategoryHTML = "";
		
		$liststyle = "| - -";
		
		if ($this->categories && count ( $this->categories->getChildren () ) > 0)
			
			$vsMenu->buildOptionMenuTree ( $this->categories->getChildren (), &$listCategoryHTML, $liststyle );
		
		return $this->view->displayListCategory ( $listCategoryHTML, $buttons, $message );
	
	}

	public function displayCategoryForm($form = array(), $category = NULL) {
		
		if (! $category) {
			
			$category = new Menu ();
			
			$category->visible = 'checked';
			
			$category->unVisible = '';
			
			$category->dropdown = '';
			
			$category->unDropdown = 'checked';
		
		} else {
			
			if ($category->getIsVisible ()) {
				
				$category->visible = 'checked';
				
				$category->unVisible = '';
			
			} else {
				
				$category->visible = '';
				
				$category->unVisible = 'checked';
			
			}
			
			if ($category->getIsDropdown ()) {
				
				$category->dropdown = 'checked';
				
				$category->unDropdown = '';
			
			} else {
				
				$category->dropdown = '';
				
				$category->unDropdown = 'checked';
			
			}
		
		}
		
		return $this->view->displayCategoryForm ( $form, $category );
	
	}

	public function addEditCategory($array) {
		
		global $vsModule, $vsMenu;
		
		$menu = new Menu ();
		
		$vsMenu->menu->__destruct ();
		
		$menu->setTitle ( $array ['categoryName'] );
		
		$menu->setUrl ( $this->name ); // Category url will be the modules url		

		$menu->setType ( 0 ); // Menu type of category is internal		

		$menu->setAlt ( $array ['categoryDesc'] );
		
		$menu->setIsVisible ( $array ['categoryIsVisible'] );
		
		$menu->setIsDropdown ( $array ['categoryIsDropdown'] );
		
		$menu->setIndex ( $array ['categoryIndex'] );
		
		$menu->setIsLink ( 1 );
		
		$menu->setIsAdmin ( - 1 ); // Category will have menu type -1		

		// Set the parent Id		

		// If choose root, the parent will be the root category of this module and its level is 2		

		if (! intval ( $array ['categoryParentId'] )) {
			
			$menu->setParentId ( $this->categories->getId () );
			
			$menu->setLevel ( 2 );
		
		} 

		else {
			
			// Get the parent category information			

			$parentCategory = $vsMenu->getCategoryById ( $array ['categoryParentId'] );
			
			// Set the parent Id			

			$menu->setParentId ( $parentCategory->getId () );
			
			// The level of the category will be its parent's + 1			

			$menu->setLevel ( $parentCategory->getLevel () + 1 );
		
		}
		
		if ($array ['formType'] == 'Edit') {
			
			$menu->setId ( $array ['categoryId'] );
			
			$vsMenu->menu = $menu;
			
			$vsMenu->updateMenu ();
		
		} 

		else {
			
			$vsMenu->menu = $menu;
			
			$vsMenu->insertMenu ();
		
		}
		
		if ($vsMenu->result ['status']) {
			
			$form ['type'] = "Add";
			
			$form ['message'] = $vsMenu->result ['message'];
			
			$form ['message'] = 'Thành công';
		
		}
		
		$vsMenu->menu->__destruct ();
		
		global $vsStd;
		
		$vsStd->requireFile ( CORE_PATH . 'menus/menus_admin.php' );
		
		$menu_admin = new menus_admin ();
		
		$vsMenu->getAllMenu ();
		
		$menu_admin->buildCache ( true );
		
		$this->setCategories ( $vsMenu->getCategoryGroup ( $vsModule->obj->getClass () ) );
		
		return $this->displayMainPage ( $form );
	
	}

	function deleteCategory($array) {
		
		global $vsMenu, $vsLang, $vsModule;
		
		$vsMenu->menu->setParentId ( $array [2] );
		
		$categories = $vsMenu->filterMenu ( array ('parentId' => true ), $vsMenu->arrayCategory );
		
		if (count ( $categories )) {
			
			return $this->displayListCategory ( $vsLang->getWords ( 'err_category_delete_unempty', "You cannot delete an unempty category! Please delete its children fisrt." ) );
		
		}
		
		$vsMenu->menu->__destruct ();
		
		$vsMenu->deleteCategoryById ( $array [2] );
		
		//		if ($vsMenu->result ['status']) {		

		$this->setCategories ( $vsMenu->getCategoryGroup ( $vsModule->obj->getClass () ) );
		
		//		}		

		global $vsStd;
		
		$vsStd->requireFile ( CORE_PATH . 'menus/menus_admin.php' );
		
		$menu_admin = new menus_admin ();
		
		$vsMenu->getAllMenu ();
		
		$menu_admin->buildCache ( true );
		
		$buttons = $this->view->displayButtonEditAndDelete ();
		
		return $this->displayListCategory ( $buttons, $vsMenu->result ['message'] );
	
	}

	/**
	 * @return unknown
	 */
	
	public function getCategories() {
		
		return $this->categories;
	
	}

	/**
	 * @return unknown
	 */
	
	public function getName() {
		
		return $this->name;
	
	}

	/**
	 * @return unknown
	 */
	
	public function getView() {
		
		return $this->view;
	
	}

	/**
	 * @param unknown_type $categories
	 */
	
	public function setCategories($categories) {
		
		$this->categories = $categories;
	
	}

	/**
	 * @param unknown_type $name
	 */
	
	public function setName($name) {
		
		$this->name = $name;
	
	}

	/**
	 * @param unknown_type $view
	 */
	
	public function setView($view) {
		
		$this->view = $view;
	
	}

}

?>