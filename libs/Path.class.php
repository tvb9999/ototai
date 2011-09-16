<?php
class VSFPath {
	
	private $paramIndex = 0;
	private $alias = array();
	private $realurl = array();
	private $seo 	= array();
	
	function __construct() {
		global $bw, $vsLang;
		$flaglang = 0;
		$urls = explode('/',$bw->input['vs']);
		
		$languages = new languages ();
		if(APPLICATION_TYPE=='user'){
			if(strlen($urls[0]) > 0 && strlen($urls[0]) < 3){
				foreach ($languages->arrayLang as $value)
					if ($value->getFolderName()==$urls[0] ){
						$vsLang->setCurrentLang($value);
						break;
					}
			}
		}

		$this->initPath();
		if(APPLICATION_TYPE!='install')
			if(!$vsLang->currentLang->getUserDefault() && APPLICATION_TYPE=='user' ) {
				$bw->input['vs'] = str_replace($vsLang->currentLang->getFolderName()."/",'',$bw->input['vs']);
				$this->initPath();
			}

		$clean_url = explode("/",trim($bw->input['vs'],"/"));
		
		$bw->input = array_merge($clean_url, $bw->input);
		$bw->input['module'] = $bw->input[0];
		$bw->input['action'] = $bw->input[1];
		$this->setupBaseUrl();
	}
	
	function initPath() {
		global $bw;
		
		if(!$bw->input['vs'] || !isset($bw->input ['vs'])) {
			switch(APPLICATION_TYPE) {
			case 'admin': $bw->input['vs'] = $bw->vars['admin_frontpage'];	
				break;
			
			case 'user': $bw->input['vs'] = $bw->vars['public_frontpage'];
				break;
			}
		}
	}
	
	function setupBaseUrl() {
		global $bw, $vsLang;

		if(APPLICATION_TYPE=='admin')
			$bw->base_url = $bw->vars['board_url'].'/acprs.'.$bw->vars['php_ext'].'?vs=';
		else {
			if($bw->vars['public_cleanurl']=='Yes') {
				$bw->base_url = $bw->vars['board_url'].'/';
				if(!$vsLang->currentLang->getUserDefault())
					$bw->base_url = $bw->vars['board_url'].'/'.$vsLang->currentLang->getFolderName().'/';
				
			}
			else{
				$language = $vsLang->currentLang->getFolderName()."/";
				$bw->base_url = $bw->vars['board_url'].'/'.'index.'.$bw->vars['php_ext'].'?vs=';
				if(!$vsLang->currentLang->getUserDefault())
					$bw->base_url = $bw->vars['board_url'].'/'.'index.'.$bw->vars['php_ext'].'?vs='.$vsLang->currentLang->getFolderName().'/';				
				
				
			}
			if(APPLICATION_TYPE=='install')
				$bw->base_url = $bw->vars['board_url'].'/installer/index.'.$bw->vars['php_ext'].'?vs=';
		}
	}
}
?>