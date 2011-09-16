<?php
class VSFImage {
	public $desiredWidth = 0;
	public $desiredHeight = 0;
	
	function __construct($desiredWidth = 0, $desiredHeight = 0) {
		$this->desiredHeight = $desiredHeight;
		$this->desiredWidth = $desiredWidth;
	}

	/**
	 * Get new dimensions for resizing
	 *
	 * @access	protected
	 * @param	integer 	Maximum width
	 * @param	integer 	Maximum height
	 * @return	array		[img_width,img_height]
	 */
	function getResizeDimensions($fileName) {
		//---------------------------------------------------------
		// Verify width and height are valid and > 0
		//---------------------------------------------------------
		$imageDim = $this->getImageDimension ( $fileName );
		
		if (! $this->desiredWidth or ! $this->desiredHeight) {
			return false;
		}
		
		//---------------------------------------------------------
		// Is the current image already smaller?
		//---------------------------------------------------------
		

		if ($this->desiredWidth >= $imageDim ['width'] and $imageDim ['height'] >= $this->desiredHeight) {
			return false;
		}
		
		//---------------------------------------------------------
		// Return new dimensions
		//---------------------------------------------------------
		

		return $this->scaleImage ( array ('cur_height' => $imageDim ['height'], 'cur_width' => $imageDim ['width'], 'max_height' => $this->desiredHeight, 'max_width' => $this->desiredWidth ) );
	}

	/**
	 * Return proportionate image dimensions based on current and max dimension settings
	 *
	 * @access	protected
	 * @param	array 		[ cur_height, cur_width, max_width, max_height ]
	 * @return	array		[ img_height, img_width ]
	 */
	function scaleImage($arg) {
		$ret = array ('img_width' => $arg ['cur_width'], 'img_height' => $arg ['cur_height'] );
		if ($arg ['cur_width'] > $arg ['max_width']) {
			$ret ['img_width'] = $arg ['max_width'];
			$ret ['img_height'] = ceil ( ($arg ['cur_height'] * (($arg ['max_width'] * 100) / $arg ['cur_width'])) / 100 );
			$arg ['cur_height'] = $ret ['img_height'];
			$arg ['cur_width'] = $ret ['img_width'];
		}
		
		if ($arg ['cur_height'] > $arg ['max_height']) {
			$ret ['img_height'] = $arg ['max_height'];
			$ret ['img_width'] = ceil ( ($arg ['cur_width'] * (($arg ['max_height'] * 100) / $arg ['cur_height'])) / 100 );
		}
		$ret['padding-top'] = $ret ['img_height']?($arg ['max_height']-$ret ['img_width'])/2:0;
		return $ret;
	}

	function getImageDimension($fileName) {
		$imageDim = getimagesize ( $fileName );
		
		$returnDim = array ('width' => $imageDim [0], 'height' => $imageDim [1], 'mime' => $imageDim ['mime'] );
		
		return $returnDim;
	}
}