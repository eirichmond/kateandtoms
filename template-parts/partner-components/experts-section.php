<div class="span12">
<h2 class="lg-text text-left text-bold text-color1"><?php echo esc_html( $this->title ); ?></h2>
<div class="row">
	<div class="span4 pcontainer">

		<div class="pcontent">
			<?php echo $this->content; ?>
		</div>

	</div>
	<div class="span3 pimages">

		<?php echo wp_get_attachment_image( $this->image_a, 'square' ); ?>

		<?php echo wp_get_attachment_image( $this->image_b, 'square' ); ?>

	</div>
	<div class="span5 imagebreak">

		<?php echo wp_get_attachment_image( $this->image_c, 'square' ); ?>

	</div>
</div>
</div>
