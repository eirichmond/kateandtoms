<?php get_header(); ?>
<div style="height:400px; text-align:center;">
	<div class="absoluteCenterWrapper" style="height: 400px; overflow:hidden; ">
	<img loading="lazy" src="<?php bloginfo('template_directory'); ?>/images/404.jpg" class="absoluteCenter" alt="Lost ducks" />
	</div>
</div>
<div id="content" class="page-title_cont error404 not-found">
	<div class="container min-page">
	<h1 class="page-title">Page not found</h1>
		<?php
			$from = $_SERVER['SERVER_NAME'];
			if ( $from == 'kateandtoms.com' || $from == 'partners.kateandtoms.com' || $from == 'partyweekends.kateandtoms.com' ) {
				echo '<p>Unfortunately, we couldn\'t find anything with that URL. You can go back to the main site by <a href="http://kateandtoms.com">clicking here</a>.</p>';
			} 
			else {
				echo '<p>Unfortunately, we couldn\'t find anything with that URL. You can browse all the houses by <a href="/houses/">clicking here</a>.</p>';	
			}
		?>
	</div>
</div>
<?php get_footer(); ?>