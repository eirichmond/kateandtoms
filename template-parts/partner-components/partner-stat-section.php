<div class="span12">
	<h2 class="lg-text text-center text-bold"><?php echo esc_html( $this->title ); ?></h2>
	<div class="row">
		<div class="span12 stats">

			<?php for($i = 0; $i < $this->partner_stats; ++$i) { ?>
				<div class="stat">
					<div class="stat-title"><?php echo get_post_meta($ID, 'widgets_'.$key.'_partner_stat_'.$i.'_stat_title', true); ?></div>
					<div class="stat-content"><?php echo get_post_meta($ID, 'widgets_'.$key.'_partner_stat_'.$i.'_stat_text', true); ?></div>

				</div>
			<?php } ?>


		</div>
	</div>
	<div class="row">
		<div class="span6 offset3 text-center cta">

			<?php echo $this->content_top; ?>

			<div id="st-trigger-effects">
				<a href="#" data-effect="st-effect-3" id="get-intouch-button" class="btn btn-3 btn-large"><span class="glyphicon <?php echo $this->button_icon; ?>" aria-hidden="true"></span><?php echo $this->button_text; ?></a>
			</div>

			<?php echo $this->content_bottom; ?>

		</div>
	</div>
</div>
