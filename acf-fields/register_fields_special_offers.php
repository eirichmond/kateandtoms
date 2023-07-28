<?php 
register_field_group(array (
	'id' => '50a3be9bd08aa',
	'title' => 'Special Offers',
	'fields' => 
	array (
		0 => 
		array (
			'key' => 'field_5033f77a4a17a',
			'label' => 'Offer',
			'name' => 'offer_repeater',
			'type' => 'repeater',
			'order_no' => '0',
			'instructions' => '',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'sub_fields' => 
			array (
				0 => 
				array (
					'key' => 'field_5033f77a4a56e',
					'label' => 'Offer period name',
					'name' => 'offer_period_name',
					'type' => 'text',
					'default_value' => '',
					'formatting' => 'none',
					'order_no' => '0',
				),
				1 => 
				array (
					'key' => 'field_5033f77a4ad3c',
					'label' => 'Houses',
					'name' => 'houses',
					'type' => 'repeater',
					'sub_fields' => 
					array (
						// at this point we need an image to upload to this taxonomy
						0 => 
						array (
							'key' => 'field_offerimage290414',
							'label' => 'Image',
							'name' => 'image',
							'type' => 'image',
							'save_format' => 'id',
							'preview_size' => 'thumbnail',
							'order_no' => '0',
						),
						1 => 
						array (
							'key' => 'field_5033f77a4b125',
							'label' => 'House',
							'name' => 'house',
							'type' => 'post_object',
							'post_type' => 
							array (
								0 => 'houses',
							),
							'taxonomy' => 
							array (
								0 => 'all',
							),
							'allow_null' => '0',
							'multiple' => '0',
							'order_no' => '0',
						),
						2 => 
						array (
							'key' => 'field_5033f77a4b509',
							'label' => 'Offer details',
							'name' => 'offer_details',
							'type' => 'text',
							'default_value' => '',
							'formatting' => 'none',
							'order_no' => '1',
						),
						3 => 
						array (
							'key' => 'field_573c355f7e3ba',
							'label' => 'Offer Date',
							'name' => 'expiry_date',
							'type' => 'date_picker',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'display_format' => 'd/m/Y',
							'return_format' => 'Ymd',
							'first_day' => 5,
						),
					),
					'row_min' => '1',
					'row_limit' => '',
					'layout' => 'table',
					'button_label' => 'Add House',
					'order_no' => '1',
				),
			),
			'row_min' => '0',
			'row_limit' => '',
			'layout' => 'row',
			'button_label' => 'Add Offer Period',
		),
	),
	'location' => 
	array (
		'rules' => 
		array (
			0 => 
			array (
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'page-special-offers.php',
				'order_no' => '0',
			),
		),
		'allorany' => 'all',
	),
	'options' => 
	array (
		'position' => 'normal',
		'layout' => 'default',
		'hide_on_screen' => 
		array (
			0 => 'discussion',
		),
	),
	'menu_order' => 0,
));
?>