<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Employee;
use DB;
use Auth;
use Carbon\Carbon;
use App\Methods\Func;
use App\Device;

class LogsController extends Controller
{

   public function show()
   {
      if(Auth::check())
      {

         #GET USER ID'S
         $user_id = Auth::user()->id;
         $dept_assign = Auth::user()->dept_assign;

         #GET FILTERS
         $from = func::verifyDate(request('from') . ' 00:00:00');
         $to = func::verifyDate(request('to') . ' 23:59:59');
         $status = request('status');
         $search = request('search');
         $device = request('device');
         $typeId = request('rateType');

         $from_date = Carbon::parse($from);
         $to_date = Carbon::parse($to);

         #DATA
         $view = 'logs.admin.index';
         $logs = Log::in_records2($from_date, $to_date, $dept_assign, $device, $search, 'ASC', '0',$typeId);

         #DEVICES
         $devices = Func::devices();

         #rate type
         $rateTypes = Func::rateTypes();


         return view($view, compact(
            'from_date',
            'to_date',
            'logs',
            'devices',
            'rateTypes'
         ));  
      }
      else
      {
         return redirect()->route('login');
      }
   }
}
