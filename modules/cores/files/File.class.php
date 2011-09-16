<?php
class File extends BasicObject {
	private $module = NULL;
	private $type = NULL;
	private $size = NULL;
	private $uploadTime = NULL;
	private $path = NULL;
	private $name = NULL;
	private $youtube = NULL;

	function validate() {
		$status = true;
		return $status;
	}

	function __construct() {
		parent::__construct ();
	}

	function __set_state($array = array()) {
		$file = new File ();
		foreach ( $array as $key => $value ) {
			$file->$key = $value;
		}
		return $file;
	}

	function convertToDB() {
		isset ( $this->id ) ? ($dbobj ['fileId'] = $this->id) : '';
		isset ( $this->title ) ? ($dbobj ['fileTitle'] = $this->title) : '';
		isset ( $this->module ) ? ($dbobj ['fileModule'] = $this->module) : '';
		isset ( $this->intro ) ? ($dbobj ['fileIntro'] = $this->intro) : '';
		isset ( $this->type ) ? ($dbobj ['fileType'] = $this->type) : '';
		isset ( $this->url ) ? ($dbobj ['fileUrl'] = $this->url) : '';
		isset ( $this->path ) ? ($dbobj ['filePath'] = $this->path) : '';
		isset ( $this->status ) ? ($dbobj ['fileStatus'] = $this->status) : '';
		isset ( $this->index ) ? ($dbobj ['fileIndex'] = $this->index) : '';
		isset ( $this->name ) ? ($dbobj ['fileName'] = $this->name) : '';
		isset ( $this->size ) ? ($dbobj ['fileSize'] = $this->size) : '';
		isset ( $this->uploadTime ) ? ($dbobj ['fileUploadTime'] = $this->uploadTime) : '';
		$dbobj ['fileYoutube'] = $this->youtube ? $this->youtube : '';
		
		return $dbobj;
	}

	function convertToObject($object = array()) {
		isset ( $object ['fileId'] ) ? $this->setId ( $object ['fileId'] ) : '';
		isset ( $object ['fileIntro'] ) ? $this->setIntro ( $object ['fileIntro'] ) : '';
		isset ( $object ['fileModule'] ) ? $this->setModule ( $object ['fileModule'] ) : '';
		$object ['fileTitle'] != '' ? $this->setTitle ( $object ['fileTitle'] ) : '';
		isset ( $object ['fileType'] ) ? $this->setType ( $object ['fileType'] ) : '';
		isset ( $object ['fileUrl'] ) ? $this->setUrl ( $object ['fileUrl'] ) : '';
		isset ( $object ['filePath'] ) ? $this->setPath ( $object ['filePath'] ) : '';
		$object ['fileIndex'] != '' ? $this->setIndex ( $object ['fileIndex'] ) : '';
		isset ( $object ['fileStatus'] ) ? $this->setStatus ( $object ['fileStatus'] ) : '';
		isset ( $object ['fileName'] ) ? $this->setName ( $object ['fileName'] ) : '';
		isset ( $object ['fileSize'] ) ? $this->setSize ( $object ['fileSize'] ) : '';
		isset ( $object ['fileUploadTime'] ) ? $this->setUploadTime ( $object ['fileUploadTime'] ) : '';
		isset ( $object ['fileYoutube'] ) ? $this->setYoutube ( $object ['fileYoutube'] ) : '';
	}

	function getModule() {
		return $this->module;
	}

	function getType() {
		return $this->type;
	}

	function getSize() {
		return $this->size;
	}

	function getUploadTime() {
		return $this->uploadTime;
	}

	function getPath() {
		return $this->path;
	}

	function getUrl() {
		return $this->url;
	}

	/**
	 * @return the $youtube
	 */
	public function getYoutube() {
		return $this->youtube;
	}

	/**
	 * @param field_type $youtube
	 */
	public function setYoutube($youtube) {
		$this->youtube = $youtube;
	}

	function setUrl($path) {
		$this->url = $path;
	}

	function getTitle() {
		return ltrim ( $this->title, "~" );
	}

	function setModule($module) {
		$this->module = $module;
	}

	function setType($type) {
		$this->type = $type;
	}

	function setSize($size) {
		$this->size = $size;
	}

	function setUploadTime($uploadTime) {
		$this->uploadTime = $uploadTime;
	}

	function setPath($path) {
		$this->path = $path;
	}

	function setName($name) {
		$this->name = $name;
	}

	function getName() {
		return ltrim ( $this->name, "~" );
	}

	function getPathView($type = 2) {
		global $bw;
		if (! $type)
			return UPLOAD_PATH . "{$this->path}{$this->getName()}.{$this->type}";
		if ($type == 1)
			return "{$this->path}{$this->getName()}.{$this->type}";
		return $bw->vars ['upload_url'] . "/" . $this->path . $this->getName () . '.' . $this->type;
	}

	function show($width = 150, $height = 150, $divId = null) {
		global $bw, $vsPrint;
		
		if (stristr ( "doc pdf docx xlxs ", $this->type ))
			return "<div>" . $this->getTitle () . "." . $this->getType () . "</div>";
		
		if (stristr ( "jpg gif png", $this->type ))
			return "<img src='{$bw->vars['board_url']}/utils/timthumb.php?src={$this->getPathView()}&w=$width&h=$height&zc=1' alt='{$this->getTitle()}' />";
		if ($this->type == "swf")
			return <<<EOF
			<object height="$height" width="$width" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
	              <param value="{$this->getPathView()}" name="movie">
	              <param name="wmode" value="transparent">
	              <param value="high" name="quality">
	              <embed height="$height" width="$width" wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" quality="high" src="{$this->getPathView()}">
	   	    </object>
EOF;
		if (stristr ( "flv mp4 mov f4v 3gp 3g2 youtube mp3", $this->type )) {
			if (! $divId) {
				$divHtml = "<div id='flvdix{$this->id}'></div>";
				$divId = "flvdix{$this->id}";
			}
			$div = "
				$('#{$divId}').css('width','{$width}px');
				$('#{$divId}').css('height','{$height}px');
			";
			
			if (! isset ( $bw->jsflv ))
				$bw->jsflv = "<script type='text/javascript' src='{$bw->vars['board_url']}/javascripts/jw.player/jwplayer.js'></script>";
			else
				$bw->jsflv = "";
			if ($this->type == 'youtube') {
				$BWHTML .= <<<EOF
			{$bw->jsflv}
			$divHtml
			<script>
			$(document).ready(function(){
					jwplayer("$divId").setup({
						skin: "http://content.longtailvideo.com/skins/glow/glow.zip",
						stretching: "fill",
		            	flashplayer: "http://player.longtailvideo.com/player.swf",
		            	file: "{$this->youtube}",
		            });
		            {$div}
		            });
			</script>
EOF;
			} elseif (file_exists ( UPLOAD_PATH . "{$this->getPath()}{$this->getName()}.jpg" ))
				$BWHTML .= <<<EOF
			{$bw->jsflv}
			$divHtml
			<script>
				$(document).ready(function(){
					jwplayer("$divId").setup({
						skin: "{$bw->vars['board_url']}/javascripts/jw.player/glow.zip",
						stretching: "fill",
		            	flashplayer: "{$bw->vars['board_url']}/javascripts/jw.player/player.swf",
		            	file: "{$this->getPathView()}",
		            	image: "{$bw->vars['upload_url']}/{$this->getPath()}{$this->getName()}.jpg"
		            });
				{$div}
			});
			</script>
EOF;
			else
				$BWHTML .= <<<EOF
			{$bw->jsflv}
			$divHtml
			<script>
			$(document).ready(function(){
				jwplayer("$divId").setup({
						skin: "{$bw->vars['board_url']}/javascripts/jw.player/glow.zip",
						stretching: "fill",
		            	flashplayer: "{$bw->vars['board_url']}/javascripts/jw.player/player.swf",
		            	file: "{$this->getPathView()}"
		            });
		            {$div}
		     })
			</script>
			
EOF;
			$bw->jsflv = "";
			return $BWHTML;
		}
	}
	
	function viewFile(){
		global $bw;

		if (stristr ( "doc pdf docx xlsx ppt pptx xls", $this->type ))
			return "<img src='{$bw->vars['board_url']}/styles/images/document.png' alt='{$this->getTitle()}' width='69' height='81'/>";
		if (stristr ( "flv mp4 mov 3gp mp3 zip zar", $this->type ))
			return "<img src='{$bw->vars['board_url']}/styles/images/$this->type.png' alt='{$this->getTitle()}' width='69' height='81' />";
		if (stristr ( "f4v 3g2 m4a youtube", $this->type ))
			return "<img src='{$bw->vars['board_url']}/styles/images/video.png' alt='{$this->getTitle()}' width='69' height='81' />";
		if (stristr ( "jpg gif png", $this->type ))
			return "<img src='{$bw->vars['board_url']}/utils/timthumb.php?src={$this->getPathView()}&w=81&h=69&zc=1' alt='{$this->getTitle()}' />";
		return $BWHTML;
	}
	
}
?>