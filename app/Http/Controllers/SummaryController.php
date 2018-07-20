<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Schedule;
use App\Employee;
use DB;
use Auth;
use Carbon\Carbon;
use App\Methods\Func;


class SummaryController extends Controller
{

   public function show($employee)
   {
      if(Auth::check())
      {

         $employee_name = Employee::where('Userid', $employee)
            ->pluck('name')
            ->first();

         $hrs_render = func::none_string(request('hrs_render'));
         $lates = func::none_string(request('lates'));
         $late_count = func::none_string(request('late_count'));
         $absents = func::none_string(request('absents'));
         $overtime = func::none_string(request('overtime'));
         $present = func::none_string(request('present'));

         return view('reports.summary.index', compact(
            'employee_name',
            'hrs_render',
            'lates',
            'late_count',
            'absents',
            'overtime',
            'present'
         ));
      }
      else
      {
         return redirect()->route('login');
      }
   }
}
