<div class="span12">
	<h2 class="lg-text text-left text-bold"><?php echo esc_html( $this->title ); ?></h2>
	<div class="row">

	<?php for($i = 0; $i < $this->partner_testimonials; ++$i) { ?>
		<div class="span6">
			<div class="speech-quotes"></div>
			<div class="testimonial-text"><?php echo get_post_meta($ID, 'widgets_'.$key.'_partner_testimonials_'.$i.'_testimonial_text', true); ?></div>
			<div class="row">
				<!-- <div class="span1">
					<div class="testimonial-image">
						<?php echo wp_get_attachment_image( get_post_meta($ID, 'widgets_'.$key.'_partner_testimonials_'.$i.'_testimonial_image', true), 'thumbnail', false, array('class' => 'img-circle')); ?>
					</div>
				</div> -->
				<div class="span6">
					<div class="testimonial-owner text-bold"><?php echo get_post_meta($ID, 'widgets_'.$key.'_partner_testimonials_'.$i.'_testimonial_partner_name', true); ?></div>					
					<div class="testimonial-owner"><?php echo get_post_meta($ID, 'widgets_'.$key.'_partner_testimonials_'.$i.'_testimonial_partner_subtext', true); ?></div>
				</div>
			</div>
			
		</div>
	<?php } ?>

	</div>
</div>
</div>
