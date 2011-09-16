<?php
class tinyMCE {
	private $value = "";
	private $width = 100;
	private $height = 40;
	private $skin = "o2k7";
	private $InstanceName = "";
	private $url = "";
	private $theme = "";
	private $toolbarSet = "";

	/**
	 * @return unknown
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return unknown
	 */
	public function getInstanceName() {
		return $this->InstanceName;
	}

	/**
	 * @return unknown
	 */
	public function getSimple() {
		return $this->simple;
	}

	/**
	 * @return unknown
	 */
	public function getSkin() {
		return $this->skin;
	}

	/**
	 * @return unknown
	 */
	public function getTheme() {
		return $this->theme;
	}

	/**
	 * @return unknown
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @return unknown
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @return unknown
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param unknown_type $height
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * @param unknown_type $InstanceName
	 */
	public function setInstanceName($InstanceName) {
		$this->InstanceName = $InstanceName;
	}

	/**
	 * @param unknown_type $simple
	 */
	public function setSimple($simple) {
		$this->simple = $simple;
	}

	/**
	 * @param unknown_type $skin
	 */
	public function setSkin($skin) {
		$this->skin = $skin;
	}

	/**
	 * @param unknown_type $theme
	 */
	public function setTheme($theme) {
		$this->theme = $theme;
	}

	/**
	 * @param unknown_type $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @param unknown_type $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @param unknown_type $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	function setToolbar($toolbarSet = "") {
		switch ($toolbarSet) {
			case 'full' :
				$this->toolbarSet = <<<EOF
				theme_advanced_buttons1 : "fullscreen,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,|,sub,sup,|,charmap,emotions,template,|,insertimage,media,|,ltr,rtl",
EOF;
				break;
			
			case 'medium' :
				$this->toolbarSet = <<<EOF
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,cut,copy,paste,pastetext,pasteword,|,justifyleft,justifycenter,justifyright,justifyfull,|bullist,numlist,|,outdent,indent,|,undo,redo",
				theme_advanced_buttons2 : "link,unlink,image,insertimage, cleanup,code,forecolor,backcolor,fontsizeselect,media,|,ltr,rtl",
				theme_advanced_buttons3 : '',
EOF;
				break;
			
			case 'simple' :
				$this->toolbarSet = <<<EOF
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,pasteword,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,|,outdent,indent,blockquote,|undo,redo,|,link,unlink,anchor,forecolor,backcolor,|,code",
				theme_advanced_buttons2 : '',
EOF;
			case 'narrow' :
				$this->toolbarSet = <<<EOF
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,pasteword,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,|",
				theme_advanced_buttons2 : ",outdent,indent,blockquote,|undo,redo,|,link,unlink,anchor,forecolor,backcolor,|,code",
EOF;
				break;
			
			default :
				$this->toolbarSet = <<<EOF
				theme_advanced_buttons1 : "fullscreen,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,|,sub,sup,|,charmap,emotions,template,|,insertimage,media,|,ltr,rtl",
EOF;
				break;
		}
	}

	function createHtml() {
		$BWHTML .= <<<EOF
		<script language="javascript" type="text/javascript">
			tinyMCE.init({
				mode : "textareas",
				theme : "{$this->theme}",
				skin	:	"{$this->skin}",
				language : 'vi',
				entity_encoding : "raw",
				editor_selector : "{$this->InstanceName}",
				/* Khai bao duong dan chay file*/
				relative_urls:false,
				remove_script_host: true,
				document_base_url:"{$this->url}",
            	force_p_newlines : true,
				force_br_newlines : false,
				forced_root_block : '',
				convert_newlines_to_brs: false,
				remove_linebreaks : false,
				plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager",

				// Theme options
				{$this->toolbarSet}
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				align:"left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|style|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
				// Replace values for the template plugin
				template_replace_values : {
				username : "tongnguyen",
				staffid : "123456"
				}
			});
			
		</script>
		<textarea id="{$this->InstanceName}" name="{$this->InstanceName}" class="{$this->InstanceName}" rows="4" cols="50" style="height:{$this->height}; width:{$this->width};">{$this->value}</textarea>
EOF;
		
		return $BWHTML;
	}
}
?>