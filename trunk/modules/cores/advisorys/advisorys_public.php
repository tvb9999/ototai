<?php

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}

global $vsStd;
$vsStd->requireFile ( CORE_PATH . "gallerys/gallerys.php" );
$vsStd->requireFile ( CORE_PATH . 'advisorys/advisorys.php' );
$vsStd->requireFile ( CORE_PATH . 'pages/pages.php' );

class advisorys_public {
	private $html = "";
	public $output = "";

	function __construct() {
		global $vsTemplate, $vsPrint;
		$this->module = new advisorys ();
		$this->gallerys = new gallerys ();
		$this->html = $vsTemplate->load_template ( 'skin_advisorys' );
	
	}

	function auto_run() {
		global $bw;
		
		switch ($bw->input ['action']) {
			case 'send' :
				$this->sendadvisory ();
				break;
			
			case 'thanks' :
				$this->thankadvisory ();
				break;
			
			case 'detail' :
				$this->loadDetail ( $bw->input [2] );
				break;
			case 'category' :
				$this->loadCategory ( $bw->input [2] );
				break;
			
			default :
				$this->generalView ();
		}
	}

	function loadCategory($idCate = "") {
		global $vsMenu, $vsSettings, $vsLang, $vsPrint, $navigator;
		$count = 0;
		
		$cat = $vsMenu->getCategoryById ( $idCate );
		
		$this->module->setCondition ( "advisoryCatId in ({$idCate})" );
		$this->module->setOrder ( "advisoryStatus Desc,advisoryIndex Asc,advisoryId Desc" );
		$size = $vsSettings->getSystemKey ( "advisory_show_cat_num", 4 );
		$option = &$this->module->getPageList ( "{$bw->input[0]}/category/{$idCate}", 3, $size );
		$option ['objCat'] = $cat;
		if ($cat) {
			$vsPrint->mainTitle = $vsPrint->pageTitle = $cat->getTitle ();
			$navigator .= "<a title='" . $cat->getTitle () . "' href='" . $cat->getUrlCategory () . "'>" . $cat->getTitle () . "</a> ";
		} else
			$vsPrint->mainTitle = $vsPrint->pageTitle = $vsLang->getWords ( "news_and_action", "Tin tức và sự kiện" );
		
		return $this->output = $this->html->mainShowHtml ( $option );
	}

	public function loadDetail($objIds) {
		global $bw, $vsLang, $vsPrint;
		$query = explode ( '-', $objIds );
		$objId = abs ( intval ( $query [count ( $query ) - 1] ) );
		$obj = $this->module->getObjectById ( $objId );
		if (! $obj)
			return $vsPrint->redirect_screen ( $vsLang->getWords ( 'obj_empty', 'Không có dữ liệu theo yêu cầu' ) );
		
		$this->module->vsRelation->setRelId ( $obj->getId () );
		$this->module->vsRelation->setTableName ( "gallery_advisory" );
		$strId = $this->module->vsRelation->getObjectByRel ();
		
		$vsPrint->mainTitle = $vsPrint->pageTitle = $obj->getTitle ();
		$option = $this->module->getOtherList ( $obj, "{$bw->input[0]}/detail/{$objIds}" );
		$option ['objCat'] = $this->module->getCategories ();
		
		return $this->output = $this->html->loadDetail ( $obj, $option );
	}

	function generalView() {
		global $bw, $vsPrint, $vsLang, $vsStd, $vsSettings;
		
		$ids = $this->module->vsMenu->getChildrenIdInTree ( $this->module->getCategories () );
		$this->module->setCondition ( "advisoryStatus > 0 and advisoryCatId in ($ids)" );
		
		$option = $this->module->getPageList ( "advisorys", 1, 4 );
		$option ['objCat'] = $this->module->getCategories ();
		
		$this->output = $this->html->mainShowHtml ( $option );
	}

	function sendadvisory() {
		global $bw, $vsLang, $vsSettings;
		
		$bw->input ['advisoryPostDate'] = time ();
		
		$default_profile = array ('advisoryAddress' => $bw->input ['advisoryAddress'], 'advisoryPhone' => $bw->input ['advisoryPhone'], 'advisoryCompany' => $bw->input ['advisoryCompany'] );
		
		$bw->input ['advisoryCatId'] = $bw->input ['advisoryCatId'] ? $bw->input ['advisoryCatId'] : $this->module->categories->getId ();
		$bw->input ['advisoryProfile'] = serialize ( $default_profile );
		
		$this->module->obj->convertToObject ( $bw->input );
		$this->module->insertObject ( $this->module->obj );
		
		if ($this->error)
			$this->sendadvisoryError ();
		else
			$this->thankadvisory ();
	}

	function errorCallback($advisoryType = 0, $error) {
		global $bw, $vsStd;
		$vsStd->requireFile ( UTILS_PATH . 'PostParser.class.php' );
		
		$parser = new PostParser ();
		$parser->pp_nl2br = 1;
		
		if ($advisoryType) {
			$this->consulting ();
			$bw->input ['advisoryCompany'] = $parser->post_db_parse_html ( $bw->input ['advisoryCompany'] );
			$bw->input ['advisoryAddress'] = $parser->post_db_parse_html ( $bw->input ['advisoryAddress'] );
			
			$this->output .= <<<EOF
				<script type='text/javascript'>
					reloadadvisoryInformation(jsonObj);
				</script>
EOF;
		} else {
			$bw->input ['advisoryMessage'] = $parser->post_db_parse_html ( $bw->input ['advisoryMessage'] );
			$this->output = <<<EOF
				<div id='errorDisplay'>
					<b>The following errors were found</b>:<br />{$error}
				</div>
				<script type='text/javascript'>
					setTimeout('removeDiv()', 3000);
					function removeDiv(){
	    				$('#errorDisplay').fadeOut('slow');
					}
				  	$('#recaptcha_response_field').focus();
				  	$('#recaptcha_response_field').addClass('vs-error');
					$('#advisoryName').attr("value","{$bw->input['advisoryName']}");
					$('#advisoryAddress').attr("value","{$bw->input['advisoryAddress']}");
					$('#advisoryPhone').attr("value","{$bw->input['advisoryPhone']}");
					$('#advisoryEmail').attr("value","{$bw->input['advisoryEmail']}");
					$('#advisoryTitle').attr("value","{$bw->input['advisoryTitle']}");
					$('#advisoryMessage').attr("value","{$bw->input['advisoryMessage']}");
					refreshIdentifyCode();
				</script>
EOF;
		}
	}

	function sentadvisoryByEmail($addon_profile) {
		global $vsStd, $vsLang, $bw, $vsSettings;
		$vsStd->requireFile ( LIBS_PATH . "Email.class.php", true );
		$this->email = new Emailer ();
		
		$message = "<strong>Name:</strong> {$this->module->obj->getName()}<br />
					<strong>Email:</strong> {$this->module->obj->getEmail()}<br />";
		$message .= "<strong>Address:</strong>" . $addon_profile [0] . "<br /><strong>Phone:</strong>" . $addon_profile [1] . "<br />";
		$message .= "<strong>Message subject:</strong> {$this->module->obj->getTitle()}<br />
				    <strong>Message:</strong>" . $this->module->obj->getContent ();
		$this->email->setTo ( $vsSettings->getSystemKey ( "advisory_emailRecerver", "admin@vietsol.net" ) );
		
		$this->email->setFrom ( $this->module->obj->getEmail () );
		$this->email->setSubject ( $vsLang->getWords ( 'advisorySubject', 'advisory' ) );
		$this->email->setMessage ( $message );
		
		$this->email->send_mail ();
	}

	function thankadvisory() {
		global $vsPrint, $vsLang, $bw;
		$text = $vsLang->getWords ( 'advisory_redirectText', 'Câu hỏi của bạn đã được gửi' );
		$url = 'window.location.href="'.$bw->base_url.'advisorys"';
		$vsPrint->addJavaScriptString("sa","
		$.loadings.width = 300
					sLoading('$text')
					setTimeout('$url',1000);
		");
	}

	function sendadvisoryError() {
		global $vsLang;
		$this->output = $vsLang->getWords ( 'advisory_sendContentError', 'The following errors were found! Unknow!' );
	}

	function __destruct() {
		unset ( $this->html );
		unset ( $this->ouput );
	}

	function getOutput() {
		return $this->output;
	}

}
?>