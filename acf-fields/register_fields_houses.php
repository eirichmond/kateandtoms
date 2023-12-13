<?php
add_action('init', 'kat_register_fields_houses');
function kat_register_fields_houses() {

	$c = 0;

	$normal_widgets_fields = array (
		'key' => 'field_widgets',
		'label' => 'Widget Factory',
		'name' => 'widgets',
		'type' => 'flexible_content',
		'order_no' => '13',
		'instructions' => 'Use this to add widgets for a page.',
		'required' => '0',
		'layouts' =>
		array (
			kat_widget_row('standard_widget', 'Standard widget', 'standard_widget', $c++),
			kat_widget_row('standard_widget_hybrid', 'Standard widget hybrid', 'standard_widget_hybrid', $c++),
			kat_widget_row('video_widget', 'Video widget', 'video_widget', $c++),
			kat_widget_row('virtual_widget', 'Virtual widget', 'virtual_widget', $c++),
			kat_widget_row('image_set', 'Navigation Image Set', 'image_set', $c++),
			kat_widget_row('reviews_widget', 'Reviews Widget', 'reviews_widget', $c++),
			kat_widget_row('button_widget', 'Button Widget', 'button_widget', $c++),
			kat_widget_row('wide_widget', 'Wide Image Widget', 'wide_widget', $c++),
			kat_widget_row('matrix_widget', 'Matrix Widget', 'matrix_widget', $c++),
			kat_widget_row('matrix_widget_5', 'Matrix Widget x 5', 'matrix_widget_5', $c++),
			kat_widget_row('single_image_link', 'Single Image Link', 'single_image_link', $c++),
			kat_widget_row('faq_group', 'FAQs Group', 'faq_group', $c++),
			kat_widget_row('cta_widget', 'CTA', 'cta_widget', $c++),
			kat_widget_row('separator_widget', 'Separator Widget', 'separator_widget', $c++),

		),
		'sub_fields' =>
		array (

		),
		'button_label' => '+ Add Widget',
	);

	global $key_facts_fields;
	global $top_widgets_fields;
	global $bottom_widgets_fields;


	$periods = array (
		'' => '',
		'1 night event' => '1 night event',
		'1 night weekend' => '1 night weekend',
		'1 night midweek CV' => '1 night midweek CV',
		'2 night midweek' => '2 night midweek',
		'2 night weekend' => '2 night weekend',
		'3 night weekend' => '3 night weekend',
		'Week' => 'Week',
		'Midweek' => 'Midweek',
		'5 nights' => '5 nights',
		'2 night weekend WV' => '2 night weekend WV',
		'3 night weekend WV' => '3 night weekend WV',
		'Week WV' => 'Week WV',
		'Midweek WV' => 'Midweek WV',
	);

	$fields = array(
		kat_make_tab('Availability', 0),
		kat_radio('field_house_currency', 'Currency', 'house_currency', 99, array('&pound;','&euro;')),
		kat_radio('field_availability_option', 'Availability Option', 'availability_option', 1, array('On','Inherit','Off')),
		// kat_conditional('field_availability_option', '0',
		// 	kat_repeater('field_availability_repeater','Availability Calendar','availability_calendar', 'Add Month', 4,
		// 		array (
		// 			kat_select('field_5021859cbf28d', 'Month', 'month', 0, kat_dates_array()),
		// 			kat_field('day_picker', 'field_5021859cbf29c', 'Booked days', 'availability-days', 1),
		// 			kat_repeater('field_50269e64dd9fa', 'Rate Types', 'rate_types', 'Add Period', 2,
		// 				array(
		// 					kat_select('field_5059d07f957f0', 'Period', 'period', 0, $periods)
		// 				), 11, 11 // min and max
		// 			),
		// 			kat_repeater('field_50267a79c0f4c', 'Rates', 'rates', 'Add Rate', 3,
		// 				array (
		// 					kat_field('text', 'field_1', '', 'rate_1', 0),
		// 					kat_field('text', 'field_2', '', 'rate_2', 1),
		// 					kat_field('text', 'field_3', '', 'rate_3', 2),
		// 					kat_field('text', 'field_4', '', 'rate_4', 3),
		// 					kat_field('text', 'field_5', '', 'rate_5', 4),
		// 					kat_field('text', 'field_6', '', 'rate_6', 5),
		// 					kat_field('text', 'field_7', '', 'rate_7', 6),
		// 					kat_field('text', 'field_8', '', 'rate_8', 7),
		// 					kat_field('text', 'field_9', '', 'rate_9', 8),
		// 					kat_field('text', 'field_10', '', 'rate_10', 9),
		// 					kat_field('text', 'field_11', '', 'rate_11', 10)
		// 				), 6, 6 // min and max
		// 			)
		// 		)
		// 	)
		// ),


		// kat_conditional('field_availability_option', '0',
		// 	kat_repeater('field_506456fd0aae4','Price details','price_details_extra', 'Add Month', 5,
		// 		array (
		// 			kat_select('field_506456fd0aecd', 'Month', 'month_details',0, kat_dates_array()),
		// 			kat_field('textarea', 'field_506456fd0b2b2', 'Details', 'details_period', 1)
		// 		)
		// 	)
		// ),

		
		kat_conditional('field_availability_option', '1',
			kat_select('field_availability_site_ref', 'Site to inherit availability from', 'availability_site_ref', 2, listSites())
		),
		kat_conditional('field_availability_option', '1',
			kat_field('text', 'field_availability_site_post_id', 'Post ID', 'availability_site_post_id', 3)
		),
		kat_field('textarea', 'availability_general_text', 'Availability General Text', 'availability_general_text', 7),
		kat_make_tab('Photos', 7),
		kat_field('gallery', 'field_5019a076e2c0b', 'House Photos', 'house_photos', 8),
		kat_make_tab('Widgets', 11),
		$normal_widgets_fields,
		$key_facts_fields,
		$top_widgets_fields,
		$bottom_widgets_fields,
		kat_make_tab('Settings', 14),
		kat_field('text', 'field_506f01dee0f1c', 'Limit number of photos?', 'limit_num_photos', 15),
		kat_field('true_false', 'field_50646680c2939', 'All Prices with \'From\'', 'all_prices_with_from', 16),
		kat_field('true_false', 'field_506f0550ba423', 'Turn off \'Take a Tour\'?', 'turn_off_take_a_tour', 17),
		kat_make_tab('Key Facts', 18),
		kat_field('textarea', 'field_keyfacts_1', 'Column 1 (General Information)', 'keyfacts_1', 19),
		kat_field('textarea', 'field_keyfacts_2', 'Column 2 (Things To Do)', 'keyfacts_2', 20),
		kat_field('textarea', 'field_keyfacts_3', 'Column 3 (About your Booking)', 'keyfacts_3', 21),
		kat_make_tab('House Info', 22),
		kat_field('text', 'field_501ea79522371', (get_current_blog_id() ==  12 ? 'Wedding Size Min' : 'Sleeps Min'), 'sleeps_min', 23),
		kat_field('text', 'field_501ea79522734', (get_current_blog_id() ==  12 ? 'Wedding Size Max' : 'Sleeps Max'), 'sleeps_max', 24),
		kat_field('text', 'field_5021443fd6377', 'Location Text', 'location_text', 25),
		kat_field('text', 'field_50214cdd871ce', 'House Decription', 'brief_description', 26),
		kat_field('text', 'field_507411c39f133', 'House Decription (Winter)', 'brief_description_winter', 27),
		kat_relationship('field_50604d2b78a37', 'Related Houses', 'related_houses', array ('houses'), array ('all'), 6, 9),
		kat_field('google_map', 'field_506aac998bd6b', 'Location', 'location', 10),
	);

	$location_rules = array(
		array (
			'param' => 'post_type',
			'operator' => '==',
			'value' => 'houses',
			'order_no' => '0',
		),
	);

	$options = array (
		'position' => 'normal',
		'layout' => 'no_box',
		'hide_on_screen' =>
		array (
			'the_content',
			'comments',
			'discussion',
			'author'
		),
	);

	kat_register_field_group('houses_group', 'House Pages', $fields, $location_rules, $options);

}



?>