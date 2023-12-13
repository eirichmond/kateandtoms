<?php

/**

	Note on KT's period rates and days:

	2 night weekend = Friday(1) +1day checkout Sunday
	3 night weekend = Friday(1) +2day checkout Monday
	Week = Friday(1) +6day checkout Friday
	Midweek = Monday(1) +3day checkout Friday
	2 night midweek = Monday, Tues or Weds(1) +1day checkout +1day from checkin


**/

/**
 * Defines availability functionality for products on the sites.
 *
 * @package kate-and-toms
 */

/**
 * Defines overall availability functionality for site.
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2012
 */
abstract class ProductAvailability {

	/**
	 * Product name.
	 * @var string Name of the product.
	 */
 	protected $name;

	/**
	 * Product ID.
	 * @var integer ID of the product.
	 */
 	protected $ID;

 	/**
 	 * Number of keys
 	 * @var integer Total number of keys.
 	 */
 	protected $keyCount;

 	/**
 	 * Map of keys to dates
 	 * @var array Keys to dates.
 	 */
 	protected $key;

	/**
	 * Pricing details.
	 * @var object Mapped months with details.
	 */
 	protected $details = array();

	/**
	 * The keys for each month.
	 * @var object Months and their keys.
	 */
 	protected $monthKeys;

	/**
	 * Dates booked.
	 * @var object Months and their booked days.
	 */
 	protected $bookedDates;

	/**
	 * Month's rates.
	 * @var object Months and their booked days.
	 */
 	protected $rates;

	/**
	 * Is last day of previous month booked.
	 * @var bool True is booked, false if free.
	 */
 	protected $bookedFinalLastMonth = true;

	/**
	 * Whether to display the price.
	 * @var bool True if show week prices.
	 */
 	protected $displayPrices = true;

 	/*
	 	what currency to show
 	*/
 	protected $currency;

	/**
	 * Setup new availability.
	 */
	public function __construct($ID) {
		$this->ID = $ID;
	}

	public function getName() {
		return $this->name;
	}

	public function getMeta($str)
	{
		return get_post_meta($this->ID, $str, true);
	}

}

/**
 * Extends functionality for houses.
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2012
 */
class HouseAvailability extends ProductAvailability {

	/**
	 * Setup and produce new availability calendar.
	 *
	 * @param integer ID of product to check.
	 */
	public function __construct($ID)
	{
		$this->ID = $ID;
		$data = get_post($this->ID);
		$this->name = $data->post_name;



		$broadcast_setting = get_post_meta($ID, 'availability_option', true);

		if ($broadcast_setting == 1) {
			$broadcast_site = get_post_meta($ID, 'availability_site_ref', true);
			$broadcast_id = get_post_meta($ID, 'availability_site_post_id', true);

			if (!empty($broadcast_site) && !empty($broadcast_id)) {
				$current_site = get_current_blog_id();
				if ($current_site !== $broadcast_site) {
					switch_to_blog($broadcast_site);
					$switched = true;
				}
				$data = get_post($broadcast_id);
				$this->ID = $broadcast_id;
			}
			else {
				echo 'Notice: Broadcast site and post ID must be filled in to work correctly.';
			}
		}

		$this->keyCount = $this->getMeta('availability_calendar');

		$detailCount = $this->getMeta('price_details_extra');
		
		if(!empty($detailCount)) {
			$details = range(0,$detailCount-1);
		}

		if(!empty($this->keyCount)) {
			$keys = range(0,$this->keyCount-1);
		}

		foreach ($details as $d) {
			$month = $this->getMeta('price_details_extra_'.($d).'_month_details');
			$this->details[$month] = $this->getMeta('price_details_extra_'.($d).'_details_period');
		}

		$this->setMonthKeys();
		$this->setBookedDates();

		foreach ($keys as $key) {
			$this->monthCheck($key);
		}
		if (isset($switched)) restore_current_blog();
	}

	/**
	 * Check if -2 is present in the rate.
	 *
	 * @param array to lookup.
	 * @return array of keys to remove.
	 */
	public function check_minus_two($rates) {

		$array = array();
		foreach ($rates as $rate){
			$i = 0;
			foreach ($rate as $col) {
				if ($col == '-2') {
					$array[] = $i;
				}
				$i++;
			}
		}
		return $array;
	}

	/**
	 * Get names of the periods that are being looked up.
	 *
	 * @param integer Key of month being looked up.
	 * @todo If a period specified, only return that period.
	 */
	private function getPeriodNames($key, $specifiedPeriod = null)
	{
		$rateKeys = range(0,get_post_meta($this->ID, 'availability_calendar_'.($key).'_rate_types', true));
		$periods = array();

		$rates = get_post_meta($this->ID, 'availability_calendar_'.($key).'_rates', true);
		$rates = unserialize($rates);

		$remove_column = $this->check_minus_two($rates);

		foreach ($rateKeys as $k) {
			if (in_array($k, $remove_column)) continue;
			$period = get_post_meta($this->ID, 'availability_calendar_'.($key).'_rate_types_'.$k.'_period', true);
			if ($period && $period !== 'null') $periods[$k] = $period;
		}

		// added to remove 1 day mid weeks between normals sites and corporate site
		$blogid = $GLOBALS['current_blog']->blog_id;

		if ($blogid != 16) {
			$remove = '1 night midweek CV';
			if(($key = array_search($remove, $periods)) !== false) {
			    unset($periods[$key]);
			}
		}

		if ($blogid == 16) {
			$remove = 'Midweek';
			if(($key = array_search($remove, $periods)) !== false) {
			    unset($periods[$key]);
			}
		} else {
			$remove = '1 night midweek';
			if(($key = array_search($remove, $periods)) !== false) {
			    unset($periods[$key]);
			}
/*
			$remove = '1 night event';
			if(($key = array_search($remove, $periods)) !== false) {
			    unset($periods[$key]);
			}
*/
		}
		// create an array for the swap between wedding venues and other sites
		$keeps = array('2 night weekend WV','3 night weekend WV','Midweek WV','Week WV');
		// this is only for weddings site id
		if ($blogid == 12) {

			$result = array_diff($periods, $keeps);

			$newresult = array_diff($periods, $result);

			$periods = $newresult;

			if(($key = array_search($keeps, $periods)) !== false) {
				unset($periods[$key]);
			}
		} else {
			$newresult = array_diff($periods, $keeps);
			$periods = $newresult;
		}

		return $periods;

	}

	/**
	 * Set keys for all months
	 */
	public function setMonthKeys() {
		if(get_post_meta($this->ID, 'availability_calendar', true)) {

			$keys = range(0,get_post_meta($this->ID, 'availability_calendar', true)-1);
	
			foreach ($keys as $key) {
				$month = $this->getMeta("availability_calendar_{$key}_month");
				$this->monthKeys[$month] = $key;
				$this->key[$key] = $month;
			}
		}
	}

	/**
	 * Returns the key for a specified month
	 *
	 * @param integer Timestamp
	 * @return integer Key of month
	 */
	public function getMonthKey($time)
	{
		$month = date('m-Y', $time);
		return $monthKeys->$month;
	}

	/**
	 * Returns the key for a specified month
	 *
	 * @param integer Timestamp
	 * @return integer Key of month
	 */
	public function getMonth($key) {
		if($key == -1) {
			return;
		}
		return $this->key[$key];
	}


	public function setBookedDates()
	{
		for ($i = 0; $i <= $this->keyCount; $i++) {
			$days = $this->getMeta("availability_calendar_{$i}_availability-days");
			$rates = $this->getMeta("availability_calendar_{$i}_rates");
			$month = $this->getMeta("availability_calendar_{$i}_month");


			$this->bookedDates[$i] = $days;

			// New format is serialized
			if ((int)$rates == 0) {
				$this->rates[$i] = unserialize($rates);

			}
			// Old format is in ind. fields so use ACF's get_field function
			else {
				$availability = get_field('availability_calendar', $this->ID);
				foreach ($availability as $k => $v) {
					$this->rates[$k] = $v['rates'];
				}
				return;
			}
		}

	}

	public function getFridays($y, $m)
	{
	    return new DatePeriod(
	        new DateTime("first friday of $y-$m"),
	        DateInterval::createFromDateString('next friday'),
	        new DateTime("last day of $y-$m")
	    );
	}

	/**
	 * Get week number for the date.
	 *
	 * @param $time a timestamp
	 * @param $rollover The day on which the week rolls over
	 * @return Returns the amount of weeks into the month a date is
	 */
	private function getWeekNumber($time, $rollover = 5)
	{
		$date 	= date('Y-m-d', $time);
	    $cut    = substr($date, 0, 8);
	    $daylen = 86400;

	    $first     = strtotime($cut . "00");
	    $elapsed   = ($time - $first) / $daylen;

	    $i     = 1;
	    $weeks = 1;

	    for ($i; $i <= $elapsed; $i++) {
	        $dayfind      = $cut . (strlen($i) < 2 ? '0' . $i : $i);
	        $daytimestamp = strtotime($dayfind);

	        $day = strtolower(date("l", $daytimestamp));

	        if ($day == strtolower($rollover))
	            $weeks++;
	    }

	    return $weeks;
	}

	/**
	 * Get the price for a specified week and rate type.
	 *
	 * @param integer Key of month that we're in
	 * @param integer Week number that we're in
	 * @param integer Rate number we're looking for
	 * @return string Price (and any extra data like stars)
	 */
	private function getPrice($key, $weekNumber, $rateNumber)
	{
		$price = $this->getMeta("availability_calendar_{$key}_rates_{$weekNumber}_rate_{$rateNumber}");

		// If no price, and not in first week, return price from previous week.
		if ($price == 0 && $weekNumber > 0) return getPrice($key, $weekNumber-1, $rateNumber);

		return $price;
	}

	public function showPricingDetails($key, $star = true)
	{
		if (isset($this->details[$this->getMonth($key)]) || $star == true) {
			echo '<div class="price_details">'.$this->details[$this->getMonth($key)].'</div>';
		}
	}

	public function bookedLastDayOfMonth($key, $numDays)
	{
		if (isset($this->bookedDates[$key][$numDays])) return true;
		return false;
	}

	private function get_number_of_days_in_month($key) {
		$date = $this->getMonth($key);
		$month = strtotime('01-'.$date);
		$numDays = cal_days_in_month(CAL_GREGORIAN,date('m',$month),date('Y',$month));
		return $numDays;
	}

	private function checkLastDayCheckin($key, $dayNum, $firstAvail, $numDays) {

		$lastkey = $key-1;
		$nextkey = $key+1;

		$thisbookedDays = get_post_meta($this->ID, 'availability_calendar_'.($key).'_availability-days', true);
		$nextbookedDays = get_post_meta($this->ID, 'availability_calendar_'.($nextkey).'_availability-days', true);

		$date = $this->getMonth($key);

		// create a string from the current date
		$current_string_date = $dayNum.'-'.$date;
		$day_of_week = date('N', strtotime($current_string_date));


/*
		var_dump('availability_calendar_'.($key).'_availability-days');
		var_dump($thisbookedDays);
		var_dump('availability_calendar_'.($nextkey).'_availability-days');
		var_dump($nextbookedDays);
*/

		$numDaysThisMonth = $this->get_number_of_days_in_month($key);

/*
		var_dump($numDaysThisMonth);
		var_dump($dayNum);
		var_dump($numDays);
*/

		if (is_array($thisbookedDays)) {
			if (isset($thisbookedDays) && in_array($numDaysThisMonth, $thisbookedDays)) {
			    if (is_array($nextbookedDays)) {
	                if ($dayNum == $numDays && !in_array(1, $nextbookedDays)) {
	                    if ($day_of_week == '5' || $day_of_week == '1') {
	                        return true;
	                    } else {
	                        return false;
	                    }
	                }
	            }
			}
		}
	}

	private function checkFirstDayCheckin($key, $dayNum, $firstAvail, $numDays) {

		$lastkey = $key-1;
		$nextkey = $key+1;

		$lastbookedDays = get_post_meta($this->ID, 'availability_calendar_'.($lastkey).'_availability-days', true);
		$thisbookedDays = get_post_meta($this->ID, 'availability_calendar_'.($key).'_availability-days', true);
		$nextmonthsbookedDays = get_post_meta($this->ID, 'availability_calendar_'.($nextkey).'_availability-days', true);

		$date = $this->getMonth($key);
		$month = strtotime('01-'.$date);
		$numDays = cal_days_in_month(CAL_GREGORIAN,date('m',$month),date('Y',$month));

		$date_last_month = $this->getMonth($lastkey);
		$last_month = strtotime('01-'.$date_last_month);
		$numDaysLastMonth = cal_days_in_month(CAL_GREGORIAN,date('m',$last_month),date('Y',$last_month));

		// create a string from the current date
		$current_string_date = $dayNum.'-'.$date;
		$day_of_week = date('N', strtotime($current_string_date));

		//var_dump($nextmonthsbookedDays);




/*
		var_dump($thisbookedDays);
		var_dump(!in_array(1, $thisbookedDays));
*/


/*
		var_dump($lastbookedDays);
		var_dump($numDays);
*/

		// check if the last date is unavailable
		if (is_array($lastbookedDays)) {
			if (isset($lastbookedDays) && end($lastbookedDays) == (int)$numDaysLastMonth && $dayNum == 1) {
				// first check if this is newyears day and bail early
				if (date('z', strtotime($current_string_date)) === '0' || empty($thisbookedDays) || !in_array($dayNum, $thisbookedDays)) {
					return false;
				}
				// if this is first day of month and is available
				elseif ($firstAvail && !in_array(1, $thisbookedDays)) {
					return true;
				}
			}
		}

		// added to check if the last day of the previous month is booked
		// and this is the first day on a Friday or a Monday that is available
		if (isset($lastbookedDays) && !empty($lastbookedDays)) {

			//var_dump($lastbookedDays);
			$date = $this->getMonth($lastkey);
			$month = strtotime('01-'.$date);
			$numDays = cal_days_in_month(CAL_GREGORIAN,date('m',$month),date('Y',$month));
			if(end($lastbookedDays) == $numDays && $dayNum == 1) {

/*
				var_dump(end($lastbookedDays));
				var_dump($thisbookedDays);
				var_dump('end: '.end($lastbookedDays) . ' $numDays: '.$numDays . ' $dayNum: '.$dayNum);
*/
				if (!in_array('1', $thisbookedDays) && end($lastbookedDays) == $numDays) {
					return true;
				} elseif (in_array($dayNum, $thisbookedDays)) {
					return false;
				} elseif (!in_array('1', $thisbookedDays) && $day_of_week == '5' || $day_of_week == '1') {
					return true;
				}

			}

		}

	}

	/**
	 * Get the class for the specified date. If free, completely free.
	 *
	 * @param integer Day that is being looked up
	 * @param array All dates that have been booked
	 * @param integer Number of days in the month
	 * @param bool Availability of the first day of the next month, true if available
	 * @return string Class for date
	 */
	private function getDateClass($dayNum, $bookedDays, $numDays, $firstAvail, $dayAsTime, $key)
	{
/*
		echo '<hr>';
		echo '$dayNum';
		var_dump($dayNum);
		echo '$bookedDays';
		var_dump($bookedDays);
		echo '$numDays';
		var_dump($numDays);
		echo '$firstAvail';
		var_dump($firstAvail);
		echo '$dayAsTime';
		var_dump($dayAsTime);
		echo '$key';
		var_dump($key);
		echo '$numDays';
		var_dump($numDays);
		echo '<hr>';
*/

		if (date('d-m-Y') == date('d-m-Y', $dayAsTime)) return 'today';

		$firstdaycheckin = $this->checkFirstDayCheckin($key, $dayNum, $firstAvail, $numDays);

/*
		var_dump($firstdaycheckin);
		var_dump('key: ' .$key . ', daynumber: ' . $dayNum . ', $firstAvail: ' . $firstAvail . ', numDays: ' . $numDays);
*/

/*
	Seriously bad hack to override Polpier & Polpier and Penpol for 27/28 Apr 2017
*/
		$date = $this->getMonth($key);
		if ($this->ID == 13610 || $this->ID == 14936) {
			if ($dayNum.'-'.$date == '31-08-2020') {
				return 'bk_unav';
			}
		}
		if ($this->ID == 13610 || $this->ID == 14936) {
			if ($dayNum.'-'.$date == '1-09-2020') {
				return 'bk_avail halfafter';
			}
		}


/*
	After this date 27/28 Apr 2017, this block should be removed HACKARAMA!!
*/

		if ($firstdaycheckin) {
			return 'bk_avail halfafter';
		}



		$lastdaycheckin = $this->checkLastDayCheckin($key, $dayNum, $firstAvail, $numDays);

		if ($lastdaycheckin) {
			return 'bk_avail halfafter';
		}

		//var_dump($firstdaycheckin);

		// 12 August 2016
		// add "&& $lastdaycheckin != null" to resolve the first day of the month being available hangover if the last day of the last month is null
		if ($firstdaycheckin && $lastdaycheckin != null) {
			return 'bk_avail halfafter';
		}

		if ($dayAsTime < time() || ($dayNum == end($bookedDays) && $numDays == end($bookedDays) && $firstAvail)) return 'bk_unav';

		if (!in_array($dayNum, $bookedDays)) return 'bk_avail';

		if (($dayNum == $numDays && $firstAvail) || (!in_array($dayNum+1, $bookedDays) && $numDays > $dayNum) ) return 'bk_avail halfafter';


		if (($dayNum == 1 && !$this->bookedFinalLastMonth) || (!in_array($dayNum-1, $bookedDays) && $dayNum != 1)) return 'bk_avail halfbefore';

		return 'bk_unav';
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
	 * Calculate and display prices in table form.
	 *
	 * @param key of current month
	 * @param week number
	 * @param the periods to display
	 * @param days of week that are available (true if available, false if not, null if does not exist)
	 * @param whether end of month
	 * @param next month's availability
	 * @return bool Whether star is present
	 *
	 */
	private function showPrices($key, $week, $periods, $daysOfWeek = null, $endOfMonth = false, $bookedDaysNext = null, $nextSatAvail = true, $group = false)
	{


/*
		echo '<p>$key</p>';
		echo '<pre>';
		print_r($key);
		echo '</pre>';

		echo '<p>$week</p>';
		echo '<pre>';
		print_r($week);
		echo '</pre>';


		echo '<p>$daysOfWeek</p>';
		echo '<pre>';
		print_r($daysOfWeek);
		echo '</pre>';

		echo '<p>$endOfMonth</p>';
		echo '<pre>';
		print_r($endOfMonth);
		echo '</pre>';

		echo '<p>$bookedDaysNext</p>';
		echo '<pre>';
		print_r($bookedDaysNext);
		echo '</pre>';

		echo '<p>$nextSatAvail</p>';
		echo '<pre>';
		print_r($nextSatAvail);
		echo '</pre>';

		$date = $this->getMonth($key);
		echo '<p>$date</p>';
		echo '<pre>';
		print_r($date);
		echo '</pre>';

		var_dump($periods);
*/

		$fromAll = get_field('all_prices_with_from');

		$star = false;
		$currency = $this->checkCurrency();

/*
		if (!is_null($daysOfWeek)) {
			$periods = $this->keepValidPeriods($periods, $daysOfWeek, $endOfMonth, $key, $bookedDaysNext, $nextSatAvail, $week);
		}

*/
		if (!is_null($daysOfWeek)) {
			$periods = $this->keepValidPeriods($periods, $daysOfWeek, $endOfMonth, $key, $bookedDaysNext, $nextSatAvail, $week);
		}

		foreach ($periods as $r => $period) {

            if ($period == 'blank') echo '<td class="table_price"></td>';
			elseif ($period == 'booked') {

				// *****************************
				// Begin hack
				// *****************************
				// THIS IS A VERY VERY BAD HACK
				// AND MUST NEVER BE ATTRIBUTED
				// TO MR ELLIOTT RICHMOND
				// HE WAS MADE TO DO IT!
				// *****************************

				$included = array(
					// kate&toms house ids
					19660,
					19733,
					11069, // Pedington
					16914,
					56703,
					14767, // marsden manor
					90200, // ascot house
					// bigcottage house ids
					// 45772
					// 45755
					// 45723
					// 16914
					// 56703
					// 39234 // marsden manor from KTs
				);

				// begin nested conditional
				$rates = $this->rates;
				$date_commencing = $this->key[$key];
				if($date_commencing == '12-2023' && $week == 4 && in_array($this->ID, $included) && $rates[$key][$week]['rate_'.($r+1)] != '-1') {
					
					if (stripos($rates[$key][$week]['rate_'.($r+1)], '+') !== false) {
						$from = true;
					}

					if($from) {
						$rate = str_replace('+', 'From '.$currency, $rates[$key][$week]['rate_'.($r+1)]);
					} else {
						$rate = $currency. $rates[$key][$week]['rate_'.($r+1)];
					}

					echo '<td class="table_price">'.$rate.'</td>';

				} else {
					// when time has past this should be the default and can be refactored
					echo '<td class="table_price booked_text">Booked</td>';
				}
				// end nested conditional
			}

			// *****************************
			// Begin hack
			// *****************************
			// THIS IS A VERY VERY BAD HACKY
			// AND MUST NEVER BE ATTRIBUTED
			// TO MR ELLIOTT RICHMOND
			// HE WAS MADE TO DO IT!
			// *****************************
			//
			//
			// UNTIL i_PRO fix the incomming feed with the extra
			// week commencing that is missing from the CRM it will always be an issue

			elseif ($period == '136951020174' && $this->ID == 13610) {
				echo '<td class="table_price">&pound;1,000</td>';
			}
			elseif ($period == '136951020175' && $this->ID == 14936) {
				echo '<td class="table_price">&pound;2,400</td>';
			}



			// *****************************
			// End hack
			// *****************************

			else {
				$rates = $this->rates;

				$group = '12';

				if($group) {
					//echo $key;
					//var_dump($rates[$key][$week]['rate_'.($r+1)]);
					//var_dump($rates[$key][$week]['rate_'.($r+1)]);
				}

				$rolling_offer = $this->check_discount_logic_v2($rates, $key, $week, $period, $r);
				//var_dump($rolling_offer);

				$rate = $rates[$key][$week]['rate_'.($r+1)];

				if ($key == 35 && $week == 0 && $period == 'Midweek') {
					$key = 34; $week = 4;
					$rate = $rates[$key][$week]['rate_'.($r+1)];
				}

				if ($rate == 0 && $week != 0) $this->showPrices($key, $week-1, array($r => $period), null, $endOfMonth, $bookedDaysNext, $nextSatAvail);
				//elseif ($rate == '-2') continue;
				elseif ($rate <= 0) {
					echo '<td class="table_price booked_text">n/a</td>';
				} else {

				    $date_commencing = $this->key[$key];

                    $discount = get_post_meta( $this->ID, 'discount_logic', true);

                    if(!empty($discount)) {

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

                                    $ordinals = $this->get_ordinals();
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
                    }

                    $rate = str_replace($currency . '+', 'From '.$currency, $currency.$rate);

					if (stripos($rate, '*') !== false) {
						$star = true;
					}

					//$rate = $this->check_discount_logic($rates, $rate, $period, $week, $key, $r);

					if ($fromAll === true) {
						echo '<td class="table_price">From '.$rate.'</td>';
					} else {
						echo '<td class="table_price">'.$rate.'</td>';
					}


				}
			}
		}
		return $star;
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

//	    var_dump($from);
//	    var_dump($to);
//	    var_dump($date_to_check);
//	    var_dump($rate);
//	    var_dump($percentage);
//	    var_dump($period);

        if(strpos($rate, '*')) {
            return $rate;
        }
        if (($from <= $date_to_check) && ($date_to_check <= $to)) {
            $rate = str_replace(',','',$rate);
            $rate = $rate * ((100-$percentage) / 100);
            $rate = $this->roundUpToAny($rate);
            $rate = number_format($rate) . '***';
        }
        return $rate;
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
	 * Check to see if KT's discount logic is applied.
	 * @REMOVE
	 */
	private function check_discount_logic_v2($rates, $key, $week, $period, $r) {

		$rate = $rates[$key][$week]['rate_'.($r+1)];

		$date_range = $this->get_period_date_range($key, $week, $period, $rate);

		//$rate = $this->get_period_date_range($key, $week, $period, $rate);

		return $rate;


	}

	/**
	 * Check to see if KT's discount logic is applied.
	 * @REMOVE
	 */
	private function get_period_date_range($key, $week, $period, $rate) {

/*
		var_dump($key);
		var_dump($week);
		var_dump($period);
*/

		$ordinals = array(
			'first', 'second', 'third', 'fourth', 'fifth'
		);

		$period_start_days = array(
			'','','','Friday','Friday','Friday','Monday'
		);

		$rate_to_check = $this->getPeriodNames($key);

		$i = array_search($period, $rate_to_check);

		$cal_month = get_post_meta( $this->ID, 'availability_calendar_'.$key.'_month', true );

		$cal_month = explode('-', $cal_month);

		$get_dates = array();
		foreach ($ordinals as $ordinal) {
			$this_date = (string)$ordinal . ' '.$period_start_days[$i].' of '.date('F', strtotime($cal_month[1].'-'.$cal_month[0])).' '.$cal_month[1];
			$get_dates[] = $this_date;
		}

		$date_range = array();

		foreach ($get_dates as $get_date) {

			if (date('F', strtotime($get_date)) == date('F', strtotime($cal_month[1].'-'.$cal_month[0]))) {
				$rate = $this->period_range(date('j-m-Y', strtotime($get_date)), $key, $period, $rate);
            }
		}

		return $rate;


	}

	/**
	 * Creating date collection between two dates
	 *
	 * <code>
	 * <?php
	 * # Example 1
	 * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
	 *
	 * # Example 2. you can use even time
	 * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
	 * </code>
	 *
	 * @author Ali OYGUR <alioygur@gmail.com>
	 * @param string since any date, time or datetime format
	 * @param string until any date, time or datetime format
	 * @param string step
	 * @param string date of output format
	 * @return array
     * @REMOVE
	 */
	public function create_date_range($first, $last, $dates, $rate, $step = '+1 day', $output_format = 'j-m-Y' ) {

	    $offers_range = array();
	    $current = strtotime($first);
	    $last = strtotime($last);

	    while( $current <= $last ) {

	        $offers_range[] = date($output_format, $current);
	        $current = strtotime($step, $current);
	    }

/*
	    var_dump($offers_range);
	    var_dump($dates);
*/
	    $rolling_offer = $this->testthis($dates, $offers_range, $rate);

	    if ($rolling_offer == true) {
            $rate = $rate . '#';
        } else {
            $rate = str_replace('#','',$rate);
        }

	    return $rate;
	}

    // @REMOVE
    public function testthis($dates, $offers_range, $rate) {

	    $count = count(array_intersect($dates, $offers_range));

	    if ($rate && $count > 0) {
		    return true; //break;
	    } else {
		    return false; //break;
	    }
    }

    // @REMOVE
    public function create_accomdation_range($start, $plusdays, $rate, $step = '+1 day', $output_format = 'j-m-Y') {
	    $dates = array();
	    $current = strtotime($start);
	    $i = 1;
	    for( $i = 1; $i <= $plusdays; $i++ ) {
	        $dates[] = date($output_format, $current);
	        $current = strtotime($step, $current);
		}

		$rate = $this->check_accomodation_range($dates, $rate);


	    return $rate;
	}

	// @REMOVE
	public function check_accomodation_range($dates, $rate) {
		$rate = $this->this_logic($dates, $rate);
		return $rate;
	}

    // @REMOVE
	public function this_logic($dates, $rate) {

		$discount = get_post_meta( $this->ID, 'discount_logic', true);

		if(!empty($discount)) {

			foreach($discount as $k => $v) {

				if (get_post_meta( $this->ID, 'discount_logic_'.$k.'_logic_type', true ) == 'set') {
					$from = get_post_meta( $this->ID, 'discount_logic_'.$k.'_from', true );
					$to = get_post_meta( $this->ID, 'discount_logic_'.$k.'_to', true );
					$month_year = date('m-Y', strtotime($dates[0]));
					$percentage = get_post_meta( $this->ID, 'availability_calendar_'.$k.'_discount', true);

					if ($month_year == date('m-Y', strtotime($from))) {
							$start = date('d-m-Y', strtotime($from));
							$end = date('d-m-Y', strtotime($to));

							$rate = $this->create_date_range($start, $end, $dates, $rate);

					}
				}
			}
		}

		return $rate;

	}

	// @REMOVE
	private function period_range($kts_commence_date, $key, $period, $rate) {

		$plusdays = array(
			'','','',2,3,6,3
		);


		$rate_to_check = $this->getPeriodNames($key);
		$i = array_search($period, $rate_to_check);

		$rate = $this->create_accomdation_range($kts_commence_date, $plusdays[$i], $rate);

		return $rate;

	}

	private function get_discount_logic() {
		$discount = get_post_meta( $this->ID, 'discount_logic', true);
	}


	/**
	 * Keep only the prices that should be shown, i.e. where $daysOfWeek set to show.
	 *
	 */
	private function keepValidPeriods($periods, $daysOfWeek, $endOfMonth, $key, $bookedDaysNext, $nextSatAvail, $week) {

		$dec_2018 = $this->getMonth($key);
		$week_3 = $week;
		foreach ($periods as $key => $value) {
            if (strpos($value, 'weekend')) {


                if (!isset($daysOfWeek[1])) {
					$periods[$key] = 'blank';
				} elseif (empty($daysOfWeek[1])) {

				    $periods[$key] = 'booked';

                } elseif (empty($daysOfWeek[2])) {
                }

			}
			if ($value == 'Midweek' || $value == '1 night midweek CV' || $value == '2 night midweek') {

                if (!isset($daysOfWeek[4])) {
					$periods[$key] = 'blank';
				}
				// er added to capture emptybookeddays
				//elseif (empty($bookedDaysNext)) $periods[$key] = 'n/a';
				else {
					// *****************************
					// Begin hack
					// *****************************
					// THIS IS A VERY VERY BAD HACKY
					// AND MUST NEVER BE ATTRIBUTED
					// TO MR ELLIOTT RICHMOND
					// HE WAS MADE TO DO IT!
					// *****************************
					//
					//
					// UNTIL i_PRO fix the incomming feed with the extra
					// week commencing that is missing from the CRM it will always be an issue

					// return the price for this midweek on for week 4 december 2017
					// if ($dec_2018 == '12-2017' && $week_4 == 3) {
					// 	$periods[$key] = $value;
					// 	break;
					// }
					// return the price for this midweek on for week 4 december 2017
					// if ($dec_2017 == '10-2017' && $week_4 == 0) {
					// 	$periods[$key] = '13695102017'.$key;
					// 	//break;
					// }

					// return the price for this midweek on for week 4 december 2017
					// if ($dec_2017 == '10-2017' && $week_4 == 0) {
					// 	$periods[$key] = '13695102017'.$key;
					// 	//break;
					// }

					// Tuesday availability, true if available
					$tuesAvail = ((count($daysOfWeek) < 5 && $endOfMonth)
						? (is_array($bookedDaysNext) ? !in_array(5-count($daysOfWeek), $bookedDaysNext) : true)
						: !empty($daysOfWeek[5]));

					// in order to display price, TUES AND ( SAT OR NEXTSAT) must be available
					if (!($tuesAvail)) {
						if ($dec_2018 == '12-2018' && $value == 'Midweek' && $week_3 == 3) {
							$periods[$key] = $value;
						} else {
							$periods[$key] = 'booked';
						}

					}

				}
			}
			if ($value == 'Week') {
				if (!isset($daysOfWeek[1]) || ($daysOfWeek[1] == false) && !isset($daysOfWeek[4])) {
                    $periods[$key] = 'blank';
                }
				else {
					// this Sat availability, true if available
					$satAvail = ((count($daysOfWeek) == 1 && $endOfMonth)
						? (is_array($bookedDaysNext) ? !in_array(1, $bookedDaysNext) : true)
						: !empty($daysOfWeek[2]));
					// Tuesday availability, true if available
					$tuesAvail = ((count($daysOfWeek) < 5 && $endOfMonth)
						? (is_array($bookedDaysNext) ? !in_array(5-count($daysOfWeek), $bookedDaysNext) : true)
						: !empty($daysOfWeek[5]));
					// in order to display price, TUES AND ( SAT OR NEXTSAT) must be available
					if (!($tuesAvail && ($satAvail || $nextSatAvail))) $periods[$key] = 'booked';
				}
			}
			if ($value == '5 nights') {
				$filtered = array_filter($daysOfWeek);
				if(count($filtered) < 4) {
					if($endOfMonth && $nextSatAvail) {
						$periods[$key] = $value;
					} else {
						$periods[$key] = 'booked';
					}
				}



			}

		}

		return $periods;
	}

	/**
	 * check if a house qualifies
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function house_is_qualfied($id) {
		$boolean = false;
		$array = array(
			16914,
			19733,
			19660,
			81970,
			56703,
			11105,
			11069,
			34285,
			34215,
			14767,
			11105,
			39234,
			39236,
			39595,
			39584,
			39253,
			39223,
			39234,
			45716,
			45725,
			45723,
			45755,
			45772,
			45721
		);
		if(in_array($id,$array)){
			$boolean = true;
		}
		return $boolean;
	}

	/**
	 * Check a specified month.
	 * 0=Sunday, 1=Monday, 2=Tues, 3=Weds, 4=Thurs, 5=Fri, 6=Sat
	 *
	 * @param integer Key for month to check.
	 */
	public function monthCheck($key) {

		// added to remove 1 day mid weeks between normals sites and corporate site
		$blogid = $GLOBALS['current_blog']->blog_id;

		$date = $this->getMonth($key);

		$month = strtotime('01-'.$date);


		if (!function_exists('cal_days_in_month'))  {
		    function cal_days_in_month($calendar, $month, $year) {
		        return date('t', mktime(0, 0, 0, $month, 1, $year));
		    }
		}

		// hack to trap date for testing, must be commented out
//		if ($date != '12-2017') {
//		    return;
//        }

		$numDays = cal_days_in_month(CAL_GREGORIAN,date('m',$month),date('Y',$month));
		$bookedDays = get_post_meta($this->ID, 'availability_calendar_'.($key).'_availability-days', true);


		$bookedDaysNext = get_post_meta($this->ID, 'availability_calendar_'.($key+1).'_availability-days', true);
		$star = false;
		$isStar = false;
		$weekBooked = array();
		$firstAvail = ($bookedDaysNext ? (in_array(1,$bookedDaysNext) ? 0 : 1) : 1);
		$nextMonth = (date('m',$month) == 12 ? 1 : date('m',$month) +1);
		$next_month_sat_day = date('j',strtotime('First Saturday '.date('F o', @mktime(0,0,0, (date('m',$month) == 12 ? 1 : date('m',$month) +1), 1,
			(date('m',$month) == 12 ? date('Y',$month) +1 : date('Y',$month))))));
		if ($next_month_sat_day == 8) $next_month_sat_day = 1;
		$firstSatAvail = ($bookedDaysNext ? (in_array($next_month_sat_day,$bookedDaysNext) ? 0 : 1 ) : 1);
		if ($bookedDays == "") $bookedDays = array(null);
		if (strtotime(date('Y-m-01')) <= $month):
			echo '<div class="row">';
			echo '<div class="span8 offset2"><h2 class="avail_subtitle">'. date('F Y',$month).'</h2></div>';
			echo '<div class="span8 offset2"><div>';
			echo '<table class="avail_table table-striped" cellpadding="3" cellspacing="0"><tr class="bk_head_row">';
			$daysOfWeek = array('Fri', 'Sa', 'Su', 'M', 'Tu', 'W', 'Th');
			foreach ($daysOfWeek as $d) echo '<th class="bk_header">'.$d.'</th>';
			$pricesCount = 0;

			//var_dump($key);

			foreach ($this->getPeriodNames($key) as $period)

				//var_dump($period);

				if ($blogid == 12) {
					echo '<th class="bk_header_price">'.str_ireplace(' WV', '', $period).'</th>';
				} elseif ($blogid == 16 || $blogid == 11|| $blogid == 1)  {
					
					echo '<th class="bk_header_price">'.str_ireplace(' CV', '', $period).'</th>';
				} else {
					echo '<th class="bk_header_price">'.$period.'</th>';
				}
				echo '</tr>';


			$periods = $this->getPeriodNames($key);
			$rateCount = 0;
			$week = 0;

			foreach(range(1,$numDays) as $dayNum) {

				$dayAsTime = strtotime($dayNum.'-'.date('m-Y',$month));

				if ($dayNum === 1) {
					$SaturdayHasPassed = false;
					$additionalCount = (date('w',$month)+2) % 7;
					$showFirstMidweek = (in_array(date('w',$month), array(0,1,5,6)) ? true : false);
					$showFirstWeekends = (date('w',$month) == 5 ? true : false);
					if ($additionalCount) print '<td colspan="'.$additionalCount.'" class="filler"></td>';
					$bookedCountInd = $additionalCount;
					$BookedArray = array();
				}

				if (($dayNum + $additionalCount -1) % 7 == 0) echo '<tr class="subrow">';

				$bookedCountInd++;

				// Display cell and price inside
				$class = $this->getDateClass($dayNum, $bookedDays, $numDays, $firstAvail, $dayAsTime, $key);
				$linkPrice = (in_array($class, array('bk_avail halfbefore', 'bk_unav')) ? false : true);
				echo '<td class="'.$class.'">';
/*
				if ($bookedCountInd == 2) {
					$theSaturday = $dayNum;
					$SaturdayHasPassed = true;
				}
*/
/*
				if ($this->ID == 13695) {
					echo ($this->displayPrices && $linkPrice ? '<a href="https://booking.kateandtoms.com/agency-houses/polpier/?checkin='.str_pad($dayNum, 2, '0', STR_PAD_LEFT).'
					/'.date('m/Y',$month).'">'.$dayNum.'</a>' : $dayNum);
				} elseif ($this->ID == 13610) {
					echo ($this->displayPrices && $linkPrice ? '<a href="https://booking.kateandtoms.com/agency-houses/penpol/?checkin='.str_pad($dayNum, 2, '0', STR_PAD_LEFT).'
					/'.date('m/Y',$month).'">'.$dayNum.'</a>' : $dayNum);
				} elseif ($this->ID == 14936) {
					echo ($this->displayPrices && $linkPrice ? '<a href="https://booking.kateandtoms.com/agency-houses/polpier-and-penpol/?checkin='.str_pad($dayNum, 2, '0', STR_PAD_LEFT).'
					/'.date('m/Y',$month).'">'.$dayNum.'</a>' : $dayNum);
				}
*/
				//else {
					echo ($this->displayPrices && $linkPrice ? '<a href="/houses/'.$this->getName().'/book/d='.str_pad($dayNum, 2, '0', STR_PAD_LEFT).'-'.date('m-Y',$month).'&w='.$week.'/">'.$dayNum.'</a>' : $dayNum);
				//}

				echo '</td>';

				$weekBooked[($dayNum + $additionalCount) % 7] = $this->displayPrices && $linkPrice;

				// Show prices if 7 columns filled.
				if (($dayNum + $additionalCount) % 7 == 0) {
					$nextSatAvail = ($dayNum+2 > $numDays
						? (is_array($bookedDaysNext) ? !in_array($dayNum+2-$numDays, $bookedDaysNext) : true)
						: (is_array($bookedDays) ? !in_array($dayNum+2, $bookedDays) : true));
					$isStar = $this->showPrices($key, $week, $periods, $weekBooked, $dayNum == $numDays, $bookedDaysNext, $nextSatAvail);
					$week++;
					echo '</tr>';
					$weekBooked = array();
				}
				if ($star === false) $star = $isStar;

			}

			$selectRange = range(1,6);
			foreach ($selectRange as $selectRangeCount) {
				if (($dayNum + $additionalCount - $selectRangeCount) % 7 == 0) {
					$showFinalMidweek = ($selectRangeCount < 4 ? false : true);
					echo '<td colspan="',7-$selectRangeCount,'" class="filler"></td>';
				}
			}
			if (($dayNum + $additionalCount) % 7 != 0) $this->showPrices($key, $week, $periods, $weekBooked, true, $bookedDaysNext);

			if (($dayNum + $additionalCount - $selectRangeCount) % 7 != 0) echo '</tr>';

			$this->bookedFinalLastMonth = in_array($numDays, $bookedDays);

			echo '</tr></table></div></div>';
			echo '<div class="span8 offset2">';
			$this->showPricingDetails($key, $star);
			$this->showRollingOffers($key);
			echo '</div></div>';
		endif;


	}

	public function showRollingOffers($key) {

		$date_commencing = $this->key[$key];
		$discount = get_post_meta( $this->ID, 'discount_logic', true);

        if(!empty($discount)) {

            foreach($discount as $k => $v) {
			    if (get_post_meta( $this->ID, 'discount_logic_'.$k.'_logic_type', true ) == 'set') {

			        $from = get_post_meta( $this->ID, 'discount_logic_'.$k.'_from', true );
			        $to = get_post_meta( $this->ID, 'discount_logic_'.$k.'_to', true );
			        $from_date = date('m-Y', strtotime($from));
			        $percentage = get_post_meta( $this->ID, 'discount_logic_'.$k.'_discount', true);

			        if($from_date == $date_commencing) {

				        echo '<div class="price_details rolling-offer">*** Late Availability - '.$percentage.'% off (price includes discount)</div>';

			        }


			    }
			    if (get_post_meta( $this->ID, 'discount_logic_'.$k.'_logic_type', true ) == 'rolling') {

			        $from = date('Ymd',strtotime('today'));
			        //$from_date = date('m-Y', strtotime($from));
			        $number_of_days = get_post_meta( $this->ID, 'discount_logic_'.$k.'_no_rolling_days', true );
			        $to = date('Ym',strtotime('+'.$number_of_days.' days' ));
			        $percentage = get_post_meta( $this->ID, 'discount_logic_'.$k.'_discount', true);

			        $to = str_replace('-', '', $to);

		            $datearray = explode('-', $date_commencing);
		            $m = $datearray[0];
		            $y = $datearray[1];
			        $date_to_check = $y.$m;

			        if($to >= $date_to_check) {

				        echo '<div class="price_details rolling-offer">*** Late Availability - '.$percentage.'% off (price includes discount)</div>';

			        }

			    }
			}
		}

	}

}



?>