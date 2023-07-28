<?php
add_action('admin_head', 'admin_styles', 10 );
add_action('admin_enqueue_scripts', 'load_custom_admin');
add_action('wp_head', 'admin_bar_head' );
add_filter('login_headerurl', 'kat_url_login');
add_action('login_head', 'login_css');
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
add_action('admin_menu', 'remove_menus');
add_action('admin_bar_menu', 'change_toolbar', 999);
// add_filter('acf/fields/wysiwyg/toolbars' , 'simple_toolbars');
add_action('admin_bar_menu', 'admin_bar_menu');
add_action('admin_menu', 'remove_size_box');
add_action('admin_footer-edit-tags.php', 'remove_cat_tag_description');
add_filter('manage_media_columns', 'ST4_columns_head');  
add_action('manage_media_custom_column', 'ST4_columns_content', 10, 2);  
add_action("manage_houses_posts_custom_column", "my_custom_columns");
add_filter("manage_edit-houses_columns", "my_page_columns");
add_filter("manage_edit-houses_sortable_columns", "my_column_register_sortable");
add_action('right_now_content_table_end', 'add_houses_counts');
// add_filter('acf/settings/show_admin', '__return_false');
add_editor_style('css/editor-style.css?r=1');
function admin_styles() {
	$style = get_option('options_site_style');
    echo "<link href='/wp-content/themes/clubsandwich/css/admin_style.css?r=1' rel='stylesheet' type='text/css'>";
    echo "<link href='/wp-content/themes/clubsandwich/css/".$style."?r=1' rel='stylesheet' type='text/css'>";
   	echo "<link href='/wp-content/themes/clubsandwich/css/admin_bar_style.css?r=1' rel='stylesheet' type='text/css'>";
}
function load_custom_admin() {
	
	$display = special_offers_testing_callback();
	
    wp_enqueue_script( 'kat_admin_script', get_template_directory_uri() . '/js/admin.js' );
    wp_localize_script( 'kat_admin_script', 'specials_object', $display );

    wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
}
function admin_bar_head() {
    if ( is_user_logged_in() ) {
    	echo "<link href='/wp-content/themes/clubsandwich/css/admin_bar_style.css?r=1' rel='stylesheet' type='text/css'>";
    }
}
// Custom login URL logo link
function kat_url_login(){
	return "http://kateandtoms.com/"; // your URL here
} 
// Custom WordPress Login Logo
function login_css() {
	wp_enqueue_style( 'login_css', '/wp-content/themes/clubsandwich/css/login.css?r=1' );
	$images = array('http://kateandtoms.com/files/2012/11/railway.jpg', 
		'http://kateandtoms.com/files/2012/11/River-View-House-Pool-2.jpeg', 
		'http://kateandtoms.com/files/2012/11/Marver-House-Beach-View.jpg');
	shuffle($images);
	echo '<style>body {	background-image: url('.$images[0].')!important; }</style>';
}
function remove_dashboard_widgets() { 
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;   
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
}
function remove_menus() {
	global $menu;
	$restricted = array(__('Links'), __('Comments'),  __('SweetCaptcha'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
function change_toolbar($wp_toolbar) {  
	$wp_toolbar->remove_node('new-link');  	
}
function simple_toolbars( $toolbars )
{
	// Add a new toolbar called "Very Simple"
	// - this toolbar has only 1 row of buttons
	$toolbars['Very Simple' ] = array();
	$toolbars['Basic' ][1] = array('bold' , 'italic' , 'bullist', 'numlist', 'link', 'unlink', 'removeformat', 'code', 'fullscreen' );
 
	// Edit the "Full" toolbar and remove 'code'
	// - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
	if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
	{
	    unset( $toolbars['Full' ][2][$key] );
	}

	return $toolbars;
}
function admin_bar_menu(){
    global $wp_admin_bar;
    
    $blog_names = array();
    $sites = $wp_admin_bar->user->blogs;
    foreach($sites as $site_id=>$site){
        $blog_names[$site_id] = $site->blogname;
    }
    
    $numbersToAdd = array(11,1,7,4,9,5,6,10,8);
    $siteNums = array();
    
    // Remove blogs from list...we want that to show at the top
    foreach ($numbersToAdd as $n) {
        if (array_key_exists($n, $blog_names)) {
            if ($blog_names[$n]) {
                unset($blog_names[$n]);
                array_push($siteNums, $n);
            }
        }
    }
    
    // Order by name
    asort($blog_names);
            
    // Create new array
    $wp_admin_bar->user->blogs = array();
    
    // Add blogs back in to list
    foreach($siteNums as $n) {
        $wp_admin_bar->user->blogs[$n] = $sites[$n];
    }
    
    // Add others back in alphabetically
    foreach($blog_names as $site_id=>$name){
        $wp_admin_bar->user->blogs[$site_id] = $sites[$site_id];
    }
}
function remove_size_box() {
    remove_meta_box('sizediv', 'houses', 'normal');
}

function remove_cat_tag_description()
{
    global $current_screen;
    switch ($current_screen->id) {
        case 'edit-size':
            break;
    }
    echo "<script type=\"text/javascript\">
    jQuery(document).ready( function($) {
        $('#tag-description').parent().remove();
        $('#tag-description').parent().remove();
        $('#parent').parent().remove();
    });
    </script>";
}
// Add new column  
function ST4_columns_head($defaults) {  
    $defaults['media_size'] = 'Image Size';  
    return $defaults;  
}  
// SHOW THE FEATURED IMAGE  
function ST4_columns_content($column_name, $post_ID) {  
    if ($column_name == 'media_size') {  
        $image = wp_get_attachment_image_src($post_ID, 'full');  
        if ($image) {  
            echo $image[1] . ' x '. $image[2];  
        }  
    }  
}
function my_page_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'thumbnail' => 'Thumbnail',
        'title' => 'Title',
        'sleeps_min' => 'Sleeps (min)',
        'sleeps_max' => 'Sleeps (max)',
        'location' => 'Location',
        'author' => 'Author',
        'date' => 'Date'
    );
    return $columns;
}
function my_custom_columns($column)
{
    global $post;
    if ($column == 'thumbnail') {
        $housePhotos = get_post_custom_values('house_photos', $post->ID);
        
        $housePhotos = unserialize($housePhotos[0]);
        
        echo wp_get_attachment_image($housePhotos[0], array(
            100,
            100
        ));
    } elseif ($column == 'sleeps_max') {
        the_field('sleeps_max', $post->ID);
        
    } elseif ($column == 'sleeps_min') {
        the_field('sleeps_min', $post->ID);
        
    } elseif ($column == 'location') {
        the_field('location_text', $post->ID);
        
    }
}
function my_column_register_sortable($columns)
{
    $columns['sleeps_max'] = 'sleeps_max';
    $columns['sleeps_min'] = 'sleeps_min';
    $columns['location']   = 'location';
    return $columns;
}
function remove_wp_logo() {  
    global $wp_admin_bar;  
    $wp_admin_bar->remove_menu('wp-logo');  
}  
function add_houses_counts()
{
    if (!post_type_exists('houses')) {
        return;
    }
    
    $num_posts = wp_count_posts('houses');
    $num       = number_format_i18n($num_posts->publish);
    $text      = _n('House', 'Houses', intval($num_posts->publish));
    if (current_user_can('edit_posts')) {
        $num  = "<a href='edit.php?post_type=houses'>$num</a>";
        $text = "<a href='edit.php?post_type=houses'>$text</a>";
    }
    echo '<td class="first b b-houses">' . $num . '</td>';
    echo '<td class="t houses">' . $text . '</td>';
    
    echo '</tr>';
    
    if ($num_posts->pending > 0) {
        $num  = number_format_i18n($num_posts->pending);
        $text = _n('House Pending', 'Houses Pending', intval($num_posts->pending));
        if (current_user_can('edit_posts')) {
            $num  = "<a href='edit.php?post_status=pending&post_type=houses'>$num</a>";
            $text = "<a href='edit.php?post_status=pending&post_type=houses'>$text</a>";
        }
        echo '<td class="first b b-houses">' . $num . '</td>';
        echo '<td class="t houses">' . $text . '</td>';
        
        echo '</tr>';
    }
}
?>
