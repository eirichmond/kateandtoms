<!DOCTYPE html>
<html <?php	language_attributes();  ?>>
<?php
	global $katglobals;
	$desktop = $katglobals['site_desktop_menus'];
	$desktop_default = $katglobals['site_desktop_menus_default'];
	$mobile = $katglobals['site_mobile_menus'];
	$mobile_default = $katglobals['site_mobile_menus_default'];
	$desktop_menu = (array_key_exists(get_current_blog_id(), $desktop) ? $desktop[get_current_blog_id()] : $desktop_default);
	$mobile_menu = (array_key_exists(get_current_blog_id(), $mobile) ? $mobile[get_current_blog_id()] : $mobile_default);
	$enquire_id = $katglobals['enquire_mappings_default'][get_current_blog_id()];
?>
	<head>
		<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="p:domain_verify" content="e48da99e4d15bfacf41bf71c04f0ac90" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title><?php
	if (!empty($customtitle)) echo $customTitle.' - '.get_bloginfo();
	else { wp_title(' - ', true, 'right'); }
?></title>
		<link rel="preload" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/<?php echo get_option('options_site_style'); ?>?r=1" media="screen" />
		<link rel="preload" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/mobile.css" media="screen" />
		<link href='https://fonts.googleapis.com/css?family=News+Cycle:400,700|Oswald:400,300' rel='preload' type='text/css' as='style'>
		<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />
		<!--[if IE 7]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style-ie7.css?r=1" type="text/css" /><![endif]-->
		<!--[if IE 8]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style-ie8.css?r=1" type="text/css" /><![endif]-->
		<?php wp_head(); ?>
	</head>
	<body id="fe" <?php $id = 'site'.get_current_blog_id();  body_class($id); ?>>
		<div id="st-container" class="st-container">
			<!-- content push wrapper -->
			<div class="st-pusher">


				<?php get_all_house_ids(); ?>

				<div class="st-menu st-effect-3 <?php echo $enquire_id; ?>" id="menu-3" style="display:none;">

					<?php if(get_current_blog_id() != 8) {?>
					<?php do_action('kateandtoms_get_in_touch'); ?>
					<?php } else { ?>
					<?php do_action('kateandtoms_get_in_touch_partners'); ?>
					<?php } ?>
				</div>


				<div id="header" class="affix">


					<?php if( extension_loaded('newrelic') ) { echo newrelic_get_browser_timing_header(); } ?>
					<div class="ktmain_nav_cont">
						<div class="container">


								<?php
									if (is_home() || is_front_page()) {
										// You are on the homepage
										echo "<h2 class='visually-hidden'>Large Holiday Homes | Extraordinary Holidays, Celebrations & Adventures</h2>";
									} elseif ( is_category() ) {
										// You are on a category archive page
										echo '<h1 class="visually-hidden">' . single_cat_title() . '</h1>';
									} elseif ( is_tag() ) {
										// You are on a category archive page
										echo '<h1 class="visually-hidden">' . single_tag_title() . '</h1>';
									}
								?>


							<div class="logo span2">
								<a href="<?php echo get_blogaddress_by_id(11); ?>" title="Kate &amp; Tom's" rel="home"></a>
								<span class="dhide mobnav" id="site-navigation">
									<span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
								</span>
								<span class="dhide mobnav" id="results-filter">
									<span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
								</span>
							</div>
							<div class="span10 floatright mhide">



								<?php if(get_current_blog_id() != 8) {?>
								<span id="st-trigger-effects">
									<a role="button" data-effect="st-effect-3" id="get-intouch-button" class="btn btn-3 floatright"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Get in Touch</a>
								</span>
								<?php } else { ?>
								<span id="st-trigger-effects">
									<a role="button" data-effect="st-effect-3" id="get-intouch-button" class="btn btn-3 floatright"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Get in Touch</a>
								</span>
								<?php } ?>





								<?php

					if (!in_array(get_current_blog_id(), $katglobals['hide_search_form'])) {
						//get_search_form();
						echo '<div class="search-container">';
						echo '<input placeholder="Search..." class="dsearch" type="text">';
						echo '</div>';
					}
					if (!is_search() && !is_archive() && $id != 'site11' && $id != 'site9' && $id != 'site8' && $id != 'site13' && $id != 'site16') {
						include('search-date.php');
					}

					if ($id == 'site11') {
						wp_nav_menu(
							array(
								'theme_location' => 'top_menu',
								'container_class' => 'menu-top-menu-container floatright nav-menu',
								'menu_class' => 'topmenu kts',
								'item_spacing' => 'discard',
								'walker' => new KTS_Walker_Nav_Menu()
							)
						);
					}

					if ($id == 'site7') $width = 4;
					elseif ($id == 'site11' || $id == 'site7') $width = 5;
					else $width = 3;

				?>


							</div>
						</div>
					</div>
					<div class="moblogo dhide">
						<a href="/" title="<?php bloginfo('name'); ?>" rel="home">

							<?php if($id != 'site11') { ?>
							<img src="/wp-content/themes/clubsandwich/images/site-logo-<?php echo get_current_blog_id(); ?>.png" class="brand_logo" alt="<?php bloginfo('name'); ?>" />
							<?php } else { ?>
							<img src="https://kateandtoms.com/wp-content/uploads/2013/03/extraordinary-holidays.png" class="brand_logo" alt="<?php bloginfo('name'); ?>" width="423px" height="28px" />
							<?php } ?>

						</a>
					</div>
					<div class="ktsub_nav_cont">
						<div class="container">

							<div class="row">

								<div class="span<?php echo $width; ?>">
									<a href="/" title="<?php bloginfo('name'); ?>" rel="home"><img src="/wp-content/themes/clubsandwich/images/site-logo-<?php echo get_current_blog_id(); ?>.png" class="brand_logo" alt="<?php bloginfo('name'); ?>" <?php echo $id == 'site11' ? 'width="423px" height="28px"':'' ?> /></a>
								</div>

								<div class="span<?php echo 12-$width; ?> floatright">
									<?php
						if (!in_array(get_current_blog_id(), $katglobals['hide_all_houses_button'])) {
							echo '<a href="/houses/" class="btn btn-2 floatright"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search All</a>';
						}
						// if ($id == 'site11') {
						// 	if(!is_post_type_archive( 'houses' ) && !in_array('all', $_REQUEST)) {
						// 		include('search-date.php');
						// 	}
						// }

					?>

									<?php
					wp_nav_menu(
						array(
							'theme_location' => 'sub_menu',
							'container_class' => 'menu-top-menu-container',
							'menu_id' => 'menu-top-menu',
							'menu_class' => 'specificmenu mhide',
							'item_spacing' => 'discard',
							'walker' => new KTS_Walker_Nav_Menu()
						)
					);
					?>

									<?php
					wp_nav_menu(
						array(
							'theme_location' => 'mobile_menu',
							'container' => false,
							'menu_id' => 'mobilenav',
							'menu_class' => 'nav-menu',
							'item_spacing' => 'discard',
							'items_wrap' => '<ul id="%1$s" class="%2$s"><div id="msbh">
							<button type="button" class="close house-search">x</button>
							<div class="search-container">
							<input placeholder="Search..." class="msearch" type="text">
							</div>
						</div>%3$s

						<li id="st-trigger-effects"><a href="#" data-effect="st-effect-3" data-toggle="modal">GET IN TOUCH</a></li>
						</ul>',
							'walker' => new KTS_Walker_Mobile_Nav_Menu()
						)
					);
					?>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="openpad">


					<!-- <iframe width="100%" height="100%" src="https://my.matterport.com/show/?m=7DzAJrn9axy" frameborder="0" allowfullscreen allow="xr-spatial-tracking"></iframe> -->

