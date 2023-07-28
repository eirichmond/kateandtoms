<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package clubsandwich
 */

?>


<?php

/*
if ( is_sticky() ) {
     echo '<div>this is sticky</div>';
} else {
     echo 'not sticky';
}
*/
global $i;

$class = get_custom_post_class($i);
$crop = get_thumb_crop($i);
$core_color = get_core_colour($post->ID);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

<!-- 		<iframe width="100%" height="1200" src="https://my.matterport.com/show/?m=ZEocpcmPJYu" frameborder="0" allowfullscreen></iframe> -->

		<?php //if (has_post_thumbnail()) { echo '<a href="' . esc_url( get_permalink() ) . '">'; the_post_thumbnail( 'large' ); echo '</a>'; } ?>

		<div class="container singlebanner">
			<div class="row">
				<div class="span8 offset2">
					<?php
						if ( is_single() ) :
							the_title( '<h1 class="entry-title">', '</h1>' );
						else :
							the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
						endif;
			 		?>
			 		<div class="entry-meta">
						<?php clubsandwich_posted_on(); ?>
					</div><!-- .entry-meta -->
				</div>
			</div>
		</div>


	</header><!-- .entry-header -->

	<div class="main_body_cont">
	<div class="container singlecontent">
		<div class="row">
			<div class="span8 offset2">
				<div class="entry-content">
					<?php
						the_content();

						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'clubsandwich' ),
							'after'  => '</div>',
						) );
					?>

					<?php clubsandwich_entry_footer(); ?>



				</div><!-- .entry-content -->
			</div>
		</div>
	</div>
	</div>

<!--
	<footer class="entry-footer">
		<?php clubsandwich_entry_footer(); ?>
	</footer>
-->

</article><!-- #post-## -->
