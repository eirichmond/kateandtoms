<?php

/*
	Template Name: SynciPro
*/
	
	get_header(); 
	if ( has_post_thumbnail() ) { 
?>
<div class="top-title">
	<div class="absoluteCenterWrapper">
	<?php
		$image_id = get_post_thumbnail_id();		
		$image_as_post = get_post($image_id);
		$crop_from = $image_as_post->post_content;
		if (empty($crop_from)) { $crop_from = 'absoluteCenter'; };
		the_post_thumbnail('huge', array('class' => $crop_from)); 
	?>
	</div>
</div>
<?php 
	}
	else {
		echo '<div class="headspace"></div>';
	}
	
	Widget::createHeader($post->ID);
	Widget::createWidgets($post->ID);
	if (isset($_GET) && $_GET['refactor'] == 'true') {
		echo '<div class="container">';
		do_action('fire_ipro_sync_refactor');
		echo '</div>';
	} else {
		echo '<div class="container">';
		do_action('fire_ipro_sync');
		echo '</div>';
	}
	get_footer();
?>