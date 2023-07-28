<?php
	$specialoffers = new SpecialOffers($post->ID);
	
	/*
	Template Name: Special Offers
	*/
	get_header(); 
	if ( has_post_thumbnail() ) {
		
		if (wp_is_mobile()) {
			$height = 180;
		} else {
			$height = 400;
		}
		
		echo '<div class="top-title clholder">
			<div class="homecslider">';
			
		the_post_thumbnail('huge', array('class' => 'absoluteCenter')); 
			
		echo '</div></div>';
		
	} 
	
	Widget::createHeader($post->ID);
	
	$published_houses = get_published_house_titles();
	
	switch_to_blog(11);
	
	$specialsWidgets_ID = 22983;
	
	//Widget::createWidgets($post->ID);
	Widget::createWidgets($specialsWidgets_ID);
	
	//var_dump($published_houses);
	
	//$site_id = get_current_blog_id();
	
/*
		if ($site_id == 10 || $site_id == 11) {
		} else {
			$offers = get_field('offer_repeater');
		}
*/
		
		
		$newoffers = get_post_meta(22983, 'special_offer');
		$newoffers = $specialoffers->remap_special_offers($newoffers);
				
		if (isset($newoffers) && $newoffers != '') {
			$offers = $newoffers;
		}
		
		$date = date('Ymd', strtotime('now + 4 days'));
		
		
		
		if (is_array($offers)) {
			
			foreach( $offers as $k => $offer ) {
				
				$specialoffers->output_offer_section_header($offer);
				
				$house_count = 0;
				
				echo '<div class="container"><div class="row"><h2 class="type_title span12">' . $offer['offer_period_name'] . '</h2></div></div>';	

				echo '<div class="main_body_cont background-pink"><div class="container" style="padding-top:20px;padding-bottom:20px;"><div class="row">';

				foreach ($offer['houses'] as $house) {
					
					//echo '<pre>'; print_r($house['house']->ID); echo '</pre>';
					
					//if ($site_id == 10 || $site_id == 11) {
										
						$display = check_offer_availablitity($house['house']->ID, $house['expiry_date']);
						
						//var_dump($display);
						
						if ($date < $house['expiry_date'] && $display) {
							
							if (in_array($house['house']->post_title, $published_houses)) {
								
								$key = array_search($house['house']->post_title, $published_houses);
								
								$house_count ++;
								
								restore_current_blog();
								
								$houseNew = new HouseSearch($key);
								
								$houseNew->displayHouse('pink', 356, $house['image'], $house['offer_details']);
															
							}
						}
/*
					} else {
						
						$houseObject = $house['house'];
						
						$houseNew = new HouseSearch($houseObject->ID);
						
						$houseNew->displayHouse('pink', 356, $house['image'], $house['offer_details']);
					}
*/
				}
				
				$number_of_ads = $specialoffers->advertCount($house_count);
				$specialoffers->prepareAds($number_of_ads, 'cotswolds');

				echo '</div></div></div>';




			}
			
			
			
			
			
			
			
			
			
			
/*
			echo '<div class="main_body_cont background-pink"><div class="container" style="padding-top:20px;padding-bottom:20px;">';
			
			foreach( $offers as $k => $offer ) {
								
				$house_count = 0;
				
				if ($site_id == 10 || $site_id == 11) {
					echo '<div class="row">';
					display_period_name($offer, $published_houses);
				} else {
					echo '<div class="row"><h2 class="type_title span12">' . $offer['offer_period_name'] . '</h2>';
				}

				
				foreach ($offer['houses'] as $house) {
					
					//echo '<pre>'; print_r($house); echo '</pre>';
					
					if ($site_id == 10 || $site_id == 11) {
										
						$display = check_offer_availablitity($house['house']->ID, $house['expiry_date']);
						
						if ($date < $house['expiry_date'] && $display) {
							
							if (in_array($house['house']->post_title, $published_houses)) {
								
								$key = array_search($house['house']->post_title, $published_houses);
								
								$house_count ++;
								
								restore_current_blog();
								
								$houseNew = new HouseSearch($key);
								
								$houseNew->displayHouse('pink', 356, $house['image'], $house['offer_details']);
															
							}
						}
					} else {
						
						$houseObject = $house['house'];
						
						$houseNew = new HouseSearch($houseObject->ID);
						
						$houseNew->displayHouse('pink', 356, $house['image'], $house['offer_details']);
					}
				}
				
				$number_of_ads = $specialoffers->advertCount($house_count);
				$specialoffers->prepareAds($number_of_ads, 'cotswolds');
				
				echo '</div>';
				
				//if ($house_count == 4) { $house_count = 0; }
				
			}
			
			wp_reset_postdata(); 
			
			echo '</div></div>';
*/
			
		}
	
	

	
	get_footer(); 
?>