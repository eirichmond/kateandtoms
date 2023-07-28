<?php $house = new HousePage($post->ID); ?>

<!-- Image carousel -->
	<div id="slidedeck_frame" class="skin-slidedeck">
		<dl class="slider <?php echo $house->getPage(); ?>">

			<?php do_action('house_specials_banner'); ?>

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
					//var_dump($house->getImages('huge'));
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