<?php
	
global $wpdb;
$tablename = $wpdb->prefix;

// $sql = $wpdb->prepare( "SELECT house_id, blog_id, post_id, post_title, availability_option, availability_site_ref, availability_site_post_id FROM houses WHERE blog_id = 11 ORDER BY post_title ASC",$tablename );

// create an array of houses from kate and toms where posttitle is the key and the value is the id
$kateandtoms = $wpdb->prepare( "SELECT post_id, post_title FROM houses WHERE blog_id = 11 ORDER BY post_title ASC",$tablename );
$kateandtoms = $wpdb->get_results( $kateandtoms , ARRAY_A );
/*
echo '<pre>';
print_r($kateandtoms);
echo '</pre>';
wp_die();
*/


// get all blog ids
$sql = $wpdb->prepare( "SELECT blog_id FROM houses ORDER BY blog_id ASC",$tablename );
$results = $wpdb->get_results( $sql , ARRAY_A );
// list pluck all 'blog_id' from the array
$results = wp_list_pluck($results, 'blog_id', true);
// make the array unique
$results = array_unique($results);
// loop all blog ids by changing wpdb prefix
// stove bigcottage for later update
$blog_1 = $results[0];
unset($results[0]);

foreach ($results as $blog_id) {
	$wpdb->blogid = $blog_id;
	$wpdb->set_prefix( $wpdb->base_prefix );
	$tablename = $wpdb->prefix;
	$tablename;
	// if 'availability_option' is equal to 1
	$sql = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta
	WHERE meta_key = 'availability_option' AND meta_value = '1'
	ORDER BY post_id ASC",$tablename );
	$results = $wpdb->get_results( $sql , ARRAY_A );
	$results = wp_list_pluck($results, 'post_id', true);

echo 'info: '.$wpdb->prefix . ' | count: ' . count($results) . '<br>';
/*
echo '<pre>';
print_r($results);
echo '</pre>';
*/

	

	foreach ($results as $post_id) {
		// if 'availability_site_ref' is equal to 1
		$availability_site_ref = get_post_meta($post_id, 'availability_site_ref', true);
		if ($availability_site_ref == 1) {
			
			$house_title = get_the_title($post_id);
			// if this posttitle is in $kateandtoms[post_title]
			foreach ($kateandtoms as $k => $match) {
				if (in_array($house_title, $match)) {
					echo 'Post ID: ' . $post_id . ' - '.get_the_title($post_id).' inherits from site ID ' . $availability_site_ref . '<br>';
					echo '<p>'.$kateandtoms[$k]['post_title'] . ' - ' . $house_title . ' complete!</p>';
					// update this 'availability_site_ref' to 11
					update_post_meta($post_id, 'availability_site_ref', 11);
					// and 'availability_site_post_id' to $kateandtoms[post_id]
					update_post_meta($post_id, 'availability_site_post_id', $kateandtoms[$k]['post_id']);
				}
			}
		}
	}
}
wp_die();


$wpdb->blogid = $blog_1;
$wpdb->set_prefix( $wpdb->base_prefix );
$tablename = $wpdb->prefix;
$tablename;
// if 'availability_option' is equal to 1
$sql = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta
WHERE meta_key = 'availability_option' AND meta_value = '0'
ORDER BY post_id ASC",$tablename );
$results = $wpdb->get_results( $sql , ARRAY_A );
$results = wp_list_pluck($results, 'post_id', true);

/*
echo 'info: '.$wpdb->prefix . ' | count: ' . count($results);
echo '<pre>';
print_r($results);
echo '</pre>';
*/

foreach ($results as $post_id) {
	// if 'availability_site_ref' is equal to 1
	$availability_option = get_post_meta($post_id, 'availability_option', true);
	if ($availability_option == 0) {
		
		$house_title = get_the_title($post_id);
		// if this posttitle is in $kateandtoms[post_title]
		foreach ($kateandtoms as $k => $match) {
			if (in_array($house_title, $match)) {
				//echo 'Post ID: ' . $post_id . ' - '.get_the_title($post_id).' inherits from site ID ' . $availability_site_ref . '<br>';
				echo '<p>'.$kateandtoms[$k]['post_title'] . ' - ' . $house_title . ' complete!</p>';
				// update this 'availability_site_ref' to 11
				update_post_meta($post_id, 'availability_option', 1);
				update_post_meta($post_id, 'availability_site_ref', 11);
				// and 'availability_site_post_id' to $kateandtoms[post_id]
				update_post_meta($post_id, 'availability_site_post_id', $kateandtoms[$k]['post_id']);
			}
		}
	}
}


echo 'complete!';
wp_die();
