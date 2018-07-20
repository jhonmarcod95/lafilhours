<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Contracts\UserResolver;
use Auth;

class Schedule extends Model implements AuditableContract, UserResolver
{
	use Notifiable, Auditable;

    protected $connection = "sqlsrv";
   	protected $table = "schedule";

   	protected $fillable = [
   		'Userid', 
   		'start_time', 
   		'end_time', 
   		'days', 
   		'start_date',
   		'end_date', 
   		'remarks', 
   		'created_at',
   		'user_id'
   	];


   	public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }
}
