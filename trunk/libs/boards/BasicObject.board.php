<?php
class BasicObject {
	protected $id = NULL;
	protected $catId = NULL;
	protected $category = NULL;
	protected $index = NULL;
	protected $title = NULL;
	protected $intro = NULL;
	protected $content = NULL;
	protected $status = NULL;
	protected $url = NULL;
	protected $postdate = NULL;

	/**
	 * @return the $id
	 */
	function getTitle($size = 0) {
		if ($size)
			return VSFTextCode::cutString ( $this->title, $size );
		return $this->title;
	}

	/**
	 * @return the $index
	 */
	function getIndex() {
		return $this->index;
	}

	/**
	 * @param $index the $index to set
	 */
	
	function setIndex($index) {
		$this->index = $index;
	}

	/**
	 * @param $title the $title to set
	 */
	function setTitle($title) {
		$this->title = $title;
	}

	function getId() {
		return $this->id;
	}

	function getCatId() {
		return $this->catId;
	}

	function setCatId($catId) {
		$this->catId = $catId;
	}

	/**
	 * @return the $content
	 */
	function getContent($size = 0) {
		$parser = new PostParser ();
		$parser->pp_do_html =1;
		$parser->pp_nl2br = 0;
		
		if($size==-1) return $parser->strip_only_tags($this->content ,array("br"));
		$content = $parser->post_db_parse ( $this->content );
		if ($size)
			return VSFTextCode::cutString ( strip_tags ( $content , "<p><br><br />"), $size );
		
		return $content;
	}
	
	/**
	 * @return the $intro
	 */
	function getIntro($size = 0) {
		$parser = new PostParser ();
		$parser->pp_do_html = 1;
		$parser->pp_nl2br = 0;
		
		if($size==-1) return $parser->strip_only_tags($this->intro ,array("br"));
		
		$intro = $parser->post_db_parse ( $this->intro );
		
		if ($size)
			return VSFTextCode::cutString ( strip_tags ( $intro, "<p><br><br />" ), $size );
		return $intro;
	}

	function getUrl($module = null) {
		global $bw;
		if (! $module)
			return $this->url;
		return $bw->base_url . "{$module}/detail/" . strtolower ( VSFTextCode::removeAccent ( str_replace ( "/", '-', trim ( $this->title ) ), '-' ) ) . '-' . $this->getId () . '/';
	}

	/**
	 * @return the $status
	 */
	function getStatus($type = null) {
		global $bw;
		if (! $type)
			return $this->status;
			
		if ($type == "image") {
			$imgArray = array ('disabled.png', 'enable.png');
			return $this->status = "<img src='{$bw->vars ['img_url']}/{$imgArray[$this->getStatus()]}' alt='{$this->getStatus()}' />";
		}
		if ($type == "text")
			return $this->status ? "Hiển thị" : "Ẩn";
	}

	function getPostDate($format = null) {
		if ($format) {
			return VSFDateTime::getDate ( $this->postdate, $format );
		}
		return $this->postdate;
	}

	function setPostDate($postDate) {
		$this->postdate = $postDate;
	}

	/**
	 * @param $id the $id to set
	 */
	function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param $id the $id to set
	 */
	function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @param $status the $status to set
	 */
	function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @param $content the $content to set
	 */
	function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @param $type the $type to set
	 */
	function setType($type) {
		$this->type = $type;
	}

	function __construct() {
	}

	function __destruct() {
		unset ( $this->id );
		unset ( $this->catId );
		unset ( $this->category );
		unset ( $this->content );
		unset ( $this->index );
		unset ( $this->intro );
		unset ( $this->title );
		unset ( $this->status );
		unset ( $this->url );
	}

	/**
	 * @param $intro the $intro to set
	 */
	
	function setIntro($intro) {
		$this->intro = $intro;
	}

	/**
	 * @return the $category
	 */
	
	function getCategory() {
		return $this->category;
	}

	/**
	 * @param $category the $category to set
	 */
	function setCategory($category) {
		$this->category = $category;
	}

	/**
	 * @return the $category
	 */
	function getResizeImagePath($path, $width = 130, $height = 100, $type = 0,$filters = 0, $crop = 0) {
		global $bw;
		if (TIMTHUMB == 1)
			return $bw->vars ['board_url'] . "/cache/images/{$width}x{$height}-$filters-$crop-{$type}/uploads/{$path}";
		return "{$bw->vars['board_url']}/utils/timthumb.php?src={$path}&t=$filters&a=$crop&w={$width}&h={$height}&zc={$type}";
	}

	function getCacheImagePathByFile($fileObject, $width = 130, $height = 100, $type = 0, $filters = 0, $crop = 0, $timthumb = 0) {
		global $vsSettings,$vsFile;
		
		if (! is_object ( $fileObject ))
			$fileObject = $vsFile->arrayFiles[intval ( $fileObject ) ];
		if (! is_a ( $fileObject, "File" ))
			return $this->getResizeImagePath ( $vsSettings->getSystemKey ( 'system_noimage_img_path', 'styles/images/noimage.jpg', 'systemsettings' ), $width, $height );
		if($timthumb==2){
			$image = new VSFImage ( $width, $height );
			$size = $image->getResizeDimensions ( $fileObject->getPathView ( 0 ) );
			$imgSource ['src'] = $this->getResizeImagePath ( $fileObject->getPathView ( TIMTHUMB ), $size ['img_width'], $size ['img_height'], $type, $filters, $crop );
			$imgSource ['padding-top'] = SCALE_IMAGE_PADDING?$size ['padding-top']:0;
			return $imgSource;
		}
		if ($timthumb || $fileObject->getType () == "gif")
			return $fileObject->getPathView ();
		
		return $this->getResizeImagePath ( $fileObject->getPathView ( TIMTHUMB ), $width, $height, $type, $filters , $crop );
	}

	/**
	 * @return the $category
	 */
	public function createCategory($catId = null) {
		global $vsMenu;
		if ($catId) {
			return $vsMenu->getCategoryById ( $catId );
		}
		if (is_object ( $this->category ))
			return $this->category;
		$this->setCategory ( $vsMenu->getCategoryById ( $this->catId ) );
		return $this->getCategory ();
	}

	function createImageCache($fileObject, $width = 100, $height = 100, $type = 0, $filters = 0, $crop = 0,$timthumb = 0) {
		global $bw,$vsFile;
		if (! is_object ( $fileObject ))
			$fileObject = $vsFile->arrayFiles [intval ( $fileObject )];
		
		if (! is_a ( $fileObject, "File" ))
			return $this->imageCache = "<image alt='{$bw->vars['global_websitename']} Image' src='{$this->getCacheImagePathByFile($fileObject,$width,$height,$type, $filters , $crop,$timthumb )}'/>";
		
		$alt = $fileObject->getIntro () ? $fileObject->getIntro () : $fileObject->getTitle ();
		$img_sn = $this->getCacheImagePathByFile($fileObject,$width,$height,$type, $filters , $crop,$timthumb);
		if(is_array($img_sn)) return $this->imageCache = "<image alt='{$alt}' src='{$img_sn['src']}' style='padding-top:{$img_sn['padding-top']}px;' />";
		return $this->imageCache = "<image alt='{$alt}' src='{$img_sn}'  />";
	}

	function createSeo() {
		global $vsCom;
		if (is_object ( $vsCom->SEO->obj )) {
			if (! $vsCom->SEO->obj->getIntro ()) {
				$intro = $this->intro ? strip_tags ( $this->getIntro ( 450 ) ) : strip_tags ( $this->getContent ( 450 ) );
				$oIntro = strip_tags ( $intro );
				$vsCom->SEO->obj->setIntro ( $oIntro );
			}
			if (! $vsCom->SEO->obj->getTitle ()) {
				$oTitle = $this->title;
				$vsCom->SEO->obj->setTitle ( $oTitle );
			}
			if (! $vsCom->SEO->obj->getKeyword ()) {
				$oTitle = mb_strtolower ( $this->title, "UTF-8" );
				$specialchar = ", . ? : ! < > & * ^ % $ # @ ; ' ( ) { } [ ] + ~ = - 39 /";
				$specialchar .= "&acute; &grave; &circ; &tilde; &cedil; &ring; &uml; &amp; &quot;";
				$specialcharArr = explode ( " ", $specialchar );
				$oTitle = str_replace ( $specialcharArr, "", $oTitle );
				
				$vsCom->SEO->obj->setKeyword ( $oTitle . ", " . VSFTextCode::removeAccent ( $oTitle ) . ", " . str_replace ( " ", ", ", $oTitle ) . ", " . VSFTextCode::removeAccent ( $oTitle, ", " ) );
			}
		}
	}

	function getTimeAgo(){
		global $vsLang;
		$ago=time() - $this->postdate;
		if($ago<59) return "<span class='date'>".(int)($ago)."</span> ".$vsLang->getWordsGlobal("seccond_","giây trước");
		if($ago<60*60) return "<span class='date'>".(int)($ago/60)."</span> ".$vsLang->getWordsGlobal("min_","phút trước");
		if($ago<60*60*24) return "<span class='date'>".((int)($ago/(60*60)))."</span> ".$vsLang->getWordsGlobal("hours_","giờ trước");
		if($ago<60*60*24*30) return "<span class='date'>".((int)($ago/(60*60*24)))."</span> ".$vsLang->getWordsGlobal("day_","ngày trước");
		if($ago<60*60*24*30*12) return "<span class='date'>".((int)($ago/(60*60*24*30)))."</span> ".$vsLang->getWordsGlobal("month_","tháng trước");
		if($ago<60*60*24*30*12*300) return "<span class='date'>".((int)($ago/(60*60*24*30*12)))."</span> ".$vsLang->getWordsGlobal("year_","năm trước");
		
		return VSFDateTime::getDate($this->postdate);
	}
}