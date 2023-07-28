<div class="span12">
<div class="lg-text text-left text-bold text-color1 line-height-1"><?php echo apply_filters( 'the_content', $this->title ); ?></div>
<div class="row">
	<div class="span4 left-column">
		<?php echo $this->toptext; ?>
		<?php echo $this->render_list_items($this->listitems, $key, $ID); ?>
		<?php echo $this->bottomtext; ?>
	</div>
	<div class="span7 offset1 right-column">
		<?php echo wp_get_attachment_image( $this->image, 'square-partners' ); ?>
	</div>
</div>
</div>
