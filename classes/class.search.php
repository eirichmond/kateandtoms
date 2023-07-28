<?php
/**
 * Defines core functionality for Kate & Tom's search.
 *
 * @package kate-and-toms
 */

/**
 * Defines overall product search for site.
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2012
 */
class OnlineSearch {
	/**
	 * Seasonal period.
	 * Used when producing seasonal availability pages.
	 * @var string
	 */
 	protected static $periodSeasonal;

	/**
	 * Whether a seasonal search.
	 * @var bool
	 */
	protected static $seasonal = false;

	/**
	 * Whether to only show one category.
	 * Used to identify if the houses should not be split up.
	 * @var bool|string which location is being shown
	 */
	public static $showOneCat = false;

	/**
	 * Adverts.
	 * Used to produce filling adverts on search pages.
	 * @var array
	 */
	private static $adverts;

	/**
	 * Additional height depending on content.
	 * Styled via CSS.
	 * e.g. lines added for date search: 2 for weekend, 1 for week, 1 for midweek
	 * All to a maximum of 4 lines extra.
	 * @var integer
	 */
	protected static $additional_height;

	protected static $specified_seasonal_dates;

	/**
	 * Create the setup for seasonal searches.
	 * @param string $beginning Start of seasonal period
	 * @param string $ending End of seasonal period
	 * @param array $periods_to_include Types of period to include (eg weekends, weeks, etc)
	 * @todo May need to change from specified to all seasonal dates (if period goes outside)
	 * @todo Apply same logic for seasonal periods to normal availability search
	 */
	public static function seasonalSetup($beginning,$ending,$periods_to_include) {

		self::$seasonal = true;
		self::$additional_height = count($periods_to_include);
		if (self::$additional_height > 4) self::$additional_height = 4;
		self::$specified_seasonal_dates = self::createDateRangeArray($beginning,$ending);

		$periodSeasonal = array();

		foreach($periods_to_include as $period_to_include) {

			if (stristr($period_to_include, 'weekend') || $period_to_include == '5 nights') {
				$start_day_num = 5;
				$count_of_days = 2;
			}
			elseif ($period_to_include == 'WeekFridays') {
				$period_to_include = 'Week';
				$start_day_num = 5;
				$count_of_days = 5;
			}
			elseif ($period_to_include == 'Week') {
				$start_day_num = 2;
				$count_of_days = 5;
			}
			else {
				$start_day_num = 2;
				$count_of_days = 2;
			}

			$periodSeasonal[$period_to_include]['name'] = $period_to_include;

			$count = -1;
			$addCount = 0;
			foreach(self::$specified_seasonal_dates as $seasonal_date) :

				if ($addCount != 0) {
					$periodSeasonal[$period_to_include]['dates'][$count][$dateCount] = $seasonal_date;
					$addCount--;
					$dateCount++;
				} elseif (date('N',strtotime($seasonal_date)) == $start_day_num) {
					$count++;
					$periodSeasonal[$period_to_include]['dates'][$count][0] = $seasonal_date;
					$addCount = $count_of_days;
					$dateCount = 1;
				}

			endforeach;
			if ($addCount != 0) {
				array_pop($periodSeasonal[$period_to_include]['dates']);
			}
		}
		self::$periodSeasonal = $periodSeasonal;

	}

	/**
	 * Prepare adverts to be shown on a page.
	 */
	public static function prepareAdverts() {
		$advertsTemp = get_field('adverts','option');
		$adverts = array();
		if ($advertsTemp) {
			foreach ($advertsTemp as $advert) {
				$section_name = $advert['location'];
				if (!array_key_exists($section_name, $adverts)) {
					$adverts[$section_name] = array();
				}
				array_push($adverts[$section_name], $advert);
			}
			foreach($adverts as $section_name => $a) {
				shuffle($adverts[$section_name]);
			}
		}
		self::$adverts = $adverts;
	}

	/**
	 * Prepare adverts to be shown on a page.
	 * The order and categories changes depending upon which site the user is currently on.
	 * @return array Set of the categories page is to be separated by.
	 */
	public static function getSeparatingCats() {
		global $katglobals;

		$cats = $katglobals['separating_categories'];
		$cats_default = $katglobals['separating_categories_default'];

		$c = (array_key_exists(get_current_blog_id(), $cats) ? $cats[get_current_blog_id()] : $cats_default);
		if (self::$showOneCat) {
			foreach ($c as $id => $cat) {
				if ($cat['name'] !== self::$showOneCat) unset($c[$id]);
			}
		}

		return $c;

	}

	public static function topSectionFeatured() {
		$get_topsection_featured = get_option( 'kat_search' );
		if ($get_topsection_featured) {
		$title = $get_topsection_featured['top_section_title'];
		unset($get_topsection_featured['top_section_title']);
		$houses = $get_topsection_featured;
		?>
		<div id="toppertax" class="background-green" style="margin-top: 0px;">
			<div class="container">
				<div class="row">
					<h2 class="span12 type_title"><?php echo esc_html( $title ); ?></h2>

					<?php
						foreach ($houses as $house) {
							$house = new HouseSearch((int)$house);
							$house->displayHouse();
						}
					?>

				</div>
			</div>
		</div>
		<?php }
	}
	/**
	 * Display adverts for the specified category.
	 * @param string The category adverts should be displayed for
	 */
	public static function displayAdverts($category, $count) {

		// var_dump($category);
		// var_dump($count);
		// var_dump(self::$adverts);
		
		if($count) {

		$show = (4-(($count)%4))%4;

		if ($show && self::$adverts) {
			for ($i = 1; $i <= $show; $i++) {
				$id = self::$adverts[$category['name']][$i]['advert_image'];
				//$url = wp_get_attachment_image_src( $id, 'large' );
				$other =  wp_get_attachment_image($id, 'cross_promo_narrow');
				$other = str_replace('.test', '.com', $other);
				// $url = str_replace('.test', '.com', wp_get_attachment_image_src( $id, 'cross_promo_narrow' ));
				// echo '<div class="span3 advert '.(self::$additional_height ? 'search-add_height_'.self::$additional_height : '').'"  style="background: url('.$url[0].') center center;"></div>';
				echo '<div class="span3 advert npad '.(self::$additional_height ? 'search-add_height_'.self::$additional_height : '').'">'.$other.'</div>';
			}
		}

		}
	}

	// takes two dates formatted as YYYY-MM-DD and creates an
	// inclusive array of the dates between the from and to dates.
	protected static function createDateRangeArray($strDateFrom,$strDateTo) {

		$stone = intval(substr($strDateTo,5,2));
		$sttwo = intval(substr($strDateTo,8,2));
		$stthree = intval(substr($strDateTo,0,4));
		$frone = intval(substr($strDateFrom,5,2));
		$frtwo = intval(substr($strDateFrom,8,2));
		$frthree = intval(substr($strDateFrom,0,4));

		$aryRange=array();

		$iDateFrom=mktime(1,0,0, $frone, $frtwo, $frthree);
		$iDateTo=mktime(1, 0, 0, $stone, $sttwo, $stthree);

		if ($iDateTo>=$iDateFrom) {
			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry

			while ($iDateFrom<$iDateTo) {
			$iDateFrom+=86400; // add 24 hours
			array_push($aryRange,date('Y-m-d',$iDateFrom));
			}
		}
		return $aryRange;
	}

	// Takes datetime and search type (1,2,3), returns datetime
	public static function getOriginalDate($date_time, $search_type) {
		$day_of_week = date('N',$date_time);
		// 5 = Friday, 6 = Saturday...
		$modifiers = array(
			1 => array(5 => '+0', 6 => '-1', 7 => '-2', 1 => '+4', 2 => '+3', 3 => '+2', 4 => '+1'), // Weekends
//			1 => array(5 => '+1', 6 => '-0', 7 => '-13', 1 => '+5', 2 => '+4', 3 => '+3', 4 => '+2'), // Weekends
			2 => array(5 => '+0', 6 => '-1', 7 => '+1', 1 => '+0', 2 => '-1', 3 => '+2', 4 => '+1'), // Weeks
			3 => array(5 => '-3', 6 => '-4', 7 => '-5', 1 => '+1', 2 => '+0', 3 => '-1', 4 => '-2')  // Midweeks
		);
		return self::addDaysToDateTime($date_time, $modifiers[$search_type][$day_of_week]);
	}

	public static function getDateToShow($date_time, $search_type) {
		$original_date_time = self::getOriginalDate($date_time, $search_type);

		if ($search_type == 3) {
			return self::addDaysToDateTime($original_date_time, '-1');
		}
		else {
			return $original_date_time;
		}
	}

	public static function getFirstDateToCheck($date_time, $search_type) {

        // hack to be removed after Dec 31st 2017 and use the else statement
        if ($date_time == 1514678400) {
            $modifiers = array( 1 => '+0', 2 => '+0', 3 => '+0');
        } else {
            $modifiers = array( 1 => '+0', 2 => '+1', 3 => '+0');
        }
        if( array_key_exists($search_type, $modifiers) ) {
			return self::addDaysToDateTime($date_time, $modifiers[$search_type]);
	    }
	}

	public static function getFinalDateToCheck($date_time, $search_type) {

	    // hack to be removed after Dec 31st 2017 and use the else statement
	    if($date_time == 1514678400) {
            $modifiers = array( 1 => '+1', 2 => '+1', 3 => '+2');
        } else {
            $modifiers = array( 1 => '+1', 2 => '+6', 3 => '+2');
        }

        if( array_key_exists($search_type, $modifiers) ) {
			return self::addDaysToDateTime($date_time, $modifiers[$search_type]);
		}

	}

	public static function addDaysToDateTime($date_time, $add_days) {
		return strtotime(date('Y-m-d',$date_time). " ".$add_days." days");
	}
}

/**
 * Defines a house product used in searches.
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2012
 */
class HouseSearch extends OnlineSearch {

	public static $houses;
	public static $availability;
	public static $rates;
	public static $dates_to_check;

	/**
     * This function is called so that we may set the variable from an extended
     * class
     */
    public function set_specified_seasonal_dates ($dates) {

        self::$specified_seasonal_dates = $dates;

    }

	/**
     * This function is called so that we may set the variable from an extended
     * class
     */
    public function set_seasonal($bool) {

        self::$seasonal = $bool;

    }

	/**
     * This function is called so that we may set the variable from an extended
     * class
     */
    public function set_period_seasonal($periods) {

        self::$periodSeasonal = $periods;

    }

	public static function housesSetup($inputs) {

/*
		echo '<pre>';
		print_r($inputs);
		echo '</pre>';
		var_dump($inputs);
*/

		global $wpdb;
		$blog_id = get_current_blog_id();
		$order = ($blog_id == 7 || $blog_id == 13 ? 'ASC': 'DESC');

		self::$houses = $wpdb->get_results("
			SELECT *
			FROM houses
			WHERE blog_id = ".$blog_id."
			ORDER BY sleeps_max ".$order."
		");

		$wtf = self::$houses;

		if (array_key_exists('seasonal', $inputs)) {
			if ($inputs['seasonal'] !== false) {
				$day_start = self::$specified_seasonal_dates[0];  // First day
				$day_end = self::$specified_seasonal_dates[count(self::$specified_seasonal_dates) - 1]; // Last day
				self::availabilitySetup($day_start, $day_end);
			}
		}
		elseif (array_key_exists('date', $inputs) && array_key_exists('dtype', $inputs)) {
			$date = $inputs['date'];
			$day_number = date('N', strtotime($date));
			$dtype = $inputs['dtype'];
			// if this date is a friday on a weekend search it is potentially a cross over date
			// so push this friday search to search for days available
			// from the saturday which is day 6
			if ($day_number == 5 && $dtype == 1) {
				$date = date('d-m-Y',strtotime($date . '+ 1 day'));
			}
			// if this date is a monday on a midweek search it is potentially a cross over date
			// so push this monday search to search for days available
			// from the tuesday which is day 2
			if ($day_number == 1 && $dtype == 3) {
				$date = date('d-m-Y',strtotime($date . '+ 1 day'));
			}

			self::$dates_to_check = self::getDateRange($date, $dtype);
			$day_start = self::$dates_to_check[0];  // First day
			$day_end = self::$dates_to_check[count(self::$dates_to_check) - 1]; // Last day
			self::availabilitySetup($day_start, $day_end);

/*
			echo '<pre>';
			print_r(self::$dates_to_check);
			echo '</pre>';
*/
			//var_dump(self::$dates_to_check);

		}

	}

	public static function singleAvailability($blog_id, $post_id) {
		global $wpdb;
		return $wpdb->get_results("
			SELECT
				blog_id,
				post_id,
				month,
				booked_days
			FROM availability
			WHERE
				blog_id = ".$blog_id." AND
				post_id = ".$post_id."
		", OBJECT );
	}

	public static function singleRates($blog_id, $post_id, $month = null) {
		global $wpdb;
		$month_query = ($month !== null ? 'AND month = "'.$month.'"' : '');
		return $wpdb->get_results("
			SELECT
				blog_id,
				post_id,
				month,
				rates
			FROM rates
			WHERE
				blog_id = ".$blog_id." AND
				post_id = ".$post_id."
				".$month_query."
		", OBJECT );
	}

	public static function check_end_of_month_cockup() {
		$cockup = false;

		if(!isset($_GET['date'])) {
			return;
		}

        // check if date is end of the month to get the correct rate for the previous month

        // if($_GET['date'] == '28-08-2020') {
	    //     if($_GET['dtype'] == '1' || $_GET['dtype'] == '2') {
        //     	$cockup = true;
	    //     }
        // }


        return $cockup;
    }

	public static function availabilitySetup($day_start, $day_end) {

		//var_dump($this);

		global $wpdb;
		$month = substr($day_start, 5,2).'-'.substr($day_start, 0,4);

		// $cockup = self::check_end_of_month_cockup();

        // if($cockup && $_GET['date'] == '28-08-2020') {
        //     $month = '08-2020';
        // }

		// Get any existing copy of our transient data
		if ( false === ( self::$availability = get_transient( 'setup_availability-'.$day_start.'-'.$day_end ) ) ) {
			// It wasn't there, so regenerate the data and save the transient

			self::$availability = $wpdb->get_results("
				SELECT
					blog_id,
					post_id,
					month,
					booked_days
				FROM availability
				WHERE
					STR_TO_DATE(month,'%m-%Y') >= STR_TO_DATE('".$day_start."','%Y-%m-%d') - INTERVAL 1 MONTH - INTERVAL 1 DAY AND -- MIN DATE (one day for pricing)
					STR_TO_DATE(month,'%m-%Y') <= STR_TO_DATE('".$day_end."','%Y-%m-%d') -- MAX DATE
			", OBJECT );

			set_transient( 'setup_availability-'.$day_start.'-'.$day_end, self::$availability, HOUR_IN_SECONDS );
		}
		// Use the data like you would have normally...

// @TODO 

		// Get any existing copy of our transient data
		if ( false === ( self::$rates = get_transient( 'setup_availability_rates-'.$month ) ) ) {
			// It wasn't there, so regenerate the data and save the transient
			self::$rates = $wpdb->get_results("
				SELECT
					blog_id,
					post_id,
					month,
					rates
				FROM rates
				WHERE
					month = '".$month."'
			", OBJECT );
			set_transient( 'setup_availability_rates-'.$month, self::$rates, HOUR_IN_SECONDS );
		}
		// Use the data like you would have normally...
		

	}

	public static function getHouseAvailability($blog_id, $post_id) {
        return self::getFilteredArrayWithObjects(self::$availability, $blog_id, $post_id);
	}

	public static function getHouseRates($blog_id, $post_id) {

		// echo '<pre>';
		// var_dump(self::$rates);
		// echo '</pre>';
		// wp_die();

	    //if ($post_id == 25599) {
            return self::getFilteredArrayWithObjects(self::$rates, $blog_id, $post_id);
        //}
	}

	public static function getFilteredArrayWithObjects($array, $blog_id, $post_id) {
		return array_filter($array, function($row) use ($blog_id, $post_id) {
			if ($row->blog_id == $blog_id && $row->post_id == $post_id) {
				return true;
			}
			return false;
		});
	}

	private static function getDateRange($date, $s_date_type) {
		$date_time = strtotime($date);
		$first_date = date('Y-m-d', self::getFirstDateToCheck($date_time, $s_date_type));
		$final_date = date('Y-m-d', self::getFinalDateToCheck($date_time, $s_date_type));
		return self::createDateRangeArray($first_date,$final_date);
	}

	public static function getSearchCrossSells() {
		function startsWith($haystack, $needle)
		{
		     $length = strlen($needle);
		     return (substr($haystack, 0, $length) === $needle);
		}

		global $wpdb;
		$blog_id = get_current_blog_id();
		$site_trailing = ($blog_id == 1 ?  '' : '_'.$blog_id);

		$results = $wpdb->get_results("
			SELECT *
			FROM wp".$site_trailing."_options
			WHERE option_name LIKE 'options_cross_promotion_%'
		");

		$adverts = array();

		if (!is_array($results)) return;

		foreach ($results as $meta) {
			// remove 'options_cross_promotion_', left with narrow_0_advert_image
			$name = substr($meta->option_name, 24);

			if ((startsWith($name, 'narrow_') || startsWith($name, 'wide_'))) {

				$p1 = strpos($name, '_'); // position of first '_'
				$p2 = strpos($name, '_advert_'); // position of second '_'
				$length_of_id = $p2-$p1-1;

				$size = substr($name, 0, $p1);
				$id = intval(substr($name, $p1+1, $length_of_id)); // 0
				$option_name = substr($name, $p2+8); // image
				$option_value = $meta->option_value;

				// renames
				if ($option_name == 'links_to') {
					$option_name = 'link';
				}
				elseif ($option_name == 'tagged') {
					$option_name = 'tag';
				}

				$adverts[$size][$id][$option_name] = $option_value;

			}
		}

		$adverts_selected = array();

		// Add additional meta values
		foreach ($adverts as $size_name => $size_ads) {
			foreach ($size_ads as $ad_key => $ad) {
				$adverts[$size_name][$ad_key]['type'] = $size_name;
				$adverts[$size_name][$ad_key]['span'] = ($size_name == 'wide' ? 'span9' : 'span3');
			}
		}

		// Select one wide and one narrow image with non matching tags
		if (!array_key_exists('narrow', $adverts) || !array_key_exists('wide', $adverts)) {
			return false;
		}

		$i = array_rand($adverts['narrow']);

		$adverts_selected[0] = $adverts['narrow'][$i];
		$tag = $adverts_selected[0]['tag'];

		foreach ($adverts['wide'] as $id => $ad) {
			if ($ad['tag'] == $tag) {
				unset($adverts['wide'][$id]);
			}
		}

		$i = array_rand($adverts['wide']);
		$adverts_selected[1] = $adverts['wide'][$i];

		// Get images
		foreach ($adverts_selected as $id => $ad) {
			$adverts_selected[$id]['image'] = getImage($adverts_selected[$id]['image'], 'cross_promo_'.$ad['type']);
		}

		shuffle($adverts_selected);

		return $adverts_selected;
	}


	/**
	 * Create recommended houses area for a specified house.
	 * Returns a display of up to four recommended houses
	 */

	public static function crossSell($currentHouse) {
	    $houses = get_field('related_houses', $currentHouse);
	    if ($houses) {
	    	$c = 0;
	        foreach ($houses as $house) {
	        	if ($c < 4) {
		        	$house = new HouseSearch($house->ID);
		        	$house->displayHouse();
		        }
		        $c++;
	        }
	        if ($c < 4) {
		        $specialoffers = new SpecialOffers();
		        $number_of_ads = $specialoffers->advertCount($c);
				$specialoffers->prepareAds($number_of_ads, 'cotswolds');
	        }
	    }
	}

	private $price; // Prices to display. @var object
	private $availableDates = array(); // Period types with prices
	private $currency; //£ $ or €, the default should be £.
	private $pricesSearchComplete = false; // Specified size search. - Number the house must sleep
	private $winterText = false; //Whether winter text should be displayed.

	private $ID;

	public $house_id;
	public $blog_id;
	public $post_id;
	public $post_title;
	public $permalink;
	public $post_thumbnail;
	public $availability_option;
	public $availability_site_ref;
	public $availability_site_post_id;
	public $location;
	public $location_text;
	public $locations;
	public $sleeps_min;
	public $sleeps_max;
	public $brief_description;
	public $brief_description_winter;
	public $all_prices_with_from;
	private $display = true; // bool, whether to display


	/**
	 * Setup of new house.
	 * @param object A house object from the database
	 * @param array If set, array of key=>value inputs
	 */
	public function __construct($house, $vars = null) {
		// make backwards compatible, REMOVE THIS NOW?
		if (is_int($house)) {
			global $wpdb;
			$post_id = $house;
			$blog_id = get_current_blog_id();
			$house = $wpdb->get_results("
				SELECT *
				FROM houses
				WHERE blog_id = ".$blog_id."
				AND post_id = ".$post_id."
			");
			$house = $house[0];
		}

		$this->ID = $house->post_id;
		$this->house_id = $house->house_id;
		$this->blog_id = $house->blog_id;
		$this->post_id = $house->post_id;
		$this->post_title = $house->post_title;
		$this->permalink = $house->permalink;
		$this->post_thumbnail = $house->post_thumbnail;
		$this->availability_option = $house->availability_option;
		$this->availability_site_ref = $house->availability_site_ref;
		$this->availability_site_post_id = $house->availability_site_post_id;
		$this->location = $house->location;
		$this->location_text = $house->location_text;
		$this->locations = unserialize($house->locations);
		$this->sleeps_min = $house->sleeps_min;
		$this->sleeps_max = $house->sleeps_max;;
		$this->brief_description = $house->brief_description;
		$this->brief_description_winter = $house->brief_description_winter;
		$this->all_prices_with_from = $house->all_prices_with_from;

		// change image to work locally
		$local = '//kateandtoms.test/';
		$staging = '//staging.kateandtoms.com';
		$web = '//kateandtoms.com/';

		if (strpos($this->post_thumbnail, $local) !== false) {
			$this->post_thumbnail = str_replace($local, $web, $this->post_thumbnail);
		}

		if (strpos($this->post_thumbnail, $staging) !== false) {
			$this->post_thumbnail = str_replace($staging, $web, $this->post_thumbnail);
		}



		if (is_array($vars)) {
			$this->completeChecks($vars);
		}

	}

	//['s', 'date', 'dtype', 'taxonomies', 'size', 'smin', 'smax' ,'lall', 'fall', 'type', 'seasonal']
	// 'type' refers to a specific size search
	public function completeChecks($vars) {
        
		if (array_key_exists('size', $vars))
			if ($vars['size'] !== false)
				$this->generalSizeCheck($vars['size']);
		if (array_key_exists('taxonomies', $vars)) {
			if (array_key_exists('size', $vars['taxonomies']))
				$this->specificSizeCheck($vars['smin'], $vars['smax']);
			elseif (is_array($vars['taxonomies'])) {
				foreach ($vars['taxonomies'] as $taxonomy => $term) {
					$this->taxonomyCheck($taxonomy, $term);
				}
			}
		}
		if (array_key_exists('seasonal', $vars)) {
			if ($vars['seasonal'] !== false) {
				$this->seasonalCheck();
			}
		} elseif (array_key_exists('date', $vars) && array_key_exists('dtype', $vars)) {

			$this->availabilityCheck($vars['date'], $vars['dtype']);

		}
	}

	/**
	 * Check if house should be displayed.
	 * @return bool Returns true if should be shown, false if not shown
	 */
	public function displayCheck() {
        return $this->display;
	}

	/**
	 * Complete general size check on house.
	 * @param integer $size Size to check: 8, 12 or 20
	 */
	public function generalSizeCheck($size) {
		if ($this->display === false) return;
		$this->display = false;

		$sleeps_min = $this->sleeps_min;
		$sleeps_max = $this->sleeps_max;
		$sleeps_min = (!$sleeps_min ? $sleeps_max : $sleeps_min);

		// site_id => array (size_value => array (max, min = 0 if none)).
		// max should be smaller than min except for 0 value
		global $katglobals;
		$limits = $katglobals['sleep_limits'];

		if (array_key_exists($this->blog_id, $limits)) {
			$blog_limits = $limits[$this->blog_id];
			if (array_key_exists($size, $blog_limits)) {
				$limit_max = $blog_limits[$size][0];
				$limit_min = $blog_limits[$size][1];
				if (($sleeps_max < $limit_max || $sleeps_min > $limit_min)) return;
			}
		}

		$this->display = true;
	}

	/**
	 * Complete specific size check on house (for size landing pages)
	 * @param integer $min Minimum to sleep
	 * @param integer $max Maximum to sleep
	 */
	public function specificSizeCheck($min, $max) {
		if ($this->display === false) return;
		$this->display = false;
		$sleeps_min = $this->sleeps_min;
		$sleeps_max = $this->sleeps_max;
		$sleeps_min = (!$sleeps_min ? $sleeps_max : $sleeps_min);
		if (!empty($max) && !empty($min)) if ($sleeps_min > $max || $sleeps_max < $min) return;
		elseif (!empty($max)) if ($sleeps_min > $max) return;
		elseif (!empty($min)) if ($sleeps_max < $min) return;
		$this->display = true;
	}

	/**
	 * Complete taxonomy check for specified taxonomy
	 * @param text $name Taxonomy to check
	 * @param text $value Value to see if true
	 */
	public function taxonomyCheck($taxonomy, $term) {
		if ($this->display === false) return;
		$this->display = has_term($term, $taxonomy, $this->post_id);
	}

	/**
	 * Complete seasonal availability check. #TODO ensure this works
	 */
	public function seasonalCheck() {
		$display = $this->display;
		if ($this->display === false) return;

		if (!is_singular( 'seasonal' )){
			$this->display = false;
		}

		//if (strtotime($day_start) < strtotime("now")) continue; // Maybe use somewhere else?

		$day_start = self::$specified_seasonal_dates[0];  // First day
		$day_end = self::$specified_seasonal_dates[count(self::$specified_seasonal_dates) - 1]; // Last day

		$meta = $this->getBlogAndPostWithBroadcasting();
		$table_name = $meta['table_name'];
		$lookup_id = $meta['post_id'];
		$blog_id = $meta['blog_id'];

		$days_booked = $this->getDaysBooked($blog_id, $lookup_id);
		if ($days_booked === false) return;

		foreach (self::$periodSeasonal as $c => $period) {
			$name = $period['name'];
			if(!isset($period['dates'])) {
				continue;
			}
			foreach ($period['dates'] as $date_range) {
				$day_start_range = $date_range[0];  // First day
				if (!$this->isAvailableForPeriod($date_range, $days_booked)) continue;
				$price_array = $this->getPrices($blog_id, $lookup_id, $name, $day_start_range);

				if ($price_array != null) {
					$price = $price_array[$name];
					if (!isset($this->availableDates[$name])) {
						$this->availableDates[$name] = array($price);
					}
					else {
						array_push($this->availableDates[$name], $price);
					}
				}
			}
		}

		if (empty($this->availableDates)) return $this->display = false;

	 	$this->display = !empty($this->availableDates);
	}


	/**
	 * Complete general availability check.
	 * @param array $dates_to_check Days that are to be checked
	 * @param integer $s_date_type Type of check to be completed, 1,2 or 3
	 */
	public function availabilityCheck($date, $s_date_type) {

/*
		echo get_the_title($this->ID);
		var_dump($date);
		var_dump($this->display);
*/
		//if($this->ID == 23889) {

		$day_of_month = date('d', strtotime($date));
		$last_day_of_month = date('t', strtotime($date));
		$the_day_of_week = date('w', strtotime($date));


		if ($this->display === false) return;
		$this->display = false; // If we find it is available, we will set this to true at the end

		$date = $this->parseDateFormat($date);

		if ($date == "") return;

		$dates_to_check = self::$dates_to_check;

		if ( !$dates_to_check || !in_array($s_date_type,array(1,2,3))) return;

		//var_dump(self::$additional_height);

		self::$additional_height = ($s_date_type == 1 /* || $s_date_type == 2 */ ? 2 : 1);

/*
		echo '<hr>';
		var_dump(self::$additional_height);
*/

		$this->winterText = $this->isWinterMonth($dates_to_check);

		$meta = $this->getBlogAndPostWithBroadcasting();
		$table_name = $meta['table_name'];
		$lookup_id = $meta['post_id'];
		$blog_id = $meta['blog_id'];


		$days_booked = $this->getDaysBooked($blog_id, $lookup_id);

		if ($days_booked === false) return;

		self::$dates_to_check = $this->map_dates_to_check($s_date_type, $dates_to_check);
		
		$day_start = $dates_to_check[0];  // First day

		$day_end = $dates_to_check[count($dates_to_check) - 1]; // Last day

		if (!$this->isAvailableForPeriod($dates_to_check, $days_booked)) {
			return;
		}

		if ($day_of_month == $last_day_of_month && $the_day_of_week == 5) {
			if ($this->isAvailableForPeriod($dates_to_check, $days_booked)) {
				$day_start = $date;
			}
		}

		$this->price = $this->getPrices($blog_id, $lookup_id, $s_date_type, $day_start);

		if ($this->price === false || count($this->price) == 0) return;

/*
		var_dump($lookup_id, $days_booked, $dates_to_check);
		var_dump($this->isAvailableForPeriod($dates_to_check, $days_booked));
		var_dump($day_start);
		wp_die();
*/

		$this->display = true;

		//}

	}

	/**
	 * set bookable days based on date search type
	 *
	 * @param int $s_date_type
	 * @return string $bookable_days
	 */
	public function set_bookable_days($s_date_type) {
		$date_type_checkin_days = array(
			'1' => '2',
			'2' => '6',
			'3' => '3'
		);
		$bookable_days = $date_type_checkin_days[$s_date_type];
		return $bookable_days;
	}

    /**
     * Returns the closest Monday for checking Midweek starting date
     */
    public function get_closest_checkingday($date, $checkin_day, $s_date_type, $bookable_days) {

		// return early if the checkin day is the same as the date being searched
		$day = date('N', strtotime($checkin_day));
		$day_match = $date->format('N');
		if($day_match == $day) {
			return $date;
		}

        $next_checkin_day = new DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/London'));
        $previous_checkin_day = new DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/London'));
        
        $next_checkin_day = $next_checkin_day->modify('next '.$checkin_day);
        $previous_checkin_day = $previous_checkin_day->modify('last '.$checkin_day);
        
        $interval = $date->diff($next_checkin_day);
        if($interval->days > $bookable_days) {
            $closest_checkin_day = $previous_checkin_day;
        } else {
            $closest_checkin_day = $next_checkin_day;
        }
        return $closest_checkin_day;

    }

	private function map_dates_to_check($s_date_type, $dates_to_check) {
		$bookable_days = $this->set_bookable_days($s_date_type);

        $date = new DateTime($dates_to_check[0], new DateTimeZone('Europe/London'));

		if($s_date_type != 3) {
			$closest_day = $this->get_closest_checkingday($date, 'friday', $s_date_type, $bookable_days);
		} else {
			$closest_day = $this->get_closest_checkingday($date, 'monday', $s_date_type, $bookable_days);
		}

        $check_from = $closest_day->format('Y-m-d');
		$dates = array();
		if($s_date_type != 3) {
			// add the first date to check if date type is NOT midweek
			$dates[] = $check_from;
		}
		
		for($i = 1; $i <= $bookable_days; $i++) {
			$dates[] = date('Y-m-d',strtotime($check_from . '+'.$i.' days'));
		}
		self::$dates_to_check = $dates;
		return $dates_to_check;
	}

	// Returns whether a house is available for all dates in a given period
	// @param array $dates What dates are being checked in YYYY-MM-DD format
	// @param array $availability Array with keys of months (YYYY-MM) and array of booked days
	// @returns bool True if house is available
	private function isAvailableForPeriod($dates, $availability) {
		// Go through each date to see if there is a booking

        global $_GET;
		foreach ($dates as $k => $date) {

            if ($this->dateIsBooked($date, $availability)) {
                return false;
            }

		}
		return true;
	}

	// Returns true if the specified date is booked
	private function dateIsBooked($date_to_check, $days_booked) {

		$active_month = date('m-Y',strtotime($date_to_check));
		$active_day = date('j',strtotime($date_to_check));

		// If we don't have the month in the DB, then we should not display the house.
		if (!array_key_exists($active_month, $days_booked)) {
			return true;
		}

		$days_booked_in_current_month = unserialize($days_booked[$active_month]);

/*
var_dump($active_day);
*/

// var_dump($days_booked_in_current_month);



		if ($days_booked_in_current_month === false) {

			return false;

		} elseif (is_string($days_booked_in_current_month)) {


			if ($active_day == $days_booked_in_current_month) {


				return true;
			}

		} elseif (in_array($active_day, $days_booked_in_current_month)) {

			//var_dump($days_booked_in_current_month);

			return true;
		}



		return false;
	}

	// Get prices for a given month (id through meta key e.g. '2') and a lookup id
	// (e.g. 1 for weekends or an exact name if a seasonal search)
	// $day_start is for the first day in current period
	private function getPrices($blog_id, $post_id, $date_search_type, $day_start) {

		// SQL query to get all the values here.
		// $house_meta_prices = $this->queryMetaPrices($meta_key, $table_name, $post_id, $lookup_id);

		$all_periods = $this->queryMetaPricesFromDate($blog_id, $post_id, $day_start);

		if ($all_periods === false) return false;

		$periods = array();

            foreach ($all_periods as $period => $prices) {

                if ($date_search_type == '1') { // Weekend
                    if ( stristr($period, 'weekend') || $period == '5 nights' ) $periods[$period] = $prices;
                }
                elseif ($date_search_type == '2') { // Week
                    if ( $period == 'Week' /* || $period == 'Midweek' */ ) $periods[$period] = $prices;
                }
                elseif ($date_search_type == '3') { // Midweek


                    if ( stristr($period, 'midweek')) {
                        $periods[$period] = $prices;
                    }
                } else { // Seasonal searches or other exact
                    if ($period == $date_search_type) $periods[$period] = $prices;
                }
            }

		$current_blog_id = get_current_blog_id();

		// it was decided that all 5 night prices should be disabled and removed from search results,
		// although it may still come in from the iPro feed
        $month_is_december_or_january = date('m',strtotime($day_start));
        if ($month_is_december_or_january != '12' && $month_is_december_or_january != '01' ) {
            unset($periods['5 nights']);
        }


		// added to remove 1 day mid weeks between normals sites and corporate site
		if ($current_blog_id == 16) {
			unset($periods['Midweek']);
		} else {
			unset($periods['1 night midweek']);
		}
		if ($current_blog_id != 1) {
			unset($periods['2 night midweek']);
		}

		// create an array for the swap between wedding venues and other sites
		$keeps = array('2 night weekend WV','3 night weekend WV','Midweek WV','Week WV');
		$removes = array('1 night weekend','1 night midweek','2 night weekend','3 night weekend','Week','Midweek','5 nights');

		global $katglobals;


		foreach ($katglobals['rate_removals'][$current_blog_id] as $removal) {
			unset($periods[$katglobals['rate_headers'][$removal]]);
		}

		if (!isset($periods)) return false;

		// Weeks start on a Friday, so we want to get the row for the week of the first date ($day_start)

		$weekOfMonth = $this->weekOfMonth($day_start);
		$first_date_in_month = strtotime(substr($day_start, 0, 8).'01');
		$day_numeric_month_start = intval(date('w', $first_date_in_month)); // 0 is Sunday, 1 is Monday...
		$offset = ($day_numeric_month_start + 2) % 7; // 0 is Friday, 1 is Saturday...

		// To find the row that the first day lands on, we must
		// e.g. if the month starts on a Tuesday (offset 4), and we are considering the 15th of that month
		// the 17th is in the 3rd week on the 7th day (Thursday)
		// 17+4-1=20 (-1 as count starts at 1)
		// Every time it is a Friday, it is a new row.

		$row = floor((intval(substr($day_start, 8, 2))+$offset-1)/7); // The first row/week = 0

/*
		if ($post_id == 14767) {
			var_dump($row-1);
		}
*/

		foreach ($periods as $period => $prices) {
			$periods[$period] = array_slice($periods[$period], 0, $row+2);
		}

/*
		if ($post_id == 10716) {
			var_dump($first_date_in_month);
			var_dump($day_numeric_month_start);
			var_dump($day_start);
			var_dump($offset-1);
			var_dump($row+1);
			var_dump(substr($day_start, 8, 2));
			var_dump($periods);
		}
*/
        return $this->getPricesToShow($periods, $day_start);
/*
		var_dump(get_the_title($post_id));
		var_dump($this->getPricesToShow($periods, $day_start));
*/

	}

	public function weekOfMonth($date) {
		// estract date parts
		list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));

		// current week, min 1
		$w = 1;

		// for each day since the start of the month
		for ($i = 1; $i <= $d; ++$i) {
			// if that day was a sunday and is not the first day of month
			if ($i > 1 && date('w', strtotime("$y-$m-$i")) == 0) {
				// increment current week
				++$w;
			}
		}

		// now return
		return $w;
	}

	// New version from new table
	// Returns array mapping month to the booked days or bool(false) if no entry
	private function getDaysBooked($blog_id, $lookup_id) {
		global $wpdb;
		$booked_output = self::getHouseAvailability($blog_id, $lookup_id);
		if (empty($booked_output)) return false;

		$days_booked = array();
		foreach ($booked_output as $m) {
			$days_booked[$m->month] = $m->booked_days;
		}
		return $days_booked;
	}

	private function getBlogAndPostWithBroadcasting() {
		$broadcast_setting = $this->availability_option;
		if ($broadcast_setting == 1) {
			$broadcast_site = $this->availability_site_ref;
			$broadcast_id = $this->availability_site_post_id;

			$site_trailing = ($broadcast_site == 1 ?  '' : $broadcast_site.'_');
			$lookup_id = $broadcast_id;
		}
		else {
			$broadcast_site = $this->blog_id;
			$site_trailing = ($this->blog_id == 1 ?  '' : $this->blog_id.'_');
			$lookup_id = $this->ID;
		}

		$table_name = 'wp_'.$site_trailing.'postmeta';
		return array(
			'blog_id' => $broadcast_site,
			'post_id' => $lookup_id,
			'table_name' => $table_name
		);
	}

	// Allow dates with `/` instead of `-` format
	private function parseDateFormat($date) {
		$date = str_replace('/', '-', $date);
		preg_match('^(.*)[-](.*)[-](.*)$^', $date, $matches);

		if ($matches) {
			if (strlen($matches[3]) == 2) {
				$matches[3] = '20'.$matches[3];
			}
			return $matches[3].'-'.$matches[2].'-'.$matches[1];
		}

		return $date;
	}

	// Returns if it is classified as a Winter month by taking final date and seeing if
	// it is between October and April
	private function isWinterMonth($dates) {
		$final_date = $dates[count($dates)-1];
		$active_month = intval(date('m', strtotime($final_date)));
		$winter_months = array(1,2,3,4,10,11,12);
		return in_array($active_month, $winter_months);
	}

	// NEW version
	private function queryMetaPricesFromDate($blog_id, $lookup_id, $day_start) {

		$output = self::getHouseRates($blog_id, $lookup_id);

		//var_dump($output);

		if (empty($output)) return false;

		$sliced = array_slice($output, 0, 1);
		$first_month = array_shift($sliced);

		$days_booked = unserialize($first_month->rates);
		return $days_booked;
	}


    /**
     * Returns the amount of weeks into the month a date is
     * @param $date a YYYY-MM-DD formatted date
     * @param $rollover The day on which the week rolls over
     */
    private function getWeeks($date, $rollover) {

		// hack end of
		// August 2020
		// Dec 2020
		// Jan 2021
		if(
			$date == '2021-05-29' ||
			$date == '2021-06-25' ||
			$date == '2021-06-26' ||
			$date == '2021-07-30' ||
			$date == '2021-07-31' ||
			$date == '2021-09-24' ||
			$date == '2021-09-25' ||
			$date == '2021-10-30' ||
			$date == '2021-10-29' ||
			$date == '2021-12-31' ||
			$date == '2022-02-25' ||
			$date == '2022-02-26' ||
			$date == '2022-02-27' ||
			$date == '2022-03-25' ||
			$date == '2022-03-26'
		) {
			return 4;
		}
		//$number_of_days = (int)ceil(date( 'j', strtotime( $date ) ) / 7);
	    // commented out to see if the week issue is resolved
		// return (int)ceil( date( 'j', strtotime( $date ) ) / 7 ); // 12 Oct should resolve to the week number and pickup the right rate

        $cut = substr($date, 0, 8);

        $year = substr($date, 0, 4); // represents php year value by number 2018
        $month = substr($date, 5, 2); // represents php month value by number 1 = jan, 12 = dec
        $dayNumber = date('w', strtotime($rollover)); // represents php date value by number 0 = sunday, 6 = saturday
        $ignoreDays = array(0,1,2,3,4,5,6); // represents php date value 'w'
        if (($key = array_search($dayNumber, $ignoreDays)) !== false) {
            unset($ignoreDays[$key]);
        }

//        $weeks = $this->daycount($year, $month , $ignoreDays);
//        return $weeks;

        $daylen = 86400;

        $timestamp = strtotime($date);
        $first = strtotime($cut . "00");
        $elapsed = ($timestamp - $first) / $daylen;

		// hack for July 2021 as the week check it out by one day
		if($cut == '2021-07-' || $cut == '2022-09-') {
			$weeks = 0;
		} else {
			$weeks = 1;
		}
        

        for ($i = 1; $i <= $elapsed; $i++) {
            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);

            $day = strtolower(date("l", $daytimestamp));

            if($day == strtolower($rollover))  $weeks ++;
        }

		return $weeks;
    }

	public function weekOfMonthb($date) {
			//Get the first day of the month.
			$firstOfMonth = strtotime(date("Y-m-01", $date));
			//Apply above formula.
			return $this->weekOfYear($date) - $this->weekOfYear($firstOfMonth);
	}
	
	public function weekOfYear($date) {
		$weekOfYear = intval(date("W", $date));
		if (date('n', $date) == "1" && $weekOfYear > 51) {
			// It's the last week of the previos year.
			return 0;
		}
		else if (date('n', $date) == "12" && $weekOfYear == 1) {
			// It's the first week of the next year.
			return 53;
		}
		else {
			// It's a "normal" week.
			return $weekOfYear;
		}
	}

    public function change_week_based_on_date() {
	    $cockup = false;
        // check if it is 31st Aug 2018 to get the correct rate for the previous month
        if(isset($_GET) && $_GET['date'] != '31-08-2019') {
	        if($_GET['dtype'] == '1' || $_GET['dtype'] == '2') {
            		$cockup = true;
	        }
        }
        return $cockup;
    }

	private function get_seasonal_date_range($periods) {

		foreach ($periods as $period_name => $prices) {
			$key = $period_name;
			break;
		}

		$dtype = array(
			'1' => array('2 night weekend', '3 night weekend'),
			'2' => array('Week', '5 nights'),
			'3' => array('Midweek')
		);

		foreach($dtype as $i => $type) {

			if(!isset($key)) {
				return;
			}

			if(in_array($key, $type)) {
				$datetype = $i;
				break;
			}
		}

		return $datetype;


	}

	private function check_if_to_show_price_and_reorder_array($prices) {
		$new_prices = array();
		$i = 1;
		if($prices[0] == '-1') {
			unset($prices[0]);
		}
		foreach($prices as $k => $price) {
			// if($price == '-1') {
			// 	continue;
			// } 
			$new_prices[$i] = $price;
			$i++;
		}
		$prices = $new_prices;

		return $prices;
	}


	/**
	 * get number of week for a friday 
	 *
	 * @param string $date
	 * @return int $fridays
	 */
	public function get_friday_week_number($date) {
		$fridays = array();
		$x = explode('-', $date);
		$d = new DateTime($date);
		$firstFriday = new DateTime("{$x[0]}-{$x[1]}-01 Friday");
		$endDate = new DateTime($date);
		$int = new DateInterval('P7D');
		foreach(new DatePeriod($firstFriday, $int, $endDate) as $d) {
			$fridays[] = $d->format('Y-m-d');
		}
		if(count($fridays) === 0) {
			return 1;
		}
		return count($fridays);
		
	}

	/**
	 * get number of week for a monday 
	 *
	 * @param string $date
	 * @return int $mondays
	 */
	public function get_monday_week_number($date) {
		$mondays = array();
		$x = explode('-', $date);
		$d = new DateTime($date);
		$firstMonday = new DateTime("{$x[0]}-{$x[1]}-01 Monday");
		$endDate = new DateTime($date);
		$int = new DateInterval('P7D');
		foreach(new DatePeriod($firstMonday, $int, $endDate) as $d) {
			$mondays[] = $d->format('Y-m-d');
		}
		if(count($mondays) === 0) {
			return 1;
		}
		return count($mondays);
		
	}

	private function getPricesToShow($periods, $day_start) {

		$dtype = $this->get_seasonal_date_range($periods);
		if (self::$seasonal) {
			//$dtype = $this->get_seasonal_date_range($periods);
			self::$dates_to_check = self::getDateRange($day_start, $dtype);
		}

		// get the actual week number into the monthe for the date to check
		$get_weeks_number = array();
		foreach (self::$dates_to_check as $k => $stringdate) {

			// -minus natuarl $get_week_number by one number to match the w/c of of the $periods array
			//$get_weeks_number[] = $this->getWeeks($stringdate, 'friday');
			//$get_weeks_number[] = $this->weekOfMonthb(strtotime($stringdate));
			if($dtype != 3) {
				$get_weeks_number[] = $this->get_friday_week_number($stringdate);
			} else {
				$get_weeks_number[] = $this->get_monday_week_number($stringdate);
			}
		}

		// this is a hack if the week number ends up being week 5 all times in the array and equals 10
		// if(array_sum($get_weeks_number) == 10) {
		// 	$get_weeks_number = array(4,4);
		// }

		$prices_to_show = array();

		// For each of the periods that we have, add the price and keep updating until we reach the end of the array.
		foreach ($periods as $period_name => $prices) {

			$prices = $this->check_if_to_show_price_and_reorder_array($prices);

/*
			var_dump($get_weeks_number);
			var_dump($prices);
*/

			foreach ($get_weeks_number as $week_number) {
				// if($week_number > 4) {
				// 	--$week_number; //@TODO week number doesn't translate to the the array index so minus the index
				// }
				if( array_key_exists($week_number, $prices) ) {

	                $cost = $prices[$week_number];

	/*
	                var_dump($this);
	                var_dump($prices);
	                var_dump($week_number);
	*/

				   //var_dump(self::change_week_based_on_date());

	/*
	                if(self::change_week_based_on_date()) {
		                $week_number = --$week_number;
		                $cost = $prices[$week_number];
	                }
	*/

	                if(self::check_end_of_month_cockup()) {
	                    $cost = $prices[5];
	                }

                    if(self::check_first_may_2020()) {
	                    $cost = $prices[0];
	                }

					if ($cost != "0" && $cost != "") {
						$prices_to_show[$period_name] = $cost;
					}
					if ($cost == "-1" || $cost == "-2") {
					    unset($prices_to_show[$period_name]);
	                }

				}
				break;

			}
/*
			foreach ($prices as $i => $price) {

				if ($price != "0" && $price != "") {
					$prices_to_show[$period_name] = $price;
				}
				if ($price == "-1" || $price == "-2") {
				    unset($prices_to_show[$period_name]);
                }
			}
*/


//			if (array_key_exists($period_name, $prices_to_show)) {
//				if ($prices_to_show[$period_name] == "-1") return false;
//			}
		}


		if (empty($prices_to_show)) return false;

		return $prices_to_show;
    }
    
    public function check_first_may_2020() {
        $cockup = false;

		if(!isset($_GET['date'])) {
			return;
		}

        // check if date is 1st of May 2020

        if($_GET['date'] == '01-05-2020') {
	        if($_GET['dtype'] == '1' || $_GET['dtype'] == '2') {
            	$cockup = true;
	        }
        }


        return $cockup;

    }


	/**
	 * if currency has been set use it otherwise fallback to £
	 * @return string
	*/
	public function checkCurrency() {
		$currencies = array( '&pound;', '&euro;' );
		$this->currency = get_field('house_currency', $this->ID);
		if (empty($this->currency)) {
			$currency = '&pound;';
		} else {
			$currency = $currencies[$this->currency];
		}
		return $currency;
	}

	/**
	 * Display the house formatted correctly.
	 * Produces the search box as required.
	 */
	public function displayHouse($color = 'yellow', $height = 306, $offerimg = false, $offer = false) {
        
        //if($this->post_title == 'Swallows Croft')

		$seas = self::$seasonal;
		$availableDates = count($this->availableDates);

		if(self::$seasonal && count($this->availableDates) == 0) {
			$this->display = false; return false;
		}

		if($this->price) {
			if(in_array('-2', $this->price)) {
				$this->display = false; return false;
			}
		}

		if ($offerimg) {
			switch_to_blog(11);
			$image = wp_get_attachment_image_src($offerimg, 'house_search');
			$image = $image[0];
			restore_current_blog();
		} else {
			$image = false;
		}

		if ($image) {
			$image = $image;
		} else {
			$image = $this->post_thumbnail;
		}

		// change image to work locally
		$local = 'kateandtoms.test';
		$staging = 'staging.kateandtoms.com';
		$web = 'kateandtoms.com';

		if (strpos($this->post_thumbnail, $local) !== false) {
			$image = $this->post_thumbnail = str_replace($local, $web, $this->post_thumbnail);
		}

		if (strpos($this->post_thumbnail, $staging) !== false) {
			$image = $this->post_thumbnail = str_replace($staging, $web, $this->post_thumbnail);
		}
		
		$sleeps_min = floor($this->sleeps_min);
		$sleeps_max = floor($this->sleeps_max);
  		$location_text = $this->location_text;
		$brief_desc = $this->brief_description;
		$brief_desc_winter = $this->brief_description_winter;
		$title = $this->post_title;
		$blogid = $this->blog_id;
		$capacity = ($blogid == 12 ? 'Wedding Size ' : 'Sleeps ');

/*
		var_dump($brief_desc);
		var_dump($this->winterText);
		var_dump($brief_desc_winter);
*/

//if($this->ID === '54292') {


		echo '<span class="testing span3" id="'.$this->ID.'">',
			'<a href="'.$this->permalink.'" class="search-block ',
			(self::$additional_height ? 'search-add_height_'.self::$additional_height : ''),
			'">',
			// do_action('houses_specials_banner',$this->ID),
			'<img src="'.$image.'" alt="' . $title . '" style="width:100%" />',
			'<span class="text bg-'.$color.'"><h2 class="entry-title bg-' . $color . '">' . $title . '</h2></span>';

		echo '<div class="house_desc">', ($this->winterText === true && $brief_desc_winter != "" ? $brief_desc_winter : $brief_desc),'</div>';

		echo '<div class="house_meta"><span class="floatleft">' . $capacity, ($sleeps_min > 0 ? $sleeps_min . ' to ' : '') ,$sleeps_max, '</span><span class="floatright">', $location_text,'</span></div>';

//}
		if ($this->price != NULL && count($this->price) > 0) $this->displaySingularPrice();

		elseif (self::$seasonal) $this->displaySeasonalPrices();
		elseif ($offer) {
			//$more = (strlen($offer) >= 77 ? '...' : '');
			//$offer = substr($offer,0,77) . $more;
			echo '<div class="house_meta offer_details">'.$offer.'</div>';
		}
		echo '</a></span>';
		return true;
	}

	/**
	 * Display prices where just one date is being checked.
	 * For example, a normal availability search.
	 */
	public function displaySingularPrice() {
		echo '<div class="house_meta '.$this->post_id.'">';

		$fromAll = get_field('all_prices_with_from');
		$currency = $this->checkCurrency();

		$array = $this->price;

		foreach ($array as $name => $value) {
			// remove wedding prices
			// var_dump($name);

			$newname = str_replace(' WV', '', $name);

			echo $newname;

			if (strstr($value, '+') !== false) {
				$fromOnce = true;
				$value = str_replace('+', '', str_replace('*', '', str_replace(' ', '', $value)));
			} else {
				$fromOnce = false;
			}

			$discount = get_post_meta( $this->ID, 'discount_logic', true);

			if(!empty($discount)) {
				$value = $this->apply_discount_logic($discount, $value);
			}

			echo ($fromAll === true || $fromOnce === true ? ' from ' : ' - '), $currency, $value, '<br/>';
		}
		echo '</div>';
	}

    /**
     * Get an array to check against week number and first occurance of the day for that week
     *
     * @author Elliott Richmond <elliott@squareonemd.co.uk>
     * @return array $ordinals a set of text strings that represents week number for the rate month
     */
    public function get_ordinals() {
        $ordinals = array(
//            'zero','first', 'second', 'third', 'fourth', 'fifth'
            '0' => 'first', '1' => 'second', '2' => 'third', '3' => 'fourth', '4' => 'fifth'
        );
        return $ordinals;
    }

    /**
     * Get an array of days that start the KTs rate period starting day
     *
     * @author Elliott Richmond <elliott@squareonemd.co.uk>
     * @return array $period_start_days a set of text string of the date the period starts
     */
	public function get_kt_rate_start_days(){
        $period_start_days = array(
            '','','Friday','Friday','Friday','Friday','Monday'
        );
        return $period_start_days;
    }

    public function get_the_period($dtype) {

	    $array = array(
		    '2 night weekend',
		    'Week',
		    'Midweek'
	    );
	    return $array[$dtype];
    }

    public function roundUpToAny($n,$x=5) {
        return (ceil($n)%$x === 0) ? ceil($n) : round(($n+$x/2)/$x)*$x;
    }

    /**
     * Get the rate change to reflect the dicou
     *
     * @author      Elliott Richmond <elliott@squareonemd.co.uk>
     * @param       string      $from     the start date
     * @param       string      $to       the end date
     * @param       string      $date_to_check       the date to check
     * @param       string      $rate       the current rate
     * @param       string      $percentage       the discount to apply
     * @return      string      $rate       the discounted rate if it qualifies
     */
    public function get_discounted_rate($from, $to, $date_to_check, $rate, $percentage, $period) {

	    //var_dump($from);
	    //var_dump($to);
	    //var_dump($date_to_check);
	    //var_dump($rate);
	    //var_dump($percentage);
	    //var_dump($period);

        if(strpos($rate, '*')) {
            return $rate;
        }
        if (($date_to_check <= $to)) {
            $rate = str_replace(',','',$rate);
            $rate = $rate * ((100-$percentage) / 100);
            $rate = $this->roundUpToAny($rate);
            $rate = number_format($rate) . '†';
        }
        return $rate;
    }

	/**
	 * Apply all discount logic and return the discounted value.
	 *
	 */
	public function apply_discount_logic($discount, $rate) {

		$date_commencing = date('m-Y', strtotime($_GET['date']));
		$period = $this->get_the_period($_GET['dtype']);

        foreach($discount as $k => $v) {

            if (get_post_meta( $this->ID, 'discount_logic_'.$k.'_logic_type', true ) == 'set') {
                $from = get_post_meta( $this->ID, 'discount_logic_'.$k.'_from', true );
                $to = get_post_meta( $this->ID, 'discount_logic_'.$k.'_to', true );
                $from_date = date('m-Y', strtotime($from));
                $percentage = get_post_meta( $this->ID, 'discount_logic_'.$k.'_discount', true);

                if($from_date == $date_commencing) {

                    $datearray = explode('-', $date_commencing);
                    $m = $datearray[0];
                    $y = $datearray[1];

                    $ordinals = $availability_class->get_ordinals();
                    $period_start_days = $this->get_kt_rate_start_days();
                    $date_to_check = $ordinals[$week].' '. $period_start_days[$r];
                    $date_to_check = date('Ymd',strtotime($date_to_check . ' ' . $y.'-'.$m));

                    $rate = $this->get_discounted_rate($from, $to, $date_to_check, $rate, $percentage, $period);

                }


            }
            if (get_post_meta( $this->ID, 'discount_logic_'.$k.'_logic_type', true ) == 'rolling') {

                $from = date('Ymd',strtotime('today'));
                //$from_date = date('m-Y', strtotime($from));
                $number_of_days = get_post_meta( $this->ID, 'discount_logic_'.$k.'_no_rolling_days', true );
                $to = date('Ymd',strtotime('+'.$number_of_days.' days' ));
                $percentage = get_post_meta( $this->ID, 'discount_logic_'.$k.'_discount', true);

                //if($from_date == $date_commencing) {
                    $datearray = explode('-', $date_commencing);
                    $m = $datearray[0];
                    $y = $datearray[1];

                    $ordinals = $this->get_ordinals();

                    $period_start_days = $this->get_kt_rate_start_days();

                    $date_to_check = $ordinals[$week].' '. $period_start_days[$r];

                    $date_to_check = date('Ymd',strtotime($date_to_check . ' ' . $y.'-'.$m));
                    $rate = $this->get_discounted_rate($from, $to, $date_to_check, $rate, $percentage, $period);


                //}

            }
        }

        return $rate;

	}

	/**
	 * Count how many specific days there are in a given month.
	 * For checking number of weeks for a given search .
	 * eg: echo countDays(2013, 1, array(0, 6)); // 23 - 0 = Sunday 6 = Saturday
	 */
	public function daycount($year, $month, $ignore) {
	    $count = 0;
	    $counter = mktime(0, 0, 0, $month, 1, $year);
	    while (date("n", $counter) == $month) {
	        if (in_array(date("w", $counter), $ignore) == false) {
	            $count++;
	        }
	        $counter = strtotime("+1 day", $counter);
	    }
	    return $count;
	}


	/**
	 * Display prices where more than one date is being checked.
	 * Mainly seasonal pages.
	 */
	public function displaySeasonalPrices() {

		echo '<div class="house_meta seasp">';

		$fromAll = get_field('all_prices_with_from');

		$currency = $this->checkCurrency();

		foreach ($this->availableDates as $key => $values) {

			//var_dump($values);
			if(in_array('-2', $values)) {
				continue;
			}

			if (count($values) > 1) {
				$key = $key.'s';
				$key = str_replace('ss','s',$key);
			}
			echo ucfirst($key);
			$rates = $values;
			$plus = false;

			foreach ($values as $value) {
				if (strstr($value, '+')) {
					$plus = true;
					break;
				}
			}
			$values = str_replace('+', '', str_replace('*', '', str_replace(' ', '', $values)));
			
			echo ($fromAll != true && count($rates) == 1 && $plus == false ? ' - ' : ' from '), $currency, $this->convert_from_price($values), '<br/>';
		}
		echo '</div>';
	}

	/**
	 * Convert string to interger then find the min value and convert back to a string
	 *
	 * @param array $values
	 * @return string $from
	 */
	public function convert_from_price($values){
		$intvalues = array();
		foreach($values as $value) {
			$number = str_replace(',', '', $value);
			$number = (int)$number;
			$intvalues[] = $number;
		}
		$from = min($intvalues);
		$from = number_format($from);
		return $from;

	}

	/**
	 * Returns the dates that are available.
	 * Used in seasonal availability display.
	 * @return array Available dates
	 */
	public function getAvailableDates() {
		return $this->availableDates;
	}

}

?>
