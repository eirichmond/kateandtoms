<div class="span12">
	<div class="row <?php echo 'section-' . $this->image_alignment_class; ?>">
		
		<div class="span6">
			<?php echo wp_get_attachment_image( $this->image, 'square-partners' ); ?>
		</div>

		<div class="span6 left-column">
			<h2 class="lg-text text-bold"><?php echo esc_html( $this->title ); ?></h2>
			<?php echo $this->content_a; ?>

			<?php if($this->button_text) { ?>
				<div id="st-trigger-effects">
					<a href="#" data-effect="st-effect-3" id="get-intouch-button" class="btn btn-3 btn-large"><span class="glyphicon <?php echo $this->button_icon; ?>" aria-hidden="true"></span><?php echo $this->button_text; ?></a>
				</div>
			<?php } ?>
			
			<?php echo $this->content_b; ?>
		</div>

	</div>
</div>
