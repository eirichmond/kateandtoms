<?php

//define( 'CMB_PATH', get_template_directory() );

// disable xmlrpc to reduce cpu outage 24 Nov 2015
add_filter('xmlrpc_enabled', '__return_false');
add_action('after_setup_theme', 'clubsandwich_theme_setup');
add_filter('excerpt_length', 'katntoms_excerpt_length', 999 );
add_filter('excerpt_more', 'katntoms_excerpt_more');
add_action('template_redirect', 'ong_activate_search');
add_filter('pre_get_posts', 'ong_searchfilter');
add_action('admin_head', 'new_relic_admin');
add_action('init', 'wpcf7_change_to_blog1', 10 );
add_filter( 'wpcf7_support_html5_fallback', '__return_true' );
add_filter('enter_title_here', 'change_default_title' );
add_filter('upload_dir', 'kat_upload_dir' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'remote_login_js_loader' );
add_action( 'wp_loaded', 'overwrite_cf7_dynamic_shortcode' );

/**
 * Add google tag manager code to head
 */

 
/**
 * setup GMT tag container ids
 *
 * @return array $tag_manager
 */
function google_tag_manager_ids() {
	$tag_manager = array(
		'1' => 'GTM-T67NL8F', // bigcottage
		'5' => 'GTM-WB8Z4FX', // hens
		'6' => 'GTM-KDVCNSR', // stags
		'8' => 'GTM-5F2M9LV', // partners
		'11' => 'GTM-WNT2MLS', // kateandtoms
		'16' => 'GTM-MQFQTD8' // events
	);
	return $tag_manager;
}

/**
 * Add tracking to head
 */
add_action( 'wp_head', 'kts_global_header_google_tag_manager' );
function kts_global_header_google_tag_manager() {
	$blog_id = get_current_blog_id();
	$tag_manager = google_tag_manager_ids();
	if(!isset($tag_manager[$blog_id])) {
		return;
	}
	$gtm_code = "<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','".$tag_manager[$blog_id]."');</script>
	<!-- End Google Tag Manager -->";
	if ( !is_admin() && $gtm_code ) {
		echo $gtm_code;
	}
}

/**
 * Add Font Awesome Kit
 */
add_action( 'wp_head', 'kts_global_header_font_awesome_kit', 10 );
function kts_global_header_font_awesome_kit() {
	$output = "<!-- Font Awesome Kit -->
	<script src='https://kit.fontawesome.com/20f36f4160.js' crossorigin='anonymous'></script>
	<!-- End Font Awesome Kit -->";
	echo $output;
}

/**
 * Add tacking to footer
 */
add_action( 'wp_footer', 'kts_global_footer_tag_manager' );
function kts_global_footer_tag_manager() { 
	$blog_id = get_current_blog_id(); 
	$tag_manager = google_tag_manager_ids();
	if(!isset($tag_manager[$blog_id])) {
		return;
	}
	echo '<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$tag_manager[$blog_id].'"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->';

}


// add_action( 'wp_head', 'kat_include_google_tag_manager' );
// function kat_include_google_tag_manager() {
// 	$output = "<!-- Google Tag Manager -->
// <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
// new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
// j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
// 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
// })(window,document,'script','dataLayer','GTM-WNT2MLS');</script>
// <!-- End Google Tag Manager -->";
// 	echo $output;
// }

/**
 * Add linkedin code to footer
 */
add_action( 'wp_footer', 'kat_include_linkedin_tracker' );
function kat_include_linkedin_tracker() {
	$output = '<!-- Begin LinkedIn -->
	<script type="text/javascript"> _linkedin_partner_id = "5415716"; window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || []; window._linkedin_data_partner_ids.push(_linkedin_partner_id); </script><script type="text/javascript"> (function(l) { if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])}; window.lintrk.q=[]} var s = document.getElementsByTagName("script")[0]; var b = document.createElement("script"); b.type = "text/javascript";b.async = true; b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js"; s.parentNode.insertBefore(b, s);})(window.lintrk); </script> <noscript> <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=5415716&fmt=gif" /> </noscript>
	<!-- End LinkedIn -->';
	echo $output;
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
	add_theme_support( 'align-wide' );

	// converted menus to make them backend managable
	register_nav_menus( array(
		'top_menu' => 'Top Menu',
		'sub_menu'  => 'Sub Menu',
		'mobile_menu'  => 'Mobile Menu',
		'footer_column_1'  => 'Footer Column 1',
		'footer_column_2'  => 'Footer Column 2',
		'footer_column_3'  => 'Footer Column 3',
		'footer_column_4'  => 'Footer Column 4'
	) );
}

function kts_topmenu_classes($classes, $item, $args, $depth) {
	if($depth == 1 || $depth == 2) {
		$classes[] = 'submenu-item';
	}
	return $classes;
}
add_filter('nav_menu_css_class', 'kts_topmenu_classes', 10, 4);

function kts_link_attrs($atts, $item, $args, $depth) {
	if($args->theme_location ==  'sub_menu' && $depth == 0 || $depth == 2) {
		$atts['class'] = 'has-sub';
	}
	if($args->theme_location ==  'mobile_menu' && $depth == 0) {
		if($item->ID == 68783 || $item->ID == 41998 || $item->ID == 16800 || $item->ID == 8135) {
			$atts['id'] = 'msbhslide';
		}
		if($item->ID == 68784) {
			$atts['id'] = 'msbdslide';
		}
		if($args->walker->has_children) {
			$atts['class'] = 'submenutoggler';
			$args->after = '<span class="menu-icon"><i class="glyphicon glyphicon-chevron-right"></i></span>';
		} else {
			$args->after = '';
		}
		
	}
	if($args->theme_location ==  'mobile_menu' && $depth >= 1) {
		$atts['class'] = 'subsubmenutoggler';
		if($args->walker->has_children) {
			$args->after = '<span class="menu-icon"><i class="glyphicon glyphicon-chevron-right"></i></span>';
		} else {
			$args->after = '';
		}
	}
	

	return $atts;
}
add_filter('nav_menu_link_attributes', 'kts_link_attrs', 10, 4);

// function kts_submenu_classes($classes, $args, $depth) {
// 	if($args->theme_location ==  'mobile_menu') {
// 		if($depth == 1) {
// 			echo 'foo';
// 		}
// 		if($depth == 0) {
// 			$args->after = '<span class="menu-icon"><i class="glyphicon glyphicon-chevron-right"></i></span>';
// 		} else {
// 			$args->after = '';
// 		}
	
	
// 		$classes[] = 'secondary-nav submenu';
// 	}
// 	return $classes;
// }
// add_filter('nav_menu_submenu_css_class', 'kts_submenu_classes', 10, 3);


class KTS_Walker_Nav_Menu extends Walker_Nav_Menu {
	public function start_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"secondary-nav submenu\">\n";
	}
}

class KTS_Walker_Mobile_Nav_Menu extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		if($depth == 0) {
			$output .= "\n$indent<ul id=\"msub\" class=\"secondary-nav submenu\">\n<span id=\"submenureturn\" class=\"menu-icon-return\">
			<i class=\"glyphicon glyphicon-chevron-left\"></i>
			</span>";
		} elseif ($depth == 1) {
			$output .= "\n$indent<ul class=\"tertiery-nav submenu\">\n<span id=\"submenureturn\" class=\"menu-icon-return\">
			<i class=\"glyphicon glyphicon-chevron-left\"></i>
			</span>";
		} else {
			$output .= "\n$indent<ul class=\"secondary-nav submenu\">\n";
		}
	}
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
		$search_items[$i]['url'] = str_replace('.test', '.com', $house->permalink);
		$search_items[$i]['thumb'] = str_replace('.test', '.com', $house->post_thumbnail);
		$search_items[$i]['label'] = $house->post_title;
		$search_items[$i]['desc'] = $house->brief_description;
		$search_items[$i]['house_id'] = $house->house_id;
		$search_items[$i]['blog_id'] = $house->blog_id;
		$search_items[$i]['post_id'] = $house->post_id;
		$i++;
	}

	// then get all the locations from the locations page widgets
	// use the locations house id
	$ID = 27142;
	$key = 0;
	$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);

	$x = $i;
	for ($i = 0; $i < $rowCount; $i++) {
		for ($n = 0; $n < 4; $n++) {
			$location_photo = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
			$search_items[$x]['category'] = 'Locations';
			
			$search_items[$x]['url'] = str_replace('.test', '.com', get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true));
			$search_items[$x]['thumb'] = str_replace('.test', '.com', wp_get_attachment_image_url( $location_photo, 'thumbnail' ));
			$search_items[$x]['label'] = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
			$search_items[$x]['desc'] = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtext_text', true);
			$x++;
		}
	}


	$feature_id = 48958;
	$key = 0;
	$rowCount = get_post_meta($feature_id, 'widgets_'.$key.'_imageset', true);
	
	//$x = $i;
	for ($i = 0; $i < $rowCount; $i++) {
		for ($n = 0; $n < 4; $n++) { 
			$feature_photo = get_post_meta($feature_id, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
			$search_items[$x]['category'] = 'Features';
			
			$search_items[$x]['url'] = str_replace('.test', '.com', get_post_meta($feature_id, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true));
			$search_items[$x]['thumb'] = str_replace('.test', '.com', wp_get_attachment_image_url( $feature_photo, 'thumbnail' ));
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

	if(!is_page( 'syncipro' ) && !is_page( 'syncipro-update-rates' )) {

		wp_enqueue_style('kandt-main-styles', get_template_directory_uri() .'/style.min.css', array(), '1.6', 'all');	

		wp_enqueue_style( 'kandt-font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css' );

		wp_enqueue_style( 'kandt-omponents', get_template_directory_uri() .'/css/component.css' );
		wp_enqueue_script( 'kandt-modernizr', get_template_directory_uri() . '/js/modernizr.custom.js', array(), date('Y-m'), array( 'strategy' => 'defer' ) );

		wp_enqueue_script( 'kandt-classie', get_template_directory_uri() . '/js/classie.js',array(), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_enqueue_script( 'kandt-sidebar', get_template_directory_uri() . '/js/sidebarEffects.js',array(), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ) );

		wp_enqueue_style( 'kandt-zoom-style', get_template_directory_uri() .'/css/zoom.css' );
		wp_enqueue_script( 'kandt-zoom', get_template_directory_uri() . '/js/jquery.zoom.min.js', array('jquery'), date('Y-m'), array( 'strategy' => 'defer' ) );
		wp_enqueue_script( 'kandt-zoom-fired', get_template_directory_uri() . '/js/zoom-script.js', array(), date('Y-m'), array( 'strategy' => 'defer' ) );

		wp_enqueue_script( 'kandt-autocomplete', get_template_directory_uri() . '/js/jquery.autocomplete.js', array('jquery','jquery-ui-autocomplete'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_enqueue_script( 'kandt-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ));
		wp_enqueue_script( 'kandt-search', get_template_directory_uri() . '/js/search.js', array('jquery', 'jquery-ui-datepicker'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ));
		wp_enqueue_script( 'kandt-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ));
		wp_enqueue_script( 'kandt-cycle2', get_template_directory_uri() . '/js/cycle2.js', array('jquery'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ));
		wp_enqueue_script( 'kandt-cycle-slider', get_template_directory_uri() . '/js/cycle-slider.js', array('jquery'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ));

		wp_enqueue_script( 'kandt-katheader', get_template_directory_uri() . '/js/katHeader.jquery.js', array('jquery'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ));

	
		$search_items = get_search_items_refactor();
		$search_items = array( 'searchItems' => $search_items );
		wp_localize_script( 'kandt-autocomplete', 'object_name', $search_items );

		// load script
		wp_enqueue_script( 'ajax-search', get_template_directory_uri() . '/js/ajax-search.js', array('jquery'), date('Y-m'), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_localize_script( 'ajax-search', 'search_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nextNonce' => wp_create_nonce( 'kt-search-nonce' ) ) );


	}


}
add_action( 'wp_enqueue_scripts', 'geebee_scripts' );


/**
 * Defer scripts to help page speed insights
 *
 * @param [type] $url
 * @return void
 */
// function kandt_defer_parsing_of_js( $url ) {

// 	// don't break WP Admin
//     if ( is_user_logged_in() ) {
// 		return $url; 
// 	}

// 	// don't break other scripts
//     if ( FALSE === strpos( $url, '.js' ) ) {
// 		return $url;
// 	}

// 	// don't defer jquery
//     if ( strpos( $url, 'jquery.js' ) || strpos( $url, 'jquery.min.js' ) ) {
// 		return $url;
// 	}

// 	// include defer for all other scripts
//     return str_replace( ' src', ' defer src', $url );
// }
// add_filter( 'script_loader_tag', 'kandt_defer_parsing_of_js', 10 );

/**
 * Preload CSS to help page speed
 *
 * @param [type] $html
 * @param [type] $handle
 * @param [type] $href
 * @param [type] $media
 * @return void
 */
// function preload_filter( $html, $handle, $href, $media ){
// 	if ( ! is_admin() ) {
//         $html = '<link rel="preload" href="' . $href . '" as="style" id="' . $handle . '" media="' . $media . '" onload="this.onload=null;this.rel=\'stylesheet\'">'
//             . '<noscript>' . $html . '</noscript>';
// 	}
//     return $html;
// }
// add_filter( 'style_loader_tag',  'preload_filter', 20, 4 );


// hookup the ajax scripts to connect the browser js talk to the server #complete
// build the individual queries for server side processing matching the current search logic
// returns results
// use javascript to render the results in the browser
// merge filters on user event if results already exist
// stage for testing

/**
 * Add an ajax search.
 */
function search_houses() {
    // Handle request then generate response using WP_Ajax_Response


    $nonce = $_POST['nextNonce'];
	if ( ! wp_verify_nonce( $nonce, 'kt-search-nonce' ) ) {
		die ( 'Busted!' );
	}

	if(isset($_POST['date']) && $_POST['date'] != '') {
		$inputs['date'] = $_POST['date'];
	}
	if(isset($_POST['dtype']) && $_POST['dtype'] != '') {
		$inputs['dtype'] = $_POST['dtype'];
	}
	if(isset($_POST['size']) && $_POST['size'] != '') {
		$inputs['size'] = $_POST['size'];
	}
	if(isset($_POST['feature']) && $_POST['feature'] != '') {
		$inputs['taxonomies']['feature'] = $_POST['feature'];
	}
	if(isset($_POST['local']) && $_POST['local'] != '') {
		$inputs['taxonomies']['location'] = $_POST['local'];
	}

	$inputs = $inputs;

	// set variables in the global
	set_query_var( 'inputs', $inputs );
	// generate the response
	$template = get_template_part( 'house_display', '' );
	//include('house_display.php');



    // Don't forget to stop execution afterward.
    wp_die();
}

add_action( 'wp_ajax_nopriv_search_houses', 'search_houses' );
add_action( 'wp_ajax_search_houses', 'search_houses' );



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
		array( 'id' => 'ah_from_size', 'name' => 'Size includes', 'type' => 'taxonomy_select',  'taxonomy' => 'size',  'multiple' => true ),
		array( 'id' => 'ah_from_type', 'name' => 'Type includes', 'type' => 'taxonomy_select',  'taxonomy' => 'type',  'multiple' => true ),
		array( 'id' => 'ah_from_occasion', 'name' => 'Occasion includes', 'type' => 'taxonomy_select',  'taxonomy' => 'occasion',  'multiple' => true ),
		array( 'id' => 'ah_from_seasonal', 'name' => 'Seasonal includes', 'type' => 'post_select', 'use_ajax' => false, 'query' => array( 'post_type' => 'seasonal', 'posts_per_page' => -1 ), 'multiple' => true ),
		array( 'id' => 'ah_section_cotswolds',  'name' => 'Section Cotswolds', 'type' => 'checkbox' ),
		array( 'id' => 'ah_section_coast',  'name' => 'Section Coast', 'type' => 'checkbox' ),
		array( 'id' => 'ah_section_country',  'name' => 'Section Country', 'type' => 'checkbox' ),
		array( 'id' => 'ah_section_town',  'name' => 'Section Town', 'type' => 'checkbox' ),




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


/*
add_action( 'wp_footer', 'mycustom_wp_footer' );

function mycustom_wp_footer() {
	?>
	<script type="text/javascript">
	document.addEventListener( 'wpcf7mailsent', function( event ) {
	    window.location.replace("/thank-you");
	}, false );
	</script>
	<?php
}
*/

function my_acf_init() {

	acf_update_setting('google_api_key', 'AIzaSyCWIUdebNRovvJryUDibH8cwjkRsPI2M_8');
}

add_action('acf/init', 'my_acf_init');

function my_acf_google_map_api( $api ){
	$api['key'] = 'AIzaSyCWIUdebNRovvJryUDibH8cwjkRsPI2M_8';
	return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

/**
 * Filter menu items and change target
 *
 * @param string $target
 * @param array $item
 * @return void
 */
function filter_self_blank_callback($target, $item) {

	$blank = array(
		'privacy policy',
		'cookie policy',
		'terms and conditions'
	);

	if(in_array($item["title"], $blank)) {
		$target = '_blank';
	}

	return $target;

}
add_filter('filter_self_blank', 'filter_self_blank_callback', 10, 2);


?>