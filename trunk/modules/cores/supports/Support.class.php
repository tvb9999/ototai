<?php
class Support extends BasicObject {
	private $nick = null;
	private $type = null;
	private $profile = null;
	private $imageOffline = null;
	private $imageOnline = null;
	private $avatar = null;

	function convertToDB() {
		$dbobj ['supportId'] = $this->id ? $this->id : 0;
		$dbobj ['supportCatId'] = $this->catId ? $this->catId : 0;
		$dbobj ['supportNick'] = $this->nick ? $this->nick : '';
		$dbobj ['supportAvatar'] = $this->avatar ? $this->avatar : '';
		$dbobj ['supportType'] = $this->type ? $this->type : '';
		$dbobj ['supportImageOffline'] = $this->imageOffline ? $this->imageOffline : '';
		$dbobj ['supportImageOnline'] = $this->imageOnline ? $this->imageOnline : '';
		$dbobj ['supportIndex'] = $this->index ? $this->index : 0;
		$dbobj ['supportStatus'] = $this->status ? $this->status : '';
		$dbobj ['supportProfile'] = serialize ( $this->profile ) ? serialize ( $this->profile ) : '';
		return $dbobj;
	
	}

	function convertToObject($object) {
		$this->setId ( $object ['supportId'] ? $object ['supportId'] : 0 );
		$this->setCatId ( $object ['supportCatId'] ? $object ['supportCatId'] : '' );
		$this->setType ( $object ['supportType'] ? $object ['supportType'] : '' );
		$this->setAvatar ( $object ['supportAvatar'] ? $object ['supportAvatar'] : '' );
		$this->setNick ( $object ['supportNick'] ? $object ['supportNick'] : '' );
		$this->setProfile ( unserialize ( $object ['supportProfile'] ) ? unserialize ( $object ['supportProfile'] ) : '' );
		$this->setStatus ( $object ['supportStatus'] ? $object ['supportStatus'] : '' );
		$this->setIndex ( $object ['supportIndex'] ? $object ['supportIndex'] : 0 );
		$this->setImageOffline ( $object ['supportImageOffline'] ? $object ['supportImageOffline'] : '' );
		$this->setImageOnline ( $object ['supportImageOnline'] ? $object ['supportImageOnline'] : '' );
		$this->setName ( $object ['supportName'] ? $object ['supportName'] : '' );
		$this->setIntro ( $object ['supportIntro'] ? $object ['supportIntro'] : '' );
		$this->setPosition ( $object ['supportPosition'] ? $object ['supportPosition'] : '' );
	}

	/**
	 * change new to array modules to Template
	 * @return array $dbobj
	 */
	function validate() {
		global $vsLang;
		$status = true;
		if ($this->getNick () == "") {
			$this->message .= $vsLang->getWords ( "support_InformationError", "* Information cannot be blank!" );
			$status = false;
		}
		return $status;
	}

	function __construct() {
		parent::__construct ();
	}

	/**
	 * @return unknown
	 */
	/**
	 * @return the $avatar
	 */
	public function getAvatar() {
		return $this->avatar;
	}

	/**
	 * @param $avatar the $avatar to set
	 */
	public function setAvatar($avatar) {
		$this->avatar = $avatar;
	}

	/**
	 * @return unknown
	 */
	public function getNick() {
		if (strpos ( $this->nick, '@' ))
			return substr ( $this->nick, 0, strpos ( $this->nick, '@' ) - 1 );
		return $this->nick;
	}

	/**
	 * @return unknown
	 */
	public function getProfile() {
		return $this->profile;
	}

	/**
	 * @return unknown
	 */
	public function getImageOnline() {
		global $bw;
		return $this->imageOnline;
	}

	/**
	 * @return unknown
	 */
	public function getImageOffline() {
		global $bw;
		return $this->imageOffline;
	}

	/**
	 * @return unknown
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return unknown
	 */
	public function getUrl($default = true) {
		if ($default)
			return $this->status;
		return $this->status;
	
	}

	/**
	 * @param unknown_type $supportNick
	 */
	public function setNick($nick) {
		$this->nick = $nick;
	}

	public function setDepartment($department) {
		$this->department = $department;
	}

	/**
	 * @return unknown
	 */
	public function setImageOnline($image) {
		$this->imageOnline = $image;
	}

	/**
	 * @return unknown
	 */
	public function setImageOffline($image) {
		$this->imageOffline = $image;
	}

	/**
	 * @param unknown_type $Profile
	 */
	public function setProfile($profile) {
		$this->profile = $profile;
	}

	/**
	 * @param unknown_type $supportStatus
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @param unknown_type $supportType
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 *
	 * @return array object $this->groups of User class
	 */
	public function getName() {
		return $this->profile ['supportName'];
	
	}

	public function getIntro() {
		
		$parser = new PostParser ();
		$parser->pp_do_html = 1;
		$parser->pp_nl2br = 0;
		
		return $parser->post_db_parse ( $this->profile ['supportIntro'] );
	}

	/**
	 *
	 * @param int $id
	 */
	public function setIntro($value = '') {
		$this->profile ['supportIntro'] = $value;
	}

	/**
	 *
	 * @param int $id
	 */
	public function setName($value = '') {
		$this->profile ['supportName'] = $value;
	}

	/**
	 *
	 * @return array object $this->groups of User class
	 */
	public function getPosition() {
		return $this->profile ['supportPosition'];
	}

	/**
	 *
	 * @param int $id
	 */
	public function setPosition($value = '') {
		$this->profile ['supportPosition'] = $value;
	}

	function showYahoo() {
		global $bw, $vsMenu, $vsPrint;
		
		$rand = str_replace ( ".", "", $this->getNick () . rand ( 1, 100 ) );
		$BWHTML .= <<<EOF
			<a  href="ymsgr:sendIM?{$this->getNick()}" title="{$this->getNick()}" rel="nofollow">
			<img id='yahooimagenick{$rand}'  style='vertical-align:middle;border:none;' alt="" /></a>			
EOF;
		if ($this->imageOnline) {
			$image = $vsMenu->getCategoryById ( $this->imageOnline );
			$imageOnlinepath = $this->getCacheImagePathByFile ( $image->getFileId (), 78, '', 1, 1, 1, 1 );
		} else
			$imageOnlinepath = $this->defaulImageYahooOnline;
		if ($this->imageOffline) {
			$image = $vsMenu->getCategoryById ( $this->imageOffline );
			$imageOfflinepath = $this->getCacheImagePathByFile ( $image->getFileId (), 78, 18, 1, 1, 1, 1 );
		} else
			$imageOfflinepath = $this->defaulImageYahooOffLine;
			$vsPrint->addJavaScriptString ( "yahoo_{$this->getNick()}", "
				$(window).ready(function() {
					$.get('{$bw->vars['board_url']}/utils/CheckOnline.Api.php',{typecheck:'yahoo',nick:'{$this->getNick()}'},function(data){
							if(data == 1){
								$('#yahooimagenick{$rand}').attr({ 
									  src:  \"{$imageOnlinepath}\"
								});
							}else{
								$('#yahooimagenick{$rand}').attr({ 
									  src:  \"{$imageOfflinepath}\"
								});
							}
						});
				
				});
    		" );
		return $BWHTML;
	}

	function showSkype() {
		global $bw, $vsMenu, $vsPrint;
		$rand = $this->getNick () . rand ( 1, 100 );
		$rand = str_replace ( ".", "", $rand );
		$BWHTML .= <<<EOF
		<a  href="skype:{$this->getNick()}?chat" title="{$this->getNick()}" rel="nofollow">
				<img id='skypeimagenick{$rand}'  style='vertical-align:middle;border:none;' alt="" /></a>				
EOF;
		if ($this->imageOnline) {
			$image = $vsMenu->getCategoryById ( $this->imageOnline );
			$imageOnlinepath = $this->getCacheImagePathByFile ( $image->getFileId (), 78, 18, 1, 1, 1, 1 );
		} else
			$imageOnlinepath = $this->defaulImageSkype;
		if ($this->imageOffline) {
			$image = $vsMenu->getCategoryById ( $this->imageOffline );
			$imageOfflinepath = $this->getCacheImagePathByFile ( $image->getFileId (), 78, 18, 1, 1, 1, 1 );
		} else
			$imageOfflinepath = $this->defaulImageSkype;
		$vsPrint->addJavaScriptString ( "skype_{$this->getNick()}", "
				$(window).ready(function() {
					$.get('{$bw->vars['board_url']}/utils/CheckOnline.Api.php',{typecheck:'skype',nick:'{$this->getNick()}'},function(data){
								if(data == 1)
						 		{
									$('#skypeimagenick{$rand}').attr({ 
										  src: \"{$imageOnlinepath}\"
										});
								}
								else
								{
									$('#skypeimagenick{$rand}').attr({ 
										  src: \"{$imageOfflinepath}\"
										});
								}
							});
				});
	    	" );
		return $BWHTML;
	}

	function showPhone($srcImage) {
		if (file_exists ( $srcImage ))
			$image = "<img src='{$this->arrayStyle['image_phone']}' style='vertical-align:middle;border:none;' alt='{$vsLang->getWords('support_SupportImgAlt','Online Support')}'/>	";
		return "<span>{$this->getNick()}</span>
		{$image}
			";
	}

	function CheckSkyOnline($skyid) {
		$status = trim ( @file_get_contents ( "http://mystatus.skype.com/" . urlencode ( $skyid ) . ".num" ) );
		if ($status > 1)
			return true;
		return false;
	}

	/**
	 * @param unknown_type $supportStatus
	 */
	public function show() {
		global $vsStd;
		if ($this->type == 1) {
			return $this->showYahoo ();
		}
		if ($this->type == 2) {
			return $this->showSkype ();
		}
		
		return $this->showPhone ();
	}

	function __destruct() {
		unset ( $this->id );
		unset ( $this->nick );
		unset ( $this->profile );
		unset ( $this->status );
	}
}

?>