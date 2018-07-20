<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $connection = "sqlsrv2";
    protected $table = "FingerClient";
}
