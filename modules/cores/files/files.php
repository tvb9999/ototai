<?php
ob_start ();
global $vsStd;
$vsStd->requireFile ( CORE_PATH . "files/filetypes.php" );
$vsStd->requireFile ( CORE_PATH . "files/File.class.php" );

class files extends VSFObject {
	public $arrayFiles = array ();
	public $obj;
	public $currentPath = "";
	public $rootPath = "";
	public $result = array ();
	public $objType;
	
	function __construct() {
		parent::__construct ();
		$this->primaryField = 'fileId';
		$this->basicClassName = 'File';
		$this->tableName = 'file';
		$this->obj = $this->createBasicObject ();
		$this->objType = new filetypes ();
		$this->rootPath = ROOT_PATH . "uploads/";
		$this->getFileAll();
		
	}

	function __destruct() {
		unset ( $this );
	}

	function getFileAll(){
		$arrayFiles = array ();
		if (file_exists ( CACHE_PATH . "files.cache" )) {
			require_once (CACHE_PATH . "files.cache");
			$this->arrayFiles = $arrayFiles;
			return;
		}
		else $this->buidCache();
	}
	
	function buidCache(){
		global $DB;
		
		$this->getObjectsByCondition();
		
		$cache_content = "<?php\n";
		$cache_content .= "\$arrayFiles = " . var_export ( $this->getArrayObj(), true ) . ";\n";
		$cache_content .= "?>";
		$cache_path = CACHE_PATH . "files.cache";
		$cache_content = preg_replace ( '/\s\s+/', '', $cache_content );
		$file = fopen ( $cache_path, "w" );
		fwrite ( $file, $cache_content );
		fclose ( $file );
	}
	
	function writeCache(){
		global $DB;
		
		$this->arrayFiles[$this->obj->getId()] = $this->obj;
		
		$cache_content = "<?php\n";
		$cache_content .= "\$arrayFiles = " . var_export ( $this->arrayFiles, true ) . ";\n";
		$cache_content .= "?>";
		$cache_path = CACHE_PATH . "files.cache";
		$cache_content = preg_replace ( '/\s\s+/', '', $cache_content );
		$file = fopen ( $cache_path, "w" );
		fwrite ( $file, $cache_content );
		fclose ( $file );
	}
	
	function deleteFile($id = 0) {
		global $vsLang, $DB;
		$this->result ['status'] = true;
		if (! $id)
			return $this->result ['status'] = false;
		$this->setCondition ( "fileId in ($id)" );
		$arrFile = $this->getObjectsByCondition ();
		$this->setCondition ( "fileId in ($id)" );
		if (! $this->result ['status'])
			return;
		$this->deleteObjectByCondition ();
		foreach ( $arrFile as $file ) {
			$this->currentPath = $file->getPath ();
			@unlink ( $file->getPathView ( false ) );
		}
	
	}

	function convertVideoToFlv($source, $des, $extension = '') {
		global $_SERVER;
		$dynamic_path = $_SERVER ['DOCUMENT_ROOT'];
		
		if (! file_exists ( $source ))
			return false;
		else if ($extension == '') {
			$fileNameParts = explode ( ".", $source );
			$extension = end ( $fileNameParts );
		}
		$sourcevideo = $dynamic_path . trim ( $source, '.' );
		$desvideo = $dynamic_path . trim ( $des, '.' );
		/******************create thumbnail***************/
		
		$desimg = str_replace ( 'flv', 'png', $desvideo );
		$fileNameParts = explode ( "/", $desimg );
		$name = end ( $fileNameParts );
		//$desimg=str_replace($name,"{$name}",$desimg);
		exec ( "ffmpeg -y -i " . $sourcevideo . " -vframes 1 -ss 00:00:05 -an -vcodec png -f rawvideo -s 800x600 " . $desimg );
		if ($extension == "wav") {
			exec ( "ffmpeg -i $sourcevideo -acodec mp3 -ab 128k $desvideo" );
			if (file_exists ( $des ))
				return true;
		}
		if ($extension == "wmv") {
			exec ( "ffmpeg -i " . $sourcevideo . " -sameq -acodec libmp3lame -ar 22050 -ab 32 -f flv -s 320x240 " . $desvideo );
			if (file_exists ( $des ))
				return true;
		
		} elseif ($extension == "mp4") {
			exec ( "ffmpeg -i " . $sourcevideo . " -ar 22050 -ab 32 -acodec libmp3lame -r 25 -f flv -b 400 -s 320x240 " . $desvideo );
			if (file_exists ( $des ))
				return true;
		}
		
		if ($extension == "avi" || $extension == "mpg" || $extension == "mpeg" || $extension == "mov") {
			exec ( "ffmpeg -i $sourcevideo -ar 22050 -ab 32 -f flv -s 320x240 $desvideo" );
			if (file_exists ( $des ))
				return true;
		}
		
		return false;
	}

	function readDir($dir = "", $ignoreFiles = array(), $showfile = false) {
		$dh = opendir ( $dir );
		
		$objs = array ();
		// Start read files list
		while ( $obj = readdir ( $dh ) ) {
			if ($obj == "." || $obj == "..")
				continue;
			if (! $showfile && ! is_dir ( $dir . $obj ))
				continue;
			if (count ( $ignoreFiles ) > 0 && in_array ( $obj, $ignoreFiles ))
				continue;
			
			$objs [] .= $obj;
		}
		closedir ( $dh );
		
		rsort ( $objs );
		
		return $objs;
	}

	function getFileExtension($obj) {
		return strtolower ( str_replace ( ".", "", substr ( $obj, strrpos ( $obj, '.' ) ) ) );
	}

	/**
	 * Download file function
	 * @param integer $id the id of file in databases
	 * @return array error if fail and file streaming if success
	 */
	function downloadFile($id = 0) {
		global $vsStd, $vsLang, $bw,$DB;
		
		$this->obj = $this->getObjectById ( intval ( $id ) );
		
		if (! $this->result ['status'])
			return;
		
		$this->result ['message'] = $vsLang->getWords ( 'file_download_success', "Download file successfully!" );
		$this->result ['status'] = true;
		
		// Output file name
		$objName = VSFTextCode::removeAccent ( $bw->vars ['global_websitename'] . " " . $this->obj->getTitle (), "-" ) .".". $this->obj->getType ();
		
		if (file_exists ( $this->obj->getPathView ( 0 ) )) {
			header ( 'Content-Description: File Transfer' );
			header ( 'Content-Type: application/octet-stream' );
			header ( 'Content-Disposition: attachment; filename=' . basename ( $objName ) );
			header ( 'Content-Transfer-Encoding: binary' );
			header ( 'Expires: 0' );
			header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header ( 'Pragma: public' );
			header ( 'Content-Length: ' . $this->obj->getSize () );
			ob_clean ();
			flush ();
			readfile ( $this->obj->getPathView () );
			exit ();
		}
		
		$this->result ['status'] = false;
		$this->result ['message'] = $vsLang->getWords ( 'file_download_not_exist', "The file you want to download does not exist" );
	}

	function getFileInfo($fileName) {
		$imageDim = @getimagesize ( $fileName );
		
		$returnDim = array ('width' => $imageDim [0], 'height' => $imageDim [1], 'mime' => $imageDim ['mime'] );
		
		return $returnDim;
	}

	function formatbytes($file, $type = NULL) {
		switch ($type) {
			case "KB" :
				$filesize = filesize ( $file ) * .0009765625; // bytes to KB
				break;
			case "MB" :
				$filesize = (filesize ( $file ) * .0009765625) * .0009765625; // bytes to MB
				break;
			case "GB" :
				$filesize = ((filesize ( $file ) * .0009765625) * .0009765625) * .0009765625; // bytes to GB
				break;
			default :
				$filesize = filesize ( $file );
		}
		if ($filesize <= 0) {
			return $filesize = 'unknown file size';
		} else
			return round ( $filesize, 2 ) . ' ' . $type;
	}

	/**
	 * Upload file function
	 * @param string $uploadName the name of file in input
	 * @param string $objName the name of file
	 * @param string $pathFile the path of file can upload
	 * @return error if fail and fileId streaming if success
	 */
	function copyFile($sourceName, $pathFile = "") {
		global $vsStd, $bw;
		$checkFile = $this->getFileInfo ( $sourceName );
		if (! is_array ( $checkFile ))
			return false;
		if (! is_dir ( $this->rootPath . $pathFile )) {
			mkdir ( $this->rootPath . $pathFile, 0775, true );
		}
		$arrayEx = explode ( '/', $sourceName );
		$fileName = $arrayEx [count ( $arrayEx ) - 1];
		
		$fileType = $this->getFileExtension ( $fileName );
		$objTitle = str_replace(".".$fileType, '', $fileName);
		$vsStd->requireFile ( UTILS_PATH . "TextCode.class.php" );
		
		$objName = $vsStd->rs_unique_filename(UPLOAD_PATH. $pathFile, "redsunic.com_image",$fileType);
		
		if (! @copy ( $sourceName, UPLOAD_PATH . "{$pathFile}/{$objName}.{$fileType}" )) {
			$this->getFileContent($sourceName,UPLOAD_PATH . "{$pathFile}/{$objName}.{$fileType}" );
		}
		
		$size = $this->formatbytes ( UPLOAD_PATH . "{$pathFile}/{$objName}.{$fileType}" );
		if ($size < 0)
			return false;
		$this->obj->setPath ( rtrim ( $pathFile, '/' ) . "/" );
		$this->obj->setModule ( $bw->input [0] );
		$this->obj->setSize ( $size );
		$this->obj->setType ( $this->getFileExtension ( $fileType ) );
		$this->obj->setUploadTime ( time () );
		$this->obj->setTitle ( '~' . $objTitle );
		$this->obj->setName ( '~' . $objName );
		$this->insertObject ();
		$this->buidCache();
		return $this->obj->getId ();
	}

	/**
	 * Upload file function
	 * @param string $uploadName the name of file in input
	 * @param string $objName the name of file
	 * @param string $pathFile the path of file can upload
	 * @return error if fail and fileId streaming if success
	 */
	function uploadFile($uploadName, $pathFile = "") {
		global $vsStd, $bw;
		
		if (! is_dir ( $this->rootPath . $pathFile )) {
			mkdir ( $this->rootPath . $pathFile, 0775, true );
		}
		
		$objTitle = str_replace ( substr ( $_FILES [$uploadName] ['name'], strrpos ( $_FILES [$uploadName] ['name'], '.' ) ), "", $_FILES [$uploadName] ['name'] );
		$vsStd->requireFile ( UTILS_PATH . "TextCode.class.php" );
		$objTitle = str_replace ( "/", " ", $objTitle );
		$objName = VSFTextCode::removeAccent ( trim ( $objTitle ), "_" );
		
		$objName = $vsStd->rs_unique_filename($this->rootPath . $pathFile, $objName,$this->getFileExtension ( $_FILES [$uploadName] ['name']));
		
		$vsStd->requireFile ( UTILS_PATH . "class_upload.php" );
		$objectUpload = new class_upload ();
		$objectUpload->out_file_dir = $this->rootPath . $pathFile;
		$objectUpload->max_file_size = MAX_FILE_SIZE;
		$objectUpload->upload_form_field = $uploadName;
		$objectUpload->out_file_name = $objName;
		$objectUpload->upload_process ();
		$message = "";
		if ($objectUpload->error_no) {
			switch ($objectUpload->error_no) {
				case '1' :
					$message = $this->vsLang->getWordsGlobal ( 'global_file_upload_err_no_file', 'No file was uploaded!' );
					break;
				case '2' :
					$message = $this->vsLang->getWordsGlobal ( 'global_file_upload_err_ext', 'The file you uploaded does not allowed!' ) . " (" . $objectUpload->obj_extension . ')';
					break;
				case '3' :
					$message = $this->vsLang->getWordsGlobal ( 'global_file_upload_err_size', 'The uploaded file is larger than allowed size!' );
					break;
				case '4' :
					$message = $this->vsLang->getWordsGlobal ( 'global_file_upload_err_perm', 'Permission denied for the path ' ) . $objectUpload->out_file_dir;
					break;
				default :
					$message = $this->vsLang->getWordsGlobal ( 'global_other_error', 'No error code avaiable for error number ' . $_FILES [$this->uploadName] ['error'] ); //$_FILES[$bw->input['uploadname']]['error'];
			}
			print $message;
		} else {
			$this->obj->convertToObject ( $bw->input );
			$this->obj->setPath ( rtrim ( $pathFile, '/' ) . "/" );
			$this->obj->setModule ( $bw->input ['table'] );
			$this->obj->setSize ( $_FILES [$uploadName] ['size'] );
			$this->obj->setTitle ( '~' . $objTitle );
			$this->obj->setName ( '~' . $objName );
			$this->obj->setType ( $this->getFileExtension ( $_FILES [$uploadName] ['name'] ) );
			$this->obj->setUploadTime ( time() );
			@chmod ( $this->obj->getPathView ( 0 ), 0775 );
			$this->insertObject ();
		}
		$this->buidCache();
		$bw->show_callback = 1;
		print "FILEID:" .$this->obj->getId()."i4sn";
	}
	
	function getFileContent($sourceName, $pathFile = ""){
		$data = file_get_contents($sourceName);
		
		$handle = fopen($pathFile, "w");
		fwrite($handle, $data);
		fclose($handle);
	}
}