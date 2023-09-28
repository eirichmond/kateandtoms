<?php
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
/**
 * Defines how the booking form (and other booking pages) work on the site.
 *
 * @package kate-and-toms
 */

class ProductBook {


}


class HouseBook extends ProductBook {

	private $ID;

	private $date;

	private $house;

	private $week;

	private $dayOfWeek;

	private $fromAll;

	private $time;

	private $calendar;

	private $nextCalendar;

	private $rate;

	private $dayDefine;

	private $numDays;

	private $periods_names;

	public function __construct($ID, $house)
	{
		$this->ID = $ID;
		$this->house = $house;
		$message = $house->getMessage();
		$this->week = intval(substr($message, 15, 1));
		$this->fromAll = get_field('all_prices_with_from', $ID);
		$inherited = get_post_meta($ID, 'availability_site_post_id', true);

		echo '<script>jQuery(document).ready(function($) {$("input").click(function () {$("#bookingselectform").submit();});});
			function goBack() { window.history.back() }</script>';

		if (substr($message, 0, 2)=="d=") {
			$this->time = strtotime(substr($message, 2, 10));
			$this->dayOfWeek = date('w',$this->time);
			if ($this->dayOfWeek == 1 || $this->dayOfWeek == 5) {
				$this->bookingForm();
			} else {
				$this->chooseCorrectDate();
			}
		}

		// Step 2: Enter personal details
		elseif ($message == "details") {
			$this->progressBar(2);

			$title = 'Step 2: Personal details';
			$body = array(
					'Please enter your personal details for this booking in the form to the right.',
					'Please note that no payments are made using this form - only a provisional hold is made on a house.',
					'Check the details below to confirm that your booking is correct.',
				);

			$this->bookingGuidance($title, $body, true);

			$dateDefine=strtotime(substr($message, 2, 10));

			// if ($inherited == true || empty($inherited)) {
			// 	switch_to_blog(1);
			// 	echo '<div class="span6">',do_shortcode( '[contact-form-7 id="37581" title="Book Now"]' ),'</div>';
			// 	restore_current_blog();
			// } else {
			// 	echo '<div class="span6">',do_shortcode( '[contact-form-7 id="37581" title="Book Now"]' ),'</div>';
			// }

			echo '<div class="span6">';
			do_action('kateandtoms_book_now');
			echo '</div>';

		}

		// Step 3: Thank you page
		elseif ($message == "thank-you") {
			$this->progressBar(3);
			$dateDefine=strtotime(substr($message, 2, 10));
			echo '<h2 style="margin-bottom:20px;">Thank you</h2>',
				'<div style="margin-bottom:300px;text-align:center;">Your booking has been received and we will be in touch soon.</div>';
		}
	}

	public function chooseCorrectDate()
	{
		$this->progressBar(1);

		$title = 'Step 1: Select your booking';
		$body = array(
				'Please select which booking period you are interested on the right.',
				'Our weekend bookings start on a Friday. Our week-long bookings start on a Friday or a Monday.
					Our midweek bookings usually start on a Monday, although shorter midweek stays are usually available.',
				'Bookings outside of these periods can be made on request. Please enquire by telephone or email.'
			);

		$this->bookingGuidance($title, $body);

		$time = $this->time;

		$dates = array();
		$week = $this->week;
		$dayOfWeek = $this->dayOfWeek;
		// Saturday (6)
		if ($dayOfWeek == 6) {
			$dates[1]['time'] = strtotime(date('Y-m-d',$time). " -1 day");
			$dates[1]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[1]['time']));

			$dates[2]['time'] = strtotime(date('Y-m-d',$time). " +2 days");
			$dates[2]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[2]['time']));
		}

		// Sunday (0)
		elseif ($dayOfWeek == 0) {
			$dates[1]['time'] = strtotime(date('Y-m-d',$time). " -2 days");
			$dates[1]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[1]['time']));
			$dates[2]['time'] = strtotime(date('Y-m-d',$time). " +1 day");
			$dates[2]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[2]['time']));
		}

		// Tuesday (2)
		elseif ($dayOfWeek == 2) {
			$dates[1]['time'] = strtotime(date('Y-m-d',$time). " -1 day");
			$dates[1]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[1]['time']));

			$dates[2]['time'] = strtotime(date('Y-m-d',$time). " +3 days");
			$dates[2]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[2]['time']));

			//var_dump($this->weekOfMonth(date('d-m-Y',$dates[1]['time'])));

/*
			if (date('m',$dates[2]['time']) === date('m',$time)){
				$dates[2]['wk'] = $week+1;
			} else {
				$dates[2]['wk']=0;
			}
*/
		}

		// Wednesday (3)
		elseif ($dayOfWeek == 3) {
			$dates[1]['time'] = strtotime(date('Y-m-d',$time). " -2 days");
			$dates[1]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[1]['time']));

			$dates[2]['time'] = strtotime(date('Y-m-d',$time). " +2 days");
			$dates[2]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[2]['time']));

/*
			if (date('m',$dates[2]['time']) === date('m',$time)) $dates[2]['wk']=$week+1;
			else $dates[2]['wk']=0;
*/
		}

		// Thursday (4)
		elseif ($dayOfWeek == 4) {
			$dates[1]['time'] = strtotime(date('Y-m-d',$time). " +1 day");
			$dates[1]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[1]['time']));

			$dates[2]['time'] = strtotime(date('Y-m-d',$time). " -3 days");
			$dates[2]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[2]['time']));

/*
			if (date('m',$dates[1]['time']) === date('m',$time)) $dates[1]['wk']=$week+1;
			else $dates[1]['wk']=0;
*/
		}

		// Friday (5)
		elseif ($dayOfWeek == 5) {
			$dates[1]['time'] = strtotime(date('Y-m-d',$time). " +1 day");
			$dates[1]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[1]['time']));

			$dates[2]['time'] = strtotime(date('Y-m-d',$time). " -3 days");
			$dates[2]['wk'] = $this->weekOfMonth(date('d-m-Y',$dates[2]['time']));

/*
			if (date('m',$dates[1]['time']) === date('m',$time)) $dates[1]['wk']=$week+1;
			else $dates[1]['wk']=0;
*/
		}

		$buttonData = array();

/*
		var_dump($dayOfWeek);
		var_dump($dates);
*/

		foreach ($dates as $k => $date) {
			$url = '/houses/'.$this->house->getName().'/book/d='.date('d-m-Y',$date['time']).'&w='.(isset($date['wk']) ? $date['wk'] : $week).'/';
			$buttonData[$url] = 'Booking starting from '.date('l, j F Y',$date['time']);
		}
		$this->buttons($buttonData);
	}

	/**
	 * Returns the number of week in a month for the specified date.
	 *
	 * @param string $date
	 * @return int
	 */
	private function weekOfMonth($date) {
	    // estract date parts
	    list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));

	    // current week, min 1
	    $w = 0;

	    // for each day since the start of the month
	    for ($i = 1; $i <= $d; ++$i) {
	        // if that day was a friday and is not the first day of month
	        if ($i > 1 && date('w', strtotime("$y-$m-$i")) == 5) {
	            // increment current week
	            ++$w;
	        }
	    }

	    // now return
	    return $w;
	}

	private function progressBar($n)
	{
		echo '<p class="span4'.($n == 1 ? ' active' : '').'">1. Select your booking</p>
			<p class="span4'.($n == 2 ? ' active' : '').'">2. Personal details</p>
			<p class="span4'.($n == 3 ? ' active' : '').'">3. All done</p>';

		echo '<div class="progress span12">';
		if ($n>0) echo ($n == 2 ? '<a href="'.$_SERVER['HTTP_REFERER'].'">' : '').'<div class="bar" style="width: 33%;"></div>'.($n == 2 ? '</a>' : '');
		if ($n>1) echo '<div class="bar bar-info" style="width: 34%;"></div>';
		if ($n>2) echo '<div class="bar" style="width: 33%;"></div>';
		echo '</div>';
	}

	private function bookingGuidance($title, $body, $details = false)
	{

		echo '<div class="span6 info">
			<h2>'.$title.'</h2>
			<ul>';
		foreach ($body as $i) {
			echo '<li>'.$i.'</li>';
		}
		echo '</ul>';
		if ($details) {
			echo '<div class="booking_details"><h3>Your booking details</h3>
				<p><span class="label">House: </span><strong>',$_POST['house_name'],'</strong><br/>
				<span class="label">Date from: </span><strong>',$_POST['date_from'],'</strong><br/>
				<span class="label">Period: </span><strong>',$_POST['period'],'</strong></p></div>';
		}
		echo '</div>';
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * *
	 *
	 *  Take the checking data and the period to
	 *  return the strings for checkin and checkout
	 *  days to pass to iPro
	 *
	 *	@param string $checkin the checkin date
	 *	@param string $value the period
	 *	@return array $array the checkin and checkout date
	 *
	 * * * * * * * * * * * * * * * * * * * * * * * * */

	private function calc_checkout_date($checkin, $value) {
		global $katglobals;

		$plusdays = array(
			'1','2','3','7','4','2','5','2','3','7','4'
		);
		$periods = $katglobals['rate_headers'];
		$period_plus_days = array_combine($periods, $plusdays);

		$checkin_strtime = str_replace('/', '-', $checkin);

		$array = array();
		$array['in'] = $checkin;
		$array['out'] = date('d/m/Y', strtotime($checkin_strtime . ' + '.$period_plus_days[$value] . ' days'));

		return $array;

	}

	private function buttons($data, $radio = false, $checkin = false)
	{
		$c=0;
		echo '<div class="span4 offset1">';
		if ($radio) {
			foreach ($data as $value => $text) {

				$checkinout_dates = $this->calc_checkout_date($checkin, $value);
				$checkinmonth = str_replace('/', '-', $checkinout_dates['in']);
				$checkinmonth = date('n-Y', strtotime($checkinmonth));
				$c++;

/*
				if ($this->ID == 13695 || $this->ID == 16938) {
					if ($checkinmonth == '5-2018' || $checkinmonth == '6-2018' || $checkinmonth == '7-2018' || $checkinmonth == '8-2018' || $checkinmonth == '9-2018') {
						echo '<a href="https://booking.kateandtoms.com/agency-houses/polpier/?alttemplate=booking-step1&t=acc&checkin='.$checkinout_dates['in'].'&checkout='.$checkinout_dates['out'].'&adults=1" class="wide btn btn-'.$c.'">'.$value.' - '.$text.'</a>';
					} else {
						echo '<input type="radio" id="'.$value.'" name="period" value="'.$value.'" class="btn btn-'.$c.'" />
						<label for="'.$value.'">'.$value.' - '.$text.'</label>';
					}
				} elseif ($this->ID == 13610 || $this->ID == 16984) {
					if ($checkinmonth == '5-2018' || $checkinmonth == '6-2018' || $checkinmonth == '7-2018' || $checkinmonth == '8-2018' || $checkinmonth == '9-2018'){
						echo '<a href="https://booking.kateandtoms.com/agency-houses/penpol/?alttemplate=booking-step1&t=acc&checkin='.$checkinout_dates['in'].'&checkout='.$checkinout_dates['out'].'&adults=1" class="wide btn btn-'.$c.'">'.$value.' - '.$text.'</a>';
					} else {
						echo '<input type="radio" id="'.$value.'" name="period" value="'.$value.'" class="btn btn-'.$c.'" />
						<label for="'.$value.'">'.$value.' - '.$text.'</label>';
					}
				} elseif ($this->ID == 14936 || $this->ID == 26428) {
					if ($checkinmonth == '5-2018' || $checkinmonth == '6-2018' || $checkinmonth == '7-2018' || $checkinmonth == '8-2018' || $checkinmonth == '9-2018') {
						echo '<a href="https://booking.kateandtoms.com/agency-houses/polpier-and-penpol/?alttemplate=booking-step1&t=acc&checkin='.$checkinout_dates['in'].'&checkout='.$checkinout_dates['out'].'&adults=1" class="wide btn btn-'.$c.'">'.$value.' - '.$text.'</a>';
					} else {
						echo '<input type="radio" id="'.$value.'" name="period" value="'.$value.'" class="btn btn-'.$c.'" />
						<label for="'.$value.'">'.$value.' - '.$text.'</label>';
					}

				} else {
*/
					echo '<input type="radio" id="'.$value.'" name="period" value="'.$value.'" class="btn btn-'.$c.'" />
					<label for="'.$value.'">'.$value.' - '.$text.'</label>';
// 				}

			}
		}
		else foreach ($data as $url => $text) {
			$c++;
			echo '<p><a class="btn btn-'.$c.'" href="'.$url.'">'.$text.' &#8594;</a></p>';
		}
		echo '</div>';
	}

	public function bookingForm()
	{
		$message = $this->house->getMessage();
		$monthDefine = substr($message, 5, 7);
		$broadcast_setting = get_post_meta($this->ID, 'availability_option', true);

		if ($broadcast_setting == 1) {
			$lookup_blog_id = get_post_meta($this->ID, 'availability_site_ref', true);
			$lookup_post_id = get_post_meta($this->ID, 'availability_site_post_id', true);
		} else {
			$lookup_blog_id = get_current_blog_id();
			$lookup_post_id = $this->ID;
		}

		$availability_months = HouseSearch::singleAvailability($lookup_blog_id, $lookup_post_id);

		$rates_months = HouseSearch::singleRates($lookup_blog_id, $lookup_post_id, $monthDefine);



		foreach ($availability_months as $m) {

			if (isset($break)) {
				$this->nextCalendar = $m;
				break;
			}

			elseif ($m->month == $monthDefine) {
				$this->calendar = $m;
				$break = true;
			}
		}

		$c = 0;
		if (!is_array($rates_months)) return false;

		$rates = $rates_months[0]->rates;
		$rates = unserialize($rates);

		if ($rates) {
			$n=1;
			foreach ($rates as $period => $week_values) {
				$this->period_names[$n] = $period;
				foreach ($week_values as $week => $price) {
					if ($price != 0) {
						$this->rate[$n] = $price;
					}
					if ($week == $this->week) break;
				}
				$n++;
			}
		}


		$this->progressBar(1);

		$title = 'Step 1: Select your booking';
		$body = array(
				'Showing bookings beginning '.date('l, j F Y', $this->time).' for '.get_the_title().'
					<a href="/houses/'.$this->house->getName().'/availability/1/">change...</a>',
				'Please choose the booking that you would like to make from the options to the right.',
				'Week bookings usually begin on either a Friday or a Monday. If you would like to book a different period, please get in touch.',
				'Weekend bookings are available from Friday.'
			);

		$this->bookingGuidance($title, $body);

		$checkin = date('d/m/Y', $this->time);

		?>
		<form id="bookingselectform" action="/houses/<?php echo $this->house->getName(); ?>/book/details/" method="post">
		<input type="hidden" name="house_name" value="<?php the_title(); ?>" />
		<input type="hidden" name="date_from" value="<?php echo date('l, j F Y', $this->time); ?>" />
		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>" />
		<?php wp_nonce_field( 'booking_select_action', 'booking_select_nonce' ); ?>
		<?php
		if (isset($this->period_names)) {

			$buttonData = $this->getOptions();

			$this->buttons($buttonData, true, $checkin);
		}
		echo '</form>';

	}

    /**
     * @return array
     */
    private function getOptions()
	{

		$site_id = get_current_blog_id();

		if (!function_exists('cal_days_in_month'))  {
		    function cal_days_in_month($calendar, $month, $year) {
		        return date('t', mktime(0, 0, 0, $month, 1, $year));
		    }
		}

		$buttonData = array();
		$time = $this->time;
		$this->numDays = cal_days_in_month(CAL_GREGORIAN,date('m',$this->time),date('Y',$this->time));
		$this->dayDefine = date('j',$this->time);

/*
		var_dump(date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 1 days')));
		var_dump(date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 6 days')));
		var_dump($this->dayOfWeek);
		var_dump($this->period_names);
*/

		foreach ($this->period_names as $k => $period) {

			// What to display if a Monday
			if ($this->dayOfWeek == 1) {
				if (strstr($period, 'weekend')) $show=false;
				elseif ( $period == 'Week' ) {

					$show = $this->checkdates(date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 1 days')), date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 6 days')));

					//$show = $this->checkDate($this->dayDefine+1, $this->dayDefine+6);
				} elseif ( $period == 'Midweek' ) {
					$show = $this->checkdates(date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 1 days')), date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 3 days')));
					//$show = $this->checkDate($this->dayDefine+1, $this->dayDefine+3);
				} else $show=true;
			}
			// What to display if a Friday
			elseif ($this->dayOfWeek == 5) {

				if ($period == 'Midweek') {

					$show=false;

				} elseif ($period == 'Week') {

					$show = $this->checkdates(date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 1 days')), date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 6 days')));
					//$show = $this->checkDate($this->dayDefine+1, $this->dayDefine+6);

				} elseif (strstr($period, 'weekend')) {
					//var_dump($period);
					$show = $this->checkdates(date('d-m-Y',$this->time), date('d-m-Y', strtotime(date('d-m-Y',$this->time) . ' + 2 days') ));
					//wp_die();
					//$show = $this->checkDate($this->dayDefine, $this->dayDefine+2);
				} else $show=true;
			}

			// check if this is this is Weddings
			if ($site_id != 12) {
				if ($period == 'Week WV' || $period == 'Midweek WV') {
					$show = false;
				}
			} else {

				if ($period == 'Week WV' || $period == 'Midweek WV') {
					$show = true;
					if ($period == 'Week WV') {
						$period = 'Week';
					}
					if ($period == 'Midweek WV') {
						$period = 'Midweek';
					}
				} else {
					$show = false;
				}
			}

			$price = (isset($this->rate[$k]) ? $this->rate[$k] : -1);



			// debug this as this currently breaks button options
			//$price = $this->check_rolling_offers_price($price, $k);

			if (empty($period) || $price == -1 || $price == -2) $show = false;

			if ($show) {

				$discount = get_post_meta( $this->ID, 'discount_logic', true);

				if(!empty($discount)) {
					$price = $this->apply_discount_logic($discount, $price, $period);
				}

				if (strpos($price,'+') !== false) {
					$from = true;
					$price = str_replace("+", "", $price);
				}
				else $from = false;
				if ($this->fromAll) $from = true;
				$price = str_replace("*","",$price);
				$price = '£'.$price.'';
				//if ($from) $price .= 'From ';
				if ($from) $price = str_replace("£","From £",$price);

				$buttonData[$period] = $price;
			}
		}

		return $buttonData;

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
	public function apply_discount_logic($discount, $rate, $period) {

		$date_commencing = date('m-Y', strtotime($_GET['d']));

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


    public function roundUpToAny($n,$x=5) {
        return (ceil($n)%$x === 0) ? ceil($n) : round(($n+$x/2)/$x)*$x;
    }

   /**
     * Returns string of the price
     * @param string $price of the price
     * @param string $k key of the period for the rate
     * @return string $price changed price if offers is applied
     */
    public function check_rolling_offers_price($price, $r) {
        // die early if this is not a valid price
        if ($price == -1 || $price == -2) {
            return;
        }

        $date_commencing = date('m-Y', $this->time);
        $discount = get_post_meta( $this->ID, 'discount_logic', true);
        $availability_class = new HouseAvailability($this->ID);

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

                        $ordinals = $availability_class->get_ordinals();
                        $period_start_days = $availability_class->get_kt_rate_start_days();
                        $date_to_check = $ordinals[$this->week].' '. $period_start_days[$r];
                        $date_to_check = date('Ymd',strtotime($date_to_check . ' ' . $y.'-'.$m));

                        $price = $availability_class->get_discounted_rate($from, $to, $date_to_check, $price, $percentage, $period);
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

                    $date_to_check = $ordinals[$this->week].' '. $period_start_days[$r];

                    $date_to_check = date('Ymd',strtotime($date_to_check . ' ' . $y.'-'.$m));
                    $price = $this->get_discounted_rate($from, $to, $date_to_check, $price, $percentage, $period);


                    //}

                }
            }
        }
        return $price;
    }

    /**
	 * Returns object for the month passed
	 * @param string $availability_months array of objects
	 * @param string $mon_year the month and year as a string
	 * @return object the required object for the month and year
	 */
	private function get_months_booked_days($availability_months, $mon_year) {
		foreach($availability_months as $object){
			if ($object->month == $mon_year) {
				return $object;
			}
		}
	}

	public function resolve_houseid($blog_id, $post_id) {
		global $wpdb;
		return $wpdb->get_results("
			SELECT
				availability_site_post_id
			FROM houses
			WHERE
				blog_id = ".$blog_id." AND
				post_id = ".$post_id."
		", OBJECT );
	}

	private function checkdates($start, $end) {

		$datesrange = $this->createDateRange($start, $end);
		$resolve_id = $this->resolve_houseid(get_current_blog_id(), $this->ID);
		$availability_months = HouseSearch::singleAvailability(11, $resolve_id[0]->availability_site_post_id);


		$array = array();
		foreach($datesrange as $date) {
			$calendar = $this->get_months_booked_days($availability_months, date('m-Y', strtotime($date)));
			//$calendar = HouseSearch::singleRates(get_current_blog_id(), $this->ID, date('m-Y', strtotime($date)));
			$day = date('j', strtotime($date));


			$booked_days = $calendar->booked_days;
			$booked_days = unserialize($booked_days);

			if (!empty($booked_days)) {

				if (in_array($day, $booked_days)) {

					if (date('N', strtotime($date)) == 5 && $this->dayOfWeek == 5) {
						$array[] = true;
						break;
					} else {
						$array[] = false;
						break;
					}

				} else {
					//var_dump($this->dayOfWeek);
					$array[] = true;
				}
			} else {
				$array[] = true;
			}
		}
		$array = array_unique($array);

		// if day not available in a daterange then set array to false.
		if (in_array(false, $array)) {
			$array = false;
		}

		//var_dump( $array );
		return $array;

	}

	/**
	 * Returns every date between two dates as an array
	 * @param string $startDate the start of the date range
	 * @param string $endDate the end of the date range
	 * @param string $format DateTime format, default is Y-m-d
	 * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
	 */
	private function createDateRange($startDate, $endDate, $format = "d-m-Y")
	{
	    $begin = new DateTime($startDate);
	    $end = new DateTime($endDate);

	    $interval = new DateInterval('P1D'); // 1 Day
	    $dateRange = new DatePeriod($begin, $interval, $end);

	    $range = [];
	    foreach ($dateRange as $date) {
	        $range[] = $date->format($format);
	    }
	    $range[] = $end->format($format);

	    return $range;
	}

	private function checkDate($start, $end) {
		foreach (range($start,$end) as $day) {
			$calendar = ($this->numDays < $day ? $this->nextCalendar : $this->calendar);
			if ($this->numDays < $day) {
				$day -= $this->numDays;
			}
			$booked_days = $calendar->booked_days;
			$booked_days = unserialize($booked_days);
			if (!empty($booked_days)) {
				if (in_array($day, $booked_days)) {
					return false;
				}
			}
		}
		return true;
	}

}

?>