<?PHP
		
	add_filter("the_content", "gifpress_display");
	
	function gifpress_process_size($data){
				
		$width = 0;
		$height = 0;
				
		$dir = wp_upload_dir();		
	
		foreach($data as $picture){

			$details = getimagesize($dir['basedir'] . $picture[0]);
			
			if($details[0]>$width){
				$width = $details[0];
			}
			
			if($details[1]>$height){
				$height = $details[1];
			}

		}
		
		return array($width, $height);
		
	}
	
	function gifpress_process_picture($path, $center, $size){
	
		$ext = pathinfo($path, PATHINFO_EXTENSION);
	
		switch($ext){
						
			case "png" : $source = imagecreatefrompng($path);
						 $details = getimagesize($path);
						 $dest = imagecreatetruecolor($size[0], $size[1]);
						 if($center!=="on"){
							imagecopyresampled( $dest, $source, 0, 0, 0, 0, $size[0], $size[1] , $details[0], $details[1] );
						 }else{
							$new_x = ($size[0] - $details[0])/2;
							$new_y = ($size[1] - $details[1])/2;
							imagecopyresampled( $dest, $source, $new_x, $new_y, 0, 0, $details[0], $details[1] , $details[0], $details[1] );
						 }
						 imagegif($dest, str_replace(".png",".gif",$path));
						 $filename = str_replace(".png",".gif",$path);
						 break;
			case "jpeg": $source = imagecreatefromjpeg($path); 
						 $details = getimagesize($path);
						 print_r($details);						 
						 $dest = imagecreatetruecolor($size[0], $size[1]);
						 if($center!=="on"){
							imagecopyresampled( $dest, $source, 0, 0, 0, 0, $size[0], $size[1] , $details[0], $details[1] );
						 }else{
							$new_x = ($size[0] - $details[0])/2;
							$new_y = ($size[1] - $details[1])/2;
							imagecopyresampled( $dest, $source, $new_x, $new_y, 0, 0, $details[0], $details[1] , $details[0], $details[1] );
						 }
						 imagegif($dest, str_replace(".jpeg",".gif",$path));
						 $filename = str_replace(".jpeg",".gif",$path);
						 break;
			case "jpg" : $source = imagecreatefromjpeg($path); 
						 $details = getimagesize($path);
						 $dest = imagecreatetruecolor($size[0], $size[1]);
						 if($center!=="on"){
							imagecopyresampled( $dest, $source, 0, 0, 0, 0, $size[0], $size[1] , $details[0], $details[1] );
						 }else{
							$new_x = ($size[0] - $details[0])/2;
							$new_y = ($size[1] - $details[1])/2;
							imagecopyresampled( $dest, $source, $new_x, $new_y, 0, 0, $details[0], $details[1] , $details[0], $details[1] );
						 }
						 imagegif($dest, str_replace(".jpg",".gif",$path));
						 $filename = str_replace(".jpg",".gif",$path);
						 break;
			case "gif" : $source = imagecreatefromgif($path);
						 $details = getimagesize($path);
						 $dest = imagecreatetruecolor($size[0], $size[1]);
						 if($center!=="on"){
							imagecopyresampled( $dest, $source, 0, 0, 0, 0, $details[0], $details[1] , $details[0], $details[1] );
						 }else{
							$new_x = ($size[0] - $details[0])/2;
							$new_y = ($size[1] - $details[1])/2;
							imagecopyresampled( $dest, $source, $new_x, $new_y, 0, 0, $size[0], $size[1] , $details[0], $details[1] );
						 }
						 imagegif($dest, str_replace(".gif","2.gif",$path));
						 $filename = str_replace(".gif","2.gif",$path);
						 break;
		
		}
		
		return $filename;
	
	}

	function gifpress_display($content)
	{

		global $post;

		if($post->post_type=="animated_gifs"){

			$date = explode("-",$post->post_date);
			
			$dir = wp_upload_dir();
			
			if(file_exists($dir['basedir'] . "/" . $date[0] . "/" . $date[1] . "/gifpress_" . $post->ID . ".gif")){
			
				return "<img src='" . $dir['baseurl'] . "/" . $date[0] . "/" . $date[1] . "/gifpress_" . $post->ID . ".gif' />";
			
			}else{
			
				$center = get_post_meta($post->ID, "gifpress_center", true);
				$width = get_post_meta($post->ID, "gifpress_width", true);
				$height = get_post_meta($post->ID, "gifpress_height", true);
				$data = get_post_meta($post->ID, "gifpress_data", true);
				$data = unserialize($data);
		
				$dir = wp_upload_dir();
		
				$count = 0;
				
				if(count($data)!=0){
				
					if($width==""&&$height==""){
				
						$size = gifpress_process_size($data);
						
					}else{
					
						$size = array($width, $height);
					
					}
				
					foreach($data as $picture){ 

						$filename = gifpress_process_picture($dir['basedir'] . $picture[0], $center, $size);

						$frames [ ] = $filename; 
						
						if(isset($picture[1])){
						
							$framed [ ] = $picture[1]; 
							
						}else{
						
							$framed [ ] = 1;
						
						}
					
					}
					
					include "gif_encoder/gif_encoder.php";
					
					$gif = new GIFEncoder    ( 
                            $frames, 
                            $framed, 
                            0, 
                            2, 
                            0, 0, 0, 
                            "url" 
					); 		

					file_put_contents($dir['basedir'] . "/" . $date[0] . "/" . $date[1] . "/gifpress_" . $post->ID . ".gif", $gif->GetAnimation ( ) );
					return "<img src='" . $dir['baseurl'] . "/" . $date[0] . "/" . $date[1] . "/gifpress_" . $post->ID . ".gif' />";						
					
				}

			}
		
		}else{
		
			return $content;
		
		}

	}

?>