<?php
// save seasonal data

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */

add_action( 'save_post', 'save_special_meta', 10, 3 );

function save_special_meta( $post_id, $post, $update ) {

	/*
	echo '<pre>';
	print_r($post);
	echo '</pre>';

	echo '<pre>';
	print_r($_POST);
	echo '</pre>';

	wp_die();
	*/

	// If this isn't a 'house' post, don't update it.
	/*
	if ( $post->post_type != 'seasonal' ) {
		return;
	}
	*/
}
