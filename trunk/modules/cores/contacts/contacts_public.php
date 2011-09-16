<?php

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}

global $vsStd;
$vsStd->requireFile ( CORE_PATH . 'contacts/contacts.php' );
$vsStd->requireFile ( CORE_PATH . 'pages/pages.php' );

class contacts_public {
	private $html = "";
	public $output = "";
	public $model;

	function __construct() {
		global $vsTemplate, $vsPrint;
		$this->model = new contacts ();
		$this->html = $vsTemplate->load_template ( 'skin_contacts' );
	}

	function auto_run() {
		global $bw, $vsTemplate;
		
		switch ($bw->input ['action']) {
			case 'viewform' :
				print $this->html->contactForm ();
				exit ();
				break;
			case 'send' :
				$this->sendContact ();
				break;
			
			case 'thanks' :
				$this->thankContact ();
				break;
			
			default :
				$this->generalView ();
		}
	}

	function generalView($opt = array()) {
		global $bw, $vsStd, $vsSettings;
		
			$vsStd->requireFile ( CORE_PATH . 'pages/pages_public.php' );
			$page = new pages ();
			$option['page'] = $page->getObjByCode( $bw->input ['module'] );
			$option ['Longitude'] = $option['page']->getLongitude();
			$option ['Latitude'] = $option['page']->getLatitude();
			if($option['page']->getAddGoogle())$bw->vars ['company_address'] = $option['page']->getAddGoogle();
		
		if ($vsSettings->getSystemKey ( "setting_google_map", 1, "contacts", 1, 1 )) {
			$option ['Longitude'] = $vsSettings->getSystemKey ( "google_Longitude", 106.6719, "contacts", 1, 1 );
			$option ['Latitude'] = $vsSettings->getSystemKey ( "google_Latitude", 10.816641, "contacts", 1, 1 );
		}
		
		$this->output = $this->html->generalView ( $option );
	}

	function sendContact() {
		global $bw, $vsLang, $vsSettings, $DB;
		
		$bw->input ['contactPostDate'] = time ();
		
		$default_profile = array ("contactAddress" => $bw->input ['contactAddress'], "contactPhone" => $bw->input ['contactPhone'], "contactCompany" => $bw->input ['contactCompany'], "contactCountry" => $bw->input ['contactCountry'], "contactMobile" => $bw->input ['contactMobile'] );
		
		$bw->input ['contactProfile'] = serialize ( $default_profile );
		$this->model->obj->convertToObject ( $bw->input );
		
		$this->model->insertObject ();
		
		if ($vsSettings->getSystemKey ( "contact_sendMail", 1, "contacts" ))
			$this->sentContactByEmail ( $default_profile );
		
		if ($this->model->error != "")
			return $this->sendContactError ();
		
		global $vsPrint;
		$message = $vsLang->getWords ( 'thank_message', 'Your message have been sent' );
		$vsPrint->redirect_screen ( $message );
	
	}

	function sentContactByEmail($addon_profile) {
		global $vsStd, $vsLang, $bw, $vsSettings;
		$vsStd->requireFile ( LIBS_PATH . "Email.class.php", true );
		$this->email = new Emailer ();
		
		$message = "<strong>Name:</strong> {$this->model->obj->getName()}<br />
					<strong>Email:</strong> {$this->model->obj->getEmail()}<br />";
		$message .= "<strong>Address:</strong>" . $addon_profile ["contactAddress"] . "<br /><strong>Phone:</strong>" . $addon_profile ["contactPhone"] . "<br /><strong>Company:</strong>" . $addon_profile ["contactCompany"] . "<br /><strong>Mobile:</strong>" . $addon_profile ["contactMobile"] . "<br />";
		$message .= "<strong>Message subject:</strong> {$this->model->obj->getTitle()}<br />
				    <strong>Message:</strong>" . $this->model->obj->getContent ();
		
		$this->email->setTo ( $vsSettings->getSystemKey ( "contact_emailrecerver", "sanhpotter@redsunic.com","global",0 ) );
		$this->email->setFrom ( $this->model->obj->getEmail (), $this->model->obj->getTitle () );
		$this->email->setSubject ( $vsLang->getWords ( 'contactSubject', 'Contact' ) );
		$this->email->setBody ( $message );
		
		$this->email->sendMail ();
	}

	function thankcontact($url = "contacts") {
		global $vsLang, $vsPrint;
		$text = $vsLang->getWords ( 'contact_redirectText', 'Thankyou! Yours message have been sent.' );
		$this->output = $this->html->thankyou ( $text, $url );
	}

	function sendContactError() {
		global $vsLang;
		$this->output = $vsLang->getWords ( 'contact_sendContentError', 'The following errors were found! Unknow!' );
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