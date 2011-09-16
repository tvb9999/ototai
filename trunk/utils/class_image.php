<?php

/*
+--------------------------------------------------------------------------
|   Invision Power Board v2.0.0 PF 1
|   =============================================
|   by Matthew Mecham
|   (c) 2001 - 2004 Invision Power Services, Inc.
|   http://www.invisionpower.com
|   =============================================
|   Web: http://www.invisionboard.com
|   Time: Wed, 07 Jul 2004 18:33:33 GMT
|   Release: 84f592ff580f1e9f1567420acd235562
|   Licence Info: http://www.invisionboard.com/?license
+---------------------------------------------------------------------------
|
|   > GD / IMAGE handling methods (KERNEL)
|   > Module written by Matt Mecham
|   > Date started: 2nd Feb. 2004
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/

class class_image {
	var $in_type = 'file';
	var $out_type = 'file';
	var $out_file_name = '';
	var $out_file_dir = '';
	var $in_file_dir = '.';
	var $in_file_name = '';
	var $in_file_complete = '';
	var $desired_width = 0;
	var $desired_height = 0;
	var $gd_version = 2;
	var $image_type = '';
	var $file_extension = '';

	/*-------------------------------------------------------------------------*/
	// CONSTRUCTOR
	/*-------------------------------------------------------------------------*/
	
	function class_image() {
		//-----------------------------------
	// Full path?
	//-----------------------------------
	}

	/*-------------------------------------------------------------------------*/
	// Clean paths
	/*-------------------------------------------------------------------------*/
	
	function clean_paths() {
		$this->in_file_dir = preg_replace ( "#/$#", "", $this->in_file_dir );
		$this->out_file_dir = preg_replace ( "#/$#", "", $this->out_file_dir );
		
		if ($this->in_file_dir and $this->in_file_name) {
			$this->in_file_complete = $this->in_file_dir . '/' . $this->in_file_name;
		} else {
			$this->in_file_complete = $this->in_file_name;
		}
		
		if (! $this->out_file_dir) {
			$this->out_file_dir = $this->in_file_dir;
		}
	}

	/*-------------------------------------------------------------------------*/
	//
	// Show NORMAL created security image(s)...
	//
	/*-------------------------------------------------------------------------*/
	
	function show_gif_img($this_number = "") {
		$numbers = array (0 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUDH5hiKsOnmqSPjtT1ZdnnjCUqBQAOw==', 1 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUjAEWyMqoXIprRkjxtZJWrz3iCBQAOw==', 2 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUDH5hiKubnpPzRQvoVbvyrDHiWAAAOw==', 3 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDH5hiKbaHgRyUZtmlPtlfnnMiGUFADs=', 4 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVjAN5mLDtjFJMRjpj1Rv6v1SHN0IFADs=', 5 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUhA+Bpxn/DITL1SRjnps63l1M9RQAOw==', 6 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVjIEYyWwH3lNyrQTbnVh2Tl3N5wQFADs=', 7 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUhI9pwbztAAwP1napnFnzbYEYWAAAOw==', 8 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDH5hiKubHgSPWXoxVUxC33FZZCkFADs=', 9 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDA6hyJabnnISnsnybXdS73hcZlUFADs=' );
		
		@flush ();
		@header ( "Content-Type: image/gif" );
		echo base64_decode ( $numbers [$this_number] );
		exit ();
	
	}

	/*-------------------------------------------------------------------------*/
	// GENERATE THUMBNAIL
	/*-------------------------------------------------------------------------*/
	
	function generate_thumbnail() {
		$return = array ();
		$image = "";
		$thumb = "";
		
		//-----------------------------------
		// Set up paths
		//-----------------------------------
		

		$this->clean_paths ();
		
		$remap = array (1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP' );
		
		if ($this->desired_width and $this->desired_height) {
			//----------------------------------------------------
			// Tom Thumb!
			//----------------------------------------------------
			

			$img_size = array ();
			
			if ($this->in_type == 'file') {
				$img_size = @GetImageSize ( $this->in_file_complete );
			}
			
			if ($img_size [0] < 1 and $img_size [1] < 1) {
				$img_size = array ();
				$img_size [0] = $this->desired_width;
				$img_size [1] = $this->desired_height;
				
				$return ['thumb_width'] = $this->desired_width;
				$return ['thumb_height'] = $this->desired_height;
				
				if ($this->out_type == 'file') {
					$return ['thumb_location'] = $this->in_file_name;
					return $return;
				} else {
					//----------------------------------------------------
					// Show image
					//----------------------------------------------------
					

					$this->show_non_gd ();
				}
			
			}
			
			//----------------------------------------------------
			// Do we need to scale?
			//----------------------------------------------------
			

			if (($img_size [0] > $this->desired_width) or ($img_size [1] > $this->desired_height)) {
				
				$im = $this->scale_image ( array ('max_width' => $this->desired_width, 'max_height' => $this->desired_height, 'cur_width' => $img_size [0], 'cur_height' => $img_size [1] ) );
				
				$return ['thumb_width'] = $im ['img_width'];
				$return ['thumb_height'] = $im ['img_height'];
				
				if ($remap [$img_size [2]] == 'GIF') {
					$return ['thumb_location'] = $this->out_file_name . '.gif';
					copy ( $this->in_file_dir . '/' . $this->in_file_name, $this->out_file_dir . '/' . $this->out_file_name . '.gif' );
					return $return;
				} else if ($remap [$img_size [2]] == 'PNG') {
					$return ['thumb_location'] = $this->out_file_name . '.png';
					copy ( $this->in_file_dir . '/' . $this->in_file_name, $this->out_file_dir . '/' . $this->out_file_name . '.png' );
					return $return;
				} else if ($remap [$img_size [2]] == 'JPG') {
					if (function_exists ( 'imagecreatefromjpeg' )) {
						$image = imagecreatefromjpeg ( $this->in_file_complete );
						$this->image_type = 'jpg';
					}
				}
				if ($image) {
					if ($this->gd_version == 1) {
						$thumb = @imagecreate ( $im ['img_width'], $im ['img_height'] );
						@imagecopyresized ( $thumb, $image, 0, 0, 0, 0, $im ['img_width'], $im ['img_height'], $img_size [0], $img_size [1] );
					} else {
						$thumb = @imagecreatetruecolor ( $im ['img_width'], $im ['img_height'] );
						@imagecopyresampled ( $thumb, $image, 0, 0, 0, 0, $im ['img_width'], $im ['img_height'], $img_size [0], $img_size [1] );
					}
					
					//-----------------------------------------------
					// Saving?
					//-----------------------------------------------
					

					if ($this->out_type == 'file') {
						if (! $this->out_file_name) {
							//-----------------------------------------------
							// Remove file extension...
							//-----------------------------------------------
							

							$this->out_file_name = preg_replace ( "/^(.*)\..+?$/", "\\1", $this->in_file_name ) . '_thumb';
						}
						
						if (function_exists ( 'imagejpeg' )) {
							$this->file_extension = 'jpg';
							@imagejpeg ( $thumb, $this->out_file_dir . "/" . $this->out_file_name . '.jpg' );
							@imagedestroy ( $thumb );
							$return ['thumb_location'] = $this->out_file_name . '.jpg';
							return $return;
						} else if (function_exists ( 'imagepng' )) {
							$this->file_extension = 'png';
							@imagepng ( $thumb, $this->out_file_dir . "/" . $this->out_file_name . '.png' );
							@imagedestroy ( $thumb );
							$return ['thumb_location'] = $this->out_file_name . '.png';
							return $return;
						} else {
							//--------------------------------------
							// Can't save...
							//--------------------------------------
							

							$return ['thumb_location'] = $this->in_file_name;
							return $return;
						}
					} else {
						//-----------------------------------------------
						// Show image
						//-----------------------------------------------
						

						$this->show_image ( $thumb, $this->image_type );
					
					}
				} else {
					//----------------------------------------------------
					// Could not GD, return..
					//----------------------------------------------------
					

					if ($this->out_type == 'file') {
						$return ['thumb_width'] = $im ['img_width'];
						$return ['thumb_height'] = $im ['img_height'];
						$return ['thumb_location'] = $this->in_file_name;
					} else {
						//-----------------------------------------------
						// Show Image..
						//-----------------------------------------------
						

						$this->show_non_gd ();
					
					}
					
					return $return;
				}
			} //----------------------------------------------------
// No need to scale..
			//----------------------------------------------------
			else {
				if ($this->out_type == 'file') {
					$return ['thumb_width'] = $img_size [0];
					$return ['thumb_height'] = $img_size [1];
					$return ['thumb_location'] = $this->in_file_name;
					$this->file_extension = $this->image_type;
					copy ( $this->in_file_dir . '/' . $this->in_file_name, $this->out_file_dir . '/' . $this->out_file_name . '.' . strtolower ( $remap [$img_size [2]] ) );
					
					return $return;
				} else {
					//-----------------------------------------------
					// Show Image..
					//-----------------------------------------------
					

					$this->show_non_gd ();
				
				}
			}
		}
	}

	/*-------------------------------------------------------------------------*/
	//
	// Show GD created security image...
	//
	/*-------------------------------------------------------------------------*/
	
	function show_gd_img($content = "") {
		global $bw;
		
		$content = '  ' . preg_replace ( "/(\w)/", "\\1 ", $content ) . ' ';
		flush ();
		
		@header ( "Content-Type: image/jpeg" );
		
		$tmp_x = 140;
		$tmp_y = 20;
		$image_x = 210;
		$image_y = 65;
		
		$circles = 3;
		
		if ($bw->vars ['gd_version'] == 1) {
			$tmp = imagecreate ( $tmp_x, $tmp_y );
			$im = imagecreate ( $image_x, $image_y );
		} else {
			$tmp = imagecreatetruecolor ( $tmp_x, $tmp_y );
			$im = imagecreatetruecolor ( $image_x, $image_y );
		}
		
		$black = ImageColorAllocate ( $tmp, 0, 0, 0 );
		$white = ImageColorAllocate ( $tmp, 255, 255, 255 );
		$grey = ImageColorAllocate ( $tmp, 190, 190, 190 );
		
		imagefill ( $tmp, 0, 0, $white );
		
		for($i = 1; $i <= $circles; $i ++) {
			$values = array (0 => rand ( 0, $tmp_x - 10 ), 1 => rand ( 0, $tmp_y - 3 ), 2 => rand ( 0, $tmp_x - 10 ), 3 => rand ( 0, $tmp_y - 3 ), 4 => rand ( 0, $tmp_x - 10 ), 5 => rand ( 0, $tmp_y - 3 ), 6 => rand ( 0, $tmp_x - 10 ), 7 => rand ( 0, $tmp_y - 3 ), 8 => rand ( 0, $tmp_x - 10 ), 9 => rand ( 0, $tmp_y - 3 ), 10 => rand ( 0, $tmp_x - 10 ), 11 => rand ( 0, $tmp_y - 3 ) );
			
			$randomcolor = imagecolorallocate ( $tmp, rand ( 100, 255 ), rand ( 100, 255 ), rand ( 100, 255 ) );
			imagefilledpolygon ( $tmp, $values, 6, $randomcolor );
		}
		
		imagestring ( $tmp, 8, 0, 2, $content, $black );
		
		//-----------------------------------------
		// Distort by resizing
		//-----------------------------------------
		

		imagecopyresized ( $im, $tmp, 0, 0, 0, 0, $image_x, $image_y, $tmp_x, $tmp_y );
		
		imagedestroy ( $tmp );
		
		$white = ImageColorAllocate ( $im, 255, 255, 255 );
		$black = ImageColorAllocate ( $im, 0, 0, 0 );
		$grey = ImageColorAllocate ( $im, 100, 100, 100 );
		
		$random_pixels = $image_x * $image_y / 10;
		
		for($i = 0; $i < $random_pixels; $i ++)
			ImageSetPixel ( $im, rand ( 0, $image_x ), rand ( 0, $image_y ), $black );
		
		$no_x_lines = ($image_x - 1) / 5;
		
		for($i = 0; $i <= $no_x_lines; $i ++) {
			ImageLine ( $im, $i * $no_x_lines, 0, $i * $no_x_lines, $image_y, $grey );
			ImageLine ( $im, $i * $no_x_lines, 0, ($i * $no_x_lines) + $no_x_lines, $image_y, $grey );
		}
		
		$no_y_lines = ($image_y - 1) / 5;
		
		for($i = 0; $i <= $no_y_lines; $i ++)
			ImageLine ( $im, 0, $i * $no_y_lines, $image_x, $i * $no_y_lines, $grey );
		
		ImageJPEG ( $im );
		ImageDestroy ( $im );
		
		exit ();
	}

	/*-------------------------------------------------------------------------*/
	// Show GD image
	/*-------------------------------------------------------------------------*/
	
	function show_image($thumb, $type) {
		flush ();
		
		if ($type == 'gif') {
			@header ( 'Content-type: image/gif' );
		} else if ($type == 'png') {
			@header ( 'Content-Type: image/png' );
		} else {
			@header ( 'Content-Type: image/jpeg' );
		}
		
		print $data;
		
		exit ();
	}

	/*-------------------------------------------------------------------------*/
	// Show non GD image
	/*-------------------------------------------------------------------------*/
	
	function show_non_gd() {
		$file_extension = preg_replace ( ".*\.(\w+)$", "\\1", $this->in_file_name );
		$file_extension = strtolower ( $file_extension );
		$file_extension = $file_extension == 'jpeg' ? 'jpg' : $file_extension;
		
		if (strstr ( ' gif jpg png ', ' ' . $file_extension . ' ' )) {
			if ($data = @file_get_contents ( $this->in_file_complete )) {
				$this->show_thumbnail ( $data, $file_extension );
			}
		}
	}

	/*-------------------------------------------------------------------------*/
	// Return scaled down image
	/*-------------------------------------------------------------------------*/
	
	function scale_image($arg) {
		// max_width, max_height, cur_width, cur_height
		

		$ret = array ('img_width' => $arg ['cur_width'], 'img_height' => $arg ['cur_height'] );
		if ($arg ['cur_height'] > $arg ['max_height']) {
			$ret ['img_height'] = $arg ['max_height'];
			$ret ['img_width'] = ceil ( ($arg ['cur_width'] * (($arg ['max_height'] * 100) / $arg ['cur_height'])) / 100 );
		}
		if ($arg ['cur_width'] > $arg ['max_width']) {
			$ret ['img_width'] = $arg ['max_width'];
			$ret ['img_height'] = ceil ( ($arg ['cur_height'] * (($arg ['max_width'] * 100) / $arg ['cur_width'])) / 100 );
			$arg ['cur_height'] = $ret ['img_height'];
			$arg ['cur_width'] = $ret ['img_width'];
		}
		
		return $ret;
	}

}

?>