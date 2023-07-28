<?php 
$periods_options = array (
	'Week' => 'Weeks',
	'2 night weekend' => 'Weekends (2 night)',
	'3 night weekend' => 'Weekends (3 night)',
	'Midweek' => 'Midweeks',
	'5 nights' => '5 nights (Christmas)',
);
	
$fields = array (
	kat_field('date_picker', 'field_506da7e524002', 'Beginning', 'beginning', 0),
	kat_field('date_picker', 'field_506da7e524668', 'Ending', 'ending', 1),
	kat_select('field_506da7e524b63', 'Periods to include', 'periods_to_include', 2, $periods_options, '1'),
);

$location_rules = array (
	array (
		'param' => 'post_type',
		'operator' => '==',
		'value' => 'seasonal',
		'order_no' => '0',
	),
);

$options = 	array (
	'position' => 'side',
	'layout' => 'default',
	'hide_on_screen' => 
	array (
		0 => 'excerpt',
		1 => 'custom_fields',
		2 => 'discussion',
		3 => 'comments',
		4 => 'author',
		5 => 'format',
	),
);

kat_register_field_group('seasonal_group', 'Seasonal settings', $fields, $location_rules, $options);

?>