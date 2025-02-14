<?php
/*
Template Name: Home page
*/
$homePhotos = get_post_custom_values('image_rotator');
$homePhotos = unserialize($homePhotos[0]);
get_header(); ?>

<div class="clholder">
	<div class="homecslider">

		<?php $c = 0; foreach ($homePhotos as $image_id) { $c++; $tag_tit = get_the_title( $image_id ); $linkto = get_post_meta( $image_id, '_image_link_to_url', true ); ?>

		<div>
			<?php if ($linkto) {
					echo '<a href="'.esc_attr( $linkto ).'">';
					echo wp_get_attachment_image( $image_id, 'huge', "", array( "class" => "img-responsive", "loading" => "lazy", "sizes" => "(max-width: 300px) 300px, (max-width: 768px) 768px, (max-width: 992px) 1200px, (max-width: 1200px) 1600px, 100vw" ) );
					echo '</a>';
				} else {
					echo wp_get_attachment_image( $image_id, 'huge', "", array( "class" => "img-responsive", "loading" => "lazy", "sizes" => "(max-width: 300px) 300px, (max-width: 768px) 768px, (max-width: 992px) 1200px, (max-width: 1200px) 1600px, 100vw"
 ) );
				}

				// if ($tag_tit) {
				// 	echo '<div class="container"><div class="row"><span class="tittag">'.esc_html( $tag_tit ).'</span></div></div>';
				// }

				?>
		</div>

		<?php if ($c === 3) break; } ?>

	</div>
</div>

<?php //get_template_part( 'template-parts/removed', 'image-rotator' ); ?>

<?php Widget::createHeader($post->ID); ?>

<div class="widget widget_0 widget_button color9">
	<div class="container">
		<div class="row">
			<div class="span12">

				<?php if(get_current_blog_id() != 1) { ?>

				<?php if(!wp_is_mobile()) { ?>
				<ul style="padding-bottom:40px;">
					<li class="title">Book with confidence</li>
				</ul>
				<?php } else { ?>
				<ul>
					<li class="title">Book with confidence</li>
				</ul>
				<?php } ?>


				<!-- TrustBox widget - Carousel -->
				<div class="trustpilot-widget" data-locale="en-GB" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="5cd41de1c4dd7a0001be3a14" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="4,5" data-review-languages="en">
					<a href="https://uk.trustpilot.com/review/www.kateandtoms.com" target="_blank" rel="noopener">Trustpilot</a>
				</div>
				<!-- End TrustBox widget -->

				<?php } ?>

			</div>
		</div>
	</div>
</div>

<?php Widget::createWidgets($post->ID); ?>

<!-- <div class="widget widget_15 widget_hybrid fourimage color14">
	<div class="container main_body">
		<div class="row">
			<div class="mpadder">
				<div class="span12">
					<?php //echo do_shortcode( '[youtube-feed feed=1]' ); ?>
				</div>
			</div>
		</div>
	</div>
</div> -->


<!-- <div class="widget widget_15 widget_hybrid fourimage color14">
	<div class="container main_body">
		<div class="row">
			<div class="mpadder">
				<div class="span12">
					<?php //echo do_shortcode( '[instagram-feed feed=1]' ); ?>
				</div>
			</div>
		</div>
	</div>
</div> -->

<?php get_footer(); ?>

