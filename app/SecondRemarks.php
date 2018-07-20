<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Contracts\UserResolver;
use Auth;

class SecondRemarks extends Model implements AuditableContract, UserResolver
{
    use Notifiable, Auditable;

    protected $connection = "sqlsrv";
    protected $table = "second_remarks";

    protected $fillable = [
        'logid',
        'remarks',
        'user_id',
        'created_at'
    ];

    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }
}
