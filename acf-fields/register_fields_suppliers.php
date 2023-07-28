<?php 
	
$fields = array(
	kat_field('gallery', 'field_supplier_photos', 'Supplier Photos', 'supplier_photos', 0),
	kat_field('google_map', 'field_supplier_loc', 'Location', 'location', 1),
	kat_field('text', 'field_supplier_external', 'External name', 'external_name', 2),
	kat_field('text', 'field_supplier_loc_text', 'Location Text', 'location_text', 3),
	kat_field('text', 'field_supplier_desc', 'Supplier Description', 'brief_description', 4),
	kat_field('text', 'field_supplier_limit', 'Limit number of photos?', 'limit_num_photos', 5),
	kat_field('true_false', 'field_supplier_turn_off_tour', 'Turn off \'Take a Tour\'?', 'turn_off_take_a_tour', 6)
);

$location_rules = array (
	array (
		'param' => 'post_type',
		'operator' => '==',
		'value' => 'suppliers',
		'order_no' => '0',
	),
);

kat_register_field_group('supplier_pages', 'Supplier Settings', $fields, $location_rules);

?>