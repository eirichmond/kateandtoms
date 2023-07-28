<div class="span12">
	<h2 class="lg-text text-center text-bold text-color1"><?php echo esc_html( $this->title ); ?></h2>
	<div class="row">
		<div class="span12 features">

			<?php for($i = 0; $i < $this->feature_steps; ++$i) { ?>
				<div class="feature">
					<div class="feature-icon"><img loading="lazy" src="<?php echo get_template_directory_uri() . '/images/partner-icons/' . get_post_meta($ID, 'widgets_'.$key.'_partner_feature_'.$i.'_select_icon', true); ?>.svg" alt="bullet point with image"/></div>
					<div class="feature-title"><?php echo get_post_meta($ID, 'widgets_'.$key.'_partner_feature_'.$i.'_feature_title', true); ?></div>
					<div class="feature-content"><?php echo get_post_meta($ID, 'widgets_'.$key.'_partner_feature_'.$i.'_feature_text', true); ?></div>
				</div>
			<?php } ?>

		</div>
	</div>
</div>
