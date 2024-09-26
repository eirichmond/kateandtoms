<?php
	global $preview;
	global $the_query;
	global $katglobals;
	$options = get_option('plugin_options');

	if (isset($the_query) && !empty($the_query)) {
		$house = new HousePage($the_query->post->ID);

	} else {
		$the_query = new WP_Query( array( 'post_type' => 'houses', 'post__in' => array($options['microsite_mainpage']) ) );
		$house = new HousePage($the_query->post->ID);
	}
	$enquire_id = $katglobals['enquire_mappings_default'][get_current_blog_id()];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>

		<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="p:domain_verify" content="e48da99e4d15bfacf41bf71c04f0ac90" />
		<title><?php
	if (!empty($subpage)) echo $subpage.' - '.get_the_title().' - The Big Cottage Company';
	elseif (!empty($customtitle)) echo $customTitle.' - '.get_bloginfo();
	else { wp_title(' - ', true, 'right'); }
?></title>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>?r=1" media="screen" />
		<!-- <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/lighthouse.css" media="screen" /> -->
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/bootstrap.min.css?r=1" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/<?php echo get_option('options_site_style'); ?>?r=1" media="screen" />
		<!-- <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/mobile.css?r=2" media="screen" /> -->

		<link href='https://fonts.googleapis.com/css?family=News+Cycle:400,700|Oswald:400,300' rel='stylesheet' type='text/css'>

		<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />

		<!--[if IE 7]>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style-ie7.css?r=1" type="text/css" />
<![endif]-->
		<!--[if IE 8]>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style-ie8.css?r=1" type="text/css" />
<![endif]-->
		<?php wp_head(); ?>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/lighthouse.js"></script>

	</head>

	<?php do_action('cookies_banner_script');?>

	<body id="fe" <?php $foo = 'foo'; $id = 'site'.get_current_blog_id(); body_class($id); ?>>

		<div id="st-container" class="st-container">
			<!-- content push wrapper -->
			<div class="st-pusher">

				<?php //if(get_current_blog_id() != 8) {?>
				<div class="st-menu st-effect-3" id="menu-3">

					<?php
			// switch_to_blog(1);
			// 	echo do_shortcode('[contact-form-7 id="'.$enquire_id.'" title="Contact form" html_class="use-floating-validation-tip"]');
			// restore_current_blog();
		?>
					<?php if(get_current_blog_id() != 8) {?>
					<?php do_action('kateandtoms_get_in_touch'); ?>
					<?php } else { ?>
					<?php do_action('kateandtoms_get_in_touch_partners'); ?>
					<?php } ?>


				</div>
				<?php //} ?>

				<div id="header" class="affix">
					<?php if( extension_loaded('newrelic') ) { echo newrelic_get_browser_timing_header(); } ?>
					<div class="navi ktsub_nav_cont affix">
						<div class="container">
							<div class="row">
								<div class="span3 litepanel color6 mini-logo">

									<!-- 	<h1 class="page-title"><?php echo '<a href="/houses/' . $house->getName() . '/'.$preview.'">' . get_the_title() . '</a>'; ?></h1> -->
									<h1 class="page-title"><a href="/"><?php echo get_bloginfo('name'); ?></a></h1>
									<div class="house_page_meta">
										<?php $blog_id = get_current_blog_id(); if ($blog_id ==  12) { $berth = 'Wedding Size'; } else { $berth = 'Sleeps'; } ?>

										<span class="house_page_meta_single">
											<!-- <i class="icon-home"></i>  --><?php echo $berth.' ' . get_post_meta($the_query->post->ID, 'sleeps_min', true); ?>-<?php echo get_post_meta($the_query->post->ID, 'sleeps_max', true); ?>
										</span>

										<?php echo get_post_meta($the_query->post->ID, 'location_text', true); ?>

									</div>

								</div>
								<div class="span9">
									<ul>
										<li><?php if (!get_field('turn_off_take_a_gallery')) echo '<a href="/houses/'.$the_query->post->post_name.'/gallery/'.$preview.'">Gallery</a>'; ?></li>
										<li><a href="/houses/<?= $the_query->post->post_name;?>/more/<?= $preview; ?>">Things to do</a></li>
										<li><a href="/houses/<?= $the_query->post->post_name;?>/facts/<?= $preview; ?>">key facts</a></li>
										<li><a class="<?php if ($house->getPage() === 'availability') echo 'active'; ?>" href="/houses/<?= $the_query->post->post_name;?>/availability/<?= $preview; ?>">Availability</a></li>
										<!-- <li><a href="#getintouch" role="button" data-toggle="modal" data-backdrop="static">Enquire</a></li> -->
										<li><span id="st-trigger-effects"><a href="#getintouch" data-effect="st-effect-3" data-toggle="modal">Enquire</a></span></li>
										<li><a href="/special-offers">Offers</a></li>
										<li><a href="https://kateandtoms.com/houses/the-moult-wing/availability/">The Wing</a></li>
										<li><a class="btn btn-3 litehouse <?php if ($house->getPage() === 'book' || $house->getPage() === 'booknow') echo 'active'; ?>" href="/houses/<?= $the_query->post->post_name;?>/booknow/<?= $preview; ?>">Book Now</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="litehouse">