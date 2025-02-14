<?php
if( function_exists('acf_add_local_field_group') ):
$top_widgets_fields = array();

	global $top_widgets_fields;
	// tax Pages
	$c = 0;

	$top_widgets_fields = array (
		'key' => 'top_term_widgets',
		'label' => 'Top Widget Factory',
		'name' => 'top_widgets',
		'type' => 'flexible_content',
		'order_no' => '13',
		'instructions' => 'Use this to add widgets for a page.',
		'required' => '0',
		'layouts' => array (
			'standard_term_widget' => kat_term_widget_row('standard_term_widget', 'standard_widget', 'Standard widget', 'standard_widget', $c++),
/*
			kat_widget_row('standard_widget_hybrid', 'Standard widget hybrid', 'standard_widget_hybrid', $c++),
            kat_widget_row('video_widget', 'Video widget', 'video_widget', $c++),
            kat_widget_row('virtual_widget', 'Virtual widget', 'virtual_widget', $c++),
*/
			'term_trustpilot_micro_combo_widget' => kat_term_widget_row('term_trustpilot_micro_combo_widget', 'trustpilot_micro_combo_widget', 'Trustpilot Micro Combo', 'trustpilot_micro_combo_widget', $c++),
			'term_youtube' => kat_term_widget_row('term_youtube', 'youtube_widget', 'YouTube Feed', 'youtube_widget', $c++),
			'image_set_term_widget' => kat_term_widget_row('image_set_term_widget','image_set', 'Navigation Image Set', 'image_set', $c++),
// 			kat_widget_row('reviews_widget', 'Reviews Widget', 'reviews_widget', $c++),
			'button_widget_term_widget' => kat_term_widget_row('button_widget_term_widget', 'button_widget', 'Button Widget', 'button_widget', $c++),
			'wide_widget_term_widget' => kat_term_widget_row('wide_widget_term_widget', 'wide_widget', 'Wide Image Widget', 'wide_widget', $c++),
/*
			kat_widget_row('matrix_widget', 'Matrix Widget', 'matrix_widget', $c++),
			kat_widget_row('single_image_link', 'Single Image Link', 'single_image_link', $c++),
			kat_widget_row('faq_group', 'FAQs Group', 'faq_group', $c++),
			kat_widget_row('cta_widget', 'CTA', 'cta_widget', $c++),
			kat_widget_row('separator_widget', 'Separator Widget', 'separator_widget', $c++),
*/

		),
		'sub_fields' =>
		array (

		),
		'button_label' => '+ Add Widget',
	);


	acf_add_local_field_group(array (
		'id' => 'toptermwidgets',
		'title' => 'Top Widgets',
		'fields' => array($top_widgets_fields),
		'location' =>
		array (
			'rules' =>
			array (
/*
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => '0',
				),
*/
/*
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'suppliers',
					'order_no' => '0',
				),
*/
/*
				array (
					'param' => 'ef_taxonomy',
					'operator' => '==',
					'value' => 'location',
					'order_no' => '0',
				),
*/
				array (
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'feature',
					'order_no' => '0',
				),
/*
				array (
					'param' => 'ef_taxonomy',
					'operator' => '==',
					'value' => 'activity',
					'order_no' => '0',
				),
*/
/*
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'seasonal',
					'order_no' => '0',
				),
*/
				array (
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'size',
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
//}
?>
