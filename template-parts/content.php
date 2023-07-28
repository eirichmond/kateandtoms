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



<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
		
	<header class="entry-header <?php echo $core_color; ?>">

		<?php if (has_post_thumbnail()) {
			echo '<a href="' . esc_url( get_permalink() ) . '">';
			the_post_thumbnail( $crop );
			echo '</a>';
		} ?>

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

	</header><!-- .entry-header -->

	<div class="entry-content <?php echo $core_color; ?>">
	
	
		<?php
			echo wp_trim_words(get_the_excerpt(), 7,' ... <a href="' . esc_url( get_permalink() ) . '">read more &raquo;</a>');

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'clubsandwich' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

<!--
	<footer class="entry-footer">
		<?php clubsandwich_entry_footer(); ?>
	</footer>
-->
</article><!-- #post-## -->

