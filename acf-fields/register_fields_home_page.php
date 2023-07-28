<?php 
	
$fields = array (
	kat_field('gallery', 'field_507fca418967e', 'Image Rotator', 'image_rotator', 0),
);

$location_rules = array (
	array (
		'param' => 'page_template',
		'operator' => '==',
		'value' => 'page-home.php',
		'order_no' => '0',
	),
);

kat_register_field_group('home_gallery', 'Home Gallery', $fields, $location_rules);

?>