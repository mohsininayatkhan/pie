<?php

/**
 * This is general purpose class.
 * 
 */
class GeneralPurpose
{

    /**
     * Calculate a time ago
     * 
     * @param datetime $time_ago
     * 
     * @return string 
     */
    public static function timeAgo($time_ago)
    {
        $date = date('Y/m/d H:i:s');
        $time_ago = strtotime(date($time_ago));
        $cur_time = strtotime($date);
        $time_elapsed = $cur_time - $time_ago;
        $seconds = $time_elapsed;
        $minutes = round($time_elapsed / 60);
        $hours = round($time_elapsed / 3600);
        $days = round($time_elapsed / 86400);
        $weeks = round($time_elapsed / 604800);
        $months = round($time_elapsed / 2600640);
        $years = round($time_elapsed / 31207680);
// Seconds
        if ($seconds <= 60) {
            return $seconds . ' seconds ago';
        }
//Minutes
        elseif ($minutes <= 60) {
            if ($minutes == 1) {
                return 'one minute ago';
            } else {
                return $minutes . ' minutes ago';
            }
        }
//Hours
        else if ($hours <= 24) {
            if ($hours == 1) {
                return 'an hour ago';
            } else {
                return $hours . ' hours ago';
            }
        }
//Days
        elseif ($days <= 7) {
            if ($days == 1) {
                return 'yesterday';
            } else {
                return $days . ' days ago';
            }
        }
//Weeks
        elseif ($weeks <= 4.3) {
            if ($weeks == 1) {
                return 'week ago';
            } else {
                return $weeks . ' weeks ago';
            }
        }
//Months
        elseif ($months <= 12) {
            if ($months == 1) {
                return 'month ago';
            } else {
                return $months . ' months ago';
            }
        }
//Years
        else {
            if ($years == 1) {
                return 'year ago';
            } else {
                return $years . 'years ago';
            }
        }
    }

    public static function getQuerystringParams()
    {
        $param = array();
        foreach ($_REQUEST as $k => $v) {
            if (is_array($v)) {
                foreach($v as $j) {
                    $param[$k.'%5B%5D'] = $j;
                }
            } else {
                $param[$k] = $v;    
            }
        }
        return $param;
    }
    
    public static function getString($string, $start, $end)
    {
        $string = " ".$string;
        $pos = strpos($string,$start);
        if ($pos == 0) return "";
        $pos += strlen($start);
        $len = strpos($string,$end,$pos) - $pos;
        return substr($string,$pos,$len);
    }
	
	public static function getUserLocation()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
		if($query && $query['status'] == 'success') {
			return $query;
		}
		return array();
	}

}