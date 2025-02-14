<?php
// save houses, delete houses, trash houses, publish future post

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */

add_action( 'admin_init', 'can_delete' );
add_action( 'save_post', 'save_houses_meta', 10, 3 );
add_filter( 'acf/load_value/name=availability_calendar', 'availability_load_value', 10, 3 );
add_filter( 'acf/update_value', 'availability_update_value', 0, 3 );
add_action( 'edited_location', 'refresh_location_table', 10, 2 );

// Added to remove any passed dates upon load
function availability_load_value( $value, $post_id, $field ) {

	if ( ! is_array( $value ) ) {
		return $value;
	}

	$options         = $field['sub_fields'][0]['choices'];
	$value_to_return = array();

	$c = -1;

	foreach ( $value as $k => $v ) {

		// Only keep months in the future which are options
		if ( ! empty( $v['field_5021859cbf28d'] ) ) {
			if ( array_key_exists( $v['field_5021859cbf28d'], $options ) ) {

				++$c;

				// Add the rates
				$meta_key = 'availability_calendar_' . $k . '_rates';

				$result = get_post_meta( $post_id, $meta_key );

				$prices = unserialize( $result[0] );

				$replaced = array();
				foreach ( $prices as $row => $vals ) {
					foreach ( $vals as $rate_id => $amount ) {
						$replaced[ $row ][ str_replace( 'rate_', 'field_', $rate_id ) ] = $amount;
					}
				}

				$v['field_50267a79c0f4c'] = $replaced;

				$value_to_return[ $c ] = $v;
			}
		}
	}

	// return
	return $value_to_return;
}

function availability_update_value( $value, $post_id, $field ) {

	if ( $field['key'] != 'field_availability_repeater' ) {
		return $value;
	}

	if ( is_array( $value ) ) {
		global $wpdb;
		$blog_id = get_current_blog_id();

		$vars = array(
			'table_name', // table_name
			array( // fields to include
				'blog_id' => $blog_id,
				'post_id' => $post_id,
			),
			array( // format: %s = string, %d = integer, %f = float
				'%d', // blog_id
				'%d',  // post_id
			),
		);

		// $wpdb->delete( 'rates', $vars[1], $vars[2] );

		$c = -1;

		foreach ( $value as $k => $v ) {

			$month = $v['field_5021859cbf28d'];

			if ( $month == '' ) {
				unset( $value[ $k ] );
				continue;
			}

			++$c;

			$prices   = $v['field_50267a79c0f4c'];
			$meta_key = 'availability_calendar_' . $c . '_rates';

			$replaced = array();
			foreach ( $prices as $row => $vals ) {
				foreach ( $vals as $rate_id => $amount ) {
					if ( strpos( $amount, ',' ) == false ) {
						$astrix = strstr( $amount, '*' );
						$amount = str_replace( $astrix, '', $amount );
						$amount = floatval( $amount );
						$amount = number_format( $amount );
						if ( ! empty( $astrix ) ) {
							$amount .= '&nbsp;' . $astrix;
						}
					}
					$replaced[ $row ][ str_replace( 'field_', 'rate_', $rate_id ) ] = $amount;
				}
			}

			$meta_value = serialize( $replaced );

			$result = update_post_meta( $post_id, $meta_key, $meta_value );

			if ( $result == false ) {
				add_action( 'admin_notices', 'failed_to_save' );
			}

			// $rates = array();

			// foreach ($v['field_50269e64dd9fa'] as $rate_id => $period_array) {

			// $period = $period_array['field_5059d07f957f0'];

			// if ($period != '') {

			// for ($week_count=0; $week_count<6; $week_count++) {
			// $a = intval($rate_id)+1;
			// $rates[$period][$week_count] = $prices[$week_count]['field_'.$a];
			// }

			// }
			// }

			// // Rates
			// $vars = array(
			// 'rates', // table_name
			// array( // fields to include
			// 'blog_id' => $blog_id,
			// 'post_id' => $post_id,
			// 'month' => $month,
			// 'rates' => serialize($rates)
			// ),
			// array( // format: %s = string, %d = integer, %f = float
			// '%d', // blog_id
			// '%d', // post_id
			// '%s', // month
			// '%s'  // rates

			// ));

			// $wpdb->insert($vars[0], $vars[1], $vars[2]);

			// unset($value[$k]['field_50267a79c0f4c']);
		}
	}

	return $value;
}

function failed_to_save() {
	?>
	<div class="error">
		<p>Failed to save rates data.</p>
	</div>
	<?php
}


function save_houses_meta( $post_id, $post, $update ) {

	// If this isn't a 'house' post, don't update it.
	if ( $post->post_type != 'houses' ) {
		return;
	}

	global $wpdb;

	$blog_id    = get_current_blog_id();
	$post_title = get_the_title( $post_id );
	$permalink  = get_post_permalink( $post_id );

	// See if already in
	$existingrecord = $wpdb->get_row( 'SELECT * FROM houses WHERE blog_id = ' . $blog_id . ' AND post_id = ' . $post_id );

	// Metadata
	$m = get_post_meta( $post_id );

	// Delete existing rows
	$vars = array(
		'table_name', // table_name
		array( // fields to include
			'blog_id' => $blog_id,
			'post_id' => $post_id,
		),
		array( // format: %s = string, %d = integer, %f = float
			'%d', // blog_id
			'%d',  // post_id
		),
	);

	$wpdb->delete( 'availability', $vars[1], $vars[2] );
	// $wpdb->delete( 'rates', $vars[1], $vars[2] );

	// availability_calendar is count of months
	$month_count = intval( $m['availability_calendar'][0] );

	for ( $c = 0; $c < $month_count; $c++ ) {

		// availability_calendar_0_month is the month
		$name  = 'availability_calendar_' . $c . '_month';
		$month = $m[ $name ][0];

		// availability_calendar_0_availability-days
		$name              = 'availability_calendar_' . $c . '_availability-days';
		$month_booked_days = $m[ $name ][0];

		if ( $month != null || $month_booked_days != null ) {

			// Availability
			$vars = array(
				'availability', // table_name
				array( // fields to include
					'blog_id'     => $blog_id,
					'post_id'     => $post_id,
					'month'       => $month,
					'booked_days' => $month_booked_days,
				),
				array( // format: %s = string, %d = integer, %f = float
					'%d', // blog_id
					'%d', // post_id
					'%s', // month
					'%s',  // booked_days

				),
			);

			$wpdb->insert( $vars[0], $vars[1], $vars[2] );

		}
	}

	// Remove from houses table if status is not published
	if ( get_post_status( $post_id ) == 'private' && ! empty( $existingrecord ) ) {
		// echo '<pre>'; print_r($_POST); echo '</pre>'; wp_die();
		return $wpdb->delete( 'houses', array( 'house_id' => $existingrecord->house_id ), array( '%d' ) );
	}

	// $post_thumbnail = get_the_post_thumbnail();
	$post_thumbnail = unserialize( $m['house_photos'][0] );
	$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail[0], 'house_search' );
	$post_thumbnail = $post_thumbnail[0];

	$locations = wp_get_object_terms( $post_id, 'location' );
	if ( ! empty( $locations ) ) {
		$vals = array();
		foreach ( $locations as $l ) {
			array_push( $vals, $l->slug );
		}
		$locations = serialize( $vals );
	} else {
		$locations = '';
	}

	// Use K&T as the centeral info for id and blog id

	if ( $blog_id == 11 ) {
		$m['availability_site_ref'][0]     = 11;
		$m['availability_site_post_id'][0] = $post_id;
	}

	$vars = array(
		'houses', // table_name
		array( // fields to include
			'blog_id'                   => $blog_id, // Ensures we have still changed sites
			'post_id'                   => $post_id,
			'post_title'                => $post_title,
			'permalink'                 => $permalink,
			'post_thumbnail'            => $post_thumbnail,
			'availability_option'       => $m['availability_option'][0],
			'availability_site_ref'     => $m['availability_site_ref'][0],
			'availability_site_post_id' => $m['availability_site_post_id'][0],
			'location'                  => $m['location'][0],
			'location_text'             => $m['location_text'][0],
			'locations'                 => $locations,
			'sleeps_min'                => $m['sleeps_min'][0],
			'sleeps_max'                => $m['sleeps_max'][0],
			'brief_description'         => $m['brief_description'][0],
			'brief_description_winter'  => $m['brief_description_winter'][0],
			'all_prices_with_from'      => $m['all_prices_with_from'][0],
		),
		array( // format: %s = string, %d = integer, %f = float
			'%d', // blog_id
			'%d', // post_id
			'%s', // post_title
			'%s', // permalink
			'%s', // post_thumbnail
			'%d', // availability_option
			'%d', // availability_site_ref
			'%d', // availability_site_post_id
			'%s', // location
			'%s', // location_text
			'%s', // locations
			'%f', // sleeps_min
			'%f', // sleeps_max
			'%s', // brief_description
			'%s', // brief_description_winter
			'%d',  // all_prices_with_from
		),
	);

	if ( get_post_status( $post_id ) == 'publish' && $existingrecord == null ) {
		$wpdb->insert( $vars[0], $vars[1], $vars[2] );
	} elseif ( ! empty( $existingrecord ) ) {
		$existing_id = $existingrecord->house_id;
		$vars[1]     = array_merge( array( 'house_id' => $existing_id ), $vars[1] );
		$vars[2]     = array_merge( array( '%d' ), $vars[2] );
		$wpdb->replace( $vars[0], $vars[1], $vars[2] );
	}

	// ID generated is given by $wpdb->insert_id
	return true;
}

function can_delete() {
	if ( current_user_can( 'delete_posts' ) ) {
		add_action( 'delete_post', 'delete_sync', 10 );
	}
}

function delete_sync( $post_id ) {
	global $wpdb;
	$blog_id = get_current_blog_id();

	$existingrecord = $wpdb->get_row( 'SELECT * FROM houses WHERE blog_id = ' . $blog_id . ' AND post_id = ' . $post_id );

	if ( $existingrecord != null ) {
		$wpdb->delete( 'houses', array( 'house_id' => $existingrecord->house_id ), array( '%d' ) );
	}

	return true;
}

function refresh_location_table( $term_id, $tt_id ) {
	// Put something here to update the table when a location field is changed
}
?>