<?php

class GlobalLoad {

	function __construct() {
		$this->addDefaultScript ();
		$this->addDefaultCSS ();
	}
	
	/**
	 * This function is for add global javascript
	 * @name addDefaultScript
	 * @author BabyWolf
	 * @return void
	 */
	function addDefaultScript() {
		global $vsPrint, $bw,$vsLang;

		$vsPrint->addJavaScriptFile ( 'jquery/jquery', 1 );
		$vsPrint->addJavaScriptFile ( 'redsun/vs.ajax' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.core' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.widget' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.position' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.dialog' );
		$vsPrint->addJavaScriptFile ( "jquery.ui/ui.alerts" );
		$vsPrint->addJavaScriptFile ( "jquery.ui/ui.loading" );
		$vsPrint->addJavaScriptFile ( 'redsun/crawler' );
		
		$vsPrint->addCurentJavaScriptFile( "ddaccordion",1);
		$vsPrint->addCurentJavaScriptFile( "jcarousellite_1.0.1");
		$vsPrint->addCurentJavaScriptFile( "ddsmoothmenu",1);
		$vsPrint->addCurentJavaScriptFile( "DD_belatedPNG_0.0.8a",1);
		$vsPrint->addCurentJavaScriptFile( "jquery.jcarousellite.pauseOnHover.min");
		$vsPrint->addCurentJavaScriptFile( "jquery.simplyscroll-1.0.4");
		
		$vsPrint->addJavaScriptString ( 'global_var', '
    			var vs = {};
    		    var ajaxfile = "index.php";
				var noimage=0;
				var image = "loader.gif";
				var imgurl = "' . $bw->vars ['img_url'] . '/";
				var img = "' . $bw->vars ['cur_folder'] . 'htc";
				var boardUrl = "' . $bw->vars ['board_url'] . '";
				var baseUrl  = "' . $bw->base_url . '";
				var language = "' . $vsLang->currentLang->getFolderName() . '";
				var global_website_title = "' . $bw->vars ['global_websitename'] . '/";
    		', 1 );
	}

	/**

	 * This function is for add global css

	 * @name addDefaultCSS

	 * @author BabyWolf

	 * @return void

	 */

	function addDefaultCSS() {
		global $vsUser, $vsPrint, $vsModule;

		$vsPrint->addCSSFile ( 'global' );
		$vsPrint->addCSSFile ( 'default' );
		$vsPrint->addCSSFile ( 'content' );
		$vsPrint->addCSSFile ( 'jquery.simplyscroll-1.0.4' );

		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.theme' );
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.core' );
		$vsPrint->addGlobalCSSFile('jquery.ui/theme/base/ui.theme');
		$vsPrint->addGlobalCSSFile('jquery.ui/theme/base/ui.dialog');
		$vsPrint->addCSSFile($vsModule->obj->getClass());
	}
}

$styleLoad = new GlobalLoad();