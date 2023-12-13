<?php
//add_action('init', 'kat_initialize_fields_helpers_widgets');
if( function_exists('acf_add_local_field_group') ):

//function kat_initialize_fields_helpers_widgets() {
	global $key_facts_fields;


	// House Pages
	$c = 0;
	$key_facts_fields = array (
		'key' => 'key_fact_field_widgets',
		'label' => 'KF Widget Factory',
		'name' => 'kf_widgets',
		'type' => 'flexible_content',
		'order_no' => '12',
		'instructions' => 'Use this to add widgets to Key Facts.',
		'required' => '0',
		'button_label' => '+ Add Widget',
		'sub_fields' => array (
			array ('key' => 'kf_field_widget_standard'),
			array ('key' => 'kf_field_widget_image_set'),
		),
		'layouts' => array (
			kat_widget_row('standard_widget', 'KF Standard widget', 'kf_standard_widget', $c++),
			kat_widget_row('image_set', 'KF Navigation Image Set', 'kf_image_set', $c++),
			kat_widget_row('button_widget', 'KF Button Widget', 'kf_button_widget', $c++),
			kat_widget_row('wide_widget', 'KF Wide Image Widget', 'kf_wide_widget', $c++),
			kat_widget_row('floorplan_widget', 'Floorplan Widget', 'floorplan_widget', $c++)
		),
	);


	$page_widgets_fields = array (
		'key' => 'field_widgets',
		'label' => 'Widget Factory',
		'name' => 'widgets',
		'type' => 'flexible_content',
		'order_no' => '13',
		'instructions' => 'Use this to add widgets for a page.',
		'required' => '0',
		'layouts' => array (
            kat_widget_row('standard_widget', 'Standard widget', 'standard_widget', $c++),
            kat_widget_row('script_widget', 'Script widget', 'script_widget', $c++),
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
			/** New Widgets for partners page */
			kat_widget_row('partner_header_section', 'Partner Header Section', 'partner_header_section', $c++),
			kat_widget_row('partner_content_section', 'Partner Content Section', 'partner_content_section', $c++),
			kat_widget_row('partner_vendor_stats_section', 'Partner Vendor Stats Section', 'partner_vendor_stats_section', $c++),
			kat_widget_row('partner_chequered_section', 'Partner Chequered Section', 'partner_chequered_section', $c++),
			kat_widget_row('partner_experts', 'Partner Experts Section', 'partner_experts', $c++),
			kat_widget_row('partner_features_section', 'Partner Features', 'partner_features_section', $c++),
			kat_widget_row('partner_image_text_section', 'Partner Image Text Section', 'partner_image_text_section', $c++),
			kat_widget_row('partner_steps_section', 'Partner Steps', 'partner_steps_section', $c++),
			kat_widget_row('partner_testimonials_section', 'Partner Testimonials', 'partner_testimonials_section', $c++),
			kat_widget_row('partner_stats_section', 'Partner Stats', 'partner_stats_section', $c++),
			kat_widget_row('partner_call_to_action', 'Partner Call to Action', 'partner_call_to_action', $c++),
			/** New Widgets for partners page */

			kat_widget_row('separator_widget', 'Separator Widget', 'separator_widget', $c++),

		),
		'sub_fields' => array (),
		'button_label' => '+ Add Widget',
	);

	// if( function_exists('acf_add_local_field_group') ):
	// 	acf_add_local_field_group(array(
	// 		'key' => 'ksjkdjfkjksdjfkjskdjfksjdfsd',
	// 		'title' => 'Widgets',
	// 		'fields' => array ($normal_widgets_fields),
	// 		'location' => array (
	// 			array (
	// 				array (
	// 					'param' => 'post_type',
	// 					'operator' => '==',
	// 					'value' => 'page',
	// 					'order_no' => '0',
	// 				),
	// 				array (
	// 					'param' => 'post_type',
	// 					'operator' => '==',
	// 					'value' => 'suppliers',
	// 					'order_no' => '0',
	// 				),
	// 				array (
	// 					'param' => 'post_type',
	// 					'operator' => '==',
	// 					'value' => 'seasonal',
	// 					'order_no' => '0',
	// 				),
	// 			),
	// 		),
	// 		'menu_order' => 2,
	// 		'position' => 'normal',
	// 	));
	// endif;

	acf_add_local_field_group(array (
		'id' => 'h98c24v8uw49ehg',
		'title' => 'Widgets',
		'fields' => array($page_widgets_fields),
		'location' => array (
			'rules' => array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => '0',
				),
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'suppliers',
					'order_no' => '0',
				),
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'seasonal',
					'order_no' => '0',
				),
			),
			'allorany' => 'any',
		),
		'options' =>
		array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' =>
			array (
				0 => 'the_content',
				1 => 'comments',
				2 => 'discussion',
			),
		),
		'menu_order' => 2,
	));

endif;
?>