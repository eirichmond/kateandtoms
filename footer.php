
<?php 
	global $katglobals;
	function encode_email_address($email) {
		$output = '';
		for ($i = 0; $i < strlen($email); $i++) { 
			$output .= '&#'.ord($email[$i]).';'; 
		} 
		return $output; 
	} 
	
	// Returns code for email link
	function the_site_email_link() {
		global $katglobals;
		$email_custom = $katglobals['emails'];
		$email_default = $katglobals['emails_default'];
	
		$email = (array_key_exists(get_current_blog_id(), $email_custom) ? $email_custom[get_current_blog_id()] : $email_default);
		$email_encoded = encode_email_address ($email[0]);
		$subject = $email[1];
		echo '<a href="mailto:'.$email_encoded.'?Subject='.$subject.'">'.$email_encoded.'</a>';
	}
	
	function create_menu_li($item, $class = '') {
		$siteid = get_current_blog_id();
		if ($item['title'] == 'meet the team' && $siteid != 11) {
			return;
		} else {
			echo '<li class="menu-item '.$class.'"><a href="'.$item['url'].'" target="'.apply_filters('filter_self_blank', '_self', $item).'">'.$item['title'].'</a></li>';
		}
	}
	
	
	if (get_current_blog_id() !== 11) {
		unset($katglobals['legacy_footer_menu_main'][5]);
	}

	// Contact form
	$mappings = $katglobals['contact_mappings'];
	$default = $katglobals['contact_mappings_default'];
	$enquire_id = $katglobals['enquire_mappings_default'][get_current_blog_id()];
	
	$contact_id = (array_key_exists(get_current_blog_id(), $mappings) ? $mappings[get_current_blog_id()] : $default);
	
	$current_url = (isset($post->ID) ? get_permalink($post->ID) : get_site_url());
	$twitter_url = 'https://twitter.com/share?text=Checkout%20this%20from%20'.get_bloginfo('name');
	$fb_url = 'http://www.facebook.com/sharer.php?s=100&p[url]='.$current_url.
				'&p[images][0]=the image you want to share&p[title]='.get_bloginfo('name').
				'&p[summary]='.get_bloginfo('description');
				
?>


</div>

<div class="ftwide blk">
	<div class="container footer">
		<div class="row">
			<div class="span10">
				<img loading="lazy" class="katlogo" src="<?php bloginfo('template_directory'); ?>/images/katandtoms-trans.jpg" alt="kate and toms logo">
			</div>
			
			
			<div class="span2">
				
				


				<ul class="sharing-icons pull-right">
					<li>
						<a href="https://twitter.com/kateandtoms" target="_blank">
							<i class="fa fa-twitter-square fa-2x"></i>
						</a>
					</li>
					
					<li>
						<a href="https://www.facebook.com/kateandtoms" target="_blank">
							<i class="fa fa-facebook-square fa-2x"></i>
						</a>
					</li>
					
					<li>
						<a href="https://uk.pinterest.com/kateandtoms" target="_blank">
							<i class="fa fa-pinterest-square fa-2x"></i>
						</a>
					</li>
					
					<li>
						<a href="https://www.instagram.com/kateandtoms" target="_blank">
							<i class="fa fa-instagram fa-2x"></i>
						</a>
					</li>
					
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="span4">
				<img loading="lazy" class="extra-strap" src="<?php bloginfo('template_directory'); ?>/images/extstrap.jpg" alt="extraordinary holidays, celebrations and adventures">
			</div>
			<div class="span8">
				<div class="menu-top-menu-container floatright">
					<ul class="topmenu">
						<?php 
							foreach($katglobals['menus']['sites']['footer_items'] as $i) {
								create_menu_li($i);
							}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="ftwide">

	<?php //get_template_part( 'template-parts/footer-menus', 'footer-menus' ); ?>
	
	<div class="container footer">
		<div class="row">
			<div class="span2">
				<strong>kate & tom's</strong>
				<div class="menu-first-footer-container">
					<ul id="menu-first-footer" class="unstyled">
						<?php 
							foreach($katglobals['legacy_footer_menu_main'] as $k => $i) {
								create_menu_li($i, 'menu-item');
							}
						?>
					</ul>
				</div>
			</div>
			<?php $site_id = get_current_blog_id();
			if ($site_id != 1) { ?>
			<div class="span2">
				<strong>destinations</strong>
				<div class="menu-second-footer-container">
					<ul id="menu-second-footer" class="unstyled">
						<?php 
							foreach($katglobals['legacy_footer_menu_destinations'] as $i) {
								create_menu_li($i, 'menu-item');
							}
						?>
					</ul>
				</div>
			</div>
			<?php } ?>
			
			<div class="span2">
				<strong>join us</strong>
				<div class="menu-second-footer-container">
					<ul id="menu-second-footer" class="unstyled">
						<?php 
							foreach($katglobals['legacy_footer_menu_join_us'] as $i) {
								create_menu_li($i, 'menu-item');
							}
						?>
					</ul>
				</div>
			</div>
			<div class="span2">
				<strong>get in touch</strong>
				<ul id="menu-third-footer" class="unstyled">
					<li class="menu-item"><a href="https://kateandtoms.com/press-enquiries/">press enquiries</a></li>
					<li class="menu-item"><span id="st-trigger-effects"><a href="#getintouch" data-effect="st-effect-3" data-toggle="modal">contact us</a></span></li>
					<!-- <li class="menu-item"><span id="st-trigger-effects-dis"><a href="/get-intouch/" data-effect="st-effect-3" data-toggle="modal">contact us</a></span></li> -->
					<li class="menu-item"><?php the_site_email_link(); ?></li>
				</ul>
			</div>
			<div class="span4 pull-right text-right">
			
					<strong>call us</strong>
					<li><a href="#">01242 235151</a></li>
					<li>open 7 days a week</li>
				
			</div>
			
		</div>

	</div>
	

	
</div>

<div class="mfooter row">
	<div class="col-xs-6 col-sm-3">
		<a href="https://kateandtoms.com">
			<img loading="lazy" class="katlogo" src="<?php bloginfo('template_directory'); ?>/images/katandtoms-trans.jpg" alt="kate and toms logo">
		</a>
	</div>
	<div class="col-xs-6 col-sm-3">
		<ul class="social">
			<li><a href="tel:01242 235151"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></a></li>
			<!--<li><a href="https://www.facebook.com/kateandtoms" target="_blank"></a></li>
			<li><a href="https://twitter.com/kateandtoms" target="_blank"><i class="icon-twitter"></i></a></li>-->
		</ul>
	</div>
</div>


</div>
</div>




<?php if (get_current_blog_id() == 8) { ?>
<div id="getintouch" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
<?php 
	switch_to_blog(1);
	echo do_shortcode('[contact-form-7 id="'.$contact_id.'" title="Contact form" html_class="use-floating-validation-tip"]');
	restore_current_blog();
?>
</div>
<?php } ?>



<script>
document.addEventListener( 'wpcf7mailsent', function( event ) {
    location = '/thank-you';
}, false );
</script>

<?php wp_footer(); ?>
<?php if( extension_loaded('newrelic') ) { echo newrelic_get_browser_timing_footer(); } ?>

</body>
</html>