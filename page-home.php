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
					echo wp_get_attachment_image( $image_id, 'huge', "", array( "class" => "img-responsive", "loading" => "lazy" ) );
					echo '</a>';
				} else {
					echo wp_get_attachment_image( $image_id, 'huge', "", array( "class" => "img-responsive", "loading" => "lazy" ) );
				}

				// if ($tag_tit) {
				// 	echo '<div class="container"><div class="row"><span class="tittag">'.esc_html( $tag_tit ).'</span></div></div>';
				// }

				?>
			</div>

		<?php if ($c === 5) break; } ?>

	</div>
</div>

<?php //get_template_part( 'template-parts/removed', 'image-rotator' ); ?>

<?php Widget::createHeader($post->ID); ?>

<?php Widget::createWidgets($post->ID); ?>


<?php get_footer(); ?>