<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $connection = 'pgsql_2';
    protected $table = 'personal';
}
