<?php
// save seasonal data

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
 
add_action('save_post', 'save_seasonal_meta', 10, 3 );

function save_seasonal_meta( $post_id, $post, $update ) {

    // If this isn't a 'house' post, don't update it.
    if ( $post->post_type != 'seasonal' ) {
        return;
    }
    
/*
    $periods_to_include = get_post_meta($post_id, 'periods_to_include', true);
    
    echo $post_id;
    
    echo '<pre>';
	print_r($periods_to_include);
    echo '</pre>';
    
    wp_die();
*/
}
?>