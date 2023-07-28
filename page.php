<?php
	
	$page_object = new Get_Related_Houses($post);
	
	get_header(); 
	if ( has_post_thumbnail() ) { 
?>
<div class="h-responsive overflow-hidden flex justify-content-center align-items-center">
	<?php
		$image_id = get_post_thumbnail_id();		
		$image_as_post = get_post($image_id);
		$crop_from = $image_as_post->post_content;
		if (empty($crop_from)) { $crop_from = 'absoluteCenter attachment-post-thumbnail'; };
		
		the_post_thumbnail('huge', array('class' => $crop_from));
		 
	?>
</div>
<?php } else {
		echo '<div class="headspace"></div>';
	}
	
	Widget::createHeader($post->ID);
	
	Widget::createWidgets($post->ID);
	
	if ($page_object) {
		$page_object->render_associated_houses();
	}

	FooterWidgets::createWidgets($post->ID);

	get_footer();
?>