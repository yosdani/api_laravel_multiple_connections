<?php
/**
 * Created by PhpStorm.
 * User: joseraul
 * Date: 20/11/17
 * Time: 12:02
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesPlanReal extends Model
{
    public $mes;

    public $plan;

    public $real;

    public function __construct($m, $p, $r){
        $this->mes = $m;
        $this->plan = $p;
        $this->real = $r;
    }

}