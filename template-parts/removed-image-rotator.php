<div id="slideDeck" style="margin-bottom:0px; position: relative;">
	<dl id="sliderHome" style="position: absolute;">
		<?php
			$homePhotos = get_post_custom_values('image_rotator');
			$homePhotos = unserialize($homePhotos[0]);
			foreach ($homePhotos as $id) {
				$align = get_img_description($id);
				if (empty($align)) {$align = 'absoluteCenter';}

				$tag_tit = get_the_title( $id );
				$linkto = get_post_meta( $id, '_image_link_to_url', true );
				$image_alt = get_post_meta( $id, '_wp_attachment_image_alt', true);



				echo '<dd class="absoluteCenterWrapper"><div style="z-index:1;"><div class="cropped onpage">';

				if ($linkto) {
					echo '<a href="'.esc_attr( $linkto ).'"><img loading="lazy" alt="'.$image_alt.'" class="'.$align.'" width="100%" src="'.$imageURL[0].'" srcset="'.$srcset.'" /></a>';
				} else {
					echo '<img loading="lazy" alt="'.$image_alt.'" class="'.$align.'" width="100%" src="'.$imageURL[0].'" srcset="'.$srcset.'" />';
				}
				if ($tag_tit) {
					echo '<div class="container"><div class="row"><span class="tittag">'.esc_html( $tag_tit ).'</span></div></div></div></div></dd>';
				}
			}
			the_post();
		?>
	</dl>
</div>
