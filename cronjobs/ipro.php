<?php
/*
 * Get an array of post ids from the
 * houses table that only exist on kateandtoms.com.
 *
 * @return array  $results
 *
 */

// Script start
$rustart = getrusage();	

// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}


function get_all_house_ids() {
	global $wpdb;
	$tablename = $wpdb->prefix . 'posts';
	
/*
	$sql = $wpdb->prepare( "SELECT post_id FROM houses WHERE blog_id = 11 ORDER BY post_title ASC",$tablename );
	$results = $wpdb->get_results( $sql , ARRAY_A );
*/
	
	// $results = $wpdb->get_results( "SELECT ID FROM $tablename WHERE post_type = 'houses' AND (post_status = 'private' OR post_status = 'publish')" , ARRAY_A );
	$results = $wpdb->get_results( "SELECT ID FROM $tablename WHERE post_type = 'houses' AND (post_status = 'publish')" , ARRAY_A ); // updated 12 Oct 2018 not sure if we need private houses
	
	$results = wp_list_pluck($results, 'ID', true);
	
	return $results;

}


/*
 * Get iPro token
 *
 * @return string  $token
 *
 */

function get_ipro_token() {
	$request = new KT_iPro;
	$token = $request->get_access_token();
	
	$token = $token['access_token'];
	
	return $token;
}

/*
 * Get an array of reference ids from the iPro feed
 *
 * @return array  $results
 *
 */

function get_all_ipro_house_ids_refactor() {
	$request = new KT_iPro;
	$token = $request->get_access_token();
	
	$properties = $request->get_all_properties($token['access_token']);
	
	$references = array();

	foreach ($properties as $property) {
		$references[$property['Id']] = $property['Reference'];
	}
	
	return $references;
}

/**
 * Undocumented function
 *
 * @return void
 */
function get_updated_ipro_house_ids_refactor() {
	$request = new KT_iPro;
	$token = $request->get_access_token();
	$property_reference_lookup = $request->get_ipro_property_reference_lookup($token['access_token']);
	$property_reference_lookup = resolve_ipro_kt_ids($property_reference_lookup);
	
	$properties = $request->get_updated_properties($token['access_token']); // rates
	$properties = confirmed_rate_changed($properties);

	$property_availability_check = $request->get_updated_availability_properties($token['access_token']);
	$properties = confirmed_availability_changed($properties, $property_availability_check); // availability

	$properties = array_unique($properties);
	$references = array();
	foreach ($properties as $property) {
		$references[$property] = $property_reference_lookup[$property];
	}
	return $references;
}

function resolve_ipro_kt_ids($property_reference_lookup) {
	$array = array();
	foreach($property_reference_lookup as $k => $value) {
		$array[$value["PropertyId"]] = $value["PropertyReference"];
	}
	return $array;
}

/**
 * merge $property_availability_check in to $properties
 *
 * @param [type] $properties
 * @param [type] $property_availability_check
 * @return void
 */
function confirmed_availability_changed($properties, $property_availability_check) {
	foreach($property_availability_check["PropertyIDs"] as $k => $property) {
		$properties[] = $property;
	}
	return $properties;
}

/**
 * confirm if rate has changed and return set of iPro property IDs
 *
 * @param [type] $properties
 * @return void
 */
function confirmed_rate_changed($properties) {
	$array = array();
	foreach($properties as $k => $property) {
		// if($property["Details"]["Rates"] == "Yes") {
			$array[] = $property["PropertyId"];
		// }
	}
	return $array;
}

/*
 * Get an array of reference ids from the iPro feed
 *
 * @return array  $results
 *
 */

function get_all_ipro_house_ids() {
	$request = new KT_iPro;
	$token = $request->get_access_token();
	
	$properties = $request->get_all_properties($token['access_token']);
	
	$references = array();
	$i=0;
	foreach ($properties as $property) {
		$references[$i]['Id'] = $property['Id'];
		$references[$i]['ktId'] = $property['Reference'];
		$i++;
	}
	
	return $references;
}


/*
 * Get the months from the iPro feed
 * and change them to K&T format
 *
 * @return array  $months
 *
 */

function get_the_ipro_data_in_array($property_rates, $property_availability) {
	
/*
	echo '<pre>';
	print_r($property_rates);
	echo '</pre>';
*/

	$cal_index = count($property_rates);
	
	$calendar = array();
	
	$i=0;
	foreach ($property_rates as $property_rate) {
		$month_now = date('m-Y', strtotime('now'));
		$time_now = date_create_from_format('m-Y',$month_now);
		$from_here = date_create_from_format('m-Y',date('m-Y', strtotime($property_rate['Month'])));
		

		if ($from_here >= $time_now) {
								
			$calendar[$i]['index'] = date('Ym', strtotime($property_rate['Month']));
			$calendar[$i]['availability-days'] = get_dates_of_week_from($property_rate, $property_availability);
			$calendar[$i]['month'] = date('m-Y', strtotime($property_rate['Month']));
			$calendar[$i]['notes'] = $property_rate['Notes'];
			$calendar[$i]['rates'] = get_this_rate_ammount($property_rate);
			$calendar[$i]['rates_types'] = '11';
			$calendar[$i]['periods'] = get_this_months_rate_headers($property_rate);
	
			$i++;
			
		}
	}
	
	return $calendar;
}

/*
 * Update the custom rates table in the database
 * this modified function replicates the function availability_update_value()
 * found in /wp-content/themes/clubsandwich/core/save_house_metadata.php
 * the filter updates values upon save however does not update values when
 * using the native wp_update_post() function so note if anychanges are made here
 * they should be made in /wp-content/themes/clubsandwich/core/save_house_metadata.php
 * until that function is deprecated
 *
 * @param array  $value
 * @param int  $post_id
 * @param array  $field
 * @return null
 *
 */

function update_custom_rates_table($value, $post_id, $field) {
	
/*
	var_dump($value);
	var_dump($post_id);
	var_dump($field);
*/
	
	
	if ($field['key'] != 'field_availability_repeater') {
		return $value;
	}
	

	if (is_array($value)) {
		global $wpdb;
		$blog_id = get_current_blog_id();
		
		$vars = array(
			'table_name', // table_name
			array( // fields to include
				'blog_id' => $blog_id, 
				'post_id' => $post_id
			),
			array( // format: %s = string, %d = integer, %f = float
				'%d', // blog_id
				'%d'  // post_id
			));
		
		$wpdb->delete( 'rates', $vars[1], $vars[2] );
		
		$c = -1;
		
		foreach ($value as $k => $v) {
			
/*
	echo '<pre>';
	print_r($v);
	echo '</pre>';
*/
			
			$month = $v['month'];
			
			if ($month == '') {
				unset($value[$k]);
				continue;
			}

			$c++;
			
			$prices = $v['rates'];
			$meta_key = 'availability_calendar_'.$c.'_rates';
			
			
			$replaced = array();
			foreach($prices as $row => $vals) {
				foreach ($vals as $rate_id => $amount) {
					if (strpos($amount,',') == false) {
						$astrix = strstr($amount,'*');
						$amount = str_replace($astrix,'',$amount);
					    $amount = number_format((float)$amount);
					    if ($astrix) {
						   $amount .= '&nbsp;' . $astrix;
					    }
					}
					$replaced[$row][$rate_id] = $amount;
				}
			}
			
			$meta_value = serialize($replaced);
			
/*
			$result = update_post_meta($post_id, $meta_key, $meta_value);

			if ($result == false) {
				add_action( 'admin_notices', 'failed_to_save' );
			}
*/
			
			$rates = array();
			
			foreach ($v['rate_types'] as $rate_id => $period_array) {
				
				$period = $period_array['period'];
				
				if ($period != '') {

					for ($week_count=0; $week_count<6; $week_count++) {
						$a = intval($rate_id)+1;
						if (!empty($a)) {
							if (array_key_exists($week_count, $prices)) {
								$rates[$period][$week_count] = $prices[$week_count]['rate_'.$a];
							}
						}
					}
					
				}
			}
				
			// Rates
			$vars = array(
				'rates', // table_name
				array( // fields to include
					'blog_id' => $blog_id, 
					'post_id' => $post_id,
					'month' => $month,
					'rates' => serialize($rates)
				),
				array( // format: %s = string, %d = integer, %f = float
					'%d', // blog_id
					'%d', // post_id
					'%s', // month
					'%s'  // rates
	
				));
			//var_dump($vars); wp_die();
			
			$wpdb->insert($vars[0], $vars[1], $vars[2]);
			
			// Remove the rates from the field to be saved.
			unset($value[$k]['rates']);
		}
		
	}

}


function resolve_rates_hangover($post_id) {
	
	//var_dump($post_id);
	global $wpdb;
	
	$results = $wpdb->get_results(
	    "
	    SELECT meta_key 
	    FROM {$wpdb->prefix}postmeta 
	    WHERE meta_key
	    LIKE '%availability_calendar%'
	    AND post_id = $post_id
	    
	    ",
	    ARRAY_N
	);
	
	$results = array_map(function($value){
	
	    return $value[0];
	
	}, $results);
	
	foreach($results as $meta_key) {
		//delete_post_meta( $post_id, $meta_key );
		$wpdb->delete( $wpdb->prefix .'postmeta', array( 'post_id' => $post_id, 'meta_key' => $meta_key ), array( '%d', '%s' ) );
	}
	error_log('hangover removed!');
	//wp_die();
	
}

function update_custom_availability_table($post_id, $array) {
	$month = $array['month'];
	$availability = 'availability';

	global $wpdb;
	
	// update availability
	$availability_row = $wpdb->get_results(
	    "
	    SELECT availability_id, blog_id, post_id, month, booked_days 
	    FROM $availability 
	    WHERE post_id = $post_id
	    AND month = '$month'
	    ",
	    ARRAY_N
	);

	if ($availability_row) {
		$wpdb->update( 
			$availability, 
			array(  'booked_days' => serialize($array['availability-days']) ), 
			array( 'availability_id' => $availability_row[0][0] ), 
			array(  '%s' ), 
			array( '%d' ) 
		);
	} else {
		$wpdb->insert( 
			$availability,
			array( 
				'blog_id' => '11', 
				'post_id' => $post_id, 
				'month' => $month, 
				'booked_days' => serialize($array['availability-days']) 
			), 
			array( 
				'%s', 
				'%s', 
				'%s', 
				'%s'
			) 
		);
	}
	
	error_log($wpdb->show_errors());
	error_log($post_id . ' availability updated!');

}

function return_array_values($values) {
	$rates = array_values($values);
	$rates = array_filter($rates);
	return $rates;
}

function update_custom_rate_table($post_id, $rate_headers, $month, $new_rates ) {

	$timezone = get_option('timezone_string');
	$now = new DateTime('now', new DateTimeZone($timezone));
	$modified_date = $now->format('Y-m-d h:i:s');

	
	$new_array = array();
	foreach ($new_rates as $k => $v) {
		$new_array[] = return_array_values($v);
	}
	$rate_headers = return_array_values($rate_headers);
	
	$rates_array = array();
	foreach($rate_headers as $key => $value) {
		$rates_array[$value] = array_column($new_array, $key);
	}
		
	$rates = 'rates';
	global $wpdb;
	
	$rates_row = $wpdb->get_results(
	    "
	    SELECT pricing_id, blog_id, post_id, month, rates 
	    FROM $rates 
	    WHERE post_id = $post_id
	    AND month = '$month'
	    ",
	    ARRAY_N
	);
	
	if ($rates_row) {
		$wpdb->update( 
			$rates, 
			array( 'rates' => serialize($rates_array), 'month' => $month, 'modified_date' => $modified_date ), 
			array( 'pricing_id' => $rates_row[0][0] ), 
			array( '%s', '%s', '%s' ), 
			array( '%d' )
		);
	} else {
		$wpdb->insert( 
			$rates,
			array( 
				'blog_id' => '11', 
				'post_id' => $post_id, 
				'month' => $month, 
				'rates' => serialize($rates_array),
				'modified_date' => $modified_date
			), 
			array( 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s'
			) 
		);
	}

}

function sort_by_month($a, $b) {
    return $a['index'] - $b['index'];
}

function sync_ipro_refactor($update = null) {
	
	switch_to_blog( 11 );
	
	$now = date('h:i:s');
	WP_CLI::log( 'iPro Cron Log: ipro.php line 434 - sync_ipro_refactor fired at ' . $now );
	error_log('sync_ipro_refactor fired at ' . $now);
	
	$request = new KT_iPro;
	
//	$house_ids = get_all_house_ids();
	
// 	var_dump($house_ids);
	
	// error_log('function fired get_all_house_ids()');
	// echo '<pre>'; print_r($house_ids); echo '</pre>';  wp_die();
	// isolate update of single houses by creating an array of ids that require updating
    // $house_ids = array('41563' => '20355', '33101' => '14560');
	
	$properties = get_updated_ipro_house_ids_refactor(); // rates and availability

	
	// hack during testing
	// if($update) {
	// 	$properties = get_updated_ipro_house_ids_refactor();
	// } else {
	// 	$properties = get_all_ipro_house_ids_refactor();
	// }
	
	// isolate update of single houses by creating an array of ids that require updating
	// array equates to 'iPro id' => 'Kate and Toms ID'
	// $properties = array('49364' => '61135');
	
/*
	var_dump($properties);
	wp_die();
*/
	
	WP_CLI::log( 'iPro Cron Log: ipro.php line 459 - function fired get_all_ipro_house_ids_refactor()' );
	error_log('function fired get_all_ipro_house_ids_refactor()');

	
/*
	echo 'KtId to check in iPro (this is the Works house )';
	var_dump($house_ids);
	echo 'All of the ids provided by iPro, the Works ID is above and doesn\'t exist in iPro'; 
	echo '<pre>'; print_r($properties);echo '</pre>';
*/
	
	
	// Testing script * Testing script * Testing script * Testing script * Testing script * 
	// for the purpose of testing we shall just use one house with data
	// house id: 17794 = iPro: 28869
	

	// Testing script * Testing script * Testing script * Testing script * Testing script * 
	
	$nc = 0;
	foreach ($properties as $ipro_id => $post_id){
		
/*
		foreach ($properties as $iproproperty) {
			if (in_array($house_id, $iproproperty)) {
*/
				
				//$post_id = $house_id;
				
				$token = get_ipro_token();
				
				//$ipro_id = $iproproperty['Id'];
				
				$property_rates = $request->get_property_rates($token, $ipro_id);
				
				//var_dump($property_rates);
								
				$property_availability = $request->get_property_availability($token, $ipro_id);
				
				// var_dump($property_availability);
				
				$availability_general_text = $property_rates['AvailabilityNotes'];

				$calendar = get_the_ipro_data_in_array($property_rates['Rates'], $property_availability);
				usort($calendar,'sort_by_month');
				
				$month_now = date('m-Y', strtotime('now'));

				$index = count($calendar);
				
				update_post_meta($post_id, 'availability_general_text', $availability_general_text);
				// update_post_meta($post_id, '_availability_general_text', 'field_5092771903d83');
				
				// resolve_rates_hangover($post_id)
				// commented out to resolve removal of rates during cron update
				// monitor from 10th Jan 2024 to establish if this is needed and for what????????????
				// resolve_rates_hangover($post_id);
				
				update_post_meta($post_id, 'availability_calendar', $index);
				//update_post_meta($post_id, '_availability_calendar', 'field_availability_repeater');
			
				update_post_meta($post_id, 'price_details_extra', $index);
				//update_post_meta($post_id, '_price_details_extra', 'field_506456fd0aae4');
				
				for($x = 0; $x <= $index; $x++) {
					$time_now = date_create_from_format('m-Y',$month_now);
					$from_here = date_create_from_format('m-Y',$calendar[$x]['month']);
					
					if ($from_here >= $time_now) {


						$updated = update_post_meta($post_id, 'availability_calendar_'.$x.'_availability-days', $calendar[$x]['availability-days']);
						//update_post_meta($post_id, '_availability_calendar_'.$x.'_availability-days', sanitize_text_field('field_5021859cbf29c'));
						
						update_post_meta($post_id, 'availability_calendar_'.$x.'_month', sanitize_text_field($calendar[$x]['month']));
						//update_post_meta($post_id, '_availability_calendar_'.$x.'_month', sanitize_text_field('field_5021859cbf28d'));
						//WP_CLI::log( 'updated: availability_calendar_'.$x.'_month | ' . $calendar[$x]['month'] );
						
						update_post_meta($post_id, 'availability_calendar_'.$x.'_rates', sanitize_text_field($calendar[$x]['rates']));
						//update_post_meta($post_id, '_availability_calendar_'.$x.'_rates', sanitize_text_field('field_50267a79c0f4c'));
						//WP_CLI::log( 'updated: availability_calendar_'.$x.'_rates | ' . $calendar[$x]['rates'] );
						
						update_post_meta($post_id, 'availability_calendar_'.$x.'_rate_types', sanitize_text_field($calendar[$x]['rates_types']));
						//update_post_meta($post_id, '_availability_calendar_'.$x.'_rate_types', sanitize_text_field('field_50269e64dd9fa'));
						//WP_CLI::log( 'updated: availability_calendar_'.$x.'_rate_types | ' . $calendar[$x]['rates_types'] );
						
						update_post_meta($post_id, 'price_details_extra_'.$x.'_details_period', sanitize_text_field($calendar[$x]['notes']));
						//update_post_meta($post_id, '_price_details_extra_'.$x.'_details_period', sanitize_text_field('field_506456fd0b2b2'));
						//WP_CLI::log( 'updated: price_details_extra_'.$x.'_details_period | ' . $calendar[$x]['notes'] );
						
						update_post_meta($post_id, 'price_details_extra_'.$x.'_month_details', sanitize_text_field($calendar[$x]['month']));
						//update_post_meta($post_id, '_price_details_extra_'.$x.'_month_details', sanitize_text_field('field_506456fd0aecd'));
						//WP_CLI::log( 'updated: price_details_extra_'.$x.'_month_details | ' . $calendar[$x]['month'] );
						
						for($n = 0; $n <= $calendar[$x]['rates_types']; $n++) {
							update_post_meta($post_id, 'availability_calendar_'.$x.'_rate_types_'.$n.'_period', sanitize_text_field($calendar[$x]['periods'][$n]));
							//WP_CLI::log( 'updated: availability_calendar_'.$x.'_rate_types_'.$n.'_period | ' . $calendar[$x]['periods'][$n] );
							
							if ($n == 0) {
								$rate_headers = $calendar[$x]['periods'];
								$new_rates = unserialize($calendar[$x]['rates']);
								$month = $calendar[$x]['month'];
																		
								update_custom_rate_table($post_id, $rate_headers, $month, $new_rates);
							}
							
						}
						
						update_custom_availability_table($post_id, $calendar[$x]);
					}

					
				}
								
	
				// force the custom availablity table to update outside of the usual backend save
				$house_post = array(
					'ID' => $post_id,
				); 
				wp_update_post( $house_post );
				$now = date('h:i:s');
				WP_CLI::log( 'iPro Cron Log: House with ID ' . $post_id . ' saved and compete! | ' . $now );
				$nc++;
								

/*
			}
		}
*/
	
	}
	
	$now = date('h:i:s');
	WP_CLI::log( 'iPro Cron Log: Sync Finised at ' . $now . '. Updated '.$nc.' houses.');
	error_log('iPro Cron Log: Sync Finised at ' . $now);

	

/*
	$ru = getrusage();
	error_log('Availability Cron Finished');
	
	$input = rutime($ru, $rustart, "utime");
	$uSec = $input % 1000;
	$input = floor($input / 1000);
	
	$seconds = $input % 60;
	$input = floor($input / 60);
	
	$minutes = $input % 60;
	$input = floor($input / 60); 
	
	$message = 'This process used ' . $minutes . ':'.$seconds.' for its computations';
	error_log($message);
	$message .= 'It spent ' . rutime($ru, $rustart, "stime") . ' ms in system calls';
	error_log($message);
	
	wp_mail( 'erichmond72@gmail.com', 'Availability Cron Finished', $message );
*/

}
add_action( 'fire_ipro_sync_refactor', 'sync_ipro_refactor', 10, 1);


function sync_ipro() {
	
	$now = date('h:i:s');
	WP_CLI::log( 'iPro Cron Log: ipro.php line 634 - sync_ipro_refactor fired at ' . $now );
	error_log('iPro Cron Log: sync_ipro_refactor fired at ' . $now);
	
	global $katglobals;
	$rate_headers = $katglobals['rate_headers'];
	$request = new KT_iPro;
	
	$house_ids = get_all_house_ids();
	error_log('iPro Cron Log: function fired get_all_house_ids()');
	WP_CLI::log( 'iPro Cron Log: ipro.php line 643 - function fired get_all_house_ids()' );

	// echo '<pre>'; print_r($house_ids); echo '</pre>';  wp_die();
	// isolate update of single houses by creating an array of ids that require updating
	// $house_ids = array(19660);
	
	$properties = get_all_ipro_house_ids_refactor();
	// isolate update of single houses by creating an array of ids that require updating
	// array equates to 'iPro id' => 'Kate and Toms ID'
	// $properties = array('41851' => '21440', '33101' => '14560');

	error_log('iPro Cron Log: function fired get_all_ipro_house_ids()');
	WP_CLI::log( 'iPro Cron Log: ipro.php line 655 - function fired get_all_ipro_house_ids()' );
	
/*
	var_dump($properties);
	wp_die();
*/
	
/*
	echo 'KtId to check in iPro (this is the Works house )';
	var_dump($house_ids);
	echo 'All of the ids provided by iPro, the Works ID is above and doesn\'t exist in iPro'; 
	echo '<pre>'; print_r($properties);echo '</pre>';
*/
	
	
	// Testing script * Testing script * Testing script * Testing script * Testing script * 
	// for the purpose of testing we shall just use one house with data
	// house id: 17794 = iPro: 28869
	

	// Testing script * Testing script * Testing script * Testing script * Testing script * 
	
	$nc = 0;
	foreach ($properties as $ipro_id => $post_id){

//	foreach ($house_ids as $house_id){
		
/*
		foreach ($properties as $iproproperty) {
			if (in_array($house_id, $iproproperty)) {
*/
				
				//$post_id = $house_id;
				
				$token = get_ipro_token();
				
				//$ipro_id = $iproproperty['Id'];
				
				$property_rates = $request->get_property_rates($token, $ipro_id);
				
				//var_dump($property_rates);
								
				$property_availability = $request->get_property_availability($token, $ipro_id);
				
				//echo '<pre>'; print_r($property_availability); echo '</pre>';
				
				$availability_general_text = $property_rates['AvailabilityNotes'];

				$calendar = get_the_ipro_data_in_array($property_rates['Rates'], $property_availability);
								
				$month_now = date('m-Y', strtotime('now'));

				$index = count($calendar);
								
				update_post_meta($post_id, 'availability_general_text', $availability_general_text);
				update_post_meta($post_id, '_availability_general_text', 'field_5092771903d83');
				
/*
				error_log('firing resolve_rates_hangover()');
				resolve_rates_hangover($post_id);
*/
				
				update_post_meta($post_id, 'availability_calendar', $index);
				update_post_meta($post_id, '_availability_calendar', 'field_availability_repeater');
			
				update_post_meta($post_id, 'price_details_extra', $index);
				update_post_meta($post_id, '_price_details_extra', 'field_506456fd0aae4');
				
				for($x = 0; $x <= $index; $x++) {
					$time_now = date_create_from_format('m-Y',$month_now);
					$from_here = date_create_from_format('m-Y',$calendar[$x]['month']);
					
					if ($from_here >= $time_now) {

						update_post_meta($post_id, 'availability_calendar_'.$x.'_availability-days', $calendar[$x]['availability-days']);
						update_post_meta($post_id, '_availability_calendar_'.$x.'_availability-days', sanitize_text_field('field_5021859cbf29c'));
						
						update_post_meta($post_id, 'availability_calendar_'.$x.'_month', sanitize_text_field($calendar[$x]['month']));
						update_post_meta($post_id, '_availability_calendar_'.$x.'_month', sanitize_text_field('field_5021859cbf28d'));
						
						update_post_meta($post_id, 'availability_calendar_'.$x.'_rates', sanitize_text_field($calendar[$x]['rates']));
						update_post_meta($post_id, '_availability_calendar_'.$x.'_rates', sanitize_text_field('field_50267a79c0f4c'));
						
						update_post_meta($post_id, 'availability_calendar_'.$x.'_rate_types', sanitize_text_field($calendar[$x]['rates_types']));
						update_post_meta($post_id, '_availability_calendar_'.$x.'_rate_types', sanitize_text_field('field_50269e64dd9fa'));
						
						update_post_meta($post_id, 'price_details_extra_'.$x.'_details_period', sanitize_text_field($calendar[$x]['notes']));
						update_post_meta($post_id, '_price_details_extra_'.$x.'_details_period', sanitize_text_field('field_506456fd0b2b2'));
						
						update_post_meta($post_id, 'price_details_extra_'.$x.'_month_details', sanitize_text_field($calendar[$x]['month']));
						update_post_meta($post_id, '_price_details_extra_'.$x.'_month_details', sanitize_text_field('field_506456fd0aecd'));
						
						if (array_key_exists($x, $calendar)) {
							for($n = 0; $n <= $calendar[$x]['rates_types']; $n++) {
								update_post_meta($post_id, 'availability_calendar_'.$x.'_rate_types_'.$n.'_period', sanitize_text_field($calendar[$x]['periods'][$n]));
								update_post_meta($post_id, '_availability_calendar_'.$x.'_rate_types_'.$n.'_period', sanitize_text_field('field_5059d07f957f0'));
							}
						}

					}
					
				}

	
				$now = date('h:i:s');
				WP_CLI::log( 'iPro Cron Log: ipro.php line 760 - Updated '.get_the_title($post_id).' ID: ' .$post_id. ' with iPro: ' .$ipro_id );
				
				// force the custom availablity table to update outside of the usual backend save
				$house_post = array(
					'ID' => $post_id,
				); 
				wp_update_post( $house_post );
				$nc++;				

/*
			}
		}
*/
	}

	
	$now = date('h:i:s');
	WP_CLI::log( 'iPro Cron Log: ipro.php line 777 - sync_ipro_refactor finished at ' . $now . '. Updated '.$nc.' houses.' );


/*
	$ru = getrusage();
	echo '<h2>Availability Cron Finished</h2>';
	$message = '<p class="iproalert iproinfo">';
	$message .= "This process used " . rutime($ru, $rustart, "utime") . " ms for its computations\n";
	$message .= "It spent " . rutime($ru, $rustart, "stime") . " ms in system calls\n";
	$message .= '</p>';
	echo $message;
	
	wp_mail( 'erichmond72@gmail.com', 'Availability Cron Finished', $message );
*/

}
add_action( 'fire_ipro_sync', 'sync_ipro');


function sync_ipro_update_custom_rates_table() {
	global $katglobals;
	$rate_headers = $katglobals['rate_headers'];
	
	$request = new KT_iPro;
	
	$house_ids = get_all_house_ids();
	// isolate update of single houses by creating an array of ids that require updating
	// $house_ids = array(19660);
	
	$properties = get_all_ipro_house_ids();
		
	// isolate update of single houses by creating an array of ids that require updating
	// array equates to 'iPro id' => 'Kate and Toms ID'
	$properties = array('41851' => '21440', '33101' => '14560');
	

	// Testing script * Testing script * Testing script * Testing script * Testing script * 
	
	foreach ($house_ids as $house_id){
		
		foreach ($properties as $iproproperty) {
			if (in_array($house_id, $iproproperty)) {
				
				$post_id = $house_id;	
				
				// force the custom availablity table to update outside of the usual backend save
				$fields = get_field_objects($post_id);
																
				foreach ($fields as $field) {
					
					if ($field['key'] == 'field_availability_repeater') {
						
						update_custom_rates_table($field['value'], $post_id, $field);
						WP_CLI::log( 'iPro Cron Log: ipro.php line 830 - Updated '.get_the_title($house_id).' ID: ' .$house_id );
						
					}
					
				}

			}
		}
	}
		

	$ru = getrusage();
	echo '<p class="iproalert iproinfo">';
	echo "This process used " . rutime($ru, $rustart, "utime") .
	    " ms for its computations\n";
	echo "It spent " . rutime($ru, $rustart, "stime") .
	    " ms in system calls\n";
	echo '</p>';
	

}
add_action( 'fire_ipro_sync_update_custom_rates_table', 'sync_ipro_update_custom_rates_table');



