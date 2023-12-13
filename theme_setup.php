<?php 
// disable xmlrpc to reduce cpu outage 24 Nov 2015
add_filter('xmlrpc_enabled', '__return_false');

add_action('after_setup_theme', 'clubsandwich_theme_setup');
add_filter('excerpt_length', 'katntoms_excerpt_length', 999 );
add_filter('excerpt_more', 'katntoms_excerpt_more');

add_action('template_redirect', 'ong_activate_search');
add_filter('pre_get_posts', 'ong_searchfilter');

add_action('admin_head', 'new_relic_admin');

add_action('init', 'wpcf7_change_to_blog1', 10 );
add_filter('enter_title_here', 'change_default_title' );
add_filter('upload_dir', 'kat_upload_dir' );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_action( 'wp_loaded', 'overwrite_cf7_dynamic_shortcode' );

add_action( 'wp_head', 'kat_include_hotjar' );
function kat_include_hotjar() {
	
// 	$output = "<!-- Hotjar Tracking Code for http://www.kateandtoms.com -->
// <script>
// (function(h,o,t,j,a,r){
// h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
// h._hjSettings={hjid:290751,hjsv:5};
// a=o.getElementsByTagName('head')[0];
// r=o.createElement('script');r.async=1;
// r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
// a.appendChild(r);
// })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
// </script>";

// 	if(!is_singular( 'houses' )) {
// 		echo $output;			
// 	}

}




if (!isset($content_width)) $content_width = 640;

register_sidebar(array(
    'name' => __('Blog Sidebar'),
    'id' => 'blog-sidebar',
    'description' => __('Widgets in this area will be shown on the right-hand side of the blog.'),
    'before_title' => '<h2>',
    'after_title' => '</h2>'
));
   
function clubsandwich_theme_setup() {
    add_theme_support('automatic-feed-links');
}

function wpcf7_change_to_blog1() {
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['_wpcf7_is_ajax_call'] ) )
		return;
	switch_to_blog(1);
}

function kat_upload_dir( $args ) {
    $args['path']    = preg_replace( '/\/blogs.dir\/([0-9]{1}|[0-9]{2})\/files/i', '/uploads', $args['path'] );
    $args['url']     = preg_replace( '/\/blogs.dir\/([0-9]{1}|[0-9]{2})\/files/i', '/uploads', $args['url'] );   
    $args['basedir'] = preg_replace( '/\/blogs.dir\/([0-9]{1}|[0-9]{2})\/files/i', '/uploads', $args['basedir'] );   
    $args['baseurl'] = preg_replace( '/\/blogs.dir\/([0-9]{1}|[0-9]{2})\/files/i', '/uploads', $args['baseurl'] );   
    $args['url']     = preg_replace( '/\/files\//i', '/wp-content/uploads/', $args['url'] );   
    $args['baseurl'] = preg_replace( '/\/files/i', '/wp-content/uploads', $args['baseurl'] );   
    return $args;
}

function ong_searchfilter($query) {
    if (($query->is_search) && !$query->is_admin && $query->is_main_query()) {
        $query->set('update_post_meta_cache', false);
        $query->set('update_post_term_cache', false);
    }
    if (($query->is_archive || $query->is_tax)) {
        $query->set('update_post_meta_cache', false);
        $query->set('update_post_term_cache', false);
    }
    return $query;
}

function ong_activate_search() {
    if (($_SERVER["REQUEST_URI"] == "/?s=") || ($_SERVER["REQUEST_URI"] == "/?s=Search") || ($_SERVER["REQUEST_URI"] == "/?s=all")) {
        include(TEMPLATEPATH . '/archive-houses.php');
        exit;
    }
}

function new_relic_admin() {
    if (extension_loaded('newrelic')) {
        newrelic_set_appname('clubsandwich_admin');
    }
}

function overwrite_cf7_dynamic_shortcode() { 
    function get_title_of_page( $atts ) {
        if (is_archive() || is_search()) {
            return 'Archive or search page';
        }
        global $post;
        if (isset($post)) {
            return $post->post_title;
        }
        else {
            return 'Other type of page';
        }
    }
    remove_shortcode('CF7_get_post_var');
    add_shortcode( 'CF7_get_post_var', 'get_title_of_page' );
} 


function get_search_items_refactor() {

	global $wpdb;
	$blog_id = get_current_blog_id();
	$order = ($blog_id == 7 || $blog_id == 13 ? 'ASC': 'DESC');

	$houses = $wpdb->get_results("
		SELECT *
		FROM houses
		WHERE blog_id = ".$blog_id."
		ORDER BY sleeps_max ".$order."
	");
	
	// first get all the houses and put them in a category of 'Houses'
	$search_items = array();
	$i = 0;
	foreach ($houses as $house) {
		$search_items[$i]['category'] = 'Houses';
		$search_items[$i]['url'] = $house->permalink;
		$search_items[$i]['thumb'] = $house->post_thumbnail;
		$search_items[$i]['label'] = $house->post_title;
		$search_items[$i]['desc'] = $house->brief_description;
		$i++;
	}
	
	// then get all the locations from the locations page widgets
	// use the locations house id
	$feature_id = 27142;
	$key = 0;
	$rowCount = get_post_meta($feature_id, 'widgets_'.$key.'_imageset', true);
	
	$x = $i;
	for ($i = 0; $i < $rowCount; $i++) {
		for ($n = 0; $n < 4; $n++) { 
			$location_photo = get_post_meta($feature_id, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
			$search_items[$x]['category'] = 'Locations';
			$search_items[$x]['url'] = get_post_meta($feature_id, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
			$search_items[$x]['thumb'] = wp_get_attachment_image_url( $location_photo, 'thumbnail' );
			$search_items[$x]['label'] = get_post_meta($feature_id, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
			$search_items[$x]['desc'] = get_post_meta($feature_id, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtext_text', true);
			$x++;
		}
	}

	return $search_items;

}

/**
 * Enqueue scripts and styles.
 */
function geebee_scripts() {
	wp_enqueue_style( 'kandt-font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css' );
	
	wp_enqueue_style( 'kandt-zoom-style', get_template_directory_uri() .'/css/zoom.css' );
	wp_enqueue_script( 'kandt-zoom', get_template_directory_uri() . '/js/jquery.zoom.min.js', array('jquery'));
	wp_enqueue_script( 'kandt-zoom-fired', get_template_directory_uri() . '/js/zoom-script.js');
		
	wp_enqueue_script( 'kandt-autocomplete', get_template_directory_uri() . '/js/jquery.autocomplete.js', array('jquery','jquery-ui-autocomplete'), true );
	
	$search_items = get_search_items_refactor();
	
	
	$search_items = array( 'searchItems' => $search_items );
	wp_localize_script( 'kandt-autocomplete', 'object_name', $search_items );


}
add_action( 'wp_enqueue_scripts', 'geebee_scripts' );



/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sample_metaboxes( array $meta_boxes ) {

	// Example of all available fields
	
	$fields = array(
		array( 'id' => 'offer_period_identitfier', 'type' => 'text', 'cols' => 1 ),
		array( 'id' => 'offer_period_name', 'type' => 'text', 'cols' => 2 ),
		
		array( 'id' => 'offer_image', 'type' => 'image', 'size' => 'height=50&width=75&crop=1', 'cols' => 1 ),
		
		array( 'id' => 'offer_house', 'type' => 'post_select', 'use_ajax' => true, 'query' => array( 'post_status' => 'publish', 'post_type' => 'houses', 'posts_per_page' => -1 ), 'cols' => 2 ),
		
		//array( 'id' => 'offer_house', 'type' => 'select', 'name' => 'Select field', 'options' => array( 'option-1' => 'Option 1', 'option-2' => 'Option 2', 'option-3' => 'Option 3' ) ),

		array( 'id' => 'offer_details',  'type' => 'text', 'cols' => 3 ),

		array( 'id' => 'offer_date', 'type' => 'date', 'cols' => 2 ),
		
/*
		array( 'id' => 'field-2', 'name' => 'Read-only text input field', 'type' => 'text', 'readonly' => true, 'default' => 'READ ONLY' ),
 		array( 'id' => 'field-3', 'name' => 'Repeatable text input field', 'type' => 'text', 'desc' => 'Add up to 5 fields.', 'repeatable' => true, 'repeatable_max' => 5, 'sortable' => true ),

		array( 'id' => 'field-4',  'name' => 'Small text input field', 'type' => 'text_small' ),
		array( 'id' => 'field-5',  'name' => 'URL field', 'type' => 'url' ),

		array( 'id' => 'field-7',  'name' => 'Checkbox field', 'type' => 'checkbox' ),

		array( 'id' => 'field-8',  'name' => 'WYSIWYG field', 'type' => 'wysiwyg', 'options' => array( 'editor_height' => '100' ), 'repeatable' => true, 'sortable' => true ),

		array( 'id' => 'field-9',  'name' => 'Textarea field', 'type' => 'textarea' ),
		array( 'id' => 'field-10',  'name' => 'Code textarea field', 'type' => 'textarea_code' ),

		array( 'id' => 'field-12', 'name' => 'Image upload field', 'type' => 'image', 'repeatable' => true, 'show_size' => true ),

		array( 'id' => 'field-15b', 'name' => 'Select taxonomy field', 'type' => 'taxonomy_select',  'taxonomy' => 'category',  'multiple' => true ),
		array( 'id' => 'field-17', 'name' => 'Post select field (AJAX)', 'type' => 'post_select', 'use_ajax' => true ),
		array( 'id' => 'field-17b', 'name' => 'Post select field (AJAX)', 'type' => 'post_select', 'use_ajax' => true, 'query' => array( 'posts_per_page' => 8 ), 'multiple' => true  ),

		array( 'id' => 'field-19', 'name' => 'Time input field', 'type' => 'time' ),
		array( 'id' => 'field-20', 'name' => 'Date (unix) input field', 'type' => 'date_unix' ),
		array( 'id' => 'field-21', 'name' => 'Date & Time (unix) input field', 'type' => 'datetime_unix' ),

		array( 'id' => 'field-22', 'name' => 'Color', 'type' => 'colorpicker' ),

		array( 'id' => 'field-23', 'name' => 'Location', 'type' => 'gmap' ),

		array( 'id' => 'field-24', 'name' => 'Title Field', 'type' => 'title' ),
*/

	);


	// Example of repeatable group. Using all fields.
	// For this example, copy fields from $fields, update I
	$group_fields = $fields;
	foreach ( $group_fields as &$field ) {
		$field['id'] = str_replace( 'field', 'gfield', $field['id'] );
	}

	$meta_boxes[] = array(
		'title' => 'Special Offers',
		'pages' => 'page',
		'show_on' => array('page-template' => array( 'page-special-offers.php' )),
		'fields' => array(
			array(
				'id' => 'special_offer',
				'name' => 'Offers',
				'type' => 'group',
				'repeatable' => true,
				'sortable' => true,
				'fields' => $group_fields,
 				'desc' => 'In the first field use the symbol # to identify the id anchor "link to" associated in the Navigation Image Set from the Widget Factory.'
			)
		)
	);
	



	$settings = array(
		
		array( 'id' => 'show_associated_houses',  'name' => 'Enable', 'type' => 'checkbox' ),
		array( 'id' => 'ah_from_features', 'name' => 'Feature includes', 'type' => 'taxonomy_select',  'taxonomy' => 'feature',  'multiple' => true ),
		array( 'id' => 'ah_from_locations', 'name' => 'Location includes', 'type' => 'taxonomy_select',  'taxonomy' => 'location',  'multiple' => true ),

		
/*
		array( 'id' => 'offer_period_identitfier', 'type' => 'text', 'cols' => 1 ),
		array( 'id' => 'offer_period_name', 'type' => 'text', 'cols' => 2 ),
		
		array( 'id' => 'offer_image', 'type' => 'image', 'size' => 'height=50&width=75&crop=1', 'cols' => 1 ),
		
		array( 'id' => 'offer_house', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'houses', 'posts_per_page' => -1 ), 'cols' => 2 ),

		array( 'id' => 'offer_details',  'type' => 'text', 'cols' => 3 ),

		array( 'id' => 'offer_date', 'type' => 'date', 'cols' => 2 ),
		array( 'id' => 'field-2', 'name' => 'Read-only text input field', 'type' => 'text', 'readonly' => true, 'default' => 'READ ONLY' ),
 		array( 'id' => 'field-3', 'name' => 'Repeatable text input field', 'type' => 'text', 'desc' => 'Add up to 5 fields.', 'repeatable' => true, 'repeatable_max' => 5, 'sortable' => true ),

		array( 'id' => 'field-4',  'name' => 'Small text input field', 'type' => 'text_small' ),
		array( 'id' => 'field-5',  'name' => 'URL field', 'type' => 'url' ),

		

		array( 'id' => 'field-8',  'name' => 'WYSIWYG field', 'type' => 'wysiwyg', 'options' => array( 'editor_height' => '100' ), 'repeatable' => true, 'sortable' => true ),

		array( 'id' => 'field-9',  'name' => 'Textarea field', 'type' => 'textarea' ),
		array( 'id' => 'field-10',  'name' => 'Code textarea field', 'type' => 'textarea_code' ),

		array( 'id' => 'field-12', 'name' => 'Image upload field', 'type' => 'image', 'repeatable' => true, 'show_size' => true ),

		array( 'id' => 'field-17', 'name' => 'Post select field (AJAX)', 'type' => 'post_select', 'use_ajax' => true ),
		array( 'id' => 'field-17b', 'name' => 'Post select field (AJAX)', 'type' => 'post_select', 'use_ajax' => true, 'query' => array( 'posts_per_page' => 8 ), 'multiple' => true  ),

		array( 'id' => 'field-19', 'name' => 'Time input field', 'type' => 'time' ),
		array( 'id' => 'field-20', 'name' => 'Date (unix) input field', 'type' => 'date_unix' ),
		array( 'id' => 'field-21', 'name' => 'Date & Time (unix) input field', 'type' => 'datetime_unix' ),

		array( 'id' => 'field-22', 'name' => 'Color', 'type' => 'colorpicker' ),

		array( 'id' => 'field-23', 'name' => 'Location', 'type' => 'gmap' ),

		array( 'id' => 'field-24', 'name' => 'Title Field', 'type' => 'title' ),

*/
		
	);


	$meta_boxes[] = array(
		'title' => 'Associated Houses',
		'pages' => 'page',
		'context'    => 'side',
        'priority'   => 'high',
  		'fields' => $settings
	);
	
	
	return $meta_boxes;

}
add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );

function posts_metaboxes( array $meta_boxes ) {

	// Example of all available fields
	$colors = array(
        'color1' => 'Colour 1',
        'color2' => 'Colour 2',
        'color3' => 'Colour 3',
        'color4' => 'Colour 4',
        'color5' => 'Colour 5',
        'color6' => 'Colour 6',
        'color7' => 'Colour 7',
        'color8' => 'Colour 8',
        'color9' => 'Colour 9',
        'color10' => 'Colour 10',
        'color11' => 'Colour 11',
        'color12' => 'Colour 12',
        'color13' => 'Colour 13',
        'color14' => 'Colour 14',
        'color15' => 'Colour 15',
        'color16' => 'Colour 16',
	);

	$fields = array(

		array( 'id' => 'core-color', 'name' => 'Select Core Background Color', 'type' => 'radio', 'options' => $colors, 'multiple' => false ),

	);

	$meta_boxes[] = array(
		'title' => 'Core Colour',
		'pages' => 'post',
		'fields' => $fields
	);

	return $meta_boxes;

}
add_filter( 'cmb_meta_boxes', 'posts_metaboxes' );



?>