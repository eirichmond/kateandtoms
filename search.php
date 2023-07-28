<?php
	get_header();
	include('search-navbar.php');
	if(is_post_type_archive( 'houses')) {
		$element_id = 'topper';
	} else {
		$element_id = 'not-topper';
	}
?>
<div id="<?php echo $element_id; ?>" style="background:#fff;">
	<div id="content">
		<div class="house_main_container">
			<div id="loader" ><div id="center"><h2 style="margin-top:200px;">Loading houses...</h2></div></div>
			<div id="search-ajax" class="results"><?php include('house_display.php'); ?></div>
		</div>
	</div>
</div>
<?php get_footer(); ?>