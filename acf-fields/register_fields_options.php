<?php 
register_field_group(array (
	'id' => 'label_options',
	'title' => 'Labelling',
	'fields' => 
	array (
		0 => 
		array (
			'key' => 'field_label_keyfacts',
			'label' => 'House Page - \'Key Facts\'',
			'name' => 'label_keyfacts',
			'type' => 'text',
			'order_no' => '1',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => 'Key Facts',
			'formatting' => 'text',
		),
		1 => 
		array (
			'key' => 'field_label_availability',
			'label' => 'House Page - \'Availability\'',
			'name' => 'label_availability',
			'type' => 'text',
			'order_no' => '2',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => 'Availability',
			'formatting' => 'text',
		),
		2 => 
		array (
			'key' => 'field_label_thingstodo',
			'label' => 'House Page - \'Things To Do\'',
			'name' => 'label_thingstodo',
			'type' => 'text',
			'order_no' => '3',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => 'Things To Do',
			'formatting' => 'text',
		),
		3 => 
		array (
			'key' => 'field_label_kf_1',
			'label' => 'Key Facts Label - \'General Information\'',
			'name' => 'label_kf_1',
			'type' => 'text',
			'order_no' => '4',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => 'General Information',
			'formatting' => 'text',
		),
		4 => 
		array (
			'key' => 'field_label_kf_2',
			'label' => 'Key Facts Label - \'Things To Do\'',
			'name' => 'label_kf_2',
			'type' => 'text',
			'order_no' => '5',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => 'Things To Do',
			'formatting' => 'text',
		),
		5 => 
		array (
			'key' => 'field_label_kf_3',
			'label' => 'Key Facts Label - \'About your Booking\'',
			'name' => 'label_kf_3',
			'type' => 'text',
			'order_no' => '6',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => 'About your Booking',
			'formatting' => 'text',
		),
	),
	'location' => 
	array (
		'rules' => 
		array (
			0 => 
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-design',
				'order_no' => '1',
			),
		),
		'allorany' => 'all',
	),
	'options' => 
	array (
		'position' => 'normal',
		'layout' => 'box',
		'hide_on_screen' => 
		array (
		),
	),
	'menu_order' => 0,
));

register_field_group(array (
	'id' => '50a3be9bcbc1e',
	'title' => 'Design Options',
	'slug' => 'design-options',
	'fields' => 
	array (
		0 => 
		array (
			'key' => 'field_50ab8fa2cc99f',
			'label' => 'Logo',
			'name' => 'site_logo',
			'type' => 'image',
			'order_no' => '2',
			'instructions' => 'This is the logo used throughout the site. Make sure it\'s a good one!',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'rules' => 
				array (
					0 => 
					array (
						'field' => 'null',
						'operator' => '==',
						'value' => '',
					),
				),
				'allorany' => 'all',
			),
			'save_format' => 'url',
			'preview_size' => 'full',
		),
		1 => 
		array (
			'key' => 'field_css_file',
			'label' => 'Stylesheet',
			'name' => 'site_style',
			'type' => 'text',
			'order_no' => '7',
			'instructions' => 'Specify the stylesheet to use on this site. Must be located in <code>/wp-content/themes/clubsandwich/css/</code>.',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => 'bigcottage.css',
			'formatting' => 'html',
		),
		2 => 
		array (
			'choices' => 
			array (
				0 => 'Ajax search',
				1 => 'Availability search',
			),
			'key' => 'field_header_widgets',
			'label' => 'Header search widgets',
			'instructions' => 'Which widgets should be displayed in the header?',
			'name' => 'site_widgets',
			'type' => 'checkbox',
			'order_no' => '1',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
		),
		3 => 
		array (
			'key' => 'field_custom_header',
			'label' => 'Custom header code',
			'instructions' => 'Enter any custom code (such as stylesheets) that should be loaded into the <code>&lt;head&gt;</code> of all front-end pages.',
			'name' => 'site_custom_header',
			'type' => 'textarea',
			'formatting' => 'text',
			'order_no' => '1',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
		),
	),
	'location' => 
	array (
		'rules' => 
		array (
			0 => 
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-design',
				'order_no' => '0',
			),
		),
		'allorany' => 'all',
	),
	'options' => 
	array (
		'position' => 'normal',
		'layout' => 'box',
		'hide_on_screen' => 
		array (
		),
	),
	'menu_order' => 0,
));

?>