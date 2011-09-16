<?php
/*
 +-----------------------------------------------------------------------------
 |   VS FRAMEWORK 3.0.0
 |	Author: BabyWolf
 |	Homepage: http://vietsol.net
 |	If you use this code, please don't delete these comment line!
 |	Start Date: 21/09/2004
 |	Finish Date: 22/09/2004
 |	Version 2.0.0 Start Date: 07/02/2007
 |	Version 3.0.0 Start Date: 03/29/2009
 +-----------------------------------------------------------------------------
 */

if ( ! defined( 'IN_VSF' ) )
{
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit();
}

global $vsStd;
$vsStd->requireFile(CORE_PATH."languages/languages.php");

class languages_public extends languages
{
	private $output		= "";
	private $html       = "";

	/*-------------------------------------------------------------------------*/
	// INIT
	/*-------------------------------------------------------------------------*/

	function __construct() {
		parent::__construct();
	}
	 
	function auto_run()
	{
		global $bw;
		// What to do?
		//-------------------------------------------
		switch($bw->input['action'])
		{
			case 'image':
					$this->showImage();
				break;
			default:
					$this->switchProcess();
				break;
		}
	}
	function switchProcess() {
		global $bw, $vsLang, $vsPrint, $vsPath;

		$this->language = $this->getLangById($bw->input[1]);

		if(!$this->language->getId()) return;
		
		$vsLang->setCurrentLang($this->language);
		
		$vsPath->setupBaseUrl();
		
		$vsPrint->boink_it($_SERVER['HTTP_REFERER']);
	}
	
	function showImage(){
		global $bw, $vsStd;
		if($bw->input[2] == "") return false;
		
		unset($_SESSION['antispam']);
		$_SESSION['antispam'][$bw->input[2]]=mt_rand(100000,999999);
		$_SESSION['antispam']['user_security']=$_SESSION['antispam'][$bw->input[2]];
		$vsStd->requireFile(UTILS_PATH.'class_image.php');
		$image= new class_image();
		$image->show_gd_img($_SESSION['antispam'][$bw->input[2]]);
	}
}
?>