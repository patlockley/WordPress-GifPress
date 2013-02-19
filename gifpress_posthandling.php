<?PHP

add_action("save_post", "gifpress_create");

function gifpress_create($post_id)
{

	$data = get_post($post_id);
	
	if($data->post_type=="animated_gifs"){
	
		$dir = wp_upload_dir();

		if(count($_POST)!=0){
		
			if(!wp_verify_nonce($_POST['gifpress'],'gifpress') ){
			
				print 'Sorry, your nonce did not verify.';
				exit;
				
			}else{
			
				update_post_meta($post_id, "gifpress_center", $_REQUEST["gifpress_center"]);
				update_post_meta($post_id, "gifpress_width", $_REQUEST["gifpress_width"]);
				update_post_meta($post_id, "gifpress_height", $_REQUEST["gifpress_height"]);
				
				$post_data = array();
			
				foreach($_POST as $key => $value){
			
					if(strpos($key, "gifpress_picture_url_")!==false){
					
						array_push($post_data, array(str_replace($dir['baseurl'], "", $value), $_POST[str_replace("url","time",$key)]));
					
					}

				}
				
				update_post_meta($post_id, "gifpress_data", serialize($post_data));
				
				if($_POST['save']=="Update"){
				
					$date = explode("-",$data->post_date);
				
					$dir = wp_upload_dir();
				
					if(file_exists($dir['basedir'] . "/" . $date[0] . "/" . $date[1] . "/gifpress_" . $post_id . ".gif")){
				
						unlink($dir['basedir'] . "/" . $date[0] . "/" . $date[1] . "/gifpress_" . $post_id . ".gif");
				
					}
					
				}

			}
				
		}
	
	}

}

?>