<?php
	$term_id = get_queried_object_id();
	Widget::createTermTopWidget($term_id, 'termtop');
?>


<?php $height = ((isset($s_date_type) && isset($s_date)) ? 430 : 330);

	HouseSearch::prepareAdverts();
	HouseSearch::housesSetup($inputs);
	if (isset($inputs['taxonomies']['location'])) {
			if (in_array($inputs['taxonomies']['location'], array('town', 'country', 'sea', 'cotswolds')))  {
				HouseSearch::$showOneCat = $inputs['taxonomies']['location'];
			}
	} elseif (count($inputs) == 1 && isset($inputs['s'])) {

		echo HouseSearch::topSectionFeatured();
	}

	$sections = HouseSearch::getSeparatingCats();

	foreach ($sections as $section) {
		$house_count = 0;
		$n = 0;
		$endDiv = false;

		$houses = HouseSearch::$houses;

		foreach (HouseSearch::$houses as $house) {


			// if($house->post_id == 16914) {

				if ($house->locations !== false && $house->locations !== "0.000000") {
					$locations = unserialize($house->locations);

					if (is_array($locations)) {
						if ( in_array($section['name'], $locations) ) {

							$house = new HouseSearch($house, $inputs);

/*
							echo '<pre>';
							print_r($house);
							echo '</pre>';

*/

							if(is_post_type_archive( 'houses' )) {
								$element_id = 'toppertax';
							} else {
								$element_id = '';
							}
							$display = $house->displayCheck();
							if ($house->displayCheck()) {

								$house_count ++;

								if ($house_count === 1) {
									echo '<div id="'.$element_id.'" class="background-'.$section['color'].'">
										<div class="container"><div class="row">
										<h2 class="span12 type_title">'.$section['title'].'</h2>';
								}
								$endDiv = true;
								$none_display = true;
								$i = $house->displayHouse($section['color'], $height);
								if ($i) {
									$n++;
								}
							}
						}
					}
				}

			// }

		}
		HouseSearch::displayAdverts($section, $n);
		if ($endDiv) echo '</div></div></div>';
	}

	if (!isset($none_display)) { ?>

	<div class="background-nohouses">
		<div class="container">
			<div class="row">
				<h2 class="span12 type_title">No houses found</h2>
				<p class="aligncenter">Sorry, your search yielded no results. Please try an alternative search.</p>
			</div>
		</div>
	</div>

	<?php } ?>

	<?php
		Widget::createTermBottomWidget($term_id,'termbottom');
	?>


	<?php $cross_sells = HouseSearch::getSearchCrossSells();

	if (!empty($cross_sells)) {
		if (is_array($sections)) {
			$final_section = array_pop($sections);
			$color = $final_section['color'];
		}
		else {
			$color = '';
		}
	?>


	<div class="background-<?php echo $color; ?> advertarea kjskdjf">
		<div class="container">
			<div class="row">
				<?php foreach ($cross_sells as $cross_sell) { ?>
				<div class="<?php echo $cross_sell['span']; ?>">
					<a href="<?php echo $cross_sell['link'];?>">
						<img loading="lazy" src="<?php echo $cross_sell['image'];?>" alt="kateandtoms cross advert">
					</a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>

<?php FooterWidgets::createWidgets($post->ID); ?>

