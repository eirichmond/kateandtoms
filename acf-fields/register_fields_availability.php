<?php 
$periods_to_include_options = array (
	'Week' => 'Weeks',
	'2 night weekend' => 'Weekends (2 night)',
	'3 night weekend' => 'Weekends (3 night)',
	'Midweek' => 'Midweeks'
);

$rolling_options = array (
	'' => 'Select option',
	'1' => '1 Week',
	'4' => '4 Week',
	'6' => '6 Week',
	'8' => '8 Week',
	'12' => '12 Week',
);
	
$fields = array (
	kat_select('field_rolling_upcoming_period', 'Rolling upcoming period', 'rolling_upcoming_period', 1, $rolling_options ),
	kat_select('field_periods_to_include', 'Periods to include', 'periods_to_include', 2, $periods_to_include_options, '1'),
);

$location_rules = array (
	array (
		'param' => 'post_type',
		'operator' => '==',
		'value' => 'availability',
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

kat_register_field_group('availability_group', 'Availability settings', $fields, $location_rules, $options);

?>