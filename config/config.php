<?php

function mytheme_customize_register( $wp_customize ) {

	$wp_customize->add_section( 'mytheme_new_section_name' , array(
	    'title'      => __( 'Visible Section Name', 'mytheme' ),
	    'priority'   => 30,
	) );


}
add_action( 'customize_register', 'mytheme_customize_register' );

$seasonal_menu = array(
  0 => array(
    'title' => 'Search by Season',
    'url' => '#',
    'submenu' => array(
      array(
        'title' => 'February Half Term',
        'url' => 'https://kateandtoms.com/seasonal/february-half-term/'
      ),
      array(
        'title' => 'Spring Holidays',
        'url' => 'https://kateandtoms.com/seasonal/spring-cottage-holidays/'
      ),
      array(
        'title' => 'Easter Holidays',
        'url' => 'https://kateandtoms.com/seasonal/easter-availability/'
      ),
      array(
        'title' => 'May Day Holiday',
        'url' => 'https://kateandtoms.com/seasonal/may-day-bank-cottages/'
      ),
      array(
        'title' => 'May Half Term',
        'url' => 'https://kateandtoms.com/seasonal/may-half-term-availability/'
      ),
      array(
        'title' => 'Summer Holidays',
        'url' => 'https://kateandtoms.com/seasonal/summer-availability/'
      ),
      array(
        'title' => 'August Bank Holiday',
        'url' => 'https://kateandtoms.com/seasonal/august-bank-holiday-cottages/'
      ),
      array(
        'title' => 'Autumn Breaks',
        'url' => 'https://kateandtoms.com/seasonal/autumn-cottage-breaks/'
      ),
      array(
        'title' => 'October Half Term',
        'url' => 'https://kateandtoms.com/seasonal/october-half-term-holiday/'
      ),
      array(
        'title' => 'Winter Holidays',
        'url' => 'https://kateandtoms.com/seasonal/winter-cottage-holidays/'
      ),
      array(
        'title' => 'Christmas Cottages',
        'url' => 'https://kateandtoms.com/seasonal/christmas-holiday-cottages/'
      ),
      array(
        'title' => 'New Years Cottages',
        'url' => 'https://kateandtoms.com/seasonal/large-houses-to-rent-for-new-years-eve/'
      )
    )
  ),
  1 => array(
    'title' => 'Search by Month',
    'url' => '#',
    'submenu' => array(
      array(
        'title' => 'January',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-january/',    
      ),
      array(
        'title' => 'February',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-february/',    
      ),
      array(
        'title' => 'March',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-march/',    
      ),
      array(
        'title' => 'April',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-april/',    
      ),
      array(
        'title' => 'May',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-may/',    
      ),
      array(
        'title' => 'June',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-june/',    
      ),
      array(
        'title' => 'July',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-july/',    
      ),
      array(
        'title' => 'August',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-august/',    
      ),
      array(
        'title' => 'September',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-september/',    
      ),
      array(
        'title' => 'October',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-october/',    
      ),
      array(
        'title' => 'November',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-november/',    
      ),
      array(
        'title' => 'December',
        'url' => 'https://kateandtoms.com/seasonal/holiday-cottages-in-december/',    
      )
    )

  )
);

$location_by_regions = array(
  0 => array(
    'title' => 'Berkshire',
    'url' => '/regions/luxury-holiday-cottages-in-berkshire/',
  ),
  1 => array(
    'title' => 'Buckinghamshire',
    'url' => '/regions/luxury-holiday-cottages-in-buckinghamshire/',
  ),
  2 => array(
    'title' => 'Cornwall',
    'url' => '/luxury-cornwall-cottages/',
  ),
  3 => array(
    'title' => 'Cotswold',
    'url' => '/cotswold-cottages/',
  ),
  4 => array(
    'title' => 'Cumbria',
    'url' => '/regions/luxury-cottages-cumbria/',
  ),
  5 => array(
    'title' => 'Devon',
    'url' => '/regions/luxury-devon-cottages/',
  ),
  6 => array(
    'title' => 'Dorset',
    'url' => '/large-dorset-cottages/',
  ),
  7 => array(
    'title' => 'Essex',
    'url' => '/regions/large-group-accommodation-in-essex/',
  ),
  8 => array(
    'title' => 'Gloucestershire',
    'url' => '/regions/luxury-holiday-cottages-in-gloucestershire/',
  ),
  9 => array(
    'title' => 'Hampshire',
    'url' => '/regions/luxury-holiday-cottages-in-hampshire/',
  ),
  10 => array(
    'title' => 'Herefordshire',
    'url' => '/regions/luxury-cottages-in-herefordshire/',
  ),
  11 => array(
    'title' => 'Isle of Wight',
    'url' => '/coast/coastal-cottages-on-the-isle-of-wight/',
  ),
  12 => array(
    'title' => 'Kent',
    'url' => '/regions/luxury-holiday-cottages-in-kent/',
  ),
  13 => array(
    'title' => 'Lake District',
    'url' => '/lake-district-cottages/',
  ),
  14 => array(
    'title' => 'Lancashire',
    'url' => '/regions/holiday-cottages-in-lancashire/',
  ),
  15 => array(
    'title' => 'Lincolnshire',
    'url' => '/regions/luxury-holiday-cottages-in-lincolnshire/',
  ),
  16 => array(
    'title' => 'Norfolk',
    'url' => '/large-holiday-homes-in-norfolk/',
  ),
  17 => array(
    'title' => 'Northamptonshire',
    'url' => '/regions/holiday-cottages-in-northamptonshire/',
  ),
  18 => array(
    'title' => 'Northumberland',
    'url' => '/regions/luxury-cottages-in-northumberland/',
  ),
  19 => array(
    'title' => 'Oxfordshire',
    'url' => '/regions/luxury-cottages-oxfordshire/',
  ),
  20 => array(
    'title' => 'Peak District',
    'url' => '/regions/luxury-peak-district-cottages/',
  ),
  21 => array(
    'title' => 'Shropshire',
    'url' => '/regions/luxury-holiday-cottages-in-shropshire/',
  ),
  22 => array(
    'title' => 'Somerset',
    'url' => '/regions/luxury-holiday-cottages-in-somerset/',
  ),
  23 => array(
    'title' => 'Staffordshire',
    'url' => '/regions/holiday-cottages-in-staffordshire/',
  ),
  24 => array(
    'title' => 'Suffolk',
    'url' => '/regions/luxury-suffolk-cottages/',
  ),
  25 => array(
    'title' => 'Sussex',
    'url' => '/regions/large-houses-to-rent-in-sussex/',
  ),
  26 => array(
    'title' => 'Wales',
    'url' => '/large-holiday-homes-in-wales/',
  ),
  27 => array(
    'title' => 'Wiltshire',
    'url' => '/luxury-holiday-cottages-in-wiltshire/',
  ),
  28 => array(
    'title' => 'Warwickshire',
    'url' => '/regions/warwickshire/',
  ),
  29 => array(
    'title' => 'Worcestershire',
    'url' => '/regions/luxury-holiday-cottages-in-worcestershire/',
  ),
  30 => array(
    'title' => 'Yorkshire',
    'url' => '/regions/luxury-cottages-in-yorkshire/',
  ),
);
$location_by_town = array(
  0 => array(
    'title' => 'Bath',
    'url' => '/towns/holiday-cottages-in-bath/',
  ),
//   1 => array(
//     'title' => 'Blackpool',
//     'url' => '/towns/holiday-cottages-in-blackpool/',
//   ),
  2 => array(
    'title' => 'Canterbury',
    'url' => '/towns/holiday-cottages-in-canterbury/',
  ),
  3 => array(
    'title' => 'Cheltenham',
    'url' => '/towns/holiday-cottages-in-cheltenham/',
  ),
  4 => array(
    'title' => 'Chester',
    'url' => '/towns/holiday-cottages-in-chester/',
  ),
  5 => array(
    'title' => 'Dartmouth',
    'url' => '/towns/luxury-self-catering-cottages-in-dartmouth/',
  ),
  6 => array(
    'title' => 'Hastings',
    'url' => '/towns/holiday-cottages-in-hastings/',
  ),
  7 => array(
    'title' => 'Leeds',
    'url' => '/towns/holiday-cottages-in-leeds/',
  ),
  8 => array(
		'title' => 'Liverpool',
		'url' => '/towns/cottages-in-liverpool-to-rent/',
	),
	9 => array(
		'title' => 'London',
		'url' => '/towns/luxury-holiday-cottages-in-london/',
	),
  10 => array(
    'title' => 'Lyme Regis',
    'url' => '/towns/luxury-holiday-cottages-in-lyme-regis/',
  ),
  11 => array(
    'title' => 'Manchester',
    'url' => '/towns/holiday-cottages-in-manchester/',
  ),
  12 => array(
    'title' => 'Oxford',
    'url' => '/towns/holiday-cottages-in-oxford/',
  ),
  13 => array(
    'title' => 'Newquay',
    'url' => '/towns/luxury-cottages-in-newquay/',
  ),
  14 => array(
    'title' => 'Salcombe',
    'url' => '/towns/luxury-cottages-in-salcombe/',
  ),
  15 => array(
    'title' => 'Stratford upon Avon',
    'url' => '/holiday-cottages-in-stratford-upon-avon/',
  ),
  16 => array(
    'title' => 'Portsmouth',
    'url' => '/towns/luxury-cottages-in-portsmouth/',
  ),
);
$location_by_coast = array(
  0 => array(
    'title' => 'Cornwall',
    'url' => '/coast/coastal-cottages-in-cornwall/',
  ),
  1 => array(
    'title' => 'Cumbria',
    'url' => '/coastal-cottages/coastal-cottages-in-cumbria/',
  ),
  2 => array(
    'title' => 'Devon',
    'url' => '/coast/coastal-cottages-in-devon/',
  ),
  3 => array(
    'title' => 'Dorset',
    'url' => '/coast/coastal-cottages-in-dorset/',
  ),
  4 => array(
    'title' => 'Essex',
    'url' => '/coast/coastal-cottages-in-essex/',
  ),
  5 => array(
    'title' => 'Hampshire',
    'url' => '/coast/coastal-cottages-in-hampshire/',
  ),
  6 => array(
    'title' => 'Isle of Wight',
    'url' => '/coast/coastal-cottages-on-the-isle-of-wight/',
  ),
  7 => array(
    'title' => 'Norfolk',
    'url' => '/coast/coastal-cottages-in-norfolk/',
  ),
  8 => array(
    'title' => 'Northumberland',
    'url' => '/coast/coastal-cottages-in-northumberland/',
  ),
  9 => array(
    'title' => 'Somerset',
    'url' => '/coast/coastal-cottages-in-somerset/',
  ),
  10 => array(
    'title' => 'Sussex',
    'url' => '/coast/coastal-cottages-in-sussex/',
  ),
  11 => array(
    'title' => 'Suffolk',
    'url' => '/coastal-cottages/holiday-cottages-on-the-suffolk-coast/',
  ),
  12 => array(
    'title' => 'Wales',
    'url' => '/coast/coastal-cottages-in-wales/',
  ),
);

// Define anything here that we will reuse
$mobile_menu_items = array(
	array('title' => 'Search All', 'url' => '/houses/'),
	array('title' => 'Search House Name', 'url' => '#', 'id' => 'msbhslide'),
	array('title' => 'Offers', 'url' => '/special-offers/'),
);

$kateandtoms_menu_items = array(
	array('title' => 'Search Date', 'url' => '#', 'id' => 'msbdslide'),
	array('title' => 'Search Sizes', 'url' => '/sizes/'),
	array('title' => 'Search Location', 'url' => '/location/'),
	array('title' => 'Search Feature', 'url' => '/feature/'),
	array('title' => 'Owners', 'url' => 'https://partners.kateandtoms.com/'),
	array('title' => 'Party', 'url' => '/party-houses/'),
	array('title' => 'New', 'url' => '/new-to-kate-toms/'),
	//array('title' => 'Discover', 'url' => '/explore/'),
);

//var_dump($kateandtoms_menu_items);

$kateandtoms_mobile_menu_items = array(
	array('title' => 'Search All', 'url' => '/houses/'),
	array('title' => 'Search House Name', 'url' => '#', 'id' => 'msbhslide'),
	array('title' => 'Search Date', 'url' => '#', 'id' => 'msbdslide'),
	array(
    'title' => 'Search Seasonal',
    'url' => '#',
    'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
    'submenu' => $seasonal_menu
  ),

	array(
    'title' => 'Search Sizes',
    'url' => '#',
    'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
    'submenu' => array(
      0 => array(
        'title' => 'Group Size',
        'url' => '#',
        'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
        'submenu' => array(
          0 => array(
            'title' => 'Sleeps up to 8',
            'url' => '/size/holiday-cottages-sleeping-8/',
          ),
          1 => array(
            'title' => 'Sleeps up to 10',
            'url' => '/size/holiday-cottages-sleeping-10/',
          ),
          2 => array(
            'title' => 'Sleeps up to 12',
            'url' => '/size/holiday-cottages-sleeping-12/',
          ),
          3 => array(
            'title' => 'Sleeps up to 14',
            'url' => '/size/holiday-cottages-sleeping-14/',
          ),
          4 => array(
            'title' => 'Sleeps up to 15',
            'url' => '/size/holiday-cottages-sleeping-15/',
          ),
          5 => array(
            'title' => 'Sleeps up to 16',
            'url' => '/size/holiday-cottages-sleeping-16/',
          ),
          6 => array(
            'title' => 'Sleeps up to 17',
            'url' => '/size/holiday-cottages-sleeping-17/',
          ),
          7 => array(
            'title' => 'Sleeps up to 18',
            'url' => '/size/holiday-cottages-sleeping-18/',
          ),
          8 => array(
            'title' => 'Sleeps up to 20',
            'url' => '/size/holiday-cottages-sleeping-20/',
          ),
          9 => array(
            'title' => 'Sleeps up to 22',
            'url' => '/size/holiday-cottages-sleeping-22/',
          ),
          10 => array(
            'title' => 'Sleeps up to 24',
            'url' => '/size/holiday-cottages-sleeping-24/',
          ),
          11 => array(
            'title' => 'Sleeps up to 25',
            'url' => '/size/holiday-cottages-sleeping25/',
          ),
          12 => array(
            'title' => 'Sleeps up to 30',
            'url' => '/size/holiday-cottages-sleeping-30/',
          ),
          13 => array(
            'title' => 'Sleeps up to 40',
            'url' => '/size/holiday-cottages-sleeping-40/',
          ),
        ),    
      ),
      1 => array(
        'title' => 'Bedrooms',
        'url' => '#',
        'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
        'submenu' => array(
          0 => array(
            'title' => '5 Bedrooms',
            'url' => '/sizes/5-bedroom-holiday-homes',
          ),
          1 => array(
            'title' => '6 Bedrooms',
            'url' => '/sizes/6-bedroom-holiday-homes',
          ),
          2 => array(
            'title' => '7 Bedrooms',
            'url' => '/sizes/7-bedroom-holiday-homes',
          ),
          3 => array(
            'title' => '8 Bedrooms',
            'url' => '/sizes/8-bedroom-holiday-homes',
          ),
          4 => array(
            'title' => '9 Bedrooms',
            'url' => '/sizes/9-bedroom-holiday-homes',
          ),
          5 => array(
            'title' => '10 Bedrooms',
            'url' => '/sizes/10-bedroom-holiday-houses',
          ),
          6 => array(
            'title' => '11 Bedrooms',
            'url' => '/11-bedroom-holiday-cottages/',
          ),
          7 => array(
            'title' => '12+',
            'url' => '/12-bedroom-luxury-houses-to-rent/',
          ),
        ),    
      ),

    )

  ),
	//array('title' => 'Search Location', 'url' => '/location/'),
	array (
    'title' => 'Search Location',
    'url' => '#',
    'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
    'submenu' => array(
        0 => array(
          'title' => 'Search by Regions',
          'url' => '#',
          'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
          'submenu' => $location_by_regions
        ),
        1 => array(
          'title' => 'Search by Towns',
          'url' => '#',
          'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
          'submenu' => $location_by_town
        ),
        2 => array(
          'title' => 'Search by Coast',
          'url' => '#',
          'icon' => '<i class="glyphicon glyphicon-chevron-right"></i>',
          'submenu' => $location_by_coast
        ),
    )
  ),
	array('title' => 'Search Feature', 'url' => '/feature/'),
	array('title' => 'Party Houses', 'url' => '/party-houses/'),
	array('title' => 'New Houses', 'url' => '/new-to-kate-toms/'),
	//array('title' => 'Discover', 'url' => '/explore/'),
	array('title' => 'Special Offers', 'url' => '/special-offers/'),
	array('title' => 'Owners', 'url' => 'https://partners.kateandtoms.com/')
);

$sites_for_global_menus = array(
	array('title' => 'Big Cottage Company', 'url' => 'http://bigcottage.com'),
	array('title' => 'Country Houses', 'url' => 'http://luxurycountryhouses.kateandtoms.com/'),
	array('title' => 'Holidays', 'url' => 'https://holidays.kateandtoms.com/'),
	array('title' => 'Party Weekends', 'url' => 'https://partyweekends.kateandtoms.com/'),
);




// Global settings go below

/*
	 @TODO
	 urls need to be more dynamic as migration between live, local and staged
	 are going to be difficult being hard coded like this.
*/

$katglobals =

array (
  'legacy_footer_menu_main' =>
  array (
      array('url' => 'https://kateandtoms.com/about-us/', 'title' => 'about us',),
      array('url' => 'https://kateandtoms.com/our-blog/', 'title' => 'our blog',),
      array('url' => 'https://docs.kateandtoms.com/packs/kateandtoms-privacy-policy2-March-24.pdf', 'title' => 'privacy policy',),
      array('url' => 'https://docs.kateandtoms.com/packs/kateandtoms-cookie-policy-March-24.pdf', 'title' => 'cookie policy',),
      array('url' => 'https://docs.kateandtoms.com/packs/kateandtoms-terms-and-conditions-March-2024.pdf', 'title' => 'terms and conditions',),
      array('url' => 'https://kateandtoms.com/company-information', 'title' => 'company information',),
      array('url' => 'https://kateandtoms.com/faqs/', 'title' => 'faqs',),
      array('url' => 'https://kateandtoms.com/meet-the-team/', 'title' => 'meet the team',),
  ),
  'legacy_footer_menu_destinations' =>
  array (
      array('url' => 'https://kateandtoms.com/lake-district-cottages/', 'title' => 'lake district cottages',),
      array('url' => 'https://kateandtoms.com/location/cotswolds/', 'title' => 'cotswold cottages',),
      array('url' => 'https://kateandtoms.com/location/cornwall/', 'title' => 'luxury cornwall cottages',),
      array('url' => 'https://kateandtoms.com/location/devon-cottages/', 'title' => 'cottages in devon',),
      array('url' => 'https://kateandtoms.com/location/liverpool/', 'title' => 'liverpool party apartments',),
  ),
  'legacy_footer_menu_join_us' =>
  array (
      array('url' => 'https://partners.kateandtoms.com/holiday-house-owners/', 'title' => 'holiday house owners',),
      array('url' => 'https://kateandtoms.com/join-us', 'title' => 'careers'),
      //array('url' => 'https://partners.kateandtoms.com/activity-providers/', 'title' => 'activity providers',),
      //array('url' => 'https://partners.kateandtoms.com/jobs-and-internships/', 'title' => 'jobs and internships',),
  ),
  'site_desktop_menus_default' =>
  array (
    0 =>
    array (
      'title' => 'Big Holidays',
      'url' => '/big-holidays/',
    ),
    1 =>
    array (
      'title' => 'Big Weekends',
      'url' => '/big-weekends/',
    ),
    2 =>
    array (
      'title' => 'Find a Big Cottage',
      'url' => '/find-a-big-cottage/',
    ),
    3 =>
    array (
      'title' => 'Offers',
      'url' => '/special-offers/',
    ),
  ),
  'site_desktop_menus' =>
  array (
    4 =>
    array (
      0 =>
      array (
        'title' => 'Country Houses',
        'url' => '/find-a-country-house/',
      ),
      1 =>
      array (
        'title' => 'Weddings',
        'url' => 'https://weddings.kateandtoms.com/',
      ),
      2 =>
      array (
        'title' => 'Corporate Events',
        'url' => 'https://events.kateandtoms.com',
      ),
      3 =>
      array (
        'title' => 'Offers',
        'url' => '/special-offers/',
      ),
    ),
    5 =>
    array (
      0 =>
      array (
        'title' => 'Party Houses',
        'url' => '/party-houses/',
      ),
      1 =>
      array (
        'title' => 'Activities',
        'url' => '/hen-activities/',
      ),
      2 =>
      array (
        'title' => 'Destinations',
        'url' => '/destinations/',
      ),
      3 =>
      array (
        'title' => 'Offers',
        'url' => '/special-offers/',
      ),
    ),
    6 =>
    array (
      0 =>
      array (
        'title' => 'PARTY HOUSES',
        'url' => '/party-houses/',
      ),
      1 =>
      array (
        'title' => 'ACTIVITIES',
        'url' => '/stag-activities/',
      ),
      2 =>
      array (
        'title' => 'DESTINATIONS',
        'url' => '/destinations/',
      ),
      3 =>
      array (
        'title' => 'PARTY GURU',
        'url' => '/party-guru/',
      ),
    ),
    7 =>
    array (
      0 =>
      array (
        'title' => 'Escapes',
        'url' => '/escapes/',
      ),
      1 =>
      array (
        'title' => 'Adventures',
        'url' => '/adventures/',
      ),
      2 =>
      array (
        'title' => 'Inspiration',
        'url' => '/inspiration/',
      ),
      3 =>
      array (
        'title' => 'Organiser',
        'url' => '/help/',
      ),
    ),
    8 =>
    array (
      0 =>
      array (
        'title' => 'Holiday House Owners',
        'url' => '/holiday-house-owners/',
      ),
/*
      1 =>
      array (
        'title' => 'Activity Providers',
        'url' => '/activity-providers/',
      ),
      2 =>
      array (
        'title' => 'Jobs and Internships',
        'url' => '/jobs-and-internships/',
      ),
*/
    ),
    9 =>
    array (
      0 =>
      array (
        'title' => 'Hen Parties',
        'url' => 'https://henparties.kateandtoms.com',
      ),
      1 =>
      array (
        'title' => 'Stag Parties',
        'url' => 'https://stagparties.kateandtoms.com',
      ),
      2 =>
      array (
        'title' => 'Big Parties',
        'url' => 'https://bigparties.kateandtoms.com',
      ),
      3 =>
      array (
        'title' => 'Party Guru',
        'url' => '/party-guru/',
      ),
    ),
    10 =>
    array (
      0 =>
      array (
        'title' => 'PARTY HOUSES',
        'url' => '/party-houses/',
      ),
      1 =>
      array (
        'title' => 'ACTIVITIES',
        'url' => '/activities/',
      ),
      2 =>
      array (
        'title' => 'DESTINATIONS',
        'url' => '/destinations/',
      ),
      3 =>
      array (
        'title' => 'OFFERS',
        'url' => '/special-offers/',
      ),
    ),
    11 => array (
      0 =>  array (
        'title' => 'SIZES',
        'url' => '/sizes/',
        'submenu' => array(
	        0 => array(
		        'title' => 'Group Size',
		        'url' => '#',
            'submenu' => array(
              0 => array(
                'title' => 'Sleeps up to 8',
                'url' => '/size/holiday-cottages-sleeping-8/',
              ),
              1 => array(
                'title' => 'Sleeps up to 10',
                'url' => '/size/holiday-cottages-sleeping-10/',
              ),
              2 => array(
                'title' => 'Sleeps up to 12',
                'url' => '/size/holiday-cottages-sleeping-12/',
              ),
              3 => array(
                'title' => 'Sleeps up to 14',
                'url' => '/size/holiday-cottages-sleeping-14/',
              ),
              4 => array(
                'title' => 'Sleeps up to 15',
                'url' => '/size/holiday-cottages-sleeping-15/',
              ),
              5 => array(
                'title' => 'Sleeps up to 16',
                'url' => '/size/holiday-cottages-sleeping-16/',
              ),
              6 => array(
                'title' => 'Sleeps up to 17',
                'url' => '/size/holiday-cottages-sleeping-17/',
              ),
              7 => array(
                'title' => 'Sleeps up to 18',
                'url' => '/size/holiday-cottages-sleeping-18/',
              ),
              8 => array(
                'title' => 'Sleeps up to 20',
                'url' => '/size/holiday-cottages-sleeping-20/',
              ),
              9 => array(
                'title' => 'Sleeps up to 22',
                'url' => '/size/holiday-cottages-sleeping-22/',
              ),
              10 => array(
                'title' => 'Sleeps up to 24',
                'url' => '/size/holiday-cottages-sleeping-24/',
              ),
              11 => array(
                'title' => 'Sleeps up to 25',
                'url' => '/size/holiday-cottages-sleeping25/',
              ),
              12 => array(
                'title' => 'Sleeps up to 30',
                'url' => '/size/holiday-cottages-sleeping-30/',
              ),
              13 => array(
                'title' => 'Sleeps up to 40',
                'url' => '/size/holiday-cottages-sleeping-40/',
              ),
            ),    
	        ),
	        1 => array(
		        'title' => 'Bedrooms',
		        'url' => '#',
            'submenu' => array(
              0 => array(
                'title' => '5 Bedrooms',
                'url' => '/features/5-bedroom-holiday-homes',
              ),
              1 => array(
                'title' => '6 Bedrooms',
                'url' => '/features/6-bedroom-holiday-homes',
              ),
              2 => array(
                'title' => '7 Bedrooms',
                'url' => '/features/7-bedroom-holiday-homes',
              ),
              3 => array(
                'title' => '8 Bedrooms',
                'url' => '/features/8-bedroom-holiday-homes',
              ),
              4 => array(
                'title' => '9 Bedrooms',
                'url' => '/features/9-bedroom-holiday-homes',
              ),
              5 => array(
                'title' => '10 Bedrooms',
                'url' => '/features/10-bedroom-holiday-houses',
              ),
			  6 => array(
				'title' => '11 Bedrooms',
				'url' => '/features/11-bedroom-holiday-cottages/',
			  ),
			  7 => array(
				'title' => '12+ Bedrooms',
				'url' => '/features/12-bedroom-luxury-houses-to-rent/',
			  ),
            ),    
	        ),
        ),
      ),
      1 => array (
        'title' => 'LOCATION',
        'url' => '/location/',
        'submenu' => array(
	        0 => array(
		        'title' => 'Search by Regions',
		        'url' => '/search-by-regions/',
            'submenu' => $location_by_regions    
	        ),
	        1 => array(
		        'title' => 'Search by Towns',
		        'url' => '/towns/',
            'submenu' => $location_by_town    
	        ),
	        2 => array(
		        'title' => 'Search by Coast',
		        'url' => '/search-by-coast/',
            'submenu' => $location_by_coast    
	        ),
        ),
      ),
      2 =>
      array (
        'title' => 'FEATURE',
        'url' => '/feature/',
      ),
    ),
    12 =>
    array (
      0 =>
      array (
        'title' => 'Locations',
        'url' => '/wedding-locations/',
      ),
      1 =>
      array (
        'title' => 'Ideas',
        'url' => '/wedding-ideas/',
      ),
      2 =>
      array (
        'title' => 'Venue Finder',
        'url' => '/venue-finder/',
      ),
    ),
    13 =>
    array (
      0 =>
      array (
        'title' => 'Find a Cottage',
        'url' => '/luxury-cottages/',
      ),
      1 =>
      array (
        'title' => 'Destinations',
        'url' => '/locations/',
      ),
      2 =>
      array (
        'title' => 'Child Friendly',
        'url' => 'https://escapesandadventures.kateandtoms.com',
      ),
      3 =>
      array (
        'title' => 'Last Minute',
        'url' => '/last-minute-cottages/',
      ),
    ),
    15 =>
    array (
      0 =>
      array (
        'title' => 'Find a Luxury Villa',
        'url' => '/find-a-luxury-villa/',
      ),
      1 =>
      array (
        'title' => 'Destinations',
        'url' => '/destinations/',
      ),
      2 =>
      array (
        'title' => 'Offers',
        'url' => '/offer/',
      ),
      3 =>
      array (
        'title' => 'Concierge',
        'url' => '/concierge/',
      ),
    ),
    16 =>
    array (
      0 =>
      array (
        'title' => 'Events',
        'url' => '/corporate-events-2/',
      ),
      1 =>
      array (
        'title' => 'Activities',
        'url' => '/teambuilding/',
      ),
      2 =>
      array (
        'title' => 'Concierge',
        'url' => '/concierge/',
      ),
    ),
  ),
  'site_mobile_menus_default' => $mobile_menu_items,
  'site_mobile_menus' =>
  array (
    6 => array (
      0 => array('title' => 'Search All', 'url' => '/houses/'),
      1 => array('title' => 'Search House Name', 'url' => '#', 'id' => 'msbhslide'),
      2 => array ( 'title' => 'PARTY HOUSES', 'url' => '/party-houses/' ),
      3 => array ( 'title' => 'ACTIVITIES', 'url' => '/stag-activities/' ),
      4 => array ( 'title' => 'DESTINATIONS', 'url' => '/destinations/' ),
      5 => array ( 'title' => 'PARTY GURU', 'url' => '/party-guru/' ),
    ),
    8 => array (
      0 => array (
        'title' => 'Holiday House Owners',
        'url' => '/holiday-house-owners/',
      ),
    ),
    9 => array(
      array('title' => 'Hen Parties', 'url' => 'https://henparties.kateandtoms.com/'),
      array('title' => 'Stag Parties', 'url' => 'https://stagparties.kateandtoms.com/'),
      array('title' => 'Big Parties', 'url' => 'https://bigparties.kateandtoms.com/'),
    ),
    11 => array_merge($kateandtoms_mobile_menu_items),
    12 => array (
      0 => array('title' => 'Search All', 'url' => '/houses/'),
      1 => array('title' => 'Search House Name', 'url' => '#', 'id' => 'msbhslide'),
      2 => array ( 'title' => 'Locations', 'url' => '/wedding-locations/' ),
      3 => array ( 'title' => 'Ideas', 'url' => '/wedding-ideas/' ),
      4 => array ( 'title' => 'Venue Finder', 'url' => '/venue-finder/' ),
    ),
    16 => array (
      0 => array('title' => 'Search All', 'url' => '/houses/'),
      1 => array('title' => 'Search House Name', 'url' => '#', 'id' => 'msbhslide'),
      2 => array ( 'title' => 'Events', 'url' => '/corporate-events-2/'),
      3 => array ( 'title' => 'Activities', 'url' => '/teambuilding/' ),
      4 => array ( 'title' => 'Concierge', 'url' => '/concierge/' ),
    ),
  ),
  'menus' =>
  array (
    'sites' =>
    array (
      'title' => 'Sites',
      'class' => 'offset3',
      'items' =>
      array (
        0 =>
        array (
          'title' => 'PARTY HOUSES',
          'url' => 'https://kateandtoms.com/party-houses',
        ),
        1 =>
        array (
          'title' => 'NEW HOUSES',
          'url' => 'https://kateandtoms.com/new-to-kate-toms/',
        ),
        2 =>
        array (
          'title' => 'SPECIAL OFFERS',
          'url' => 'https://kateandtoms.com/special-offers/',
        ),
/*
        3 =>
        array (
          'title' => 'DISCOVER',
          'url' => 'https://kateandtoms.com/explore/',
        ),
*/
        4 =>
        array (
          'title' => 'SEASONAL',
          'url' => '/seasonal/',
          'submenu' => $seasonal_menu
        ),
      ),
      'footer_items' =>
      array (
        0 =>
        array (
          'title' => 'Big Cottage Company',
          'url' => 'https://bigcottage.com',
        ),
/*
        1 =>
        array (
          'title' => 'Country Houses',
          'url' => 'https://luxurycountryhouses.kateandtoms.com/',
        ),
*/
/*
        2 =>
        array (
          'title' => 'Holidays',
          'url' => 'https://holidays.kateandtoms.com/',
        ),
*/
        3 =>
        array (
          'title' => 'Party Houses',
          'url' => 'https://kateandtoms.com/party-houses',
        ),
      ),
    ),
    'company' =>
    array (
      'title' => 'Company',
      'class' => '',
      'items' =>
      array (
        0 =>
        array (
          'title' => 'About',
          'url' => 'https://kateandtoms.com/about-us/',
        ),
        1 =>
        array (
          'title' => 'Blog',
          'url' => 'https://kateandtoms.com/our-blog/',
        ),
        2 =>
        array (
          'title' => 'Privacy',
          'url' => 'https://docs.kateandtoms.com/packs/kateandtoms-privacy-policy2-FEB22.pdf',
        ),
        3 =>
        array (
          'title' => 'Terms',
          'url' => 'https://docs.kateandtoms.com/packs/kateandtoms-terms-and-conditions-FEB22.pdf',
        ),
        4 =>
        array (
          'title' => 'Company Info',
          'url' => 'https://kateandtoms.com/company-information/',
        ),
        5 =>
        array (
          'title' => 'Image Credits',
          'url' => 'http://web.bigcottage.com/image-credits/',
        ),
      ),
    ),
    'opportunities' =>
    array (
      'title' => 'Opportunities',
      'class' => '',
      'items' =>
      array (
        0 =>
        array (
          'title' => 'Holiday House Owners',
          'url' => 'https://partners.kateandtoms.com/holiday-house-owners/',
        ),
        1 =>
        array (
          'title' => 'Activity Providers',
          'url' => 'https://partners.kateandtoms.com/activity-providers/',
        ),
        2 =>
        array (
          'title' => 'Jobs and Internships',
          'url' => 'https://partners.kateandtoms.com/jobs-and-internships/',
        ),
      ),
    ),
  ),
  'emails_default' =>
  array (
    0 => 'hello@kateandtoms.com',
    1 => 'Hello Kate and Tom!',
  ),
  'emails' =>
  array (
    7 =>
    array (
      0 => 'escapes@kateandtoms.com',
      1 => 'Hello Escapes at Kate and Tom\'s',
    ),
    4 =>
    array (
      0 => 'houses@kateandtoms.com',
      1 => 'Hello Houses at Kate and Tom\'s',
    ),
    9 =>
    array (
      0 => 'party@kateandtoms.com',
      1 => 'Hello Party at Kate and Tom\'s',
    ),
    10 =>
    array (
      0 => 'party@kateandtoms.com',
      1 => 'Hello Party at Kate and Tom\'s',
    ),
    5 =>
    array (
      0 => 'hens@kateandtoms.com',
      1 => 'Hello Hens at Kate and Tom\'s',
    ),
    6 =>
    array (
      0 => 'stags@kateandtoms.com',
      1 => 'Hello Stags at Kate and Tom\'s',
    ),
  ),
  'contact_mappings_default' => 274,
  'enquire_mappings_default' => array(
	  1 => 37580,
	  4 => 37580,
	  5 => 37580,
	  6 => 37580,
	  7 => 37580,
	  8 => 37582,
	  9 => 37580,
	  10 => 37580,
	  11 => 37580,
	  12 => 37580,
	  13 => 37580,
	  14 => 37580,
	  15 => 37580,
	  16 => 37580,
	  17 => 37580,
	  18 => 37580,
	  20 => 37580,
	  21 => 37580,
	  22 => 37580,
	  23 => 37580,
	  24 => 37580,
	  25 => 37580,
	  27 => 37580,
	  28 => 37580
  ),
  //'enquire_mappings_default' => 35394,

  'contact_mappings' =>
  array (
    //1 => 15027,
    1 => 274,
    4 => 15028,
    9 => 15029,
    8 => 37582,
    10 => 15029,
    12 => 16079,
    13 => 16100,
    14 => 16101,
    15 => 16102,
    5 => 15030,
    6 => 15031,
    16 => 17596,
  ),
  'separating_categories_default' => array(
      array('name' => 'cotswolds','title' => 'kate &amp; tom\'s in the Cotswolds','color' => 'yellow'),
      array('name' => 'sea','title' => 'kate &amp; tom\'s by the Coast','color' => 'blue'),
      array('name' => 'country','title' => 'kate &amp; tom\'s in the Country','color' => 'green'),
      array('name' => 'town','title' => 'kate &amp; tom\'s in Town','color' => 'blue'),
      //array('name' => 'french','title' => 'kate &amp; tom\'s in France','color' => 'green')
  ),
  'separating_categories' => array(
    12 => array( // Weddings
      array('name' => 'cotswolds','title' => 'Wedding Venue in the Cotswolds','color' => 'yellow'),
      array('name' => 'sea','title' => 'Wedding Venue by the Coast','color' => 'blue'),
      array('name' => 'country','title' => 'Wedding Venue in the Country','color' => 'green'),
      array('name' => 'town','title' => 'Wedding Venue in Town','color' => 'orange')
    ),
    13 => array( // Holidays
      array('name' => 'sea','title' => 'Holidays by the Coast','color' => 'blue'),
      array('name' => 'cotswolds','title' => 'Holidays in the Cotswolds','color' => 'yellow'),
      array('name' => 'country','title' => 'Holidays in the Country','color' => 'green'),
      array('name' => 'europe','title' => 'Holidays in Europe','color' => 'orange'),
      array('name' => 'caribbean','title' => 'Holidays in the Caribbean','color' => 'yellow')
    ),
    14 => array( // Luxury Cottages
      array('name' => 'town','title' => 'Luxury Cottage Close to Town','color' => 'orange'),
      array('name' => 'cotswolds','title' => 'Luxury Cottage in the Cotswolds','color' => 'yellow'),
      array('name' => 'country','title' => 'Luxury Cottage in the Country','color' => 'green'),
      array('name' => 'sea','title' => 'Luxury Cottage by the Coast','color' => 'blue')
    ),
    15 => array( // Luxury villas
      array('name' => 'caribbean','title' => 'Luxury Villa in the Caribbean','color' => 'yellow'),
      array('name' => 'europe','title' => 'Luxury Villa in Europe','color' => 'green'),
      array('name' => 'worldwide','title' => 'Luxury Villa Worldwide','color' => 'blue')
    ),
    16 => array( // Events
      array('name' => 'cotswolds','title' => 'Venues in the Cotswolds','color' => 'yellow'),
      array('name' =>'country','title' => 'Venues in the Country','color' => 'green'),
      array('name' => 'sea','title' => 'Venues by the Coast','color' => 'blue'),
      array('name' => 'town','title' => 'Venues in Town','color' => 'orange')
    ),
    4 => array( // Large Country Houses
      array('name' => 'country','title' => 'Country House','color' => 'green'),
      array('name' => 'cotswolds','title' => 'Country House in the Cotswolds','color' => 'yellow'),
      array('name' => 'sea','title' => 'Country House by the Coast','color' => 'blue'),
      array('name' => 'town','title' => 'Town House','color' => 'green')
    ),
    7 => array( // Escapes and Adventures
      array('name' => 'cotswolds','title' => 'Cotswolds Escape','color' => 'yellow'),
      array('name' => 'sea','title' => 'Seaside Escape','color' => 'blue'),
      array('name' => 'country','title' => 'Country Escape','color' => 'green'),
      array('name' => 'french','title' => 'French Escape','color' => 'green')
    ),
    5 => array( // Party sites
      array('name' => 'town','title' => 'Party House Close to Town','color' => 'orange'),
      array('name' => 'cotswolds','title' => 'Party House in the Cotswolds','color' => 'yellow'),
      array('name' => 'country','title' => 'Party House in the Country','color' => 'green'),
      array('name' => 'sea','title' => 'Party House by the Coast','color' => 'blue')
    ),
    6 => array( // Party sites
      array('name' => 'town','title' => 'Party House Close to Town','color' => 'orange'),
      array('name' => 'cotswolds','title' => 'Party House in the Cotswolds','color' => 'yellow'),
      array('name' => 'country','title' => 'Party House in the Country','color' => 'green'),
      array('name' => 'sea','title' => 'Party House by the Coast','color' => 'blue')
    ),
    9 => array( // Party sites
      array('name' => 'town','title' => 'Party House Close to Town','color' => 'orange'),
      array('name' => 'cotswolds','title' => 'Party House in the Cotswolds','color' => 'yellow'),
      array('name' => 'country','title' => 'Party House in the Country','color' => 'green'),
      array('name' => 'sea','title' => 'Party House by the Coast','color' => 'blue')
    ),
    10 => array( // Party sites
      array('name' => 'town','title' => 'Party House Close to Town','color' => 'orange'),
      array('name' => 'cotswolds','title' => 'Party House in the Cotswolds','color' => 'yellow'),
      array('name' => 'country','title' => 'Party House in the Country','color' => 'green'),
      array('name' => 'sea','title' => 'Party House by the Coast','color' => 'blue')
    ),
    1 => array ( // Big Cottage
      array('name' => 'cotswolds','title' => 'Big Cottage in the Cotswolds','color' => 'yellow'),
      array('name' => 'sea','title' => 'Big Cottage by the Coast','color' => 'blue'),
      array('name' => 'country','title' => 'Big Cottage in the Country','color' => 'green'),
      array('name' => 'town','title' => 'Big Cottage in Town','color' => 'blue'),
      array('name' => 'french','title' => 'Big Cottage in France','color' => 'green')
    )
  ),
  'sleep_limits' =>
  array(
			1  => array( 20 => array(20, 999), 14 => array(14, 20.95), 6 => array(6, 12.95)),
			16 => array( 20 => array(20, 999), 14 => array(14, 20.95), 6 => array(6, 12.95)),
			4  => array( 24 => array(24, 999), 20 => array(20, 24.95), 16 => array(16, 20.95)),
			16 => array( 24 => array(24, 999), 20 => array(20, 24.95), 16 => array(16, 20.95)),
			7  => array( 12 => array(12, 999), 8 => array(8, 12.95), 2 => array(2, 6.95)),
			5  => array( 20 => array(20, 999), 16 => array(16, 20.95), 12 => array(12, 16.95)),
			6  => array( 20 => array(20, 999), 16 => array(16, 20.95), 12 => array(12, 16.95)),
			10 => array( 24 => array(24, 999), 20 => array(20, 24.95), 16 => array(16, 20.95)),
			11 => array( 20 => array(20, 999), 10 => array(10, 20.95), 2 => array(2, 10.95)),
			12 => array( 150 => array(150, 999), 100 => array(100, 150.95), 40 => array(40, 100.95)),
			14 => array( 12 => array(12, 999), 8 => array(8, 12.95), 2 => array(2, 8.95)),
			13 => array( 12 => array(12, 999), 8 => array(8, 12.95), 2 => array(2, 8.95)),
			15 => array( 12 => array(12, 999), 8 => array(8, 12.95), 2 => array(2, 8.95)),
    ),
  'sleep_default' =>
  array (
    6 => '6-12',
    14 => '14-20',
    20 => '20+',
  ),
  'sleep' =>
  array (
    7 =>
    array (
      2 => '2-6',
      8 => '8-12',
      12 => '12+',
    ),
    13 =>
    array (
      2 => '2-6',
      8 => '8-12',
      12 => '12+',
    ),
    14 =>
    array (
      2 => '2-6',
      8 => '8-12',
      12 => '12+',
    ),
    15 =>
    array (
      2 => '2-6',
      8 => '8-12',
      12 => '12+',
    ),
    4 =>
    array (
      16 => '16-20',
      20 => '20-24',
      24 => '24+',
    ),
    10 =>
    array (
      16 => '16-20',
      20 => '20-24',
      24 => '24+',
    ),
    5 =>
    array (
      12 => '12-16',
      16 => '16-20',
      20 => '20+',
    ),
    6 =>
    array (
      12 => '12-16',
      16 => '16-20',
      20 => '20+',
    ),
    12 =>
    array (
      40 => '40-100',
      100 => '100-150',
      150 => '150+',
    ),
    11 =>
    array (
      2 => '2-10',
      10 => '10-20',
      20 => '20+',
    ),
  ),
  'home_intro_default' => 'Whatever the occasion we have an incredible house for you',
  'home_intro' => array(
    1 => 'Whatever the occasion we have an incredible Big Cottage for you',
  ),
  'locations_default' =>
  array (
    'cotswolds' => 'Cotswolds',
    'sea' => 'Coast',
    'country' => 'Country',
  ),
  'locations' =>
  array (
    7 =>
    array (
      'cotswolds' => 'Cotswolds',
      'sea' => 'Seaside',
      'country' => 'Country',
    ),
    4 =>
    array (
      'country' => 'Country',
      'cotswolds' => 'Cotswolds',
      'sea' => 'Coast',
    ),
    6 =>
    array (
      'town' => 'Town',
      'sea' => 'Coast',
      'country' => 'Country',
    ),
    13 =>
    array (
      'sea' => 'Coast',
      'cotswolds' => 'Cotswolds',
      'country' => 'Country',
    ),
    15 =>
    array (
      'caribbean' => 'Carribean',
      'europe' => 'Europe',
      'worldwide' => 'Worldwide',
    ),
  ),
  'berth_default' => 'Sleeps',
  'berth' =>
  array (
    12 => 'Wedding Size',
  ),
  'features_default' =>
  array (
    'pool' => 'Pool',
    'hot-tub' => 'Hot Tub',
    'beach' => 'Beach',
  ),
  'features' =>
  array (
    12 =>
    array (
      'marquee' => 'Marquee',
      'ball-room' => 'Ballroom',
      'license' => 'License',
    ),
    4 =>
    array (
      'pool' => 'Pool',
      'hot-tub' => 'Hot Tub',
      'waterside' => 'Waterside',
    ),
  ),
  'hide_all_houses_button' =>
  array (
    8,
    9,
  ),
  'hide_search_form' =>
  array (
    8,
    9,
  ),
  'wedding_venue_price_sites' =>
  array(
    12,
  ),
  'rate_removals' => array(
	  '1' => array('20','100','110','130'),
	  '4' => array('20','100','110','130'),
	  '5' => array('20','100','110','130'),
	  '6' => array('20','100','110','130'),
	  '7' => array('20','100','110','130'),
	  '9' => array('20','100','110','130'),
	  '10' => array('20','100','110','130'),
	  '11' => array('20','100','110','130'),
	  '12' => array('20','50','60','70','80','85','90'),
	  '13' => array('20','100','110','130'),
	  '14' => array('20','100','110','130'),
	  '15' => array('20','100','110','130'),
	  '16' => array('50','60','70','80','85','90','100','110','130'),
  ),
  'rate_headers' =>
  array(
  	'20' => '1 night midweek CV',
  	'50' => '2 night weekend',
  	'60' => '3 night weekend',
  	'70' => 'Week',
  	'80' => 'Midweek',
  	'85' => '2 night midweek',
  	'90' => '5 nights',
  	'100' => '2 night weekend WV',
  	'110' => '3 night weekend WV',
  	'120' => 'Week WV',
  	'130' => 'Midweek WV'
  ),
);

?>