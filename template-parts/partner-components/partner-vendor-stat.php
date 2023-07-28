<div class="span5 <?php if ($counter % 2 == 0) { echo 'offset1'; } ?> vendor">
	<?php echo wp_get_attachment_image( $image, 'square-vendor-stats' ); ?>
	<h2 class="text-bold text-left"><?php echo $title;?></h2>

	<ul class="unstyled listed-feature">
	<?php for($n = 0; $n < $lists; ++$n) { ?>
		<li><i class="glyphicon glyphicon-play text-color4"></i> <?php echo get_post_meta( $id, 'widgets_'.$key.'_vendor_stats_inner_section_'.$i.'_vendor_list_vendor_stat_'.$n.'_list_item', true );?></li>
	<?php } ?>
	</ul>
	
	<div class="text-bold text-left footerbyline"><?php echo $footerbyline;?></div>
</div>
