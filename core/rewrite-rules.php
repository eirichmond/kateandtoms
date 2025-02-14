<?php

/*
 * Adds a permalink structure to the single blog
 * posts prefixing them with /blog/
 */

// add_action('init', 'blog_front');
// function blog_front() {
// add_rewrite_rule('^blog/([^/]+)/?','index.php?name=$matches[1]','top');
// }



// add_action('template_redirect', 'say_hello_to_google');
// function say_hello_to_google() {
// if ( is_main_query() && is_single() && ( empty( get_post_type() ) || (get_post_type() === 'post') ) ) {
// if ( strpos( trim( add_query_arg( array() ), '/' ), 'blog' ) !== 0 ) {
// global $post;
// $url = str_replace( $post->post_name, 'blog/' . $post->post_name, get_permalink( $post ) );
// wp_safe_redirect( $url, 301 );
// exit();
// }
// }
// }

// add_filter('the_permalink', 'post_permalink_w_blog');
// function post_permalink_w_blog( $link ) {
// global $post;
// if ( $post->post_type === 'post' ) {
// $link = str_replace( $post->post_name, 'blog/' . $post->post_name, get_permalink( $post ) );
// }
// return $link;
// }

/*
add_action('pre_get_posts','bamboo_pre_get_posts');
function bamboo_pre_get_posts( $query ) {
	if( $query->is_main_query() && !$query->is_feed() && !is_admin() ) {
		$query->set( 'paged', str_replace( '/', '', get_query_var( 'page' ) ) );
	}
}
*/
