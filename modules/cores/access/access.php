<?php

require_once(CORE_PATH."access/Access.class.php");
class accesses extends VSFObject {
	public $obj;

	function __construct(){
		parent::__construct();
		$this->primaryField 	= 'accessId';
		$this->basicClassName 	= 'Access';
		$this->tableName 		= 'access';
		$this->cache_path="./cache/".APPLICATION_TYPE."_access.php";
		$this->obj = $this->createBasicObject();
		$this->createAccess();
	}

	function createAccess(){
		global $bw,$DB;
		if( file_exists($this->cache_path)&&!RELOAD_CACHE)
		{
			require_once ($this->cache_path);
			return $this->setArrayObj($access);// cái này tự động được require $arraySystemSetting
		}
		$vars=$this->getObjectsByCondition("getRelUrl");
		$this->buildCache($vars);
	}
	function buildCache($vars=NULL){
		global $bw,$DB;
		if(!$vars)
		$vars=$this->getObjectsByCondition("getRelUrl");
		$cache_content  = "<?php\n";
		$cache_content .= "\$access = ".var_export($vars,true).";\n";
		$cache_content .= "?>";
		$file = fopen($this->cache_path, "w");
		fwrite($file, $cache_content);
		fclose($file);
		unset($vars);
	}

	function insertAccess(){
		global  $bw;
		$this->obj->setModule($bw->input[0]);
		$this->obj->setAction($bw->input['action']);
		$this->obj->setTime(time());
		$this->obj->setHits(1);
		$this->insertObject($this->obj);
		$this->buildCache();
	}

	function updateAccess($obj){
		$this->obj->setId($obj->getId());
		$this->obj->setHits($obj->getHits()+1);
		$this->obj->setTime(time());
		$this->updateObjectById($this->obj);
		$this->buildCache();
	}

	function getObjectByKey($vars){
		if($this->arrayObj[$vars])
		return $this->arrayObj[$vars];
		return false;
	}

}
?>