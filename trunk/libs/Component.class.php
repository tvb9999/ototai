<?php
class VSFComponent {
	
	public $aInstalledComponent = array ();

	function __construct() {
		global $DB, $vsStd;
		
		$DB->simple_construct ( array ('select' => '*', 'from' => 'components', 'where' => 'comInstalled=1' ) );
		$DB->simple_exec ();
		
		$com = $DB->fetch_row ();
		while ( $com ) {
			$this->aInstalledComponent [$com ['comPackage']] = $com ['comPackage'];
			
			$comClass = "COM_" . $com ['comPackage'];
			$vsStd->requireFile ( COM_PATH . $com ['comPackage'] . "/" . $com ['comPackage'] . ".php", true, true );
			
			$this->$com ['comPackage'] = new $comClass ();
			$com = $DB->fetch_row ();
		}
	}

	function __destruct() {
	
	}

	function injectLastProcess() {
		
		foreach ( $this->aInstalledComponent as $com ) {
			if (method_exists ( $this->$com, 'lastProcess' )) {
				$this->$com->lastProcess ();
			}
		}
	}
}
?>