<?php
	
global $wpdb;
$tablename = $wpdb->prefix;

$sql = $wpdb->prepare( "SELECT house_id, blog_id, post_id, post_title, availability_option, availability_site_ref, availability_site_post_id FROM houses WHERE blog_id = 11 ORDER BY post_title ASC",$tablename );
$results = $wpdb->get_results( $sql , ARRAY_A );

/*
echo '<pre>';
print_r($results);
echo '</pre>';
wp_die();
*/

function update_meta_data($from_site, $from_id, $to_id) {
	
	switch_to_blog($from_site);
	$count = get_post_meta($from_id, 'availability_calendar', true);
	$acfcount = get_post_meta($from_id, '_availability_calendar', true);
	$availability_option = get_post_meta($from_id, 'availability_option', true);
	$acfavailability_option = get_post_meta($from_id, '_availability_option', true);
	restore_current_blog();
	update_post_meta($to_id, 'availability_calendar', $count);
	update_post_meta($to_id, '_availability_calendar', $acfcount);
	
	for ($i = 0; $i < $count; $i++) {
		
		switch_to_blog($from_site);
		$days = get_post_meta($from_id, 'availability_calendar_'.$i.'_availability-days', true);
		$acfdays = get_post_meta($from_id, '_availability_calendar_'.$i.'_availability-days', true);
		
		$month = get_post_meta($from_id, 'availability_calendar_'.$i.'_month', true);
		$acfmonth = get_post_meta($from_id, '_availability_calendar_'.$i.'_month', true);

		$rates = get_post_meta($from_id, 'availability_calendar_'.$i.'_rates', true);
		$acfrates = get_post_meta($from_id, '_availability_calendar_'.$i.'_rates', true);

		$rate_types = get_post_meta($from_id, 'availability_calendar_'.$i.'_rate_types', true); 
		$acfrate_types = get_post_meta($from_id, '_availability_calendar_'.$i.'_rate_types', true); 
		restore_current_blog();
		update_post_meta($to_id, 'availability_calendar_'.$i.'_rate_types', $rate_types);
		update_post_meta($to_id, '_availability_calendar_'.$i.'_rate_types', $acfrate_types);

		for ($n = 0; $n < $count; $n++) {
			
			switch_to_blog($from_site);

			$rate_types_period = get_post_meta($from_id, 'availability_calendar_'.$i.'_rate_types_'.$n.'_period', true);
			$acfrate_types_period = get_post_meta($from_id, '_availability_calendar_'.$i.'_rate_types_'.$n.'_period', true);

			restore_current_blog();
			
			update_post_meta($to_id, 'availability_calendar_'.$i.'_rate_types_'.$n.'_period', $rate_types_period);
			update_post_meta($to_id, '_availability_calendar_'.$i.'_rate_types_'.$n.'_period', $acfrate_types_period);
			
		}
		
		
		restore_current_blog();
		update_post_meta($to_id, 'availability_calendar_'.$i.'_availability-days', $days);
		update_post_meta($to_id, '_availability_calendar_'.$i.'_availability-days', $acfdays);

		update_post_meta($to_id, 'availability_calendar_'.$i.'_month', $month);
		update_post_meta($to_id, '_availability_calendar_'.$i.'_month', $acfmonth);
		
		update_post_meta($to_id, 'availability_calendar_'.$i.'_rates', $rates);
		update_post_meta($to_id, '_availability_calendar_'.$i.'_rates', $acfrates);


		
	}
	update_post_meta($to_id, 'availability_option', '0');
	update_post_meta($to_id, '_availability_option', $acfavailability_option);

	
	
	
	
}

foreach ($results as $result) {
	if ($result['availability_option'] == 1) {
		$from_post_id = $result['availability_site_post_id'];
				
		update_meta_data($result['availability_site_ref'], $from_post_id, $result['post_id']);
		$wpdb->update( 'houses', array('availability_option' => 0), array('house_id' => $result['house_id']) );
	}
}


$sql = $wpdb->prepare( "SELECT house_id, blog_id, post_id, post_title, availability_option, availability_site_ref, availability_site_post_id FROM houses WHERE blog_id = 11 ORDER BY post_title ASC",$tablename );
$results = $wpdb->get_results( $sql , ARRAY_A );


// pluck all titles and ids from blogid 11 as above
$post_titles = wp_list_pluck($results, 'post_title', true);
$post_ids = wp_list_pluck($results, 'post_id', true);

// create an array with the new data
$merged = array();
foreach ($post_ids as $k => $v) {
	$merged[$k]['post_title'] = $post_titles[$k];
	$merged[$k]['post_id'] = $v;
}

foreach ($merged as $k => $v) {
	$title = $v['post_title'];
	// get all houses other than those from blog id 11
	$sql = $wpdb->prepare( "SELECT house_id, blog_id, post_id, post_title, availability_site_post_id FROM houses WHERE blog_id != 11 AND post_title = '$title' ORDER BY post_title ASC",$tablename );
	//$zero = $wpdb->prepare( "SELECT house_id, blog_id, post_id, post_title, availability_site_post_id FROM houses WHERE blog_id = 11 AND post_title = '$title' ORDER BY post_title ASC",$tablename );
	
	$results = $wpdb->get_results( $sql , ARRAY_A );

	//$zero_kts = $wpdb->get_results( $zero , ARRAY_A );
	
	foreach ($results as $result) {
		$wpdb->update( 'houses', array('availability_option' => 1), array('house_id' => $result['house_id']) );
		$wpdb->update( 'houses', array('availability_site_ref' => 11), array('house_id' => $result['house_id']) );
		$wpdb->update( 'houses', array('availability_site_post_id' => $v['post_id']), array('house_id' => $result['house_id']) );
	}

}




/*
// pluck all titles and ids from blogid 11 as above
$post_titles = wp_list_pluck($results, 'post_title', true);
$post_ids = wp_list_pluck($results, 'post_id', true);

// create an array with the new data
$merged = array();
foreach ($post_ids as $k => $v) {
	$merged[$k]['post_title'] = $post_titles[$k];
	$merged[$k]['post_id'] = $v;
}

foreach ($merged as $k => $v) {
	$v['post_id'];
	$title = $v['post_title'];
	// get all houses other than those from blog id 11
	$sql = $wpdb->prepare( "SELECT house_id, blog_id, post_id, post_title, availability_site_post_id FROM houses WHERE blog_id != 11 AND post_title = '$title' ORDER BY post_title ASC",$tablename );
	$zero = $wpdb->prepare( "SELECT house_id, blog_id, post_id, post_title, availability_site_post_id FROM houses WHERE blog_id = 11 AND post_title = '$title' ORDER BY post_title ASC",$tablename );
	
	$results = $wpdb->get_results( $sql , ARRAY_A );

	$zero_kts = $wpdb->get_results( $zero , ARRAY_A );
	
	foreach ($results as $result) {
		$wpdb->update( 'houses', array('availability_site_post_id' => $v['post_id']), array('house_id' => $result['house_id']) );
	}

	foreach ($zero_kts as $zero_kt) {
		$wpdb->update( 'houses', array('availability_site_post_id' => 0), array('house_id' => $zero_kt['house_id']) );
	}

	var_dump($results);
	var_dump($zero_kts); 
}
*/

echo 'complete!';
wp_die();
