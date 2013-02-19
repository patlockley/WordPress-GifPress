<?PHP

	add_action('init', 'gifpress_custom_page_type_create');

	function gifpress_custom_page_type_create() 
	{
	  $labels = array(
		'name' => _x('Animated GIFs', 'post type general name'),
		'singular_name' => _x('Animated GIF', 'post type singular name'),
		'add_new' => _x('Add New', 'animated_gif'),
		'add_item' => __('Add New '),
		'edit_item' => __('Edit an Animated GIF'),
		'item' => __('New Animated GIF'),
		'view_item' => __('View Animated GIFs'),
		'search_items' => __('Search Animated GIFs'),
		'not_found' =>  __('No Animated GIFs found'),
		'not_found_in_trash' => __(	'No Animated GIFs found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => 'Animated GIFs'

	  );
	  
	  $args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'menu_item' => plugin_dir_url(__FILE__) . "logo.jpg",
		'_edit_link' => 'post.php?post=%d',	
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'rewrite' => true,
		'description' => 'A Collection of terms which which to search for resources with',
		'supports' => array('title')
	  ); 
	  register_post_type('animated_gifs',$args);
	  
	  global $wp_rewrite;

	  $wp_rewrite->flush_rules();

	}

?>