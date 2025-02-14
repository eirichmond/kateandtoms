<?php
// Availability calculator functions

function lastday( $month = '', $year = '' ) {
	if ( empty( $month ) ) {
		$month = date( 'm' );
	}
	if ( empty( $year ) ) {
		$year = date( 'Y' );
	}
	$result = strtotime( "{$year}-{$month}-01" );
	$result = strtotime( '-1 second', strtotime( '+1 month', $result ) );
	return date( 'Y-m-d', $result );
}

/**
 * Returns the amount of weeks into the month a date is
 *
 * @param $date a YYYY-MM-DD formatted date
 * @param $rollover The day on which the week rolls over
 */
function getWeeks( $date, $rollover ) {
	$cut    = substr( $date, 0, 8 );
	$daylen = 86400;

	$timestamp = strtotime( $date );
	$first     = strtotime( $cut . '00' );
	$elapsed   = ( $timestamp - $first ) / $daylen;

	$i     = 1;
	$weeks = 1;

	for ( $i; $i <= $elapsed; $i++ ) {
		$dayfind      = $cut . ( strlen( $i ) < 2 ? '0' . $i : $i );
		$daytimestamp = strtotime( $dayfind );

		$day = strtolower( date( 'l', $daytimestamp ) );

		if ( $day == strtolower( $rollover ) ) {
			++$weeks;
		}
	}

	return $weeks;
}
