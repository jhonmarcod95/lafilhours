<?php

namespace App\Http\Controllers;

use App\RateType;
use App\SecondRemarks;
use Illuminate\Http\Request;
use Auth;
use App\Methods\Func;
use Carbon\Carbon;
use DB;
use App\Employee;
use App\Log;
use App\Device;
use App\Department;
use App\Approval;
use App\Remarks;

class AttendanceController extends Controller
{
    public function show()
    {
        if(Auth::check())
        {
            
    		#GET USER ID'S
    		$user_id = Auth::user()->id;
            $dept_assign = Auth::user()->dept_assign;

    		#GET FILTERS
    		$from_date = func::verifyDate(request('from'));
            $to_date = func::verifyDate(request('to'));
            $start_time = request('start_time');
            $end_time = request('end_time');
            $search = request('search');
            $req_dept = request('department');
            $req_device = request('device');
            $typeId = request('rateType');

            if($start_time == '') $start_time = "00:00:00";
            if($end_time == '') $end_time = "23:59:00";
            if($dept_assign <> null) $req_dept = $dept_assign;

            $f_from = Func::toDate($from_date) . ' ' . $start_time;
            $f_to = Func::toDate($to_date) . ' ' . $end_time;

            #DATA
            $logs = Log::in_records2($f_from, $f_to, $req_dept, $req_device, $search, 'ASC', '0',$typeId )->sortBy('date')->toArray();

            $employees = Func::getEmployeeList($logs);

            $dates = array_unique(array_map(function ($i) { return $i->date; }, $logs));
            
            $departments = ['' => 'All'] + Department::where('Deptid', '<>', '1')
                                         ->pluck('DeptName', 'Deptid')->all();

            $rateTypes = Func::rateTypes();
                                
            #DEVICES
            $devices = Func::devices();
            
            return view('reports.attendance.index', compact(
                'logs',
                'dates',
                'departments',
                'dept_assign',
                'devices',
                'employees',
                'rateTypes'
            ));
        }
        else
        {
            return redirect()->route('login');
        }
    }

    public function addRemarks()
    {
        $user_type = Auth::user()->user_type;
        $remarks = trim(preg_replace('/\s\s+/', ' ', request('remarks')));

        if($user_type == "1") #FOR FOREMEN ONLY (to be enhance)
        {
            Remarks::create([
                'logid' => request('Logid'), 
                'remarks' => $remarks,
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now()
            ]);
        }
        elseif($user_type == "2"){ #FOR SECOND LEVEL
            SecondRemarks::create([
                'logid' => request('Logid'),
                'remarks' => $remarks,
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now()
            ]);
        }

        return redirect(url()->previous());
    }

    public function deleteRemarks()
    {
        $remarksID = request('remarksID');

        $id = Remarks::where('logid', $remarksID)
            ->pluck('id')
            ->first();

        $remarks = Remarks::find($id);
        $remarks->delete();   
        return redirect(url()->previous());
    }

    public function deleteRemarks2()
    {
        $secondRemarksID = request('remarksID');

        $id = SecondRemarks::where('logid', $secondRemarksID)
            ->pluck('id')
            ->first();

        $remarks = SecondRemarks::find($id);
        $remarks->delete();
        return redirect(url()->previous());
    }

    #APPROVAL OF LOGS USING MULTIPLE CHECKBOXES--------------------------------------------------
    public function approval(){
        $approvals = request('approval');
        $user_type = Auth::user()->user_type;

        #VALIDATION : check if has selected approvals (to be enhance)----------------
        if(count($approvals) < 1)
        {
            ?>
            <script>
                alert('Select atleast one record');
                window.location.href = document.referrer;
            </script>
            <?php
            
        }
        #----------------------------------------------------------------------------

        if($user_type == "1"){ #Managers / Foreman USERS

            foreach($approvals as $approval)
            {
                #VALIDATION : avoid double approvals---------------------------------
                if(DB::connection('sqlsrv')
                    ->table('approvals')
                    ->where('logid', '=', $approval)
                    ->count())
                {
                    continue;
                }
                #--------------------------------------------------------------------

                DB::connection('sqlsrv')->table('approvals')->insert([
                    'logid' => $approval, 
                    'approve' => '1',
                    'approved_by' => Auth::user()->id, 
                    'approved_at' => Carbon::now()
                ]);
            }

        }
        elseif($user_type == "2"){ #AP USERS
            foreach($approvals as $approval)
            {
                DB::connection('sqlsrv')->table('approvals')
                ->where('logid', $approval)
                ->update([
                    'ap_approve' => '1',
                    'ap_approved_by' => Auth::user()->id,
                    'ap_approved_at' => Carbon::now()
                ]);
            }

        }
          
        return redirect(url()->previous());
    }
    #----------------------------------------------------------------------------------------------

    public function ap_approval(){
        #validation here for no selected

        $users = User::find(Auth::user()->id);
        $users->password = bcrypt(request('password'));
        $users->save();
    }


    #FOR EXPORTING -------------------------------------------------------------------------------------------------------
    public function export()
    {
        if(Auth::check())
        {
            
            #GET USER ID'S
            $user_id = Auth::user()->id;
            $dept_assign = Auth::user()->dept_assign;

            #GET FILTERS
            $from_date = func::verifyDate(request('from'));
            $to_date = func::verifyDate(request('to'));
            $start_time = request('start_time');
            $end_time = request('end_time');
            $search = request('search');
            $req_dept = request('department');
            $req_device = request('device');
            $typeId = request('rateType');

            if($start_time == '') $start_time = "00:00:00";
            if($end_time == '') $end_time = "23:59:00";
            if($dept_assign <> null) $req_dept = $dept_assign;

            $f_from = Func::toDate($from_date) . ' ' . $start_time;
            $f_to = Func::toDate($to_date) . ' ' . $end_time;

            #DATA
            $logs = Log::in_records2($f_from, $f_to, $req_dept, $req_device, $search, 'ASC', '0', $typeId)->sortBy('date')->toArray();

            $employees = Func::getEmployeeList($logs);

            $dates = array_unique(array_map(function ($i) { return $i->date; }, $logs));
            
            $departments = ['' => 'All'] + Department::where('Deptid', '<>', '1')
                                         ->pluck('DeptName', 'Deptid')->all();
          
                                
            #DEVICES
            $devices = Func::devices();
            
            return view('reports.attendance.export', compact(
                'logs',
                'dates',
                'departments',
                'dept_assign',
                'devices',
                'employees'
            ));
        }
        else
        {
            return redirect()->route('login');
        }
        
    }

   



}
