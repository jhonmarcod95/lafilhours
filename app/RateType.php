<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateType extends Model
{
    protected $connection = "sqlsrv";
    protected $table = 'rate_types';
}
