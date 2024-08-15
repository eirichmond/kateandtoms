<?php
	
add_action('init', 'add_post_types');
add_action('init', 'ong_custom_house_pages');

add_filter('wpseo_canonical', 'custom_canonical');
add_filter('wpseo_metadesc', 'custom_metadesc');
	
function add_post_types() {

	$options = get_option('plugin_options');
	if($options['activate_houses']) {
		houses_type();
		create_house_taxonomies();
	}
	if(isset($options['activate_suppliers'])) {
		if ($options['activate_suppliers']) {
			supplier_type();
			// error check added 27th June 2013 to reduce errors
			if(isset($options['activate_audience'])) {
				if ($options['activate_audience']) create_supplier_taxonomies();
			}
		}
	}
	if($options['activate_seasonal']) {
		seasonal_type();
	}
	if($options['activate_late_availability']) {
		late_availability_type();
	}
}

function houses_type() {
	$labels = array(
		'name' => _x('Houses', 'post type general name'),
		'singular_name' => _x('House', 'post type singular name'),
		'add_new' => _x('Add New', 'houses', 'houses'),
		'add_new_item' => __('Add New House', 'houses'),
		'edit_item' => __('Edit House', 'houses'),
		'new_item' => __('New House', 'houses'),
		'all_items' => __('All Houses', 'houses'),
		'view_item' => __('View House', 'houses'),
		'search_items' => __('Search Houses', 'houses'),
		'not_found' =>  __('No houses found', 'houses'),
		'not_found_in_trash' => __('No houses found in Trash', 'houses'), 
		'parent_item_colon' => '',
		'menu_name' => __('Houses', 'houses'),
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'has_archive' => true, 
		'capability_type' => 'page',
		'supports' => array( 'title', 'author', 'revisions' ),
		'taxonomies' => array ('size','feature','location','activity'),
	); 
    register_post_type( 'houses', $args );
    
	
}

function supplier_type() {
	$labels = array(
		'name' => _x('Suppliers', 'post type general name'),
		'singular_name' => _x('Supplier', 'post type singular name'),
		'add_new' => _x('Add New', 'houses', 'suppliers'),
		'add_new_item' => __('Add New Supplier', 'suppliers'),
		'edit_item' => __('Edit Supplier', 'suppliers'),
		'new_item' => __('New Supplier', 'suppliers'),
		'all_items' => __('All Suppliers', 'suppliers'),
		'view_item' => __('View Supplier', 'suppliers'),
		'search_items' => __('Search Suppliers', 'suppliers'),
		'not_found' =>  __('No suppliers found', 'suppliers'),
		'not_found_in_trash' => __('No suppliers found in Trash', 'suppliers'), 
		'parent_item_colon' => '',
		'menu_name' => __('Suppliers', 'suppliers'),
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'has_archive' => false,
		'rewrite' => array('slug' => 'suppliers'), 
		'capability_type' => 'page',
		'supports' => array( 'title', 'author' ),
	); 
    register_post_type( 'suppliers', $args );
}
/**
 * Add a late availability custom post type
 *
 * @return void
 */
function late_availability_type() {
	$labels = array(
		'name' => _x('Availability Periods', 'post type general name'),
		'singular_name' => _x('Availability Period', 'post type singular name'),
		'add_new' => _x('Add New', 'houses', 'availability'),
		'add_new_item' => __('Add New Period', 'availability'),
		'edit_item' => __('Edit Period', 'availability'),
		'new_item' => __('New Period', 'availability'),
		'all_items' => __('All Availability Periods', 'availability'),
		'view_item' => __('View Period', 'availability'),
		'search_items' => __('Search Availability Periods', 'availability'),
		'not_found' =>  __('No Availability Periods found', 'houses'),
		'not_found_in_trash' => __('No periods found in Trash', 'availability'), 
		'parent_item_colon' => '',
		'menu_name' => __('Avail. Periods', 'houses')
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'has_archive' => false, 
		'capability_type' => 'page',
		'supports' => array( 'title', 'editor', 'thumbnail' ),
	); 
    register_post_type( 'availability', $args );
}
function seasonal_type() {
	$labels = array(
		'name' => _x('Seasonal Periods', 'post type general name'),
		'singular_name' => _x('Seasonal Period', 'post type singular name'),
		'add_new' => _x('Add New', 'houses', 'seasonal'),
		'add_new_item' => __('Add New Period', 'seasonal'),
		'edit_item' => __('Edit Period', 'seasonal'),
		'new_item' => __('New Period', 'seasonal'),
		'all_items' => __('All Seasonal Periods', 'seasonal'),
		'view_item' => __('View Period', 'seasonal'),
		'search_items' => __('Search Seasonal Periods', 'seasonal'),
		'not_found' =>  __('No seasonal periods found', 'houses'),
		'not_found_in_trash' => __('No periods found in Trash', 'seasonal'), 
		'parent_item_colon' => '',
		'menu_name' => __('Seasonal Periods', 'houses')
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'has_archive' => false, 
		'capability_type' => 'page',
		'supports' => array( 'title', 'editor', 'thumbnail' ),
	); 
    register_post_type( 'seasonal', $args );
}
function create_house_taxonomies() {
  $labels = array(
	'name' => _x( 'Features', 'taxonomy general name' ),
	'singular_name' => _x( 'Feature', 'taxonomy singular name' ),
	'search_items' =>  __( 'Search Features' ),
	'popular_items' => __( 'Popular Features' ),
	'all_items' => __( 'All Features' ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit Feature' ), 
	'update_item' => __( 'Update Feature' ),
	'add_new_item' => __( 'Add New Feature' ),
	'new_item_name' => __( 'New Feature Name' ),
	'separate_items_with_commas' => __( 'Separate features with commas' ),
	'add_or_remove_items' => __( 'Add or remove features' ),
	'choose_from_most_used' => __( 'Choose from the most used features' ),
	'menu_name' => __( 'Features' ),
  ); 	
  register_taxonomy('feature','houses', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
	'show_admin_column' => true,
    'rewrite' => array( 'slug' => 'feature' ),
  ));
  
  $areas = array('houses');
  
  $options = get_option('plugin_options');
  if(isset($options['activate_suppliers'])) {
	if ($options['activate_suppliers']) array_push($areas, 'suppliers');
  }

  $labels = array(
	'name' => _x( 'Locations', 'taxonomy general name' ),
	'singular_name' => _x( 'Location', 'taxonomy singular name' ),
	'search_items' =>  __( 'Search Locations' ),
	'popular_items' => __( 'Popular Locations' ),
	'all_items' => __( 'All Locations' ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit Location' ), 
	'update_item' => __( 'Update Location' ),
	'add_new_item' => __( 'Add New Location' ),
	'new_item_name' => __( 'New Location Name' ),
	'separate_items_with_commas' => __( 'Separate locations with commas' ),
	'add_or_remove_items' => __( 'Add or remove locations' ),
	'choose_from_most_used' => __( 'Choose from the most used locations' ),
	'menu_name' => __( 'Locations' ),
  ); 

  register_taxonomy('location','houses',array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
	'show_admin_column' => true,
    'rewrite' => array( 'slug' => 'location' ),
  ));

  $labels = array(
	'name' => _x( 'Size Searches', 'taxonomy general name' ),
	'singular_name' => _x( 'Size Search', 'taxonomy singular name' ),
	'search_items' =>  __( 'Search Sizes' ),
	'popular_items' => __( 'Popular Sizes' ),
	'all_items' => __( 'All Sizes' ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit Search Size' ), 
	'update_item' => __( 'Update Size' ),
	'add_new_item' => __( 'Add New Size' ),
	'new_item_name' => __( 'New Size Title' ),
	'separate_items_with_commas' => __( 'Separate sizes with commas' ),
	'add_or_remove_items' => __( 'Add or remove sizes' ),
	'choose_from_most_used' => __( 'Choose from the most used sizes' ),
	'menu_name' => __( 'Search Sizes' ),
  ); 

  register_taxonomy('size','houses',array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
	'show_admin_column' => true,
	'show_in_quick_edit'         => true,
    'rewrite' => array( 'slug' => 'size' ),
  ));
  
  // Activities
  $labels = array(
	'name' => _x( 'Activities', 'taxonomy general name' ),
	'singular_name' => _x( 'Activity', 'taxonomy singular name' ),
	'search_items' =>  __( 'Search Activities' ),
	'popular_items' => __( 'Popular Activities' ),
	'all_items' => __( 'All Activities' ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit Activity' ), 
	'update_item' => __( 'Update Activity' ),
	'add_new_item' => __( 'Add New Activity' ),
	'new_item_name' => __( 'New Activity Name' ),
	'separate_items_with_commas' => __( 'Separate activities with commas' ),
	'add_or_remove_items' => __( 'Add or remove activities' ),
	'choose_from_most_used' => __( 'Choose from most used activities' ),
	'menu_name' => __( 'Activities' ),
  ); 	

  register_taxonomy('activity','houses', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'activity' ),
  ));

  // house types
  $labels = array(
	'name' => _x( 'Types', 'taxonomy general name' ),
	'singular_name' => _x( 'Type', 'taxonomy singular name' ),
	'search_items' =>  __( 'Search Types' ),
	'popular_items' => __( 'Popular Types' ),
	'all_items' => __( 'All Types' ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit Type' ), 
	'update_item' => __( 'Update Type' ),
	'add_new_item' => __( 'Add New Type' ),
	'new_item_name' => __( 'New Type Name' ),
	'separate_items_with_commas' => __( 'Separate types with commas' ),
	'add_or_remove_items' => __( 'Add or remove types' ),
	'choose_from_most_used' => __( 'Choose from the most used types' ),
	'menu_name' => __( 'Types' ),
  ); 	

  register_taxonomy('type','houses', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'type' ),
  ));

  // house occasions
  $labels = array(
	'name' => _x( 'Occasions', 'taxonomy general name' ),
	'singular_name' => _x( 'Occasion', 'taxonomy singular name' ),
	'search_items' =>  __( 'Search Occasions' ),
	'popular_items' => __( 'Popular Occasions' ),
	'all_items' => __( 'All Occasions' ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit Occasion' ), 
	'update_item' => __( 'Update Occasion' ),
	'add_new_item' => __( 'Add New Occasion' ),
	'new_item_name' => __( 'New Occasion Name' ),
	'separate_items_with_commas' => __( 'Separate Occasions with commas' ),
	'add_or_remove_items' => __( 'Add or remove Occasions' ),
	'choose_from_most_used' => __( 'Choose from the most used Occasions' ),
	'menu_name' => __( 'Occasions' ),
  ); 	

  register_taxonomy('occasion','houses', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'occasion' ),
  ));
}

function create_supplier_taxonomies() 
{
  // Audiences
  $labels = array(
	'name' => _x( 'Audiences', 'taxonomy general name' ),
	'singular_name' => _x( 'Audience', 'taxonomy singular name' ),
	'search_items' =>  __( 'Search Audiences' ),
	'popular_items' => __( 'Popular Audiences' ),
	'all_items' => __( 'All Audiences' ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit Audience' ), 
	'update_item' => __( 'Update Audience' ),
	'add_new_item' => __( 'Add New Audience' ),
	'new_item_name' => __( 'New Audience Name' ),
	'separate_items_with_commas' => __( 'Separate audiences with commas' ),
	'add_or_remove_items' => __( 'Add or remove audiences' ),
	'choose_from_most_used' => __( 'Choose from most used audiences' ),
	'menu_name' => __( 'Audience' ),
  ); 	

  register_taxonomy('audience','suppliers', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'suitable-for' ),
  ));
}

function change_default_title( $title ){
     $screen = get_current_screen();
     if  ( 'suppliers' == $screen->post_type ) {
          $title = 'Internal Supplier Name';
     }
     return $title;
}

function ong_custom_house_pages() {
	function add_query_vars($houseVars) {
	$houseVars[] = "current_house_page"; 
	$houseVars[] = "addvariable"; 
		return $houseVars;
	}
	add_filter('query_vars', 'add_query_vars');
	add_rewrite_rule("^houses/([^/]+)/([^/]+)/([^/]+)/?",'index.php?houses=$matches[1]&current_house_page=$matches[2]&addvariable=$matches[3]','top');
	add_rewrite_rule("^houses/([^/]+)/([^/]+)/?",'index.php?houses=$matches[1]&current_house_page=$matches[2]','top');
}	

function custom_canonical($str)
{
    global $wp_query;
    if (isset($wp_query->query_vars['current_house_page'])) {
        $current_house      = urldecode($wp_query->query_vars['houses']);
        $current_house_page = urldecode($wp_query->query_vars['current_house_page']);
        return '/houses/' . $current_house . '/' . $current_house_page . '/';
    } else {
        return $str;
    }
}
function custom_metadesc($str) {
    global $wp_query;
	if(isset($wp_query->query['s'])) {
		return 'Search results';
	}
	if(!empty($wp_query->query["current_house_page"]) && $wp_query->query["current_house_page"] == 'more') {
		$str .= ' Things to do.';
	}
	if(!empty($wp_query->query["current_house_page"]) && $wp_query->query["current_house_page"] == 'gallery') {
		$str .= ' View the gallery.';
	}
	if(!empty($wp_query->query["current_house_page"]) && $wp_query->query["current_house_page"] == 'facts') {
		$str .= ' Key Facts.';
	}
	if(!empty($wp_query->query["current_house_page"]) && $wp_query->query["current_house_page"] == 'availability') {
		$str .= ' Availability.';
	}
	if(!empty($wp_query->query["current_house_page"]) && $wp_query->query["current_house_page"] == 'booknow') {
		$str .= ' Book Now.';
	}

    // if (isset($wp_query->query_vars['current_house_page'])) {
    //     return false;
    // } 
    return $str;
}

?>
