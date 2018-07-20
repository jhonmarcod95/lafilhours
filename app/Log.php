<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use App\Methods\Func;


class Log extends Model
{
   	protected $connection = "sqlsrv2";
   	protected $table = "Checkinout";


	public static function in_records2($from, $to, $dept, $device, $search, $sort, $view, $type_id) #ADMIN VIEW
	{
		$search = str_replace(' ', '', $search);


    	#LOG UID
        if($type_id == '2'){
            $whereLaborerType = "(laborer_types.type_id = '$type_id') AND ";
        }
        else{
            $whereLaborerType = "(laborer_types.type_id IS NULL) AND ";
        }
		$log_uids = DB::connection('sqlsrv2')
		   	   ->select("SELECT Userid, laborer_types.type_id FROM Userinfo LEFT JOIN dtr.dbo.laborer_types AS laborer_types ON laborer_types.user_id = Userinfo.Userid WHERE $whereLaborerType (" . Func::search_names($search) . ")");

		   	   
		if(is_numeric($search)){ #To SummaryReport Only
			$log_uids = $search;
		}
		else{
			$log_uids = Func::logUID_list($log_uids);
		}
		
		$chklogs = DB::connection('sqlsrv')->select(
		"EXEC P_LOGS @date_from = '$from', @date_to = '$to', @dept = '$dept', @device = '$device', @uid = '$log_uids', @sort_by = '$sort', @view_type = '$view'"
		);


		#Avoid multiple logs (duplicate in/out) ----------------------------
		$chklogs = collect($chklogs)->unique(function ($item) {
		    return $item->date . $item->Logid . $item->Logid_out;
		});
		#-------------------------------------------------------------------

		// $logs_filter = collect($chklogs)->pluck('Logid')->min();
        // dd($logs_filter);
        // $collection = $collection->where('logid', '27007');
		$hide_logids = [];

		foreach ($chklogs as $chklog){
			$logid = $chklog->Logid;
			$logid_out = $chklog->Logid_out;
			$user_id = $chklog->Userid;
			$date = $chklog->date; 
			$in = $chklog->checkIN;         
			$out = $chklog->checkOUT;  
			
			#GET TIMEOUT FOR PM - AM LOGS ------------------------------------------------------------
			if(empty($out) && !empty($in)){
			
				#if(empty($logid)) $logid = ''; #convert null to blank to avoid InvalidArgumentException

				$next_in = Log::where('Logid', '>', $logid)
					->where('CheckType', '0')
					->where('Userid', $user_id)
					->pluck('Logid')
					->first();

				#if next time in is NULL next in will be equal to last logout [scenarios for new logs]
				if(empty($next_in)) $next_in = Log::where('Logid', '>', $logid)->where('CheckType', '1')->where('Userid', $user_id)->pluck('Logid')->max() + 1; #

				$out = Log::where('CheckType', '1')
					->where('Logid', '>', $logid)
					->where('Logid', '<', $next_in)
					->where('Userid', $user_id)
					->max('CheckTime');

				#
				$hide_logids[] = Log::where('CheckType', '1')
					->where('Logid', '>', $logid)
					->where('Logid', '<', $next_in)
					->where('Userid', $user_id)
					->pluck('Logid');

			}
			#-----------------------------------------------------------------------------------------
	   
			$total_hours = Func::total_hours($in, $out);
			$overtime = Func::get_overtime($total_hours, $chklog);

        	$logs[] = (object)[
				"logid" => $logid,
				"logid_out" => $logid_out,
				"date" =>  $date,
				"timein" => $in,
				"sensorin" => $chklog->sensorIN,
				"timeout" => $out,
				"sensorout" => $chklog->sensorOUT,
				"user_id" => $user_id,
				"employee_name" => $chklog->name,
				"total_hours" => $total_hours,
				"late" => $chklog->late,
				"ot" => $overtime,
				"days" => $chklog->days,
				"schedule_id" => $chklog->schedule_id,
				"remarks" => $chklog->remarks,
				"second_remarks" => $chklog->second_remarks,
				"start_time" => Func::toTime($chklog->start_time),
				"end_time" => Func::toTime($chklog->end_time),
				"updated_by" => $chklog->updated_by,
				"second_updated_by" => $chklog->second_updated_by,
				"approved_by" => $chklog->approved_by,
				"approved_at" => $chklog->approved_at,
				"ap_approved_by" => $chklog->ap_approved_by,
				"ap_approved_at" => $chklog->ap_approved_at
        	];

        }



        #
        $hide_logid_outs = [];
        foreach($hide_logids as $hide_logid){
        	foreach($hide_logid as $value){
        		$hide_logid_outs[] = $value;
        	}
        }


     	return collect($logs)->whereNotIn('logid_out', $hide_logid_outs)->sortByDesc('date');
	}


	

}
