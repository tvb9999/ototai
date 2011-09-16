<?php

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}

class users_public {
	public $output = "";
	private $html = "";
	protected $module = "";

	function getOutput() {
		return $this->output;
	}

	function setOutput($outputHTML) {
		$this->output = $outputHTML;
	}

	function __construct() {
		global $bw, $vsTemplate;
		$this->module = new users ();
		$this->html = $vsTemplate->load_template ( 'skin_users' );
//		$vsTemplate->global_template = $this->html;
	}

	function auto_run() {
		global $bw, $vsUser, $vsLang, $member;
		
		$vsUser->authorize();
		switch ($bw->input [1]) {			
			case "resgister" :
				$this->registerUserForm ( );
				break;				
					
			case 'login-form' :
				$this->loginForm ();
				break;
				
			case 'login-process' :
				$this->loginProcess ();
				break;	
				
			case 'forgot-password-form' :
				$this->forgotPasswordForm ();
				break;		
			case 'forgot-password-process' :
				$this->forgotPasswordProcess();
				break;			
			
			case 'user-infor-form' :
				$this->userInforForm ();
				break;
			case 'user-info-process' :
				$this->userInforProcess ();
				break;		

			case 'change-pass-form' :				
				$this->changePasswordForm ();
				break;
			case 'change-pass-form-process' :
				$this->changePasswordProcess ();
				break;
			
			case 'active-account' :
				$this->activeAccount ();
				break;
				
			case 'logout-process' :
				$this->logoutProcess ();
				break;	
				
			default :
				$this->loadDefault ();
				break;
		}
	}

	function loadDefault() {
		return $this->output = $this->registerUserForm ( $this->module->obj );
	}
	
	function activeAccount() {
		global $vsLang, $vsPrint, $bw;
		$this->module->setCondition ( "userName='{$bw->input[2]}' AND userPassword='{$bw->input[3]}' " );
		$account = $this->module->getOneObjectsByCondition ();
		
		if (! is_object ( $account )) {
			$message = $vsLang->getWords ( 'wrong_account', 'Tài khoản này không tồn tại trong hệ thống!' );
			return $vsPrint->redirect_screen ( $message );
		}
		
		if ($account->getScore () || $account->getStatus ()) {
			$message = $vsLang->getWords ( 'wrong_account_active', 'Tài khoản này đã được kích hoạt!' );
			return $vsPrint->redirect_screen ( $message );
		}
		
		$abc = time ();
		$this->term->setCondition ( "termStatus > 0 AND termEndDate >= {$abc} AND termStartDate <= {$abc} " );
		$term = $this->term->getOneObjectsByCondition ();
		
		if ($term)
			$account->setScore ( $term->getScore () );
		else
			$account->setScore ( 1 );
		$account->setStatus ( 1 );
		$this->module->updateObjectById ( $account );
		return $vsPrint->redirect_screen ( $vsLang->getWords ( 'global_activesucess', 'Hoàn tất kích hoạt tài khoản! Hệ thống tự động chuyển bạn về trang đăng nhập.' ), 'users/login-form' );
	}

	function send_email($info = array()) {
		global $vsUser, $vsStd, $vsLang;
		$vsStd->requireFile ( LIBS_PATH . "Email.class.php", true );
		$this->email = new Emailer ();
		$this->email->setTo ( $vsUser->obj->getEmail () );
		$this->email->setSubject ( "Re: " . $info ['re'] );
		$this->email->setBody ( $info ['message'] );
		
		$this->email->sendMail ();
	}

	function userInforForm() {
		global $bw, $vsUser, $vsPrint, $vsLang;

		$message = '';
		if($bw->input['userId']){
			$this->userInforProcess();
		}else{
			$vsUser->obj = $vsUser->getObjectById($vsUser->obj->getId());
			$vsPrint->pageTitle = $vsPrint->mainTitle = $vsLang->getWords('user_infor','Thông tin tài khoản');
			$this->output = $this->html->userInforForm ( $message );
		}
	}
	
	function userInforProcess() {
		global $bw, $vsLang, $vsUser, $vsPrint, $vsStd;
		
		if ($bw->input ['userId']) {
			$vsStd->requireFile ( ROOT_PATH . "captcha/securimage.php" );
			$img = new Securimage();
		  	$valid = $img->check($bw->input['captcha']);
			if(!$valid){
				$message = 'validCode();';
				return $this->output = $this->html->userInforForm ( $message );
			}else{
				$message = '';
			}

			$vsUser->obj->convertToObject ( $bw->input );
			$this->module->updateObjectById ( $vsUser->obj );
			$user = $vsUser->obj;
			$vsUser->sessions->updateLoginSession ( $user );
		}
		$_SESSION [APPLICATION_TYPE] ['obj'] = $user->convertToDB ();
		
		$vsPrint->redirect_screen ( $vsLang->getWords ( 'global_renew_success1', 'Hoàn tất thay đổi thông tin! Hệ thống tự động chuyển bạn về trang thông tin thành viên.' ), 'users/user-infor-form/' );
	}

	function forgotPasswordForm() {
		global $bw, $vsStd, $vsLang, $vsUser, $vsPrint;
		$message = '';
		if($bw->input['userEmail']){
			$this->forgotPasswordProcess();
		}else{
			$vsPrint->pageTitle = $vsPrint->mainTitle = $vsLang->getWordsGlobal("user_forgot_password","Lấy Lại Mật Khẩu");
			$this->output = $this->html->forgotPasswordForm ( $message );
		}
	}

	function forgotPasswordProcess() {
		global $bw, $vsStd, $vsLang, $vsSettings, $vsPrint, $vsUser;
		
		if($bw->input['userEmail']){
			$vsStd->requireFile ( ROOT_PATH . "captcha/securimage.php" );
			$img = new Securimage();
		  	$valid = $img->check($bw->input['captcha']);
			if(!$valid){
				$message = $vsLang->getWords ( 'error_cache_re', 'Nhập lại mã xác nhận' );
				return $this->output = $this->html->forgotPasswordForm ( $message );
			}
			
			$this->module->setCondition ( "userEmail='{$bw->input['userEmail']}'" );
			$this->module->obj = $this->module->getOneObjectsByCondition ();
			
			if (! is_object ( $this->module->obj )) {
				$message = $vsLang->getWords ( 'wrong_email', 'Email này không tồn tại trong hệ thống!' );
				return $this->output = $this->html->forgotPasswordForm ( $message );
			}
			
			$vsStd->requireFile ( UTILS_PATH . "rndPass.class.php" );
			$rndpass = new rndPass(6);
			$password = $rndpass->PassGen();
			$this->module->obj->setPassword ( $password );
			$this->module->updateObjectById ( $this->module->obj );
			
			if ($vsSettings->getSystemKey ( "use_send_mail_renew_password", 1 )) {
				$vsStd->requireFile ( LIBS_PATH . "email.php", true );
				$email = new Email();
				$email->addRecipient( $this->module->obj->getEmail ());
				$email->setSubject("Re: " . $vsLang->getWords ( 'user_sendEmail_forgot', 'Re New PassWord!' ));
				$email->addHeader('MIME-Version', '1.0');
				$email->addHeader('Content-type', 'text/html; charset=utf-8');
				$email->addHeader('From', $bw->vars['global_systememail']);
				$email->setMessage($this->html->emailHtmlForgot ( $this->module->obj, $password ));
				$email->send();
			}			
			$vsPrint->redirect_screen ( $vsLang->getWords ( 'send_email_note', 'Bạn vui lòng kiểm tra email để thực hiện quá trình phục hồi mật mã!' ), 'users/login-form/' );
		}
	}
	
	function changePasswordForm() {
		global $bw, $vsUser, $vsSettings, $vsPrint, $vsLang;
		$message = '';
		if($bw->input['userId']){
			$this->changePasswordProcess();
		}else{
			$vsPrint->pageTitle = $vsPrint->mainTitle = $vsLang->getWords('user_change_pass','Thay đổi mật khẩu');
			$this->output = $this->html->changePasswordForm ( $message );
		}
	}
	
	function changePasswordProcess() {
		global $bw, $vsStd, $vsLang, $vsUser, $vsPrint;
		
		if($bw->input ['userId']){
			$vsStd->requireFile ( ROOT_PATH . "captcha/securimage.php" );
			$img = new Securimage();
		  	$valid = $img->check($bw->input['captcha']);
			if(!$valid){
				$message = $vsLang->getWords ( 'error_cache_re', 'Nhập lại mã xác nhận' );
				return $this->output = $this->html->changePasswordForm ( $message );
			}
			if ($vsUser->obj->getPassword () != md5 ( $bw->input ['userOldPassword'] )) {
				$message = $vsLang->getWords ( 'error_user_password_old', 'Mật khẩu củ không đúng!' );
				return $this->output = $this->html->changePasswordForm ( $message );
			}
			$vsUser->obj->setPassword ( $bw->input ['userNewPassword'] );
			$vsUser->updateObjectById ( $vsUser->obj );
			$vsUser->sessions->updateLoginSession ( $vsUser->obj );
			$vsPrint->redirect_screen ( $vsLang->getWords ( 'user_ChangePasswordSuccessfully', 'Hoàn tất thay đổi mật khẩu! Hệ thống tự động chuyển bạn về trang thông tin thành viên.' ), 'users/user-infor-form/' );
		}
	}

	function registerUserForm($message = "") {
		global $vsLang,$vsPrint,$bw;
		if($bw->input['captcha']) 
			$message = $this->registerUserProcess();
		if($message===true) 
			return $this->setOutput($this->html->messageRegist());
		$vsPrint->mainTitle = $vsPrint->pageTitle = $vsLang->getWords('user_title_reg','Đăng ký thành viên');
		
		return $this->output = $this->html->registerUserForm ($message);
	}
	
	function registerUserProcess() {
		global $bw, $vsStd, $vsLang, $vsUser, $vsPrint, $vsSettings;
		
		$vsStd->requireFile ( ROOT_PATH . "captcha/securimage.php" );
		$img = new Securimage();
	  	$valid = $img->check($bw->input['captcha']);
	  	
		if(!$valid){
			$message = 'validCode();';
			return $message;
		}
		$bw->input['userStatus'] = 1;
		$this->module->obj->convertToObject ( $bw->input );
		$objfail = $this->module->obj;
		$defaultGroup = $vsSettings->getSystemKey ( "user_default_group", 1, "users", 2 ) ;
		$this->module->setCondition ( "userName ='{$bw->input['userName']}' OR userEmail ='{$bw->input['userEmail']}'" );
		$users = $this->module->getObjectsByCondition ();
		if (count ( $users )) {
			foreach ( $users as $obj ) {
				if ($obj->getName () == $bw->input ['userName']) {
					$message .= $vsLang->getWords ( 'e_duplicate', 'Tài khoản này đã tồn tại trong hệ thống!' ) . "<br>";
				}
				if ($obj->getEmail () == $bw->input ['userEmail']) {
					$message .= $vsLang->getWords ( 'e_duplicate_email', 'Email này đã tồn tại trong hệ thống.Hãy nhập email khác!' ) . "<br>";
				}
			}
			$message = "$('.error').html('{$message}')";
			return $message;
		}
		$bw->input ['userJoinedDate'] = time ();
		$bw->input ['userLastLogin'] = time ();
		$bw->input ['userStatus'] = $vsSettings->getSystemKey ( "user_default_active", 1, "users", 2 );
		$groups = $this->module->groupusers->getGroupById ( $defaultGroup );
		$this->module->obj->addGroup ( $groups );
		if ($this->module->obj->getId ()) {
			$this->module->updateObjectById ( $this->module->obj );
		} else {
			$this->module->obj->setJoinDate ( time () );
			$this->module->obj->setLastLogin ( time () );
			$this->module->obj->setPassword ( $bw->input ['userPassword'] );
			$this->module->insertObject ( $this->module->obj );
		}
		
		if ($this->module->result ['status']) {
			$this->module->vsRelation->setObjectId ( $this->module->obj->getId () );
			$this->module->vsRelation->setRelId ( $defaultGroup );
			$this->module->vsRelation->setTableName ( $this->module->getRelTableName () );
			$this->module->vsRelation->insertRel ();
		}
		$this->module->sessions->updateLoginSession ( $this->module->obj );
		$vsUser->obj = $this->module->obj;
		/*
		if ($vsSettings->getSystemKey ( "user_send_mail_renew_password", 1,"users",2 )) {
			$vsStd->requireFile ( LIBS_PATH . "Email.class.php", true );
			$this->email = new Emailer ();
			$this->email->setTo ( $this->module->obj->getEmail () );
			$this->email->setSubject ( $vsLang->getWords ( 'user_info_sendEmail_forgot', 'Vui lòng check mail để kích hoạt tài khoản!' ) );
			$this->email->setBody ( $this->html->emailRegisterSuccess ( $this->module->obj ) );
			$this->email->sendMail ();
			$mes = $vsLang->getWords ( 'user_info_sendEmail_forgot', 'Vui lòng check mail để kích hoạt tài khoản!' );
		}
		*/
		$vsPrint->redirect_screen ( $vsLang->getWords ( 'global_register_success', 'Bạn đã đăng ký thành công! Hệ thống tự động chuyễn bạn về trang Chủ.' ) . "</br>" . $mes );
	
	}

	function loginForm($message = "") {
		global $bw, $vsLang,$vsPrint;
		
		if ($bw->input [2] == "nosession")
			$message = $vsLang->getWords ( 'require_login', 'Hệ thống yêu cầu Bạn phải đăng nhập trước khi truy cập chức năng này' );
		elseif ($bw->input [2] == "timeout")
			$message = $vsLang->getWords ( 'require_login_timeout', 'Tài khoản của bạn đã hết thời gian sử dụng, vui lòng đăng nhập lại' );
		$vsPrint->mainTitle = $vsPrint->pageTitle = $vsLang->getWords('user_title_login','Đăng nhập');
		return $this->output = $this->html->loginForm ( $message );
	}

	function loginProcess() {
		global $bw, $vsPrint, $vsLang,$vsStd;
		
		if($bw->input['captcha']){
			$vsStd->requireFile ( ROOT_PATH . "captcha/securimage.php" );
			$img = new Securimage();
		  	$valid = $img->check($bw->input['captcha']);
			if(!$valid){
				$message = $vsLang->getWords ( 'none_code', 'Mã xác nhận không đúng!' );
				return $this->output = $this->loginForm ( $message );
			}
		}
		
		$this->module->setCondition ( "userName ='{$bw->input['userName']}'" );
		$this->module->getOneObjectsByCondition ();
		if (! $this->module->result ['status']) {
			$message = $vsLang->getWords ( 'none_useraccount', 'Không tồn tại tài khoản này trong hệ thống!' );
			return $this->output = $this->loginForm ( $message );
		}
		if (! $this->module->obj->getStatus ()) {
			$message = $vsLang->getWords ( 'none_user_notactive', 'Tài khoản bị khóa hay chưa được kích hoạt.Liên hệ nhà quản trị' );
			$this->module->obj->__destruct ();
			return $this->output = $this->loginForm ( $message );
		}
		if ($this->module->obj->getPassword () != md5 ( $bw->input ['userPassword'] )) {
			$message = $vsLang->getWords ( 'error_user_password', 'Mật khẩu không đúng!' );
			$this->module->obj->__destruct ();
			return $this->output = $this->loginForm ( $message );
		}
		$this->module->vsRelation = new VSFRelationship ();
		$this->module->vsRelation->setObjectId ( $this->module->obj->getId () );
		$this->module->vsRelation->setTableName ( $this->module->getRelTableName () );
		$groupStr = $this->module->vsRelation->getRelByObject ();
		
		if (! $groupStr) {
			$message = $vsLang->getWords ( 'invalid_account', 'Tài khoản của bạn không hợp lệ vì chưa thuộc nhóm phân quyền nào' );
			return $this->output = $this->loginForm ( $message );
		}
		$array = $this->module->vsRelation->arrval;
		
		foreach ( $array as $id => $group ) {
			$this->module->obj->addGroup ( $this->module->groupusers->getGroupById ( $id ) );
		}
		
		if($bw->input['cookieuser']){
			$vsStd->my_setcookie("rsuserId",$this->module->obj->getId());
			$vsStd->my_setcookie("rspassword",$this->module->obj->getPassword());
		}
		$this->module->sessions->updateLoginSession ( $this->module->obj );
		$_SESSION [APPLICATION_TYPE] ['obj'] = $this->module->obj->convertToDB ();
		
		$vsPrint->redirect_screen ( $vsLang->getWords ( 'global_login_success', 'Đăng nhập thành công! Hệ thống tự động chuyển trang.' ), 'users/user-infor-form/' );
	}

	function logoutProcess($message = "") {
		global $bw, $vsLang, $vsPrint,$vsUser,$vsStd;
		unset ( $_SESSION [APPLICATION_TYPE] ['obj'] );
		unset ( $_SESSION [APPLICATION_TYPE] ['groups'] );
		unset ($_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart']);
		$_SESSION [APPLICATION_TYPE] ['session'] ['userId'] = 0;
		$returnmes = $vsLang->getWords ( 'global_logout_success', 'Bạn đã thoát khỏi hệ thống!.' );
		$vsUser = new users();
		$vsStd->my_setcookie("rsuserId",'');
		$vsStd->my_setcookie("rspassword",'');
		if ($message)
			$returnmes = $message;
		$vsPrint->redirect_screen ( $returnmes );
	}

	public function setModule($module) {
		$this->module = $module;
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	public function getModule() {
		return $this->module;
	}

	public function getHtml() {
		return $this->html;
	}

}
?>
