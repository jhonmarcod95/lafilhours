<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $connection = "sqlsrv2";
    protected $table = "Dept";
}
