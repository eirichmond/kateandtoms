
<div class="container footer">
		<div class="row">
			<div class="span2">
				<strong>kate & tom's</strong>
				<div class="menu-first-footer-container">
					<?php wp_nav_menu( array(
						'theme_location' => 'footer_column_1',
						'container_class' => 'menu-first-footer-container',
						'menu_class' => 'unstyled'
					) ); ?>
				</div>
			</div>
			<?php $site_id = get_current_blog_id();
			if ($site_id != 1) { ?>
			<div class="span2">
				<strong>destinations</strong>
				<div class="menu-second-footer-container">
					<?php wp_nav_menu( array(
						'theme_location' => 'footer_column_2',
						'container_class' => 'menu-first-footer-container',
						'menu_class' => 'unstyled'
					) ); ?>

				</div>
			</div>
			<?php } ?>
			
			<div class="span2">
				<strong>join us</strong>
				<div class="menu-second-footer-container">
					<?php wp_nav_menu( array(
						'theme_location' => 'footer_column_3',
						'container_class' => 'menu-first-footer-container',
						'menu_class' => 'unstyled'
					) ); ?>

				</div>
			</div>
			<div class="span2">
				<strong>get in touch</strong>
				<?php wp_nav_menu( array(
						'theme_location' => 'footer_column_4',
						'container_class' => 'menu-first-footer-container',
						'menu_class' => 'unstyled'
					) ); ?>

			</div>
			<div class="span4 pull-right text-right">
			
					<strong>call us</strong>
					<li><a href="#">01242 235151</a></li>
					<li>open 7 days a week</li>
				
			</div>
			
		</div>

	</div>