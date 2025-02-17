<?php

declare(strict_types = 1);

class Time
{

    public static function now(string $format = 'Y-m-d H:i:s') : string
    {
        date_default_timezone_set('Asia/Bangkok');
        return date($format);
    }

    public static function currentYear() : string
    {
        date_default_timezone_set('Asia/Bangkok');
        return strval(date('Y'));
    }

    public static function currentThaiYear() : string
    {
        date_default_timezone_set('Asia/Bangkok');
        return strval(date('Y') + 543);
    }

    public static function currentMonth() : string
    {
        date_default_timezone_set('Asia/Bangkok');
        return date('m');
    }

    public static function currentThaiMonth() : string
    {
        date_default_timezone_set('Asia/Bangkok');
        $month = date('m');
        return self::thaiMonth($month);
    }

    public static function currentDay() : string
    {
        date_default_timezone_set('Asia/Bangkok');
        return date('d');
    }
    
    private static function thaiMonth($month) : string
    {
        $months = array(
            '01' => 'มกราคม',
            '02' => 'กุมภาพันธ์',
            '03' => 'มีนาคม',
            '04' => 'เมษายน',
            '05' => 'พฤษภาคม',
            '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม',
            '08' => 'สิงหาคม',
            '09' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม'
        );
        return $months[$month];
    } 

	/**
	 * Get
	 *
	 * Time format examples:
	 * с                – 1970-01-01T00:00:00+00:00
	 * F j, Y g:i a     – January 1, 1970 00:00 am
	 * F j, Y           – January 1, 1970
	 * F, Y             – January, 1970
	 * g:i a            – 00:00 am
	 * g:i:s a          – 00:00:00 am
	 * l, F jS, Y       – Thursday, January 1th, 1970
	 * M j, Y @ G:i     – Jan 1, 1970 @ 0:00
	 * Y/m/d \a\t g:i A – 1970/01/01 at 00:00 AM
	 * Y/m/d \a\t g:ia  – 1970/01/01 at 00:00am
	 * Y/m/d g:i:s A    – 1970/01/01 00:00:00 AM
	 * Y/m/d            – 1970/01/01
	 */
	// public static function get( string $time_created, string $time_updated = '0000-00-00 00:00:00', string $time_format = 'Y-m-d H:i' ) : string
	// {
	// 	$time = $time_updated != '0000-00-00 00:00:00' ? $time_updated : $time_created;
	// 	$time = date( $time_format, strtotime( $time ) );

	// 	return $time;
	// }

}
