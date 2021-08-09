<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public $id;

    public $name;

    public function __construct($i, $n){
        $this->id = $i;
        $this->name = $n;
    }
}