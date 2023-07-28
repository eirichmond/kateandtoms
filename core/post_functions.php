<?php
if ( ! function_exists( 'clubsandwich_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function clubsandwich_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'clubsandwich' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'clubsandwich' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
	
	$categories_list = get_the_category_list( esc_html__( ', ', 'clubsandwich' ) );
	if ( $categories_list && clubsandwich_categorized_blog() ) {
		printf( '<span class="cat-links">' . esc_html__( ' | %1$s', 'clubsandwich' ) . '</span>', $categories_list ); // WPCS: XSS OK.
	}
	
/*
	edit_post_link(
		sprintf(
			esc_html__( ' | Edit %s', 'clubsandwich' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
*/

}
endif;


if ( ! function_exists( 'clubsandwich_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function clubsandwich_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'clubsandwich' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'clubsandwich' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'clubsandwich' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

}
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function clubsandwich_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'clubsandwich_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'clubsandwich_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so clubsandwich_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so clubsandwich_categorized_blog should return false.
		return false;
	}
}

/**
 * Filters the text content and trim it at the last punctuation.
 *
 * @param string  $text          The trimmed text.
 * @param int     $num_words     The number of words to trim the text to.
 *                               Default 5.
 * @param string  $more          An optional string to append to the end of
 *                               the trimmed text, e.g. &hellip;.
 * @param string  $original_text The text before it was trimmed.
 *
 * @return string                Trimmed text.
 */
function excerpt_end_with_punctuation( $text, $num_words, $more, $original_text ) {

	/**
	 * If text is empty exit.
	 */
	if ( empty( $text ) ) {
		return $text;
	}

	/**
	 * First trim the "more" tags
	 *
	 * @var string
	 */
	$text = str_replace( $more, '', $text );

	/**
	 * Paragraphs often end with space ' '.
	 * The space at the end of the punctuation prevents that links like www.mysite.com it will be trimmed.
	 *
	 * @var array
	 */
	$needles = apply_filters( 'your_prefix_excerpt_end_punctuation', array( '. ', '? ', '! ' ) );

	$found = false;

	foreach ( $needles as $punctuation ) {
		/**
		 * Return early
		 */
		if ( $found = strrpos( $text, $punctuation ) ) {
			return substr( $text, 0, $found + 1 ) . $more;
		}
	}

	return $text . $more;
}
add_filter( 'wp_trim_words', 'excerpt_end_with_punctuation', 10, 4 );