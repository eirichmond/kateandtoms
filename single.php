<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package clubsandwich
 */

$blog_id = get_current_blog_id();
if ($blog_id == 24) {
	get_header('litehouse');
} else {
	get_header();
}
 ?>

	<div class="headspace"></div>
	<main id="main" class="site-main seventeen" role="main">

	<?php
	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/content', 'single' );

		the_post_navigation();

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile; // End of the loop.
	?>

	</main><!-- #main -->

<?php get_footer(); ?>
