<?php

function special_offers_testing_callback() {
	global $post;

	if ( $post == null ) {
		return;
	}

	if ( $post->ID == 22983 ) {
		$specials = get_post_meta( $post->ID, 'special_offer' );
		// var_dump($specials);
		$display  = array();
		$nextweek = date( 'Ymd', strtotime( 'now + 1 week' ) );

		foreach ( $specials as $offer ) {
			// if(in_array('85548',$offer)) {

				$date = date( 'Ymd', strtotime( $offer['offer_date'] ) );

			if ( $nextweek > $date ) {
				$display[] = false;
			} else {
				$display[] = check_offer_availablitity_backend( $offer['offer_house'], $date );
			}

			// }

		}

		return $display;
	}
}


function my_acf_prepare_field( $field ) {
	global $post;
	$custom_datas = get_post_meta( $post->ID );
	foreach ( $custom_datas as $k => $v ) {

		if ( $v[0] == $field['value'] ) {
			$key      = str_replace( 'expiry_date', 'house', $k );
			$house_id = get_post_meta( $post->ID, $key, true );
			$display  = check_offer_availablitity( $house_id, $v[0] );
		}
	}

	$date_now = date( 'Ymd', strtotime( 'now + 1 week' ) );
	if ( $date_now > $field['value'] || $display == false ) {
		$field['wrapper'] = array(
			'class' => 'expired',
		);
	}

	return $field;
}

add_filter( 'acf/prepare_field/key=field_573c355f7e3ba', 'my_acf_prepare_field' );


function check_offer_availablitity_backend( $house_id, $expiry_date ) {

	$expiry_date = date( 'Ymd', strtotime( $expiry_date . ' + 1 day' ) );

	$availability_month = date( 'm-Y', strtotime( $expiry_date ) );
	$availability_day   = date( 'j', strtotime( $expiry_date ) );

	$custom_datas = get_post_meta( $house_id );

	foreach ( $custom_datas as $k => $v ) {

		if ( $v[0] == $availability_month ) {
			$index = filter_var( $k, FILTER_SANITIZE_NUMBER_INT );

			$availability_days = get_post_meta( $house_id, 'availability_calendar_' . $index . '_availability-days', true );
		}
	}

	$display = false;

	if ( $availability_days == null ) {
		$display = true;
	}

	if ( is_array( $availability_days ) ) {
		if ( ! in_array( $availability_day, $availability_days ) ) {
			$display = true;
		}
	}

	return $display;
}


function check_offer_availablitity( $house_id, $expiry_date ) {

	switch_to_blog( 11 );

	// this is wrong as it adds 1 to a numeric number and not a date
	// $expiry_date = $expiry_date + 1;
	$expiry_date = date( 'Ymd', strtotime( $expiry_date . ' + 1 day' ) );

	$availability_month = date( 'm-Y', strtotime( $expiry_date ) );
	$availability_day   = date( 'j', strtotime( $expiry_date ) );
	$availability_days  = array();

	$custom_datas = get_post_meta( $house_id );

	if ( $custom_datas ) {
		foreach ( $custom_datas as $k => $v ) {
			if ( $v[0] == $availability_month ) {
				$index             = filter_var( $k, FILTER_SANITIZE_NUMBER_INT );
				$availability_days = get_post_meta( $house_id, 'availability_calendar_' . $index . '_availability-days', true );
			}
		}
	}
	$display = false;

	if ( is_array( $availability_days ) ) {
		if ( ! in_array( $availability_day, $availability_days ) ) {
			$display = true;
		}
	}

	restore_current_blog();

	return $display;
}






$stats_count = 0;
function print_stats( $title, $color = 'white' ) {
	global $stats_count;
	$m = $title . ': ' . timer_stop( 0 ) . 's, ' . get_num_queries() . ' queries';
	debug_to_console( $m );
	++$stats_count;
}

// helper to debug to console
function debug_to_console( $data ) {
	if ( is_array( $data ) ) {
		$output = "<script>console.log( 'Debug: " . implode( ',', $data ) . "' );</script>";
	} else {
		$output = "<script>console.log( 'Debug: " . $data . "' );</script>";
	}
	echo $output;
}

function katntoms_excerpt_length( $length ) {
	return 80;
}

function katntoms_excerpt_more( $more ) {
	global $post;
	return ' <a href="' . get_permalink( $post->ID ) . '">Read more of this post...</a>';
}

// a function to return either kate and toms home link or mobile specific home url
function return_mobile_desktop_url() {
	$url = '';
	if ( wp_is_mobile() ) {
		$url = home_url( '/' );
	} else {
		$url = get_blogaddress_by_id( 11 );
	}
	return $url;
}

function get_img_description( $id ) {
	$image_querys = get_post( $id );
	$description  = $image_querys->post_content;
	return $description;
}

// Returns true if term is allowed to be shown from site options
function includeTerm( $term_id, $type ) {
	$include = get_term_meta( $term_id, 'include_in_search_filter', true );
	if ( $include == 'include' ) {
		return true;
	} else {
		return false;
	}

	$includeTerm_results = false;
	if ( $includeTerm_results === false ) {
		// Get all options once
		global $wpdb;
		$includeTerm_results = array();
		$blog_id             = get_current_blog_id();
		$site_trailing       = ( $blog_id == 1 ? '' : '_' . $blog_id );

		$results = $wpdb->get_results(
			'
			SELECT option_name, option_value
			FROM wp' . $site_trailing . "_options
			WHERE option_name LIKE '%_include_in_search_filter' AND
			option_name NOT LIKE '\_%'
		"
		);

		foreach ( $results as $result ) {
			$option_name = $result->option_name;
			$p1          = strpos( $option_name, '_' );

			$option_type    = substr( $option_name, 0, $p1 );
			$option_include = $result->option_value == 'include' ? true : false;
			$option_term_id = str_replace( '_include_in_search_filter', '', $option_name );
			$option_term_id = substr( $option_term_id, $p1 + 1 );
			$includeTerm_results[ $option_type ][ $option_term_id ] = $option_include;
		}
	}
	if ( array_key_exists( $type, $includeTerm_results ) ) {
		if ( array_key_exists( $term_id, $includeTerm_results[ $type ] ) ) {
			return $includeTerm_results[ $type ][ $term_id ];
		}
	}
	return true;
}

function getImage( $id, $size ) {
	// $imageURL = wp_get_attachment_image_src( $id, $size );
	$imageURL = str_replace( '.test', '.com', wp_get_attachment_image_src( $id, $size ) );

	$imageURL = str_replace( 'staging.', '', $imageURL );

	return $imageURL[0];
}

function getAlttag( $id ) {
	$alttag = get_post_meta( $id, '_wp_attachment_image_alt', true );
	if ( $alttag ) {
		return 'alt="' . $alttag . '"';
	}
	return;
}

/**
 * used to get srcset for mobile rendering
 *
 * @param ing    $id
 * @param string $size
 * @return void
 */
function getSrcset( $id, $size ) {

	$testurl   = 'kateandtoms.test';
	$stagedurl = 'staging.kateandtoms.com';

	switch ( wp_get_environment_type() ) {
		case 'local':
			$srcset = str_replace( $testurl, 'kateandtoms.com', wp_get_attachment_image_srcset( $id, $size ) );
			break;
		case 'staging':
			$srcset = str_replace( $stagedurl, 'kateandtoms.com', wp_get_attachment_image_srcset( $id, $size ) );
			break;
		default:
			$srcset = wp_get_attachment_image_srcset( $id, $size );
			break;
	}

	// $srcset = str_replace('.test', '.com', wp_get_attachment_image_srcset( $id, $size ));
	return $srcset;
}

function broadcast_updated_messages( $messages ) {
	$messages['houses'] = array( 1 => sprintf( __( 'Contact updated' ) ) );
}

function plugin_options_validate( $input ) {
	$input['text_string'] = wp_filter_nohtml_kses( $input['text_string'] );
	return $input;
}

function get_this_months_rate_headers( $property_rate ) {

	// having to hardcode to make wp-cli script work as it doesn't like global variables!!
	$katglobals = array(
		'rate_headers' => array(
			'20'  => '1 night midweek CV',
			'50'  => '2 night weekend',
			'60'  => '3 night weekend',
			'70'  => 'Week',
			'80'  => 'Midweek',
			'85'  => '2 night midweek',
			'90'  => '5 nights',
			'100' => '2 night weekend WV',
			'110' => '3 night weekend WV',
			'120' => 'Week WV',
			'130' => 'Midweek WV',
		),
	);

	$weeksrates = array();
	$hider      = true;
	foreach ( $property_rate['WeekPriceList'] as $k => $weeklys ) {
		if ( ! in_array( '-2', $weeklys['Amount'] ) ) {
			$hider = false;
		}
		$weeksrates[] = array_keys( $weeklys['Amount'] );
	}
	$result  = call_user_func_array( 'array_merge', $weeksrates );
	$results = array_unique( $result );

	foreach ( $results as $k => $v ) {
		if ( $hider == true && $v == 9 ) {
			unset( $results[ $k ] );
		}
	}

	$rates = array();
	$i     = 0;
	foreach ( $results as $k => $rate ) {
		$rates[] = $katglobals['rate_headers'][ $rate ];
		++$i;
	}
	while ( $k < 10 ) {
		++$k;
		$rates[ $k ] = '';
	}

	return $rates;
}

// TODO write comment
function resolve_kt_ipro_id( $post_id, $properties ) {
	foreach ( $properties as $property ) {

		if ( $post_id == $property['Reference'] ) {
			$id = $property['Id'];
			break;
		}
	}
	return $id;
}

function get_this_rate_ammount( $property_rate ) {
	global $katglobals;
	$rates = array();
	$i     = 0;
	foreach ( $property_rate['WeekPriceList'] as $k => $rate ) {

		$n = 1;

		foreach ( $rate['Amount'] as $key => $amount ) {

			if ( $amount == -1 ) {
				$rates[ $i ][ 'rate_' . $n ] = '-1';
			} elseif ( $amount == '-2' ) {
				$rates[ $i ][ 'rate_' . $n ] = '-2';
			} elseif ( $amount == 0 ) {
				// continue;
				$rates[ $i ][ 'rate_' . $n ] = '';
			} else {
				$astrix = strstr( $amount, '*' );
				if ( ! empty( $astrix ) || strstr( $amount, '+' ) ) {
					if ( ! empty( $astrix ) && strstr( $amount, '+' ) ) {
						$amount                      = str_replace( $astrix, '', $amount );
						$amount                      = str_replace( '+', '', $amount );
						$rates[ $i ][ 'rate_' . $n ] = '+' . number_format( $amount, 0, '', ',' ) . ' ' . $astrix;
					} elseif ( ! empty( $astrix ) ) {
						$amount                      = str_replace( $astrix, '', $amount );
						$rates[ $i ][ 'rate_' . $n ] = number_format( $amount, 0, '', ',' ) . ' ' . $astrix;
					} elseif ( strstr( $amount, '+' ) ) {
						$amount                      = str_replace( '+', '', $amount );
						$rates[ $i ][ 'rate_' . $n ] = '+' . number_format( floatval( $amount ), 0, '', ',' );
					}
				} else {
					$rates[ $i ][ 'rate_' . $n ] = number_format( floatval( $amount ), 0, '', ',' );
				}
			}

			++$n;
		}
		while ( $n <= 11 ) {
			$rates[ $i ][ 'rate_' . $n ] = '';
			++$n;
		}
		++$i;
	}
	$rates = serialize( $rates );
	return $rates;
}


function get_dates_of_week_from( $property_rate, $property_availability ) {

	$month = date( 'n', strtotime( $property_rate['Month'] ) );
	$year  = date( 'Y', strtotime( $property_rate['Month'] ) );

	$availables = $property_availability[ $year ][ $month ];

	if ( $availables ) {
		$counter  = count( $availables );
		$last_day = end( $availables );

		$days         = array();
		$is_available = false;
		foreach ( $availables as $k => $available ) {

			// echo '<pre>'; print_r($available); echo '</pre>';

			$available = strtolower( $available );

			if ( $available == 'b' || $available == 'u' || $available == 'c' || $available == 'ob' || $available == 'p' ) {
				$days[]       = $k;
				$is_available = true;
			}

			if ( $last_day == 'a' && $k == 1 && $available == 'a' ) {
				$is_available = false;
			} elseif ( $is_available && $available == 'a' ) {
				$days[]       = $k;
				$is_available = false;
			}
		}
	}

	return $days;
}

function get_published_house_titles() {

	global $wpdb;
	$tablename = $wpdb->prefix;
	$sql       = $wpdb->prepare( "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = %s ORDER BY post_title ASC", 'houses' );
	$results   = $wpdb->get_results( $sql, OBJECT );

	$houses = array();
	foreach ( $results as $house ) {
		$houses[ $house->ID ] = $house->post_title;
	}

	return $houses;
}

function display_period_name( $offer_house, $published_houses ) {

	$date = date( 'Ymd', strtotime( 'now + 1 week' ) );

	$i = 0;
	foreach ( $offer_house['houses'] as $house ) {

		$display = check_offer_availablitity( $house['house']->ID, $house['expiry_date'] );

		if ( $date < $house['expiry_date'] && $display && $i <= 0 ) {

			if ( in_array( $house['house']->post_title, $published_houses ) ) {

				echo '<h2 class="type_title span12">' . $offer_house['offer_period_name'] . '</h2>';
				++$i;
			}
		}
	}
}

function get_custom_post_class( $i ) {
	$classes = array(
		'span8',
		'span4',
	);
	$class   = $classes[ $i ];
	if ( empty( $class ) ) {
		$class = 'span3';
	}
	return $class;
}

function get_thumb_crop( $i ) {
	$postthumbs = array(
		'blog-wide',
		'blog-square',
	);
	$crop       = $postthumbs[ $i ];
	if ( $i > 1 ) {
		$crop = 'blog-square-wide';
	}
	return $crop;
}

function get_core_colour( $post_id ) {
	$core_color = get_post_meta( $post_id, 'core-color', true );
	if ( empty( $core_color ) ) {
		$core_color = 'color3';
	}
	return $core_color;
}

function get_attachment_id_from_url( $image_url ) {
	global $wpdb;

	// Escape the URL to prevent SQL injection.
	$image_url = esc_url_raw( $image_url );

	// Query the database to find the attachment ID based on the image URL.
	$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM wp_11_posts WHERE guid='%s'", $image_url ) );

	return $attachment_id;
}

add_action( 'check_image_url_alt', 'check_image_url_alt_callback', 10, 1 );
function check_image_url_alt_callback() {
	$image_url     = 'https://kateandtoms.com/wp-content/uploads/2013/05/14.-Gym-280x280.jpg';
	$attachment_id = get_attachment_id_from_url( $image_url );
}

/*
function litehouse_blog( $template ) {
	$litehouse_ids = array('24');

	$blog_id = get_current_blog_id();

	global $wp_query;

// print_r($wp_query);

	if ( in_array($blog_id, $litehouse_ids) && $wp_query->is_posts_page == 1) {
		$new_template = locate_template( array( 'index-litehouse.php' ) );
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}

	return $template;
}
add_filter( 'template_include', 'litehouse_blog', 99 );
*/


// For getting site menus
// $vals = array();
// $menu_slug = 'main-menu';
// foreach(wp_get_sites() as $site) {
// switch_to_blog($site['blog_id']);
// $locations = get_nav_menu_locations();

// $menu = wp_get_nav_menu_object( $locations[ $menu_slug ] );
// $menu_items = wp_get_nav_menu_items($menu->term_id);
// if(is_array($menu_items)) {
// foreach($menu_items as $k => $object) {
// $vals[$site['blog_id']][$k]['title'] = $object->title;
// $vals[$site['blog_id']][$k]['url'] = $object->url;
// }
// }
// else error_log('No menu for blog ID '.$site['blog_id']);
// }
// echo '<pre>';
// var_export($vals);
// exit;
