<?php
	$blog_id = get_current_blog_id();
if ( $blog_id == 24 ) {
	get_header( 'litehouse' );
} else {
	get_header();
}
?>

	<div class="headspace"></div>
	<div class="main_body_cont">


		<div class="container main_body">


			<div class="row">

				<main id="main" class="site-main seventeen" role="main">

				<?php
				$i = 0;
				if ( have_posts() ) :

					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );
						++$i;
					endwhile;

					the_posts_navigation(
						array(
							'prev_text' => '&laquo; Older',
							'next_text' => 'Newer &raquo;',
						)
					);

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

				</main><!-- #main -->

			</div>
		</div>


	</div>
<?php get_footer(); ?>
