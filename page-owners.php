<?php
/*
Template Name: House owners
*/
get_header(); ?>
<div class="page-title_cont">
	<div class="container">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</div>
</div>
<?php 
	if ( has_post_thumbnail() ) { 
		echo '<div style="height:400px; text-align:center;">
			<div class="absoluteCenterWrapper" style="height: 400px; overflow:hidden; ">'; 
		the_post_thumbnail('full', array('class' => 'absoluteCenter')); 
		echo '</div></div>'; 
	} 
?>
<div class="main_body_cont">
	<div class="container main_body">
		<?php the_post(); ?>
		<div <?php post_class(); ?>>
			<div class="row">
				<div class="span3">
					<div style="background: #fff6e2; padding: 20px;">
					<?php wp_nav_menu( array('menu' => 'Owners' )); ?>
					</div>
				</div>
				<div class="span9">
				<?php the_content(); new ImageSet($post->ID); ?>
				<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'blankslate' ) . '&after=</div>') ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>