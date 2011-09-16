<?php
class SimpleIO{
	function getRemoteMyData($remoteSource, $localSource, $delimted, $time){
		if(file_exists($localSource)){
			$fileOpen = fopen($localSource, 'r');
			$firstTime = stream_get_line( $fileOpen, 1024, $delimted);
				
			if((time() - $firstTime) >= $time){
				$content = file_get_contents($remoteSource, 0);
				$fileOpen = fopen($localSource, 'w') or die("Updating...");
				fwrite($fileOpen, time().$delimted.$content);
			}else{
				$content = stream_get_contents( $fileOpen, 1024000);
			}
				
		}else{
			$content = file_get_contents($remoteSource, 0) or die("Updating...");
			$fileOpen = fopen($localSource, 'w');
			fwrite($fileOpen,  time().$delimted.$content);
		}
		fclose($fileOpen);

		return $content;
	}
}