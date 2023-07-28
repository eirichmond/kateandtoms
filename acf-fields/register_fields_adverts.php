<?php 
// TO DO: Make the below only run when in admin
$options_locations = array(
	'cotswolds' => 'In the Cotswolds',
	'sea' => 'By the Sea',
	'country' => 'In the Country',
	'town' => 'Close to Town',
	'french' => 'French',
	'' => '',
);

function get_cross_promo_tags() {
	$tags['choices'] = array();
	$choices = get_option('options_cross_promotion_tags');
	$choices = explode("\n", $choices);
	$choices = array_map('trim', $choices);
	if( is_array($choices) )
	{
		$tags['choices'][ 'Select' ] = 'Select';
		foreach( $choices as $choice )
		{
			$tags['choices'][ $choice ] = $choice;
		}
	}
	return $tags['choices'];
}

$options_promo_tags = get_cross_promo_tags();

$fields = array (
	kat_repeater('field_50699560299c1', 'Adverts', 'adverts', 'New Advert', 0, 
		array(
			kat_field('image', 'field_506995c71b41a', 'Advert image', 'advert_image', 0),
			kat_select('field_5092a1350acca', 'Location', 'location', 1, $options_locations)
		)
	),
	kat_repeater('field_29', 'Cross Promotion Wide', 'cross_promotion_wide', 'New Cross Promotion', 1, 
		array(
			kat_field('image', 'field_30', 'Advert Image', 'advert_image', 0),
			kat_select('field_305', 'Advert Tagged', 'advert_tagged', 1, $options_promo_tags),
			kat_field('text', 'field_31', 'Link (with http)', 'advert_links_to', 2),
		)
	),
	kat_repeater('field_32', 'Cross Promotion Narrow', 'cross_promotion_narrow', 'New Cross Promotion', 2, 
		array(
			kat_field('image', 'field_33', 'Advert Image', 'advert_image', 0),
			kat_select('field_335', 'Advert Tagged', 'advert_tagged', 1, $options_promo_tags),
			kat_field('text', 'field_34', 'Link (with http)', 'advert_links_to', 2),
		)
	),
	kat_field('textarea', 'field_crosspromotags', 'Cross promotion tags', 'cross_promotion_tags', 3),
);

$location_rules = array (
	array (
		'param' => 'options_page',
		'operator' => '==',
		'value' => 'acf-options-adverts',
		'order_no' => 0,
	),
);

kat_register_field_group('54cfa4ccc187b', 'Adverts', $fields, $location_rules);

?>