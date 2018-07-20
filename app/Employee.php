<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $connection = "sqlsrv2";
    protected $table = "Userinfo";

    public static function fullname($id)
    {
       //  $log_uids = DB::connection('sqlsrv2')
       //        ->select("SELECT Userid FROM Userinfo WHERE " . Func::search_names($search));  

      	// $hr_uid = (string)DB::connection('sqlsrv2')
      	// 	    ->table('users_mapping')
      	// 	    ->where('log_uid', $id)
      	// 	    ->pluck('hr_uid')
      	//     	->first();
        $fullname = Employee::where('Userid', $id)
              ->pluck('name')
              ->first();

        return $fullname;
    }
}
