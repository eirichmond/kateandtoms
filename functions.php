<?php

//include 'testing/special-offers.php';

// if site is staged include this file
//include 'staged/staged_functions.php';

include 'config/config.php';

// run this first
//kat_include('core/dbstuff.php');

// run this second
//kat_include('core/sitewrite.php');

kat_include('cronjobs/ipro.php');

kat_include('core/helpers.php');
kat_include('core/post_functions.php');
kat_include('core/admin_styles.php');
kat_include('core/availability_functions.php');
kat_include('core/config_pages.php');
kat_include('core/image_sizes.php');
kat_include('core/post_types.php');
kat_include('core/save_house_metadata.php');
kat_include('core/save_seasonal_metadata.php');
kat_include('core/save_special_offers.php');
kat_include('core/rewrite-rules.php');

kat_include('core/inheritance-meta.php');
kat_include('core/theme_setup.php');

kat_include('core/settings.php');

kat_include('classes/class.availability.php');
kat_include('classes/class.iprokit.php');
kat_include('classes/class.book.php');
kat_include('classes/class.imageset.php');
kat_include('classes/class.productpage.php');
kat_include('classes/class.search.php');
kat_include('classes/class.widget.php');
kat_include('classes/class.partner-widgets.php');
kat_include('classes/class.specialoffers.php');
kat_include('classes/class.relatedhouses.php');
kat_include('classes/class.attachment-extras.php');

kat_include('classes/class.settings-api.php');



kat_include('acf-fields/register_fields.php');

function kat_include( $file ) {
	$directory = get_theme_root().'/clubsandwich/';
    $path = $directory . $file;
	if( file_exists($path) ) {
		include_once( $path );
	}
}

/*
function do_robots() {
	header( 'Content-Type: text/plain; charset=utf-8' );

	do_action( 'do_robotstxt' );

	$output = '';
	$public = get_option( 'blog_public' );
	if ( '0' ==  $public ) {
		$output .= "User-agent: *\n";
		$output .= "Disallow: /\n";
	} else {
		$output .= "User-agent: *\n";
		$output .= "Disallow:\n";
	}
	echo apply_filters('robots_txt', $output, $public);
}
*/

/*
	filter robots.txt file for themoult
*/
function create_robotstxt($output, $public) {
	$id = get_current_blog_id();
	if ( $id == 1 || $id == 5 || $id == 6 || $id == 11 || $id == 12 || $id == 16 ) {
		$output = "";
		$output .= "User-agent: *\n";
		$output .= "Disallow: /?\n";
		$output .= "Disallow: /terms-and-conditions\n";
		$output .= "Disallow: *availability\n";
		$output .= "Disallow: *book\n";
		$output .= "Disallow: *gallery\n";
		$output .= "Disallow: *booknow\n";
		$output .= "Disallow: /search\n";
		$output .= "Disallow: /process-contact-forms\n";
		$output .= "Disallow: /syncipro\n";
		$output .= "Disallow: /wp-admin\n";
		$output .= "Disallow: /?s=\n";
	}
	if ($id == 24) {
		$output = "";
		$output .= "User-agent: *\n";
		$output .= "Disallow: \n";
	}
	if($id == 1){
		$output .= "Sitemap: https://bigcottage.com/sitemap_index.xml";
	}
	if($id == 5){
		$output .= "Sitemap: https://henparties.kateandtoms.com/sitemap_index.xml";
	}
	if($id == 6){
		$output .= "Sitemap: https://stagparties.kateandtoms.com/sitemap_index.xml";
	}
	if($id == 8){
		$output .= "Sitemap: https://partners.kateandtoms.com/sitemap_index.xml";
	}
	if($id == 11){
		$output .= "Sitemap: https://kateandtoms.com/sitemap_index.xml";
	}
	if($id == 12){
		$output .= "Sitemap: https://weddings.kateandtoms.com/sitemap_index.xml";
	}
	if($id == 16){
		$output .= "Sitemap: https://events.kateandtoms.com/sitemap_index.xml";
	}
	if($id == 24){
		$output .= "Sitemap: https://themoult.co.uk/sitemap_index.xml";
	}
	return $output;
}
add_filter('robots_txt', 'create_robotstxt', 10, 2);

/**
 * Fix Yoast SEO robots.txt changes.
 * https://wordpress.org/support/topic/disable-robots-txt-changing-by-yoast-seo/#post-16648736
 */
function kateandtoms_fix_yoast_seo_robots_txt() {

	global $wp_filter;

	if ( isset( $wp_filter['robots_txt']->callbacks ) && is_array( $wp_filter['robots_txt']->callbacks ) ) {

		foreach ( $wp_filter['robots_txt']->callbacks as $callback_priority => $callback ) {
			foreach ( $callback as $function_key => $function ) {

				if ( 'filter_robots' === $function['function'][1] ) {
					unset( $wp_filter['robots_txt']->callbacks[ $callback_priority ][ $function_key ] );
				}
			}
		}
	}
}

add_action( 'wp_loaded', 'kateandtoms_fix_yoast_seo_robots_txt' );

//require_once('Custom-Meta-Boxes/custom-meta-boxes.php');

new KTSite_Settings();

// adds a custom title tag on extra house pages
add_filter( 'wp_title', 'kate_and_toms_title_filter_callback', 99, 2 );
function kate_and_toms_title_filter_callback($title, $sep) {
 	global $post, $wp;

 	if (in_array('booknow', $wp->query_vars)) {
	 	$title = $post->post_title . ' - booknow - Kate & Tom\'s';
 	}
 	if (in_array('availability', $wp->query_vars)) {
	 	$title = $post->post_title . ' - availability - Kate & Tom\'s';
 	}
 	if (in_array('gallery', $wp->query_vars)) {
	 	$title = $post->post_title . ' - gallery - Kate & Tom\'s';
 	}
 	if (in_array('more', $wp->query_vars)) {
	 	$title = $post->post_title . ' - things to do - Kate & Tom\'s';
 	}
 	if (in_array('facts', $wp->query_vars)) {
	 	$title = $post->post_title . ' - key facts - Kate & Tom\'s';
 	}

	return $title;
}

function kate_toms_global_colour() {
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name' => 'Colour 1',
				'slug' => 'color1',
				'color' => '#236d6f'
			),
			array(
				'name' => 'Colour 2',
				'slug' => 'color2',
				'color' => '#777f2d'
			),
			array(
				'name' => 'Colour 3',
				'slug' => 'color3',
				'color' => '#e3634b'
			),
			array(
				'name' => 'Colour 4',
				'slug' => 'color4',
				'color' => '#af2426'
			),
			array(
				'name' => 'Colour 5',
				'slug' => 'color5',
				'color' => '#dead14'
			),
			array(
				'name' => 'Colour 6',
				'slug' => 'color6',
				'color' => '#a8af61'
			),
			array(
				'name' => 'Colour 7',
				'slug' => 'color7',
				'color' => '#ca3d52'
			),
			array(
				'name' => 'Colour 8',
				'slug' => 'color8',
				'color' => '#72b0bb'
			),
			array(
				'name' => 'Colour 9',
				'slug' => 'color9',
				'color' => '#D4DACC'
			),
			array(
				'name' => 'Colour 10',
				'slug' => 'color10',
				'color' => '#F5F1CE'
			),
			array(
				'name' => 'Colour 11',
				'slug' => 'color11',
				'color' => '#DFD9B7'
			),
			array(
				'name' => 'Colour 12',
				'slug' => 'color12',
				'color' => '#C9CB9A'
			),
			array(
				'name' => 'Colour 13',
				'slug' => 'color13',
				'color' => '#D6D9CE'
			),
			array(
				'name' => 'Colour 14',
				'slug' => 'color14',
				'color' => '#F2EDDF'
			),
			array(
				'name' => 'Colour 15',
				'slug' => 'color15',
				'color' => '#E6DFD1'
			),
			array(
				'name' => 'Colour 16',
				'slug' => 'color16',
				'color' => '#ffffff'
			),
		)
	);

}
add_action('init', 'kate_toms_global_colour');


function misha_sources( $sources, $size_array, $image_src, $image_meta, $attachment_id ){
	/*
	 * Your variables here
	 */
	$image_size_name = 'thumbnail'; // add_image_size('square500', 500, 500, true);
	$breakpoint = 279;
 
	$upload_dir = wp_upload_dir();
 
	$img_url = $upload_dir['baseurl'] . '/' . str_replace( basename( $image_meta['file'] ), $image_meta['sizes'][$image_size_name]['file'], $image_meta['file'] );
 
	$sources[ $breakpoint ] = array(
		'url'        => $img_url,
		'descriptor' => 'w',
		'value'      => $breakpoint,
	);
	return $sources;
}
 
add_filter('wp_calculate_image_srcset','misha_sources',10,5);

add_action('cleanup_revisions', 'cleanup_revisions_callback');
function cleanup_revisions_callback() {
	$blog_ids = array(
		1,5,6,11,12,14,16
	);
	foreach ($blog_ids as $blog_id) {
		switch_to_blog( $blog_id );
		
		$blogid = get_current_blog_id();
		error_log('Current site ID: ' . $blogid);
	
		error_log('Clean up started: ' . date('Y-m-d h:i:s'));
		$args = array(
			'post_type' => 'any',
			'posts_per_page' => '-1',
			'post_status' => 'publish'
		);
		$posts = get_posts($args);
		error_log(count($posts) . ' posts found');
		$n = 1;
		foreach ($posts as $post) {
			$args = array(
				'post_type' => 'revision',
				'post_parent' => $post->ID,
				'posts_per_page' => '-1',
				'post_status' => 'inherit',
			);
			$revisions = get_posts($args);
			error_log(count($revisions) . ' revisions found for ' . $post->ID);
	
			$i = 1;
			foreach ($revisions as $revision) {
				if($i <= 10) {
					$i++;
					continue;
				} else {
					error_log('Cleaning up revision ' . $revision->ID . ' starting at revision no: ' . $i);
					wp_delete_post( $revision->ID, true );
					$i++;
				}
				
			}
			$n++;
			error_log('completed '.$n);
		}

	}
}

function custom_calculate_image_sizes($sizes, $size, $image_src) {
    // Modify the sizes attribute based on your requirements
    $custom_sizes = '(max-width: 300px) 300px';
    // Return the modified sizes attribute
    return $custom_sizes;
}
add_filter('wp_calculate_image_sizes', 'custom_calculate_image_sizes', 10, 3);

function swap_image_source_to_production($attr, $attachment, $size) {
	// make filter magic happen here... 
	$testurl = 'kateandtoms.test';
	$stagedurl = 'staging.kateandtoms.com';
	$attr["src"] = str_replace($testurl, 'kateandtoms.com', $attr["src"]);
	$attr["srcset"] = str_replace($testurl, 'kateandtoms.com', $attr["srcset"]);
	$attr["src"] = str_replace($stagedurl, 'kateandtoms.com', $attr["src"]);
	$attr["srcset"] = str_replace($stagedurl, 'kateandtoms.com', $attr["srcset"]);

	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'swap_image_source_to_production', 10, 3);

// define the do_shortcode_tag callback 
function filter_do_shortcode_tag( $output, $tag, $attr, $m ) { 
	// make filter magic happen here... 
	$testurl = 'kateandtoms.test';
	$stagedurl = 'staging.kateandtoms.com';
	$output = str_replace($testurl, 'kateandtoms.com', $output);
	$output = str_replace($stagedurl, 'kateandtoms.com', $output);
	return $output; 
}; 
			
// add the filter 
add_filter( 'do_shortcode_tag', 'filter_do_shortcode_tag', 10, 4 ); 

function tweakjp_rm_comments_att( $open, $post_id ) {
    $post = get_post( $post_id );
    if( $post->post_type == 'houses' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'tweakjp_rm_comments_att', 10 , 2 );


add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
     
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
 
    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
 
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
 
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
 
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
 
// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
 
// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

add_action('custom_save_houses', 'custom_save_houses_callback');
function  custom_save_houses_callback(){
	switch_to_blog( 1 );
	// Retrieve all posts of custom post type 'houses'
	$houses = get_posts(array(
		'post_type' => 'houses',
		'posts_per_page' => -1, // Get all posts
	));

	// Loop through each house post
	foreach ($houses as $house) {
		// Update and resave the post
		wp_update_post(array(
			'ID' => $house->ID,
		));

		// Optionally, you can output the post ID for each post processed
		error_log( 'Post updated: ' . $house->ID );
	}
}
?>