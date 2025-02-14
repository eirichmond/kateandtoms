<?php
/*
	Template Name: Micro Site Home Page
*/

	$the_query      = new WP_Query( array( 'post_type' => 'houses' ) );
	$preview        = ( ! empty( $_GET['preview'] ) ? '?preview=true' : '' );
	$id             = get_current_blog_id();
	$secondsubtitle = 'Things to do';
if ( $id == 12 ) {
	$secondsubtitle = 'The House';
}

	$house   = new HousePage( $the_query->post->ID );
	$subpage = $house->getPageName();

	get_header( 'litehouse' );


	the_post();
if ( $house->getPage() == 'facts' ) :
	$location = explode( '|', get_post_meta( $the_query->post->ID, 'location', true ) );
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
			var mapLoc = new google.maps.LatLng(<?php echo $location['1']; ?>);
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
	<div id="map_canvas" style="height:400px; width:100%;"></div>
<?php else : ?>
<!-- Image carousel -->
<div id="slidedeck_frame" class="skin-slidedeck">

	<!-- Page title -->
	<div id="content" class="page-title_cont <?php echo $house->getTitleClass(); ?>">
		<?php // echo '<pre>'; print_r($house); echo '</pre>'; ?>

		<div class="container">


			<div class="row positop">

			</div>
		</div>
	</div>


	<dl class="slider" style="height:<?php echo ( $house->getPage() === 'house_home' ? '600' : '400' ); ?>px;">
	<?php

		$house_photos = get_post_meta( $the_query->post->ID, 'house_photos' );
		$c            = 0;
	foreach ( $house_photos[0] as $image_id ) {

		$args        = array(
			'p'         => $image_id,
			'post_type' => 'attachment',
		);
		$seps_query  = get_posts( $args );
		$sep_content = $seps_query[0]->post_content;
		$sep_title   = $seps_query[0]->post_content;
		$sep_img     = wp_get_attachment_image_src( $image_id, 'huge' );
		if ( ! stristr( $sep_title, 'thumb' ) && ! stristr( $sep_content, 'thumb' ) ) {
			++$c;

			echo '<dd style="background: url(' . $sep_img[0] . ') no-repeat ' . $sep_content . ' center; width:100%; height:600px; display:inline-block; background-size: 100%; background-width:960px;"></dd>';
		}
		if ( $c === 5 ) {
			break;
		}
	}

	?>
	</dl>
</div>
<?php endif; ?>




<?php

	// Home Page
if ( $house->getPage() === 'house_home' ) {
	Widget::createWidgets( $the_query->post->ID );
}

	// Other Page
elseif ( $house->getPage() == 'more' ) {
	Widget::createWidgets( $the_query->post->ID, true );
}

	// Availability Page

elseif ( $house->getPage() === 'availability' ) {
	$inherited         = get_post_meta( $the_query->post->ID, 'availability_site_post_id', true );
	$inherited_site_id = get_post_meta( $the_query->post->ID, 'availability_site_ref', true );
	$house->startPageWrap();
	echo '<div id="page-content" >';
	echo '<div class="widget facts"><h2 class="aligncenter">Select a date to book your stay</h2></div>';
	if ( get_field( 'availability_general_text' ) ) {
		echo '<div class="house_custom_area">' . get_field( 'availability_general_text' ) . '</div>';
	}

	if ( $inherited == true ) {
		if ( $inherited_site_id == true ) {
			switch_to_blog( $inherited_site_id );
		} else {
			switch_to_blog( 1 );
		}
		echo $gentext = get_post_meta( $inherited, 'availability_general_text', true );
		restore_current_blog();
	}
	// echo '<pre>'; print_r($house); echo '</pre>';
	// echo $variable = get_post_meta($the_query->post->ID, 'availability_general_text', true);
	$availability = new HouseAvailability( $the_query->post->ID );
	echo '</div>';
	$house->endPageWrap();
}

	// add to mask the book vs. booknow problem!!
elseif ( $house->getPage() === 'booknow' ) {
	$house->startPageWrap();
	echo '<div id="page-content" >';
	echo '<div class="widget facts"><h2 class="aligncenter">Select a date to book now</h2></div>';
	if ( get_field( 'availability_general_text' ) ) {
		echo '<div class="house_custom_area">' . get_field( 'availability_general_text' ) . '</div>';
	}

	// echo '<pre>'; print_r($house); echo '</pre>';
	$availability = new HouseAvailability( $the_query->post->ID );
	echo '</div>';
	$house->endPageWrap();
}

	// Booking Page
elseif ( $house->getPage() == 'book' ) {
	$house->startPageWrap();
	echo '<div class="widget facts"><h2 class="aligncenter">Book Now</h2></div>',
		'<div class="row book">';
	$booking = new HouseBook( $the_query->post->ID, $house );
	echo '</div>';
	$house->endPageWrap();


}

	// Gallery Page
elseif ( $house->getPage() == 'gallery' ) {
	$house->startPageWrap();
	echo '<div id="page_content" class="gallery">';
	$ids    = '';
	$images = get_post_custom_values( 'house_photos' );
	$images = unserialize( $images[0] );
	/*      print_r($images); */
	foreach ( $images as $image ) {
		$ids .= $image . ',';
	}
	echo do_shortcode( '[gallery columns="4" ids="' . $ids . '"]' ), '</div>';
	$house->endPageWrap();
}

	// Key Info Page
elseif ( $house->getPage() == 'facts' ) {
	$id             = get_current_blog_id();
	$secondsubtitle = get_field( 'label_kf_2', 'options' );
	if ( $id == 12 ) {
		$secondsubtitle = 'Accommodation';
	}
	if ( $id == 12 ) {
		$kfheadone = 'Wedding Information';
	} else {
		$kfheadone = get_field( 'label_kf_1', 'options' );
	}
	$house->startPageWrap();
	echo '<div class="widget facts"><h2 class="aligncenter">Key Facts</h2></div>
			<div class="row facts">
				<div class="span4"><h3>' . $kfheadone . '</h3>' . get_field( 'keyfacts_1' ) . '</div>
				<div class="span4"><h3>' . $secondsubtitle . '</h3>' . get_field( 'keyfacts_2' ) . '</div>
				<div class="span4"><h3>' . get_field( 'label_kf_3', 'options' ) . '</h3>' . get_field( 'keyfacts_3' ) . '</div>
			</div>';
	$house->endPageWrap();
	Widget::createKeyFactsWidgets( $the_query->post->ID );
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

<!-- Cross-selling houses -->
<?php $houses = get_field( 'related_houses' ); if ( $houses ) : ?>
<div class="main_body_cont house_bottom" style="border-top:1px solid #F1F1F1;">
<div class="container main_body" style="padding-bottom: 0;">
<div class="row">
<div class="span12">
<div style="margin:5px 0 15px;"><h2>Houses you may also like...</h2></div>
</div>
	<?php HouseSearch::crossSell( $the_query->post->ID ); ?>
</div>
</div>
</div>
	<?php
	endif;
	get_footer();
?>