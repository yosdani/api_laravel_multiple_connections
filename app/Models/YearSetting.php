<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class YearSetting extends Model
{
    public $id;

    public $key;

    public $value;

    public function __construct($i, $k, $v){
        $this->id = $i;
        $this->key = $k;
        $this->value = $v;
    }
}