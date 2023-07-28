<?php
/*
	Template Name: Micro Site Blog
*/
get_header('litehouse');

?>
<div class="headspace"></div>
<div class="page-title_cont">
</div>
<div class="main_body_cont">
	<div class="container main_body">
		<div class="row">
			<div class="span8">
				<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
					<div id="nav-above" class="navigation">
						<p class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> older articles', 'blankslate' )) ?></p>
						<p class="nav-next"><?php previous_posts_link(__( 'newer articles <span class="meta-nav">&raquo;</span>', 'blankslate' )) ?></p>
					</div>
				<?php } ?>
				<?php while ( have_posts() ) : the_post() ?>
				
				
				
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Read', 'blankslate'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
						<div class="entry-meta">
							<span class="meta-prep meta-prep-entry-date"><?php _e('Written on ', 'blankslate'); ?></span>
							<span class="entry-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
							<?php edit_post_link( __( 'Edit', 'blankslate' ), "<span class=\"meta-sep\"> | </span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t" ) ?>
						</div>
						
						
						<div class="entry-content">
							<?php if ( has_post_thumbnail() ) {
								the_post_thumbnail(array(250,250));
							} ?>
							<?php the_excerpt( __( 'continue reading <span class="meta-nav">&raquo;</span>', 'blankslate' )  ); ?>
							<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'blankslate' ) . '&after=</div>') ?>
						</div>
						<div class="entry-utility">
							<span class="cat-links">
								<span class="entry-utility-prep entry-utility-prep-cat-links"><?php _e( 'Posted in ', 'blankslate' ); ?></span><?php echo get_the_category_list(', '); ?>
							</span>
							<span class="meta-sep"> | </span>
							<?php the_tags( '<span class="tag-links"><span class="entry-utility-prep entry-utility-prep-tag-links">' . __('Tagged ', 'blankslate' ) . '</span>', ", ", "</span>\n\t\t\t\t\t\t<span class=\"meta-sep\"> | </span>\n" ) ?>
							<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'blankslate' ), __( '1 Comment', 'blankslate' ), __( '% Comments', 'blankslate' ) ) ?></span>
							<?php edit_post_link( __( 'Edit', 'blankslate' ), "<span class=\"meta-sep\"> | </span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t\n" ) ?>
						</div>
					</div>
					
					
					
					
					<?php comments_template(); ?>
				<?php endwhile; ?>
			</div>
		<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>