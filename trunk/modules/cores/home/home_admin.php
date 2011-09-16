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

class home_admin
{
	private $output		= "";
	private $html       = "";
	public $vsf;

	/**
	 * @return unknown
	 */
	public function getHtml() {
		return $this->html;
	}

	/**
	 * @return unknown
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * @param unknown_type $html
	 */
	public function setHtml($html) {
		$this->html = $html;
	}

	/**
	 * @param unknown_type $output
	 */
	public function setOutput($output) {
		$this->output = $output;
	}

	function __construct(){
		global $bw, $vsTemplate;
		 
		$this->base_url = $bw->base_url;
		$this->html = $vsTemplate->load_template('skin_home');
	}
	 
	function auto_run(){
		$this->loadDefault();
	}

	function loadDefault(){
		$this->output = $this->html->MainHome($data);
		$this->output	=	str_replace("<!--MAIL ERROR LOGS-->",$this->getMailErrorLog(),$this->output);
	}

	function getMailErrorLog(){
		global $bw, $DB, $vsStd;

		$vsStd->requireFile(LIBS_PATH."DateTime.class.php");
		$dtObject = new VSFDateTime();

		$html	=	"";
		$DB->simple_construct(array(	'select'	=>	'*',
										'from'		=>	'mail_error_logs',
										'order'		=>	'mlog_date DESC',
										'limit'		=>	array(0,5)
		));
		$DB->simple_exec();
		$MailErrorLogElement	=	"";
		while($errorLog	=	$DB->fetch_row()){
			$errorLog['mlog_date']	=	$dtObject->getDate($errorLog['mlog_date'],'LONG');
			$MailErrorLogElement	.=	$this->html->MailErrorLogElement($errorLog);
		}
		$html	.=	$MailErrorLogElement;
		return $html;
	}
}
?>