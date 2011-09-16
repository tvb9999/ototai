<?phpclass Access{	private $id;	private $module;	private $action;	private $relUrl;	private $aliasUrl;	private $hits;
	private $time;
	public function __construct(){	}	function __set_state($array=array()) {		$Obj = new Access();		foreach ($array as $key => $value) {			$Obj->$key = $value;		}		return $Obj;	}	public function convertToDB() {		isset ( $this->id ) 			? ($dbobj ['accessId'] 		= $this->id) 				: '';		isset ( $this->module ) 		? ($dbobj ['accessModule'] 	= $this->module) 			: '';		isset ( $this->action ) 		? ($dbobj ['accessAction'] 	= $this->action) 			: '';		isset ( $this->relUrl ) 		? ($dbobj ['relUrl']	 	= $this->relUrl) 			: '';		isset ( $this->aliasUrl ) 		? ($dbobj ['aliasUrl']		= $this->aliasUrl) 			: '';		isset ( $this->hits ) 			? ($dbobj ['accessHits']	= $this->hits) 				: '';		isset ( $this->time ) 			? ($dbobj ['accessTime'] 	= $this->time) 				: '';		return $dbobj;	}	function convertToObject($object) {		isset ( $object ['accessId'] ) 		? $this->setId ( $object ['accessId'] ) 			: '';		isset ( $object ['accessModule'] ) 	? $this->setModule ( $object ['accessModule'] ) 	: '';		isset ( $object ['accessAction'] ) 	? $this->setAction ( $object ['accessAction'] ) 	: '';		isset ( $object ['relUrl'] ) 		? $this->setRelUrl ( $object ['relUrl'] ) 			: '';		isset ( $object ['aliasUrl'] ) 		? $this->setAliasUrl( $object ['aliasUrl'] ) 		: '';		isset ( $object ['accessTime'] ) 	? $this->setTime ( $object ['accessTime'] ) 		: '';		isset ( $object ['accessHits'] ) 	? $this->setHits ( $object ['accessHits'] ) 		: '';	}	/**	 * @return the $id	 */
	public function getId() {
		return $this->id;
	}
	/**	 * @return the $module	 */
	public function getModule() {
		return $this->module;
	}
	/**	 * @return the $action	 */
	public function getAction() {
		return $this->action;
	}
	/**	 * @return the $relUrl	 */
	public function getRelUrl() {
		return $this->relUrl;
	}
	/**	 * @return the $aliasUrl	 */
	public function getAliasUrl() {
		return $this->aliasUrl;
	}
	/**	 * @return the $hits	 */
	public function getHits() {
		return $this->hits;
	}
	/**	 * @return the $time	 */
	public function getTime() {
		return $this->time;
	}
	/**	 * @param $id the $id to set	 */
	public function setId($id) {
		$this->id = $id;
	}
	/**	 * @param $module the $module to set	 */
	public function setModule($module) {
		$this->module = $module;
	}
	/**	 * @param $action the $action to set	 */
	public function setAction($action) {
		$this->action = $action;
	}
	/**	 * @param $relUrl the $relUrl to set	 */
	public function setRelUrl($relUrl) {
		$this->relUrl = $relUrl;
	}
	/**	 * @param $aliasUrl the $aliasUrl to set	 */
	public function setAliasUrl($aliasUrl) {
		$this->aliasUrl = $aliasUrl;
	}
	/**	 * @param $hits the $hits to set	 */
	public function setHits($hits) {
		$this->hits = $hits;
	}
	/**	 * @param $time the $time to set	 */
	public function setTime($time) {
		$this->time = $time;
	}

}
?>