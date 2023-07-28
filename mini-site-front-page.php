<?php
/*
	Template Name: Mini Site
*/

	$id = get_current_blog_id();
	$secondsubtitle = 'Things to do';
	if ($id == 12) {
		$secondsubtitle = 'The House';
	}

	$house = new HousePage($post->ID);
	$subpage = $house->getPageName();
	get_header();
	$preview = (!empty($_GET['preview']) ? '?preview=true' : '');
	the_post();
	if ($house->getPage() == 'facts') :
	$data = get_post_meta($post->ID, 'location', true);
	if (!is_array($data)) {
		$location = explode('|', $data);
		$location = $location[1];
	}
	else {
		$location = $data['lat'].','.$data['lng'];
	}
?>
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { width:100%; height: 100% }
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZrz6gY1MGvUnU32TUVDxp_7jJLb46-vQ&sensor=false">
    </script>
    <script type="text/javascript">
      function initialize() {
      	var mapLoc = new google.maps.LatLng(<?php echo $location; ?>);
        var mapOptions = {
          center: mapLoc,
          scrollwheel: false,
          zoom: 10,
          mapTypeId: google.maps.MapTypeId.TERRAIN,
          disableDefaultUI: true,
          zoomControl:true,
		    zoomControlOptions: {
		      style:google.maps.ZoomControlStyle.SMALL,
		      position:google.maps.ControlPosition.LEFT_BOTTOM
		    },
        };
        var image = '/wp-content/themes/clubsandwich/images/location.png';
        var map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
        var marker = new google.maps.Marker({
	      position: mapLoc,
	      map: map,
	      title:"Hello World!",
		  });
      }
    </script>
  <body onload="initialize()">
<?php if (wp_is_mobile()) {
	$mobile_map = 200;
} else {
	$mobile_map = 400;
}?>
    <div id="map_canvas" style="height:<?php echo $mobile_map; ?>px; width:100%;"></div>
<?php else: ?>
<!-- Image carousel -->
<div id="slidedeck_frame" class="skin-slidedeck">
<dl class="slider <?php echo $house->getPage(); ?>">

<?php
	$c = 0;
	$houseImgLimit = ($house->getPage() == 'house_home' && get_field('limit_num_photos') ? get_field('limit_num_photos') : "999");

	$get_widgets = get_post_meta($post->ID, 'widgets');
	$separator_key = array_search('separator_widget', $get_widgets[0]);
	$image_ids = get_post_meta($post->ID, 'widgets_'.$separator_key.'_separator_rotator');



	if ($house->getPage() == 'more' && !empty($image_ids[0])) {
		foreach ($image_ids[0] as $image_id){

			$args = array( 'p' => $image_id, 'post_type' => 'attachment' );
			$seps_query = get_posts( $args );
			$sep_content = $seps_query[0]->post_content;
			$sep_title = $seps_query[0]->post_content;
			$sep_img = wp_get_attachment_image_src( $image_id, 'huge' );
			if ( !stristr($sep_title, 'thumb') && !stristr($sep_content, 'thumb') ) {
				$c++;

				echo '<dd style="background: url('. $sep_img[0].') no-repeat '.$sep_content.' center; width:100%; height:400px; display:inline-block; background-size: 100%; background-width:960px;"></dd>';
			}
			if ($c === 5) break;
		}
	} else {
		foreach( $house->getImages('huge') as $image ) {
			if ( !stristr($image['title'], 'thumb') && !stristr($image['description'], 'thumb') ) {
				$c++;
				$position = (stristr($image['description'], 'top') ? 'top' : ( stristr($image['description'], 'bottom') ? 'bottom' : 'center' ));
				echo '<dd style="background: url('.$image['src'].') no-repeat '.$position.' center; width:100%; height:'.
					($house->getPage() === 'house_home' ? '500' : '400').
					'px; display:inline-block; background-size: 100%; background-width:960px;"></dd>';
			}
			if ($c === 5) break;
		}
	}

?>
</dl>
</div>
<?php endif; ?>

<!-- Page title -->
<div id="content" class="page-title_cont <?php echo $house->getTitleClass(); ?>">
<div class="container">
<div class="row">
<div class="span6 house-titles">
	<h1 class="page-title"><?php echo '<a href="/houses/' . $house->getName() . '/'.$preview.'">' . get_the_title() . '</a>'; ?></h1>
	<div class="house_page_meta">
		<?php $blog_id = get_current_blog_id(); if ($blog_id ==  12) { $berth = 'Wedding Size'; } else { $berth = 'Sleeps'; } ?>
		<span class="house_page_meta_single"><!-- <i class="icon-home"></i>  --><?php echo $berth.' ' . (!get_field('sleeps_min') ? '' : floor(get_field('sleeps_min')) . '-') . floor(get_field('sleeps_max')); ?></span>
		<?php echo (!get_field('location_text') ? '' : '<span class="house_page_meta_single"> ' . get_field('location_text') . '</span>'); ?>

	</div>
</div>
<div class="span6 menu_links">



	<a href="#getintouch" role="button" data-toggle="modal" data-backdrop="static" class="btn btn-1">Enquire</a>
	<a class="btn btn-2 <?php if ($house->getPage() === 'availability') echo 'active'; ?>" href="/houses/<?= $post->post_name;?>/availability/<?= $preview; ?>">Availability</a>

	<a class="btn btn-3 <?php if ($house->getPage() === 'book' || $house->getPage() === 'booknow') echo 'active'; ?>" href="/houses/<?= $post->post_name;?>/booknow/<?= $preview; ?>">Book Now</a>
<!-- 	<a class="btn btn-1 <?php if ($house->getPage() === 'more')  echo 'active'; ?>" href="/houses/<?= $post->post_name;?>/more/<?= $preview; ?>">Book Now!</a> -->

	<div class="house_page_meta extended">
		<?php if (!get_field('turn_off_take_a_gallery')) echo '<a href="/houses/'.$post->post_name.'/gallery/'.$preview.'">Gallery</a>'; ?>
		<a href="/houses/<?= $post->post_name;?>/more/<?= $preview; ?>"><?php echo $secondsubtitle; ?></a>
		<a href="/houses/<?= $post->post_name;?>/facts/<?= $preview; ?>">key facts</a>
	</div>

</div>
</div>
</div>
</div>



<?php

	// Home Page
	if ($house->getPage() === 'house_home')
		Widget::createWidgets($post->ID);

	// Other Page
	elseif ($house->getPage() == 'more')
		Widget::createWidgets($post->ID, true);

	// Availability Page

	elseif ($house->getPage() === 'availability') {
		$inherited = get_post_meta($post->ID, 'availability_site_post_id', true);
		$inherited_site_id = get_post_meta($post->ID, 'availability_site_ref', true);
		$house->startPageWrap();
		echo '<div id="page-content">';
		echo '<div class="widget facts"><h2 class="aligncenter">Select a date to book your stay</h2></div>';
		if (get_field('availability_general_text')) echo '<div class="house_custom_area">' . get_field('availability_general_text') . '</div>';

		if ($inherited == true) {
			if ($inherited_site_id == true) {
				switch_to_blog($inherited_site_id);
			} else {
				switch_to_blog(1);
			}
			echo $gentext = get_post_meta($inherited, 'availability_general_text', true);
			restore_current_blog();
		}

		$availability = new HouseAvailability($post->ID);
		echo '</div>';
		$house->endPageWrap();
	}

	// add to mask the book vs. booknow problem!!
	elseif ($house->getPage() === 'booknow') {
		$house->startPageWrap();
		echo '<div id="page-content">';
		echo '<div class="widget facts"><h2 class="aligncenter">Select a date to book now</h2></div>';
		if (get_field('availability_general_text')) echo '<div class="house_custom_area">' . get_field('availability_general_text') . '</div>';

		$availability = new HouseAvailability($post->ID);
		echo '</div>';
		$house->endPageWrap();
	}

	// Booking Page
	elseif ($house->getPage() == 'book')
	{
		$house->startPageWrap();
		echo '<div class="widget facts"><h2 class="aligncenter">Book Now</h2></div>',
			'<div class="row book">';
		$booking = new HouseBook($post->ID, $house);
		echo '</div>';
		$house->endPageWrap();


	}

	// Gallery Page
	elseif ($house->getPage() == 'gallery') {
		$house->startPageWrap();
		echo '<div id="page_content" class="gallery">';
		$ids = '';
		$images = get_post_custom_values('house_photos');
		$images = unserialize($images[0]);
		foreach ( $images as $image ) $ids .= $image.',';
		echo do_shortcode('[gallery columns="4" ids="'.$ids.'"]'),'</div>';
		$house->endPageWrap();
	}

	// Key Info Page
	elseif ($house->getPage() == 'facts') {
		$id = get_current_blog_id();
		$secondsubtitle = get_field('label_kf_2', 'options');
		if ($id == 12) {
			$secondsubtitle = 'Accommodation';
		}
		if ($id == 12) {
			$kfheadone = 'Wedding Information';
		} else {
			$kfheadone = get_field('label_kf_1', 'options');
		}
		$house->startPageWrap();
		echo '<div class="widget facts"><h2 class="aligncenter">Key Facts</h2></div>
			<div class="row facts">
				<div class="span4"><h3>'.$kfheadone.'</h3>'.get_field('keyfacts_1').'</div>
				<div class="span4"><h3>'.$secondsubtitle.'</h3>'.get_field('keyfacts_2').'</div>
				<div class="span4"><h3>'.get_field('label_kf_3', 'options').'</h3>'.get_field('keyfacts_3').'</div>
			</div>';
		$house->endPageWrap();
		Widget::createKeyFactsWidgets($post->ID);
	}

	// No Page
	else {
		$house->startPageWrap();
		echo 'No page';
		$house->endPageWrap();
	}

?>
</div>
</div>
</div>
</div>
<?php
	// Cross sell
	$houses = get_field('related_houses');
	if ($houses):
?>
<div class="main_body_cont house_bottom" style="border-top:1px solid #F1F1F1;">
<div class="container main_body" style="padding-bottom: 0;">
<div class="row">
<div class="span12">
<div style="margin:5px 0 15px;">
<h2>Houses you may also like...</h2>
</div>
</div>
<?php HouseSearch::crossSell($post->ID); ?>
</div>
</div>
</div>
<?php
	endif;
	get_footer();
?>