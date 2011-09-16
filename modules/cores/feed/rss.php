<?php
require_once (CORE_PATH . "pages/pages.php");
require_once (UTILS_PATH . "rss.class.php");
class rss extends pages {
	
	public $rss;

	function __construct() {
		parent::__construct ();
		$this->rss = new classRss ();
		if (! is_dir ( "./rss" )) {
			mkdir ( "./rss", 0775, true );
		}
	}

	function __destruct() {
		unset ( $this );
	}

	function getFeedModule($module, $fileName = '') {
		global $bw, $vsPrint, $vsStd,$vsLang;
		
		$channel_id = $this->rss->createNewChannel ( array ('title' => $vsLang->getWords("global_rss_title","Tổng công ty Cà Phê Việt Nam"), 'link' => "{$bw->base_url}$table", 'description' => $vsLang->getWords('global_rss_description','Thông tin thị trường cà phê Việt Nam'), 'pubDate' => $this->rss->formatDate ( time () ) ) );
		$news = $this->getHostListByModule ( $module, 10 );
		if(!is_array($news)) return;
		foreach ( $news as $value ) {
			$intro = $value->getContent ( 450 );
			if ($value->getIntro ())
				$intro = $value->getIntro ();
			$this->rss->addItemToChannel ( $channel_id, array ('title' => "{$value->getTitle()}", 'link' => "{$value->getUrl($module)}", 'description' => "{$intro}", 'content' => "$intro", 'pubDate' => $this->rss->formatDate ( $value->getPostDate () ) ) );
		}
		
		$this->rss->createRssDocument ();
		if (! $fileName)
			$fileName = $module;
		if (! is_dir ( "./rss/{$vsLang->currentLang->getFolderName()}" )) {
			mkdir ( "./rss/{$vsLang->currentLang->getFolderName()}", 0775, true );
		}
		$fileName = strtolower($fileName);
		$wf = fopen ( "./rss/{$vsLang->currentLang->getFolderName()}/$fileName.rss", "w" );
		fwrite ( $wf, $this->rss->rss_document );
		fclose ( $wf );
		unset ( $news );
		return $this->rss->rss_document;
	}

	function getFeedCatId( $table,$catId = 0, $fileName = '') {
		global $bw, $vsPrint, $vsMenu, $vsStd,$vsLang;
		
		if (! $table)
			return;
		
		$table = rtrim ( $table, "s" ) . "s";
		$channel_id = $this->rss->createNewChannel ( array ('title' => $vsLang->getWords("global_rss_title","Tổng công ty Cà Phê Việt Nam"), 'link' => "{$bw->base_url}$table", 'description' => $vsLang->getWords('global_rss_description','Thông tin thị trường cà phê Việt Nam'), 'pubDate' => $this->rss->formatDate ( time () ) ) );
		
		$vsStd->requireFile ( CORE_PATH . $table . "/" . $table . ".php" );
		$module = new $table ();
		if (! $catId)
			$strIds = $this->vsMenu->getChildrenIdInTree ( $module->getCategories () );
		else
			$strIds = $this->vsMenu->getChildrenIdInTree ( $catId );
		
		$table = rtrim ( $table, "s" );
		$module->setCondition ( "{$table}CatId in ( {$strIds}) and {$table}Status >0" );
		$news = $module->getObjectsByCondition ();
		
		foreach ( $news as $value ) {
			$intro = $value->getContent ( 450 );
			if ($value->getIntro ())
				$intro = $value->getIntro ();
			$this->rss->addItemToChannel ( $channel_id, array ('title' => "{$value->getTitle()}", 'link' => "{$value->getUrl($table.'s')}", 'description' => "{$intro}", 'content' => "$intro", 'pubDate' => $this->rss->formatDate ( $value->getPostDate () ) ) );
		}
		$this->rss->createRssDocument ();
		
		if (! $fileName)
			$fileName = $table;
		if (! is_dir ( "./rss/{$vsLang->currentLang->getFolderName()}" )) {
			mkdir ( "./rss/{$vsLang->currentLang->getFolderName()}", 0775, true );
		}
		$wf = fopen ( "./rss/{$vsLang->currentLang->getFolderName()}/$fileName.rss", "w" );
		fwrite ( $wf, $this->rss->rss_document );
		fclose ( $wf );
		unset ( $news );
		return $this->rss->rss_document;
	}
}
?>