<?php 
// Top bar with area over image
register_field_group(array (
	'id' => '50a4be6badbbb',
	'title' => 'Page Title Area',
	'fields' => 
	array (
		1 => 
		array (
			'key' => 'field_page_title_image',
			'label' => 'Title image',
			'name' => 'title_image',
			'type' => 'image',
			'order_no' => '0',
			'instructions' => 'Choose an image to replace the title with.',
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
		2 => 
		array (
			'key' => 'field_page_title_textarea',
			'label' => 'Title textarea',
			'name' => 'title_textarea',
			'type' => 'wysiwyg',
			'order_no' => '1',
			'instructions' => 'Specify any text to show to the right of the title. Use <code>$$</code> to split into two columns.',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => '',
			'formatting' => 'html',
			'toolbar' => 'basic',
			'media_upload' => 'no',
			'the_content' => 'no',
		),
		3 => 
		array (
			'choices' => 
			array (
				'color1' => 'Colour 1',
				'color2' => 'Colour 2',
				'color3' => 'Colour 3',
				'color4' => 'Colour 4',
				'color5' => 'Colour 5',
				'color6' => 'Colour 6',
				'color7' => 'Colour 7',
				'color8' => 'Colour 8',
				'color9' => 'Colour 9',
				'color10' => 'Colour 10',
				'color11' => 'Colour 11',
				'color12' => 'Colour 12',
				'color13' => 'Colour 13',
				'color14' => 'Colour 14',
				'color15' => 'Colour 15',
				'color16' => 'Colour 16',
			),
			'key' => 'field_page_title_color',
			'label' => 'Colour',
			'name' => 'title_color',
			'type' => 'color_radio',
			'layout' => 'horizontal',
			'default_value' => 'color16',
			'allow_null' => '0',
			'multiple' => '0',
			'order_no' => '3',
		),
	),
	'location' => 
	array (
		'rules' => 
		array (
			0 => 
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
				'order_no' => '0',
			),
			1 => 
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'suppliers',
				'order_no' => '0',
			),
			2 => 
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
		'position' => 'side',
		'layout' => 'no_box',
		'hide_on_screen' => 
		array (
		),
	),
	'menu_order' => 0,
));






register_field_group(array (
	'id' => '528f55a22298e',
	'title' => 'Search Filter',
	'fields' => 
	array (
		array (
			'key' => 'field_26',
			'label' => 'Include in search filter',
			'name' => 'include_in_search_filter',
			'type' => 'radio',
			'order_no' => 0,
			'instructions' => 'If this filter should show in the dropdown list',
			'required' => 0,
			'conditional_logic' => 
			array (
				'status' => 0,
				'rules' => 
				array (
					0 => 
					array (
						'field' => 'null',
						'operator' => '==',
					),
				),
				'allorany' => 'all',
			),
			'choices' => 
			array (
				'include' => 'include',
				'exclude' => 'exclude',
			),
			'default_value' => 'include',
			'layout' => 'horizontal',
		),
	),
	'location' => 
	array (
		'rules' => 
		array (
			0 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'feature',
				'order_no' => '0',
			),
			1 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'location',
				'order_no' => '1',
			),
			2 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'size',
				'order_no' => '2',
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
		),
	),
	'menu_order' => 0,
));
register_field_group(array (
	'id' => '50a3be9bd12b3',
	'title' => 'Taxonomy Additional Data',
	'fields' => 
	array (
		0 => 
		array (
			'key' => 'field_taxonomy_layout_option',
			'label' => 'Taxonomy Filter Layout Option',
			'name' => 'taxonomy_filter_layout_option',
			'type' => 'select',
			'order_no' => 0,
			'instructions' => 'Basic layout doesn\'t include widgets, Custom does include widgets.',
			'required' => 1,
			'conditional_logic' => 
			array (
				'status' => 0,
				'rules' => 
				array (
					0 => 
					array (
						'field' => 'null',
						'operator' => '==',
					),
				),
				'allorany' => 'all',
			),
			'choices' => 
			array (
				'basic' => 'Basic',
				'custom' => 'Custom',
			),
			'default_value' => 'basic',
			'allow_null' => 0,
			'multiple' => 0,
		),
		1 => 
		array (
			'key' => 'field_tax_title_override',
			'label' => 'House for override',
			'name' => 'house_for_override',
			'type' => 'text',
			'order_no' => 0,
			'instructions' => 'If this field is left empty the taxonomy will read "Houses for ..."',
			'required' => 0,
			'conditional_logic' => 
			array (
				'status' => 0,
				'rules' => 
				array (
					0 => 
					array (
						'field' => 'null',
						'operator' => '==',
					),
				),
				'allorany' => 'all',
			),
			//'default_value' => 'Houses for ',
			'formatting' => 'none',
		),
		2 => 
		array (
			'key' => 'field_tax_image',
			'label' => 'Large image',
			'name' => 'tax_intro_image',
			'type' => 'image',
			'save_format' => 'id',
			'preview_size' => 'thumbnail',
			'order_no' => '0',
		),
		3 => 
		array (
			'choices' => 
			array (
				'color1' => 'Colour 1',
				'color2' => 'Colour 2',
				'color3' => 'Colour 3',
				'color4' => 'Colour 4',
				'color5' => 'Colour 5',
				'color6' => 'Colour 6',
				'color7' => 'Colour 7',
				'color8' => 'Colour 8',
				'color9' => 'Colour 9',
				'color10' => 'Colour 10',
				'color11' => 'Colour 11',
				'color12' => 'Colour 12',
				'color13' => 'Colour 13',
				'color14' => 'Colour 14',
				'color15' => 'Colour 15',
				'color16' => 'Colour 16',
			),
			'key' => 'field_tax_title_color',
			'label' => 'Taxonomy Background Colour',
			'name' => 'tax_color_scheme',
			'type' => 'color_radio',
			'layout' => 'horizontal',
			'default_value' => 'color16',
			'allow_null' => '0',
			'multiple' => '0',
			'order_no' => '3',
		),
		4 => 
		array (
			'choices' => 
			array (
				'btn-1' => 'btn-1',
				'btn-2' => 'btn-2',
				'btn-3' => 'btn-3',
				'btn-4' => 'btn-4',
				'btn-5' => 'btn-5',
			),
			'key' => 'field_tax_button_color',
			'label' => 'Taxonomy Filter Button Colour',
			'name' => 'tax_filter_button_color_scheme',
			'type' => 'color_radio',
			'layout' => 'horizontal',
			'default_value' => 'btn-5',
			'allow_null' => '0',
			'multiple' => '0',
			'order_no' => '3',
		),
		5 => 
		array (
			'key' => 'field_taxonomy_title_image',
			'label' => 'Taxonomy intro image',
			'name' => 'taxonomy_intro_image',
			'type' => 'image',
			'order_no' => '0',
			'instructions' => 'Choose an image to replace the Taxonomy title with.',
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
		6 => 
		array (
			'key' => 'field_taxonomy_title_textarea',
			'label' => 'Taxonomy Textarea',
			'name' => 'taxonomy_textarea',
			'type' => 'wysiwyg',
			'order_no' => '1',
			'instructions' => 'Specify any text to show to the right of the title. Use <code>$$</code> to split into two columns.',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => '',
			'formatting' => 'html',
			'toolbar' => 'basic',
			'media_upload' => 'no',
			'the_content' => 'no',
		),
		7 => 
		array (
			'choices' => 
			array (
				'color1' => 'Colour 1',
				'color2' => 'Colour 2',
				'color3' => 'Colour 3',
				'color4' => 'Colour 4',
				'color5' => 'Colour 5',
				'color6' => 'Colour 6',
				'color7' => 'Colour 7',
				'color8' => 'Colour 8',
				'color9' => 'Colour 9',
				'color10' => 'Colour 10',
				'color11' => 'Colour 11',
				'color12' => 'Colour 12',
				'color13' => 'Colour 13',
				'color14' => 'Colour 14',
				'color15' => 'Colour 15',
				'color16' => 'Colour 16',
			),
			'key' => 'field_taxonomy_title_color',
			'label' => 'Colour',
			'name' => 'tax_banner_color',
			'type' => 'color_radio',
			'layout' => 'horizontal',
			'default_value' => 'color16',
			'allow_null' => '0',
			'multiple' => '0',
			'order_no' => '3',
		),
	),
	'location' => 
	array (
		'rules' => 
		array (
			0 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'feature',
				'order_no' => '0',
			),
			1 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'location',
				'order_no' => '1',
			),
			2 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'size',
				'order_no' => '2',
			),
			3 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'activity',
				'order_no' => '3',
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
		),
	),
	'menu_order' => 0,
));
register_field_group(array (
	'id' => '50a3be9bced2c',
	'title' => 'Size Search Page',
	'fields' => 
	array (
		0 => 
		array (
			'key' => 'field_5073ebfbc29b0',
			'label' => 'Minimum Sleeps',
			'name' => 'min_no',
			'type' => 'number',
			'order_no' => '0',
			'instructions' => 'Specify the minimum number required (leave blank for no limit)',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => '',
		),
		1 => 
		array (
			'key' => 'field_5073ebfbc30fa',
			'label' => 'Maximum Sleeps',
			'name' => 'max_no',
			'type' => 'number',
			'order_no' => '1',
			'instructions' => 'Specify the maximum number required (leave blank for no limit)',
			'required' => '0',
			'conditional_logic' => 
			array (
				'status' => '0',
				'allorany' => 'all',
				'rules' => false,
			),
			'default_value' => '',
		),
	),
	'location' => 
	array (
		'rules' => 
		array (
			0 => 
			array (
				'param' => 'ef_taxonomy',
				'operator' => '==',
				'value' => 'size',
				'order_no' => '0',
			),
		),
		'allorany' => 'all',
	),
	'options' => 
	array (
		'position' => 'normal',
		'layout' => 'no_box',
		'hide_on_screen' => 
		array (
		),
	),
	'menu_order' => 0,
));

?>