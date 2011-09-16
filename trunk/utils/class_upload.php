<?php

class class_upload {
	// name of upload form field
	public $upload_form_field = 'FILE_UPLOAD';
	
	// Out filename *without* extension
	// (Leave blank to retain user filename)
	public $out_file_name = '';
	
	// Out dir (./upload) - no trailing slash
	public $out_file_dir = './';
	
	// maximum file size of this upload
	public $max_file_size = 0;
	
	// Forces PHP, CGI, etc to text
	public $make_script_safe = 1;
	
	// Force non-img file extenstion (leave blank if not) (ex: 'ibf'a makes upload.doc => upload.ibf)
	public $force_data_ext = '.vsf';
	
	// Allowed file extensions array( 'gif', 'jpg', 'jpeg'..)
	public $allowed_file_ext = array ('gif', 'jpeg', 'jpg', 'png', 'doc', 'pdf', 'xls', 'mp3', 'docx', 'xlsx', 'xps', 'csv', 'zip', 'rar', 'swf', 'avi', 'mp4', 'mpg', 'wmv', 'flv', 'wav', 'wma', 'm4a','3gp' );
	
	// Array of IMAGE file extensions
	public $image_ext = array ('gif', 'jpeg', 'jpg', 'png' );
	
	// Returns current file extension	
	public $file_extension = '';
	
	// If force_data_ext == 1, this will return the 'real' extension
	// and $file_extension will return the 'force_data_ext'
	public $real_file_extension = '';
	
	// Returns error number	
	public $error_no = 0;
	
	// Returns if upload is img or not
	public $is_image = 0;
	
	public $is_file = 0;
	// Returns file name as was uploaded by user
	public $original_file_name = "";
	
	// Returns final file name as is saved on disk. (no path info)
	public $parsed_file_name = "";
	
	// Returns final file name with path info
	public $saved_upload_name = "";

	/*-------------------------------------------------------------------------*/
	// CONSTRUCTOR
	/*-------------------------------------------------------------------------*/
	/*-------------------------------------------------------------------------*/
	// PROCESS THE UPLOAD
	/*-------------------------------------------------------------------------*/
	
	function upload_process() {
		global $bw;
		
		$this->_clean_paths ();
		//-------------------------------------------------
		// Set up some variables to stop carpals developing
		//-------------------------------------------------
		

		$FILE_NAME = $_FILES [$this->upload_form_field] ['name'];
		$FILE_SIZE = $_FILES [$this->upload_form_field] ['size'];
		$FILE_TYPE = $_FILES [$this->upload_form_field] ['type'];
		
		//-------------------------------------------------
		// Naughty Opera adds the filename on the end of the
		// mime type - we don't want this.
		//-------------------------------------------------
		

		$FILE_TYPE = preg_replace ( "/^(.+?);.*$/", "\\1", $FILE_TYPE );
		
		//-------------------------------------------------
		// Naughty Mozilla likes to use "none" to indicate an empty upload field.
		// I love universal languages that aren't universal.
		//-------------------------------------------------
		

		if ($_FILES [$this->upload_form_field] ['name'] == "" or ! $_FILES [$this->upload_form_field] ['name'] or ! $_FILES [$this->upload_form_field] ['size'] or ($_FILES [$this->upload_form_field] ['name'] == "none")) {
			$this->error_no = 1;
			return;
		}
		
		//-------------------------------------------------
		// De we have allowed file_extensions?
		//-------------------------------------------------
		

		if (! is_array ( $this->allowed_file_ext ) or ! count ( $this->allowed_file_ext )) {
			$this->error_no = 2;
			return;
		}
		
		//-------------------------------------------------
		// Get file extension
		//-------------------------------------------------
		

		$this->file_extension = $this->_get_file_extension ( $FILE_NAME );
		if (! $this->file_extension) {
			$this->error_no = 2;
			return;
		}
		
		$this->real_file_extension = $this->file_extension;
		
		//-------------------------------------------------
		// Valid extension?
		//-------------------------------------------------
		

		if (! in_array ( $this->file_extension, $this->allowed_file_ext )) {
			$this->error_no = 2;
			return;
		}
		
		//-------------------------------------------------
		// Check the file size
		//-------------------------------------------------
		

		if (($this->max_file_size) and ($FILE_SIZE > $this->max_file_size)) {
			$this->error_no = 3;
			return;
		}
		
		//-------------------------------------------------
		// Make the uploaded file safe
		//-------------------------------------------------
		

		$FILE_NAME = preg_replace ( "/[^\w\.]/", "_", $FILE_NAME );
		
		$this->original_file_name = $FILE_NAME;
		
		//-------------------------------------------------
		// Is it an image?
		//-------------------------------------------------
		

		if (is_array ( $this->image_ext ) and count ( $this->image_ext )) {
			if (in_array ( $this->file_extension, $this->image_ext )) {
				$this->is_image = 1;
			}
		}
		
		//-------------------------------------------------
		// Is it an file?
		//-------------------------------------------------
		

		if (is_array ( $this->allowed_file_ext ) and count ( $this->allowed_file_ext )) {
			if (in_array ( $this->file_extension, $this->allowed_file_ext )) {
				$this->is_file = 1;
			}
		}
		
		//-------------------------------------------------
		// Convert file name?
		// In any case, file name is WITHOUT extension
		//-------------------------------------------------
		

		if ($this->out_file_name) {
			$this->parsed_file_name = $this->out_file_name;
		} else {
			$this->parsed_file_name = str_replace ( '.' . $this->file_extension, "", $FILE_NAME );
		}
		
		//-------------------------------------------------
		// Make safe?
		//-------------------------------------------------
		

		if ($this->make_script_safe) {
			if (preg_match ( "/\.(cgi|pl|js|asp|php|html|htm|jsp|jar)/", $FILE_NAME )) {
				$FILE_TYPE = 'text/plain';
				$this->file_extension = 'txt';
			}
		}
		
		//-------------------------------------------------
		// Add on the extension...
		//-------------------------------------------------
		

		if ($this->force_data_ext and ! $this->is_image and ! $this->is_file) {
			$this->file_extension = str_replace ( ".", "", $this->force_data_ext );
		}
		
		$this->parsed_file_name .= '.' . $this->file_extension;
		
		//-------------------------------------------------
		// Copy the upload to the uploads directory
		//-------------------------------------------------
		

		$this->saved_upload_name = $this->out_file_dir . '/' . $this->parsed_file_name;
		
		if (! move_uploaded_file ( $_FILES [$this->upload_form_field] ['tmp_name'], $this->saved_upload_name )) {
			$this->error_no = 4;
			return;
		} else {
			@chmod ( $bw->vars ['upload_dir'] . "/" . $this->saved_upload_name, 0775 );
		}
	}

	/*-------------------------------------------------------------------------*/
	// INTERNAL: Get file extension
	/*-------------------------------------------------------------------------*/
	
	function _get_file_extension($file) {
		return strtolower ( str_replace ( ".", "", substr ( $file, strrpos ( $file, '.' ) ) ) );
	}

	/*-------------------------------------------------------------------------*/
	// INTERNAL: Clean paths
	/*-------------------------------------------------------------------------*/
	
	function _clean_paths() {
		$this->out_file_dir = preg_replace ( "#/$#", "", $this->out_file_dir );
	}

}

?>