<?PHP

add_action("admin_menu", "gifpress_editor");
add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');

function wp_gear_manager_admin_scripts() {

	wp_enqueue_script('add_picture', plugins_url('/js/gifpress.js', __FILE__));		
	wp_enqueue_script('jquery');
	wp_register_style( 'gifpress_css', plugins_url('/css/gifpress.css', __FILE__) );
	wp_enqueue_style( 'gifpress_css' );
	
}

function gifpress_editor_make($hook) {

	global $post;
	
	if($post->post_type=="animated_gifs"){

		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' =>'image',
			'post_status' => 'inherit',
			'posts_per_page' => -1,
		);
		$query_images = new WP_Query( $args );
		$images = array();
		foreach ( $query_images->posts as $image) {
			$images[]= array($image->post_title, $image->guid);
		}

	}
	
	if(count($images)!=0){
		
		wp_nonce_field('gifpress','gifpress');
	
		echo "<p> Choose an image </p>";
		
		echo "<div>";
	
		foreach($images as $image){
		
			echo " <img class='gifimage' alt='" . $image[0] . "' title='" . $image[0] . "' onclick='javascript:add_gif_to_list(this);' src='" . $image[1] . "' /> ";
		
		}
		
		echo "</div>";
		
		$center = get_post_meta($post->ID, "gifpress_center", true);
		$width = get_post_meta($post->ID, "gifpress_width", true);
		$height = get_post_meta($post->ID, "gifpress_height", true);
		$data = get_post_meta($post->ID, "gifpress_data", true);
		
		if($center=="on"){
		
			$checked = " checked ";
		
		}else{
		
			$checked = "";
		
		}
		
		echo "<div><p>Centre images <input type='checkbox' name='gifpress_center' " . $checked . " /> | ";
		echo " Scale Image to a Maximum height : <input type='text' name='gifpress_height' value='" . $width . "' /> ";
		echo " width : <input type='text' name='gifpress_width' value='" . $height . "' /> </p>";
		echo "<p>If you don't centre the images, they will appear in the top left.</p>";
		echo "<p>If you don't enter a size, they largest image will be the size of the gif</p></div>";
		
		echo "<div id='gifpress_creation'>";
		
		$data = unserialize($data);
		
		if($data!=""){
			
			$dir = wp_upload_dir();
			
			$count = 0;
			
			if(count($data)!=0){
			
				foreach($data as $picture){
				
					echo "<div id='gif_" . $count . "' style='position:relative'>";
					echo "<img class='gifimage' src='" . $dir['baseurl'] . $picture[0] . "' />";
					echo "Hundreths of a second<input value='" . $dir['baseurl'] . $picture[0] . "' type='hidden' name='gifpress_picture_url_" . $count . "'>";
					echo "<input type='text' name='gifpress_picture_time_" . $count . "' value='" . $picture[1] . "' />";
					echo "<span class='giflink' onclick='move_gif_up(this.parentNode);' id='gif_up_" . $count . "'>Move Up</span> | ";
					echo "<span class='giflink' onclick='move_gif_down(this.parentNode);' id='gif_down_" . $count . "'>Move Down</span> | ";
					echo "<span class='giflink_warn' onclick='jQuery(this.parentNode).remove(); reorder();' id='gif_delete_" . $count++ . "'>Delete</span>  </div>";

				}
			
			}
			
		}
			
		echo "</div>";

	}
	
}

function gifpress_editor()
{

	add_meta_box("gifpress_editor_make", "GIF Press Editor", "gifpress_editor_make", "animated_gifs");
	
}


?>