<?php
/*
Template Name: Page with side menu
*/
get_header(); ?>
<div class="page-title_cont">
<div class="container">
<h1 class="page-title"><?php the_title(); ?></h1>
</div>
</div>
<?php
if ( has_post_thumbnail() ) {
	echo '<div style="height:400px; text-align:center;"><div class="absoluteCenterWrapper" style="height: 400px; overflow:hidden; ">';
	the_post_thumbnail( 'full', array( 'class' => 'absoluteCenter' ) );
	echo '</div></div>'; }
?>
<div class="main_body_cont">
<div class="container main_body">
<?php the_post(); ?>
<div <?php post_class(); ?>>
<div class="row">
<div class="span3">
<div style="background: #fff6e2; padding: 20px;">
<?php
if ( get_the_ID() == 13 ) {
	echo '<h3>Our Top Weekends</h3>';
	wp_nav_menu( array( 'menu' => 'Big Weekends' ) ); } elseif ( get_the_ID() == 11 ) {
	echo '<h3>Our Top Holidays</h3>';
	wp_nav_menu( array( 'menu' => 'Big Holidays' ) ); } elseif ( get_the_ID() == 15 ) {
		echo '<h3>How Big</h3>';
		wp_nav_menu( array( 'menu' => 'Find 1' ) );
		echo '&nbsp;<br />';
		echo '<h3>Where it is</h3>';
		wp_nav_menu( array( 'menu' => 'Find 2' ) );
		echo '&nbsp;<br />';
		echo '<h3>Top Searches</h3>';
		wp_nav_menu( array( 'menu' => 'Find 3' ) );
		echo '&nbsp;<br />';
		echo '<h3>Popular Dates</h3>';
		wp_nav_menu( array( 'menu' => 'Find 4' ) );
	} elseif ( get_the_ID() == 1905 ) {
		echo '<h3>Search by Location</h3>';
		wp_nav_menu( array( 'menu' => 'Search by Location' ) ); }
	?>
</div>
</div>
<div class="span9">
<?php the_content(); ?>
<?php new ImageSet( $post->ID ); ?>
<?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'blankslate' ) . '&after=</div>' ); ?>
</div>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>