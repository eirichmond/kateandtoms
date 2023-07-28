<?php get_header(); ?>
<div id="slidedeck_frame" class="skin-slidedeck">
<dl class="slider" style="height:500px;">
<?php
	$c = 0;
	foreach( get_field('supplier_photos') as $image ) {
		if ( !stristr($image['title'], 'thumb') && !stristr($image['description'], 'thumb') ) {
			$c++;
			$position = (stristr($image['description'], 'top') ? 'top' : ( stristr($image['description'], 'bottom') ? 'bottom' : 'center' ));
			echo '<dd style="background: url('.$image['sizes']['huge'].') no-repeat '.$position.' center; width:100%; height:500px; display:inline-block; background-size: 100%; background-width:960px;"></dd>';
		}
		if ($c === 5) break;
	}
?>
</dl>
</div>
<?php 
	Widget::createHeader($post->ID);
	Widget::createWidgets($post->ID);
	get_footer(); 
?>