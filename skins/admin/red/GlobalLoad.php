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
		global $vsPrint, $vsUser, $bw, $vsLang;
		
		$vsPrint->addJavaScriptFile ( 'jquery/jquery', 1 );
		if (! $vsUser->obj->getId ())
			return;
		
		$vsPrint->addJavaScriptString ( 'global_var', '
    			var vs = {};
    		    var ajaxfile = "acprs.php";
				var noimage=0;
				var imgurl = "' . $bw->vars ['img_url'] . '/";
				var global_website_title = "' . $bw->vars ['global_websitename'] . '/";
				var global_website_choise = "' . $vsLang->getWordsGlobal ( 'global_website_choise', 'You haven\'t choose any items !' ) . '";
				var boardUrl = "' . $bw->vars ['board_url'] . '";
				var baseUrl  = "' . $bw->base_url . '";
    		' );
		
		$vsPrint->addJavaScriptFile ( 'ajaxupload/ajaxfileupload' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.core' );
		$vsPrint->addJavaScriptFile ( "jquery.ui/ui.widget" );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.tabs' );
		$vsPrint->addJavaScriptFile ( "jquery.ui/ui.mouse" );
		$vsPrint->addJavaScriptFile ( 'redsun/vs.ajax' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.position' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.dialog' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.draggable' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.accordion' );
		$vsPrint->addJavaScriptFile ( 'jquery.ui/ui.alerts' );
		$vsPrint->addJavaScriptFile ( 'redsun/jquery.numeric' );
		$vsPrint->addJavaScriptFile ( "jquery.ui/ui.datepicker" );
		$vsPrint->addJavaScriptFile ( "jquery.ui/ui.autocomplete" );
		
		$vsPrint->addJavaScriptFile ( "swfupload/swfupload",1 );
		$vsPrint->addJavaScriptFile ( "swfupload/handlers",1 );
		//    	$vsPrint->addJavaScriptFile('mudim');
		$vsPrint->addJavaScriptFile ( 'redsun/ddsmoothmenu' );
		$vsPrint->addJavaScriptFile ( 'redsun/jquery.timeout' );
		//    	$vsPrint->addJavaScriptString('mudim_config','
		//    			Mudim.SetMethod(0);
		//    			Mudim.PANEL_BACKGROUND="#FFFFFF";
		//    		');
		$vsPrint->addJavaScriptFile ( 'redsun/checkbox' );
		$vsPrint->addJavaScriptString ( 'topmenu', '
    		$(document).ready(function(){
                        $(document).idleTimeout({
                            inactivity:1200000,
                            noconfirm: 60000,
                            redirect_url: "' . $bw->vars ['board_url'] . '/acprs.php?vs=admins/logout",
                            alive_url: "' . $bw->vars ['board_url'] . '/acprs.php",
                            logout_url: "' . $bw->vars ['board_url'] . '/acprs.php?vs=admins/logout",
                            sessionAlive: 1200000
                        });

    			ddsmoothmenu.init({
				mainmenuid: "topmenu",
				orientation: "h",
				classname: "ddsmoothmenu vsf-topmenu",
				img: 0,
				//customtheme: ["#1c5a80", "#18374a"], //override default menu CSS background values? Uncomment: ["normal_background", "hover_background"]
				contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
				
				});
    		})' );
	}

	/**
	 * This function is for add global css
	 * @name addDefaultCSS
	 * @author BabyWolf
	 * @return void
	 */
	
	function addDefaultCSS() {
		global $vsUser, $vsPrint, $vsModule;
		
		if (! $vsUser->obj->getId ()) {
			$vsPrint->addCSSFile ( 'uvn-login' );
			return;
		}
		
		// Add the default script that only use for admin
		//    	$vsPrint->addGlobalCSSFile('jquery/base/ui.all');
		$vsPrint->addCSSFile ( 'global' );
		$vsPrint->addCSSFile ( 'ceedos' );
		$vsPrint->addCSSFile ( 'input_style' );
		
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.core' );
		$vsPrint->addGlobalCSSFile ( 'ddsmoothmenu' );
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.theme' );
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.tabs' );
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.accordion' );
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.dialog' );
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.custom' );
		$vsPrint->addGlobalCSSFile ( 'jquery.ui/theme/base/ui.datepicker' );
		$vsPrint->addGlobalCSSFile ( "jquery.ui/theme/base/ui.autocomplete" );
		
		$vsPrint->addCSSFile ( $vsModule->obj->getClass () );
	}
}

$styleLoad = new GlobalLoad ();