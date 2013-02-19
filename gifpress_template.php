<?PHP

	function gifpress_template($single_template) {

		 global $post;

		 if ($post->post_type == 'animated_gifs') {
		 
			  $single_template = dirname( __FILE__ ) . '/gifpress_display.php';
		 }
		 
		 return $single_template;
		 
	}

	add_filter( "single_template", "gifpress_template" ) ;

?>