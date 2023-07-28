<?php

	
/**
 * Register meta box(es).
 */
function inheritance_meta_boxes() {
    add_meta_box( 'inheritance-meta', __( 'Inheritance Association', 'kateandtoms' ), 'inheritance_display_callback', 'houses', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'inheritance_meta_boxes' );

function distribute_house_info() {
	$array = array(
		'sleeps_min',
		'sleeps_max',
		'brief_description',
		'brief_description_winter',
		'location',
		'location_text',
	);
	return $array;
}
 
function distribute_keyfacts() {
	$array = array(
		'keyfacts_1',
		'keyfacts_2',
		'keyfacts_3',
	);
	return $array;
}

function distribute_settings() {
	$array = array(
		'limit_num_photos',
		'all_prices_with_from',
		'turn_off_take_a_tour',
	);
	return $array;
}
 
function distribute_related_houses() {
	$array = array(
		'related_houses',
	);
	return $array;
}

/**
 * Get post titles from array of ids for the current site.
 *
 * @param array $array Array of post ids for the current site.
 * @return array $post_titles Array of post titles.
 */

function exchange_for_titles($array) {
	
	$post_titles = array();
	foreach ($array as $post_object) {
		$post_object = get_post($post_object);
		$post_titles[] = $post_object->post_title;
	}

	return $post_titles;
}

/**
 * Get post id from array of titles for the current site.
 *
 * @param array $array Array of post titles for the current site.
 * @return array $post_ids Array of post ids.
 */
function exchange_for_house_ids($array, $distribution_blog_id) {
	
	global $wpdb;
	
	$results = array();
	foreach ($array as $title) {
		
		$sql = $wpdb->prepare( "
			SELECT blog_id, post_id, post_title, permalink
			FROM houses
			WHERE blog_id = '$distribution_blog_id' AND post_title = '$title'
			ORDER BY post_title ASC"
		);
		$results[] = $wpdb->get_results( $sql , ARRAY_A );
		
	}
	
	$ids = array();
	foreach ($results as $result) {
		$ids[] = $result[0]['post_id'];
	}
	
	return array_filter($ids);
}
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function inheritance_display_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
    
    $current_blog_id = get_current_blog_id();
    
	global $wpdb;
	$title = html_entity_decode($post->post_title);
		
	$sql = $wpdb->prepare("SELECT blog_id, post_id, post_title, permalink FROM houses WHERE post_title = %s ORDER BY post_title ASC", $title );
		
	$results = $wpdb->get_results( $sql , ARRAY_A );
	
	foreach ($results as $key => $house) {
		//echo $key . ' - ' . $house['blog_id'];
		if ($current_blog_id == $house['blog_id']) {
			unset($results[$key]);
		}
	}
	
	
	?>
	
	<p>Distribute the same settings to other Kate & Tom's sites by selecting the relevant checkbox.</p> 
		<table class="form-table">
			<tr>
				<th class="row-title"><?php esc_attr_e( 'Site', 'kateandtoms' ); ?></th>
				<th><?php esc_attr_e( 'House info', 'kateandtoms' ); ?></th>
				<th><?php esc_attr_e( 'Keyfacts', 'kateandtoms' ); ?></th>
				<th><?php esc_attr_e( 'Settings', 'kateandtoms' ); ?></th>
				<th><?php esc_attr_e( 'Related Houses', 'kateandtoms' ); ?></th>
			</tr>
			
			<?php
			$i = 0;
			foreach ($results as $key => $blog) {
				switch_to_blog($blog['blog_id']);
				$blogname = get_bloginfo( 'name' );
				restore_current_blog();
	/*
				var_dump( get_bloginfo( 'name' ) );
				var_dump( $blog['blog_id'] );
				var_dump( $blog['post_id'] );
				var_dump( $blog['post_title'] );
				var_dump( $blog['permalink'] );
	*/
			?>
			<tr>
				<td class="row-title">
					<label for="tablecell"><a href="<?php echo esc_attr($blog['permalink']); ?>" target="_blank" ><?php esc_attr_e($blogname, 'kateandtoms'); ?></a></label>
				</td>
	
				<td>
					<input name="distribute_house_info[<?php echo esc_attr($i); ?>]" type="checkbox" />
				</td>
	
				<td>
					<input name="distribute_keyfacts[<?php echo esc_attr($i); ?>]" type="checkbox" />
				</td>
	
				<td>
					<input name="distribute_settings[<?php echo esc_attr($i); ?>]" type="checkbox" />
				</td>
	
				<td>
					<input name="distribute_related_houses[<?php echo esc_attr($i); ?>]" type="checkbox" />
				</td>
	
			</tr>
			
			<input type="hidden" name="distribute_post_id[]" value="<?php echo esc_attr($blog['post_id']); ?>">
			<input type="hidden" name="distribute_blog_id[]" value="<?php echo esc_attr($blog['blog_id']); ?>">
			<?php $i++; } ?>
	
	
			
	
		</table>
		<?php wp_nonce_field( 'inheritance_meta_action', 'inheritance_meta_nonce_field' ); ?>
	
	<?php
	
	//var_dump($results);
	
/*
	$results = wp_list_pluck($results, 'post_id', true);
	
	return $results;
*/

}

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function distribute_meta_save_meta_box( $post_id, $post, $update ) {
	
    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $slug = 'houses';

    // If this isn't a 'book' post, don't update it.
    if ( $slug != $post->post_type ) {
        return;
    }
	
/*
	if ( ! isset( $_POST['inheritance_meta_nonce_field'] ) || ! wp_verify_nonce( $_POST['inheritance_meta_nonce_field'], 'inheritance_meta_action' ) ) {
	
	   wp_die( 'Sorry, your nonce did not verify.' );
	
	} else {
*/
	
		
		if  ($update) {

		    // - Update the post's metadata.
/*
		    var_dump($post_id);
		    var_dump($_REQUEST);
		    wp_die();
*/
			
			if (isset($_REQUEST['distribute_house_info']) && $_REQUEST['distribute_house_info'] != '') {
			    // @TODO should refactor this to make it more efficient
			    foreach ($_REQUEST['distribute_house_info'] as $k => $v) {
				    if ($v == 'on') {
					    $distribution_id = $_REQUEST['distribute_post_id'][$k];
					    $distribution_blog_id = $_REQUEST['distribute_blog_id'][$k];
					    $house_info_meta_keys = distribute_house_info();
					    foreach ($house_info_meta_keys as $meta_key) {
						    $get_meta = get_post_meta($post_id, $meta_key, true);
						    switch_to_blog($distribution_blog_id);
						    update_post_meta($distribution_id, $meta_key, $get_meta);
						    restore_current_blog();
					    }
				    }
			    }
			}
		   
			if (isset($_REQUEST['distribute_keyfacts']) && $_REQUEST['distribute_keyfacts'] != '') {
			    foreach ($_REQUEST['distribute_keyfacts'] as $k => $v) {
				    if ($v == 'on') {
					    $distribution_id = $_REQUEST['distribute_post_id'][$k];
					    $distribution_blog_id = $_REQUEST['distribute_blog_id'][$k];
					    $house_info_meta_keys = distribute_keyfacts();
					    foreach ($house_info_meta_keys as $meta_key) {
						    $get_meta = get_post_meta($post_id, $meta_key, true);
						    switch_to_blog($distribution_blog_id);
						    update_post_meta($distribution_id, $meta_key, $get_meta);
						    restore_current_blog();
					    }
				    }
			    }
			}    

			if (isset($_REQUEST['distribute_settings']) && $_REQUEST['distribute_settings'] != '') {
			    foreach ($_REQUEST['distribute_settings'] as $k => $v) {
				    if ($v == 'on') {
					    $distribution_id = $_REQUEST['distribute_post_id'][$k];
					    $distribution_blog_id = $_REQUEST['distribute_blog_id'][$k];
					    $house_info_meta_keys = distribute_settings();
					    foreach ($house_info_meta_keys as $meta_key) {
						    $get_meta = get_post_meta($post_id, $meta_key, true);
						    switch_to_blog($distribution_blog_id);
						    update_post_meta($distribution_id, $meta_key, $get_meta);
						    restore_current_blog();
					    }
				    }
			    }
			}	    

			if (isset($_REQUEST['distribute_related_houses']) && $_REQUEST['distribute_related_houses'] != '') {
			    foreach ($_REQUEST['distribute_related_houses'] as $k => $v) {
				    if ($v == 'on') {
					    $distribution_id = $_REQUEST['distribute_post_id'][$k];
					    $distribution_blog_id = $_REQUEST['distribute_blog_id'][$k];
					    $house_info_meta_keys = distribute_related_houses();
					    foreach ($house_info_meta_keys as $meta_key) {
						    $get_meta = get_post_meta($post_id, $meta_key, true);
						    $exchange_for_titles = exchange_for_titles($get_meta);
						    switch_to_blog($distribution_blog_id);
						    $exchange_for_house_ids = exchange_for_house_ids($exchange_for_titles, $distribution_blog_id);
						    update_post_meta($distribution_id, $meta_key, $exchange_for_house_ids);
						    restore_current_blog();
					    }
				    }
			    }
			}	    

		    
		
		
		/*
		    if ( isset( $_REQUEST['book_author'] ) ) {
		        update_post_meta( $post_id, 'book_author', sanitize_text_field( $_REQUEST['book_author'] ) );
		    }
		
		    if ( isset( $_REQUEST['publisher'] ) ) {
		        update_post_meta( $post_id, 'publisher', sanitize_text_field( $_REQUEST['publisher'] ) );
		    }
		
		    // Checkboxes are present if checked, absent if not.
		    if ( isset( $_REQUEST['inprint'] ) ) {
		        update_post_meta( $post_id, 'inprint', TRUE );
		    } else {
		        update_post_meta( $post_id, 'inprint', FALSE );
		    }
		*/

		}

	//}

}
add_action( 'save_post', 'distribute_meta_save_meta_box', 10, 3 );


