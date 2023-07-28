<?php 
/*
	Template Name: Booking Thank you
*/

get_header(); ?>

<?php if ( has_post_thumbnail() ) { ?>
<div style="text-align:center;">
	<div class="absoluteCenterWrapper" style="overflow:hidden; ">
		
		<?php the_post_thumbnail('huge'); ?>
		
	</div>
</div>
<?php } ?>

<div id="content" class="page-title_cont error404 not-found">
<div class="container min-page">
<h1 class="page-title"><?php the_title();?></h1>

<?php the_content();?>

<?php
/*
$from = $_SERVER['SERVER_NAME'];
if ( $from == 'kateandtoms.com' || $from == 'partners.kateandtoms.com' || $from == 'partyweekends.kateandtoms.com' ) {
	echo '<p>Unfortunately, we couldn\'t find anything with that URL. You can go back to the main site by <a href="http://kateandtoms.com">clicking here</a>.</p>';
} else {
	echo '<p>Unfortunately, we couldn\'t find anything with that URL. You can browse all the houses by <a href="/houses/">clicking here</a>.</p>';	
};
*/

//echo '<pre>'; print_r($_SERVER); echo '</pre>';
?>


<?php //if(function_exists('trueGoogle404')){ echo "<p><b>Here are some suggestions for the page you might be looking for:</p>"; echo trueGoogle404(); }?>

</div>
</div>

<?php get_footer(); ?>