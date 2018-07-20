<?php

namespace App\Methods;

use App\RateType;
use Carbon\Carbon;
use App\Schedule;
use App\Device;
use DB;
use DateTime;
use Exception;
use Log;
use Auth;
use Session;

class Func
{

	public static function diffInMinutes($from, $to){
		// $to_time = strtotime($to);
		// $from_time = strtotime($from);
		return round(abs($to - $from) / 60,2);
	}

	public static function workingDays($workingdays){
		$result = "";
		foreach ($workingdays as $weekday) {
			$result .= $weekday  . ',';
		}
		$result = rtrim($result, ',');
		return $result;
	}

	public static function flash_message($message, $type)
	{
		Session::flash('flash_message', $message);
		Session::flash('flash_font', $type);
	}

	public static function devices(){
		$granted_devices = explode(',', Auth::privelege()->bio_view); #granted biometrics view

		#temporary with all
		return ['' => 'All'] + Device::whereIn('Clientid', $granted_devices)->pluck('ClientName', 'Clientid')->all();
	}

	public static function rateTypes(){
        return RateType::all()->pluck('type', 'id');
    }

	public static function total_hours($in, $out)
	{
		$val = "00:00:00";
		if($in != null && $out != null)
		{
			$total = strtotime($out) - strtotime($in);
			$val = gmdate('H:i:s', $total);
		}	
		return $val;
	}


	public static function delQryStr($url)
	{
		return strtok($url, '?');
	}

	public static function authorized_message($ren, $late, $ot){
		$result = "";
		if(!empty($ren)){
			$result .= "<div class=\"row\">
							<div class=\"col-sm-5\">
								Render:
							</div>
							<div class=\"col\">
								$ren
							</div>
						</div>";
		}
		if(!empty($late))
		{
			$result .= "<div class=\"row\">
							<div class=\"col-sm-5\">
								Late:
							</div>
							<div class=\"col\">
								$late
							</div>
						</div>";
		}
		if(!empty($ot))
		{
			$result .= "<div class=\"row\">
							<div class=\"col-sm-5\">
								Overtime:
							</div>
							<div class=\"col\">
								$ot
							</div>
						</div>";
		}


		return $result;
	}

	public static function attendance_msg($log){

		$result = "";


		if(empty($log->schedule_id) && !empty($log->time_in) && !empty($log->time_out))
		{
			$result = "<span class=\"text-danger\">
					   	Schedule not found

					   </span>";
		}
		elseif($log->time_in == "No Timein" || $log->time_out == "No Timeout"){
			$result = "";
		}

		/*****************
		* Un-Authorized Log IF
		* timein and timeout < timein sched OR
		* timein and timeout > timeout sched
		*******************/
		// elseif((Func::to24Time($log->time_in) < Func::to24Time($log->start_time) && 
		// 	   Func::to24Time($log->time_out) < Func::to24Time($log->start_time)) ||
		// 	   (Func::to24Time($log->time_in) > Func::to24Time($log->end_time) && 
		// 	   Func::to24Time($log->time_out) > Func::to24Time($log->end_time))
		// 		){
		// 	$result = $result . 
		// 			  "<span class=\"text-danger\">
		// 			   	Un-Authorized Log
		// 			   </span>";
		// }
		else
		{
			$result = "
			";
		}

		#Has schedule : display sched
		if(!empty($log->schedule_id))
		{
			$result = $result . 
					  "<div class=\"text-info\" style=\"font-size:10px\">
					   	[$log->start_time - $log->end_time]
					   </div>";
		}
		return $result;
	}


	public static function check_day($selected_day, $schedule){
		$result = "";

		$days = explode(',', $schedule->days);
		foreach ($days as $day)
		{
			if($selected_day == $day)
			{
				$result = "checked";
				break;
			}
		}
		return $result;

	}

	public static function sched_days($days)
	{
		$result = "";
		$days = explode(',', $days);
		foreach($days as $day)
		{
			if(!empty($day))
			{
				$result .= $day . ',';
			}
		}

		return rtrim($result, ',');
	}

	public static function search_names($search)
	{
		$val = "";
		$names = explode(';', $search);

		foreach ($names as $name) {
			$val .= "REPLACE(name,' ','') LIKE '%$name%' OR ";
		}

		return rtrim($val, "OR ");
	}

	public static function count_absent($dates, $schedule)
	{
		$cnt = 0;
		foreach ($dates as $date)
		{
			$day = date('N', strtotime($date));

			if (strpos($schedule, $day) !== false) {
			    $cnt++;
			}
		}
		return $cnt;
	}

	public static function count_late($lates){
		$cnt = 0;
		foreach ($lates as $late){
			if($late < 0){
				$cnt++;
			}
		}
		return $cnt;
	}

	public static function getEmployeeList($logs)
	{
	

		#Hide employee if no timein in dates
		$employees = array_where($logs, function ($key, $value) {
			if($key->timein != null) return $key; 
		});

		#1D Distinct $employees = array_unique(array_map(function ($i) { return $i->employee_name; }, $employees));

		#DISTinct Based ON KEY
		// walk input array
		$_data = array();
		foreach ($employees as $v) {
		  if (isset($_data[$v->employee_name])) {
		    // found duplicate
		    continue;
		  }
		  // remember unique item
		  $_data[$v->employee_name] = $v;
		}
		// if you need a zero-based array, otheriwse work with $_data
		$employees = array_values($_data);


		return $employees;
	}

	public static function sum_time($array) {
		$m = 0;
	    $i = 0;

	    foreach ($array as $time) {
	    	if(empty($time)) $time = "00:00:00";

	        sscanf($time, '%d:%d:%d', $hour, $min,$sec);
	        $i += ($hour * 60 + $min)*60+$sec;
	    }
	    if ($h = floor($i / 3600)) {
	        $i %= 3600;
	        if ($m = floor($i / 60)) {
	            $i %= 60;
	        }
	    }

	    if($h < 1 && $m < 1) return;

	    return sprintf('%02d hrs %02d min', $h, $m);
	}


	public static function sum_minute($minutes)
	{
		$result = "";
		$time = 0;
		foreach ($minutes as $minute)
		{
			if($minute < 0)
			{
				$time += $minute;
			}
		}

		$time = $time * -1;

		if ($time < 1) {
        	return;
	    }

	    $hours = floor($time / 60);
	    $minutes = ($time % 60);

	    $result = sprintf('%02d hrs %02d min', $hours, $minutes);
		

	    return $result;
	}

	public static function sum_overtime($times) {
		$m = 0;
	    $i = 0;

	    foreach ($times as $time) {
	    	if(empty($time)) $time = "00:00:00";

	    	sscanf($time, '%d:%d:%d', $hour, $min,$sec);
		    $i += ($hour * 60 + $min)*60+$sec;

	    }

	    if ($h = floor($i / 3600)) {
	        $i %= 3600;
	        if ($m = floor($i / 60)) {
	            $i %= 60;
	        }
	    }

	    if($h == "0" && $m == "0") 
	    	return "";
	    else
	    	return sprintf('%02d hrs %02d min', $h, $m);
	}

	public static function sum_undertime($minutes)
	{
		$sum = 0;
		foreach ($minutes as $minute)
		{
			if($minute > 0)
			{
				$sum += $minute;
			}
		}
		return $sum * -1 . "m";
	}

	public static function toHours($time) {
	

		$format = '%02d:%02d:%02d';
	    if ($time < 1) {
	        return;
	    }
	    $hours = floor($time / 60);
	    $minutes = ($time % 60);
	    return sprintf($format, $hours, $minutes, ':00');
	}

	public static function get_overtime($total_hours, $log)
	{	
		$result = '';

		$consider_ot = strtotime('08:00:00');

		#CHECK IF STRAIGHT SCHED 8:00am - 5pm 
		if($log->start_time == '08:00:00.0000000' && $log->end_time == '17:00:00.0000000'){
			$consider_ot = strtotime('09:00:00');
		}


		if(strtotime($total_hours) > $consider_ot)
		{
			$val = gmdate( 'H:i:s', strtotime($total_hours) - $consider_ot );

			if (strtotime($val) > strtotime('01:00:00')){
				$result = $val;
			}
		}
		
		return $result;
	}

	public static function get_late($log){
		
		if($log->late < 0){
			$result = Func::toHours($log->late * -1);
		}
		else{
			$result = null;
		}
		return $result;
	}

	public static function add_days($date, $day)
	{
		return date('Y-m-d', strtotime($date . " $day days"));
	}


	public static function getFilter($arr, $date, $employee)
	{
		$filtered = array_filter($arr, 
		function($v) use($date, $employee) { 
			return strstr($v->date, $date) && 
				   strstr($v->employee_name, $employee) !== false;
		});

		$logid = null;
		$logid_out = null;
		$time_in = null;
		$time_out = null;
		$render = null;
		$late = null;
		$ot = null;
		$log_count = 0; #GET No. of logs in single day *tap ng tap
		$days = null;
		$remarks = null;
		$second_remarks = null;
		$schedule_id = null;
		$start_time = null;
		$end_time = null;
		$updated_by = null;
		$second_updated_by = null;
		$approved_by = null; $ap_approved_by = null;
		$sensorin = null; $sensorout = null;

		$filtered = collect($filtered)->sortByDesc('timein'); #new code inorder to get first timein and last timeout

		foreach($filtered as $val) #LOOP TIL END TO GET FIRST IN AND LAST OUT
		{
			#IN MSG
			if(empty($val->timein)){
				$time_in = "No Timein";
			}
			else{
				$time_in = Func::toTime($val->timein);
			}
			
			#OUT MSG
			if(empty($val->timeout)){	
				$time_out = "NO TIMEOUT";
				$render = $val->total_hours; # AVOID BLANK VALUE
			}
			else{
				$time_out = Func::toTime($val->timeout);
				$render = $val->total_hours;
			}

			#Updated by with dash
			if(!empty($val->updated_by)){
				$val->updated_by = " By " . $val->updated_by;
			}

            #Updated by with dash
            if(!empty($val->second_updated_by)){
                $val->second_updated_by = " By " . $val->second_updated_by;
            }


			$logid = $val->logid;
			$logid_out = $val->logid_out;
			$late = $val->late;
			$ot = $val->ot;
			$days = $val->days;
			$remarks = $val->remarks;
			$second_remarks = $val->second_remarks;
			$schedule_id = $val->schedule_id;
			$start_time = $val->start_time;
			$end_time = $val->end_time;
			$updated_by = $val->updated_by;
			$second_updated_by = $val->second_updated_by;
			$approved_by = $val->approved_by;
			$ap_approved_by = $val->ap_approved_by;

			#device names
			$sensorin  = $val->sensorin;
			$sensorout  = $val->sensorout;
			$log_count++;

			if(!empty($approved_by)){
				$approved_by = "Approved By : " . $approved_by . "<br> Approved At : " . Func::toSimple12Date($val->approved_at);
			}

			if(!empty($ap_approved_by)){
				$ap_approved_by = "Confirm By : " . $ap_approved_by . "<br> Confirm At : " . Func::toSimple12Date($val->ap_approved_at);
			}

		}



		#----------- no timeout will look to next day --------#
        #-----------comment this if problem encounter ) ------#
        # --------adjust this code if code above changes -----#
        if($time_out == "NO TIMEOUT"){
            $time_log = collect($filtered)->sortByDesc('timeout')->first();

            #IN MSG
            if(empty($time_log->timein)){
                $time_in = "No Timein";
            }
            else{
                $time_in = Func::toTime($time_log->timein);
            }

            #OUT MSG
            if(empty($time_log->timeout)){
                $time_out = "No Timeout";
                $render = $time_log->total_hours; # AVOID BLANK VALUE
            }
            else{
                $time_out = Func::toTime($time_log->timeout);
                $render = $time_log->total_hours;
            }

            #Updated by with dash
            if(!empty($time_log->updated_by)){
                $time_log->updated_by = " By " . $time_log->updated_by;
            }

            #Updated by with dash
            if(!empty($time_log->second_updated_by)){
                $val->second_updated_by = " By " . $val->second_updated_by;
            }

            $logid = $time_log->logid;
            $logid_out = $time_log->logid_out;
            $late = $time_log->late;
            $ot = $time_log->ot;
            $days = $time_log->days;
            $remarks = $time_log->remarks;
            $second_remarks = $time_log->second_remarks;
            $schedule_id = $time_log->schedule_id;
            $start_time = $time_log->start_time;
            $end_time = $time_log->end_time;
            $updated_by = $time_log->updated_by;
            $second_updated_by = $time_log->second_updated_by;
            $approved_by = $time_log->approved_by;
            $ap_approved_by = $time_log->ap_approved_by;

            #device names
            $sensorin  = $time_log->sensorin;
            $sensorout  = $time_log->sensorout;
            $log_count++;

            if(!empty($approved_by)){
                $approved_by = "Approved By : " . $approved_by . "<br> Approved At : " . Func::toSimple12Date($time_log->approved_at);
            }

            if(!empty($ap_approved_by)){
                $ap_approved_by = "Confirm By : " . $ap_approved_by . "<br> Confirm At : " . Func::toSimple12Date($time_log->ap_approved_at);
            }
        }
        #-----------------------------------------------------#


		// if($time_in != null && $time_out != null){ $time = $time_in . ' - <br>' . $time_out; }
		// if($time_in == "No Timein"){ $time = null; $render = null; } #hide if no timein
		
		$log[] = (object)[
			"logid" => $logid,
			"logid_out" => $logid_out,
			"time_in" => $time_in,
			"time_out" => $time_out,
			"render" => $render,
			"late" => $late,
			"ot" => $ot,
			"log_count" => $log_count,
			"days" => $days,
			"schedule_id" => $schedule_id,
			"remarks" => $remarks,
			"second_remarks" => $second_remarks,
			"start_time" => $start_time,
			"end_time" => $end_time,
			"updated_by" => $updated_by,
			"second_updated_by" => $second_updated_by,
			"approved_by" => $approved_by,
			"ap_approved_by" => $ap_approved_by,
			"sensorin" => $sensorin,
			"sensorout" => $sensorout

		];

		return $log;
	}

	public static function getHrUID($log_id)
	{
		return (string)DB::connection('mysql')
               ->table('users_mapping')
               ->where('log_uid', $log_id)
               ->pluck('hr_uid')
               ->first();
	}

	public static function getLogUID($hr_uid)
	{
		return (string)DB::connection('mysql')
               ->table('users_mapping')
               ->where('hr_uid', $hr_uid)
               ->pluck('log_uid')
               ->first();
	}

	public static function getPrivelege($hr_uid)
	{
		return (string)DB::connection('mysql')
               ->table('users_mapping')
               ->where('hr_uid', $hr_uid)
               ->pluck('privelege')
               ->first();
	}

	public static function logUID_list($log_uids)
	{
		$val = "";
		foreach ($log_uids as $log_uid) {
      		$val .=  $log_uid->Userid . ",";
        }

        if($val == ""){
        	$val = "";
        }

        return rtrim($val, ",");
	}

	public static function hrUID_list($hr_uids)
	{
		$val = "";
		foreach ($hr_uids as $hr_uid) {
      		$val .= "'" . $hr_uid->user_id . "',";
        }

        if($val == ""){
        	$val = "";
        }

        return rtrim($val, ",");
	}

	public static function statusQuery($status)
	{
		if($status == '3'){
			$val = "WHERE time_in_out.checkIN IS NULL AND time_in_out.checkOUT IS NULL";
		}
		else{
			$val = "";
		}
		return $val;
	}

	public static function verifyDate($date) #AVOID ERR IF USER TYPES IN URL
	{
		try
		{
			$val = Carbon::parse($date);
		}
		catch (\Exception $e) {
			$val = Carbon::now();
		}
		return $val;
	}

	public static function toTime($date)
	{
		if($date != null)
			return Carbon::parse($date)->format('h:i:s A');
	}

	public static function to24Time($date)
	{
		if($date != null)
			return Carbon::parse($date)->format('H:i:s');
	}

	public static function toLongDate($date)
	{
		if($date != null)
			return Carbon::parse($date)->format('F j, Y D');
	}

	public static function toSimpleDate($date)
	{
		if($date != null)
			return Carbon::parse($date)->format('M. j, (D)');
	}

	public static function dateColumnName($date)
	{
		if($date != null)
			return Carbon::parse($date)->format('Mj');
	}

	public static function toDate($date)
	{
		if($date != null)
			return Carbon::parse($date)->format('Y-m-d');
	}

	public static function toSimple12Date($date)
	{
		if($date != null)
			return Carbon::parse($date)->format('Y-m-d h:i:s A');
	}

	public static function toDays($days)
	{
		$return = "";
		$weekDays = [
		  1 => 'M',
		  2 => 'T',
		  3 => 'W',
		  4 => 'Th',
		  5 => 'F',
		  6 => 'Sat',
		  7 => 'Sun'
		];

		$days = explode(',' , $days);

		foreach($days as $day)
		{
			@$return .= $weekDays[$day] . ', ' ;
		}

		return rtrim($return, ', ');
	}

	public static function none_string($val){
		$result = "";
		if(empty($val))
		{
			$result =  "N/A";
		}
		else
		{
			$result = $val;
		}
		return $result;
	}

	public static function text_message($text, $val){
		$result = "";
		if(empty($val))
		{
			$result =  "";
		}
		else
		{
			$result = "$text : " . $val . "<br>";
		}
		return $result;
	}

}
