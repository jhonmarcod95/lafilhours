<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Employee;
use DB;
use Auth;
use Carbon\Carbon;
use App\Methods\Func;
use App\Schedule;
use App\Device;

use Illuminate\Validation\Validator;

class EmployeeController extends Controller
{

   public function show()
   {
      if(Auth::check())
      {
         $device = request('device');
         $employees = DB::connection('sqlsrv')
            ->select("EXEC P_WORKERS @search = '', @location = '$device'");

         #DEVICES
         $devices = Func::devices();

         return view('employees.index', compact(
            'employees',
            'devices'
         ));
      }
      else
      {
         return redirect()->route('login');
      }
   }

   public function schedule($employee)
   {
      if(Auth::check())
      {
         $employees = DB::connection('sqlsrv')->select("EXEC P_SCHEDULE @emp_id = '$employee'");
   

         return view('employees.schedule', compact(
            'employees'
         ));
      }
      else
      {
         return redirect()->route('login');
      }      
   }

   public function search(Request $request) 
   {
      $checklist = collect($request->checklist);

      if($request->ajax())  
      {
         $output="";
         $employees = DB::connection('sqlsrv')
            ->select("EXEC P_WORKERS @search = '$request->worker_name', @location = '$request->location'");
          
         if($employees)
         {
            foreach ($employees as $key => $employee) {
               
               $status = $checklist->contains($employee->Userid);

               $checkstat = "";
               if($status){
                  $checkstat = "checked";
               }


               $output.="
               <tr>
                  <td><input id=\"chk\" name=\"checkitems[]\" type=\"checkbox\" value=\"$employee->Userid\" $checkstat></td>
                  <td>$employee->Name</td>
                  <td>$employee->Location</td>
                  <td></td>
               </tr>";
            }
       
            return Response($output);
         }
      }

   }

}
