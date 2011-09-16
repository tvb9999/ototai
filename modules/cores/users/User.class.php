<?php
class User extends BasicObject {
	private $name = null;
	private $password = null;
	private $birthday = null;
	private $arrayinfo = null;
	private $address = null;
	private $phone = null;
	private $email = null;
	private $lastLogin = null;
	private $joinDate = null;
	private $avatar = null;
	private $firstName = null;
	private $province = null;
	private $score = NULL;
	private $newsletter = NULL;
	private $mobile = NULL;
	private $dealer = NULL;
	private $groups = array ();

	function __construct() {
		parent::__construct ();
	}

	/**
	 * @param $arrayinfo the $arrayinfo to set
	 */
	function __destruct() {
		unset ( $this->score );
		unset ( $this->name );
		unset ( $this->password );
		unset ( $this->birthday );
		unset ( $this->address );
		unset ( $this->phone );
		unset ( $this->fax );
		unset ( $this->email );
		unset ( $this->lastLogin );
		unset ( $this->joinDate );
		unset ( $this->arrayinfo );
		unset ( $this->avatar );
		unset ( $this->newsletter );
		unset ( $this->mobile );
		unset ( $this->groups );
		unset ( $this->firstName );
		unset ( $this->province );
		unset ( $this->dealer );
	}

	function validate() {
		global $vsLang;
		$status = true;
		if ($this->getName () == "") {
			$this->message .= $vsLang->getWords ( 'err_user_name_blank', "Username can't be left blank!" );
			$status = false;
		}
		return $status;
	}

	/**
	 * change User object to array to insert database
	 * @return array $dbobj
	 */
	///////////// infomation gom firstName ,lastName ,Gender ,Birthday ,Company ,Fax ,Town ,City ,Country
	function convertToDB() {
		$dbobj ['userId'] = $this->id ? $this->id : 0;
		$dbobj ['userName'] = $this->name ? $this->name : '';
		$dbobj ['userInfo'] =  is_array($this->arrayinfo) ? serialize ( $this->arrayinfo ) : '';
		
		$dbobj ['userAddress'] = $this->address ? $this->address : '';
		$dbobj ['userEmail'] = $this->email ? $this->email : '';
		isset ( $this->joinDate ) ? $dbobj ['userJoinDate'] = $this->joinDate : '';
		//$dbobj ['userLastLogin'] = $this->lastLogin ? $this->lastLogin : '';
		isset ( $this->lastLogin ) ? $dbobj ['userLastLogin'] = $this->lastLogin : '';
		//$dbobj ['userStatus'] = $this->status ? $this->status : 0;
		isset ( $this->status ) ? $dbobj ['userStatus'] = $this->status : 1;
		
		// Password will just set when user have input it
		if ($this->password)
			$dbobj ['userPassword'] = $this->password;
		return $dbobj;
	}

	/**
	 * change User from database object to User object
	 * @param array $dbobj Database object
	 * @return void
	 *
	 */
	function convertToObject($object) {
		
		$this->setId ( $object ['userId'] ? $object ['userId'] : 0 );
		$this->setName ( $object ['userName'] ? $object ['userName'] : '' );
		$this->password = $object ['userPassword'] ? $object ['userPassword'] : '';
		$this->setAddress ( $object ['userAddress'] ? $object ['userAddress'] : '' );
		
		$this->setEmail ( $object ['userEmail'] ? $object ['userEmail'] : '' );
		//$this->setJoinDate ( isset( $object ['userJoinDate'] )? $object ['userJoinDate'] : '' );
		isset ( $object ['userJoinDate'] ) ? $this->setJoinDate ( $object ['userJoinDate'] ) : '';
		//$this->setLastLogin ( isset( $object ['userLastLogin'] )? $object ['userLastLogin'] : '' );
		isset ( $object ['userLastLogin'] ) ? $this->setLastLogin ( $object ['userLastLogin'] ) : '';
		//$this->setStatus ( isset( $object ['userStatus'] )? $object ['userJoinDate'] : '' );
		isset ( $object ['userStatus'] ) ? $this->setStatus ( $object ['userStatus'] ) : '';
		
		isset ( $object ['userJoinDate'] ) ? $this->setJoinDate ( $object ['userJoinDate'] ) : '';
		isset ( $object ['userInfo'] ) ? $this->setArrayInfo ( unserialize ( $object ['userInfo'] ) ) : '';
		isset ( $object ['userAvatar'] ) ? $this->setAvatar ( $object ['userAvatar'] ) : '';
		isset ( $object ['userFirstName'] ) ? $this->setFirstName ( $object ['userFirstName'] ) : '';
		isset ( $object ['userFullName'] ) ? $this->setFullName ( $object ['userFullName'] ) : '';
		isset ( $object ['userGender'] ) ? $this->setGender ( $object ['userGender'] ) : '';
		isset ( $object ['userBirthday'] ) ? $this->setBirthday ( $object ['userBirthday'] ) : '';
		isset ( $object ['userPhone'] ) ? $this->setPhone ( $object ['userPhone'] ) : '';
		isset ( $object ['userMobile'] ) ? $this->setMobile ( $object ['userMobile'] ) : '';
		isset ( $object ['userCompany'] ) ? $this->setCompany ( $object ['userCompany'] ) : '';
		isset ( $object ['userAgent'] ) ? $this->setAgen ( $object ['userAgent'] ) : '';
		isset ( $object ['userPosition'] ) ? $this->setPosition ( $object ['userPosition'] ) : '';
		
		isset ( $object ['realInterested'] ) ? $this->setRealInterested ( $object ['realInterested'] ) : '';
		isset ( $object ['projectInterested'] ) ? $this->setProjectInterested ( $object ['projectInterested'] ) : '';
		isset ( $object ['userFax'] ) ? $this->setFax ( $object ['userFax'] ) : '';
		
		isset ( $object ['userTown'] ) ? $this->setTown ( $object ['userTown'] ) : '';
		isset ( $object ['userNewsletter'] ) ? $this->setNewsletter ( $object ['userNewsletter'] ) : '';
		isset ( $object ['userCity'] ) ? $this->setCity ( $object ['userCity'] ) : '';
		isset ( $object ['userCountry'] ) ? $this->setCountry ( $object ['defaultAvatar'] ) : '';
		isset ( $object ['userSkype'] ) ? $this->setYahoo ( $object ['userSkype'] ) : '';
		isset ( $object ['userYahoo'] ) ? $this->setSkype ( $object ['userYahoo'] ) : '';
		isset ( $object ['userMobile'] ) ? $this->setMobile ( $object ['userMobile'] ) : '';
		isset ( $object ['userProvince'] ) ? $this->setProvince ( $object ['userProvince'] ) : '';
		isset ( $object ['userScore'] ) ? $this->setScore ( $object ['userScore'] ) : '';
		isset ( $object ['userDealer'] ) ? $this->setDealer ( $object ['userDealer'] ) : '';
	}

	function getStatus($type = null) {
		global $bw;
		if (! $type)
			return $this->status;
		if ($type == "image") {
			$imgArray = array ('disabled.png', 'enable.png', 'home.gif' );
			return $this->status = "<img src='{$bw->vars ['img_url']}/{$imgArray[$this->getStatus()]}' alt='{$this->getStatus()}' />";
		}
		if ($type == "text")
			return $this->status ? "Hiển thị" : "Ẩn";
	}

	function addGroup($group) {
		$this->groups [$group->getId ()] = $group;
	}

	/**
	 * @return array object $this->groups of User class
	 */
	function getGroups() {
		return $this->groups;
	}

	/**
	 * get array Groups object of GroupUser class
	 *
	 * @return array object $this->groups of User class
	 */
	function getArrayInfo() {
		return $this->arrayinfo;
	}

	/**
	 * get the Name of User class
	 *
	 * @return string $this->name of User class
	 */
	function getName() {
		return $this->name;
	}

	/**
	 *
	 */
	function getNick() {
		return $this->nick;
	}

	//get all info
	/**
	 * @return array object $this->groups of User class
	 */
	function getAvatar() {
		return $this->arrayinfo ['userAvatar'];
	}

	function getDealer() {
		return $this->arrayinfo ['userDealer'];
	}

	function getScore() {
		return $this->arrayinfo ['userScore'];
	}

	function getFirstName() {
		return $this->arrayinfo ['userFirstName'];
	}

	/**
	 * @return array object $this->groups of User class
	 */
	function getCompany() {
		return $this->arrayinfo ['userCompany'];
	}

	/**
	 * @return unknown
	 */
	function getYahoo() {
		return $this->arrayinfo ['userYahoo'];
	}

	/**
	 * @return unknown
	 */
	function getSkype() {
		return $this->arrayinfo ['userSkype'];
	}

	function getPassword() {
		return $this->password;
	}

	function getFullName() {
		return $this->arrayinfo ['userFullName'];
	}

	function getGender() {
		return $this->arrayinfo ['userGender'];
	}

	function getBirthday($format = "") {
		if ($format)
			return VSFDateTime::GetDate ( $this->arrayinfo ['userBirthday'], $format );
		return $this->arrayinfo ['userBirthday'];
	}

	function getAddress() {
		return $this->address;
	}

	function getPhone() {
		return $this->arrayinfo ['userPhone'];
	}

	function getProvince() {
		return $this->arrayinfo ['userProvince'];
	}

	function getFax() {
		return $this->arrayinfo ['userFax'];
	}

	function getEmail() {
		return $this->email;
	}

	function getTown() {
		return $this->arrayinfo ['userTown'];
	}

	function getCity() {
		return $this->arrayinfo ['userCity'];
	}

	function getRoot() {
		return $this->root;
	}

	function getCountry() {
		return $this->arrayinfo ['userCountry'];
	}

	/**
	 * get Last Login time of User class
	 * @return  int $this->lastLogin of User class
	 */
	function getLastLogin($format = "") {
		if ($format)
			return VSFDateTime::GetDate ( $this->lastLogin, $format );
		return $this->lastLogin;
	}

	/**
	 * get Join Date of the User class
	 * @return
	 */
	function getJoinDate($format = '') {
		if ($format)
			return VSFDateTime::getDate ( $this->joinDate, $format );
		return $this->joinDate;
	}

	/**
	 * set Groups for User
	 *
	 * @param array object of GroupUser class
	 */
	function setGroups($groups = array()) {
		$this->groups = $groups;
	}

	/**
	 * @param unknown_type $productImages
	 */
	function setArrayInfo($array) {
		$this->arrayinfo = $array;
	}

	/**
	 * @param unknown_type Avatar
	 */
	function setAvatar($avatar = '') {
		$this->arrayinfo ['userAvatar'] = $avatar;
	}

	function setDealer($avatar = '') {
		$this->arrayinfo ['userDealer'] = $avatar;
	}

	function setScore($avatar = '') {
		$this->arrayinfo ['userScore'] = $avatar;
	}

	/**
	 * @param unknown_type Avatar
	 */
	function setNick($nick) {
		$this->nick = $nick;
	}

	function setName($name = "") {
		$this->name = strtolower ( $name );
	}

	function setPassword($password) {
		$this->password = md5 ( $password );
	}

	function setFullName($name) {
		$this->arrayinfo ['userFullName'] = $name;
	}

	function setProvince($name) {
		$this->arrayinfo ['userProvince'] = $name;
	}

	function setCompany($company = '') {
		$this->arrayinfo ['userCompany'] = $company;
	}

	function setFirstName($firstname = '') {
		$this->arrayinfo ['userFirstName'] = $firstname;
	}

	function setGender($gender) {
		$this->arrayinfo ['userGender'] = $gender;
	}

	function setBirthday($birthday) {
		$this->arrayinfo ['userBirthday'] = $birthday;
	}

	function setFax($fax) {
		$this->arrayinfo ['userFax'] = $fax;
	}

	function setYahoo($yahoo) {
		$this->arrayinfo ['userYahoo'] = $yahoo;
	}

	function setSkype($skype) {
		$this->arrayinfo ['userSkype'] = $skype;
	}

	function setTown($town) {
		$this->arrayinfo ['userTown'] = $town;
	}

	function setCity($city) {
		$this->arrayinfo ['userCity'] = $city;
	}

	function setCountry($country) {
		$this->arrayinfo ['userCountry'] = $country;
	}

	function setAddress($address) {
		$this->address = $address;
	}

	function setPhone($phone) {
		$this->arrayinfo ['userPhone'] = $phone;
	}

	function setEmail($email) {
		$this->email = $email;
	}

	function setLastLogin($lastLogin) {
		$this->lastLogin = $lastLogin;
	}

	function setJoinDate($joinDate) {
		$this->joinDate = $joinDate;
	}

	function convertRelToDB($group) {
		$dbobj = array ('userId' => $this->id, 'groupId' => $group->getId () );
		return $dbobj;
	}

	function getNewsletter() {
		return $this->arrayinfo ['userNewsletter'];
	}

	function setNewsletter($newsletter) {
		$this->arrayinfo ['userNewsletter'] = $newsletter;
	}

	function getMobile() {
		return $this->arrayinfo ['userMobile'];
	}

	function setMobile($mobile) {
		$this->arrayinfo ['userMobile'] = $mobile;
	}
}
?>