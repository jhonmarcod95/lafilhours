<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privelege extends Model
{
    protected $connection = "sqlsrv";
    protected $table = "privelege";
}
