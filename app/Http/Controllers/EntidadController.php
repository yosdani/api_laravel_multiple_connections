<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class EntidadController extends Controller {

    public function getUserEntidad ($idUser) {     
        $data['padre'] = DB::table('general.organizations_users as ouser')
        ->select('org.idorganizationcategory as category','org.name as entidad','org.idorganization as identidad')
        ->join('general.organizations as org','ouser.idorganization','=','org.idorganization')
        ->where('ouser.iduser', $idUser)
        ->first();

        if($data['padre']->identidad == 4) {
            $data['hijas'] = DB::table('general.organizations as org')
            ->select('org.name as entidad','org.idorganization as identidad')
            ->whereIn('org.idorganizationcategory', [3,5])
            ->orderby('org.name')
            ->get();
        } else {
            $data['hijas'] = DB::table('general.organizations as org')
            ->select('org.name as entidad','org.idorganization as identidad')
            ->where('org.idparent', $data['padre']->identidad)
            ->orderby('org.name')
            ->get();
        }

        return response()->json($data);
    }

    public function getEntidad()
    {
        $data['entidades'] = DB::table('general.organizations as org')
            ->select('org.name as entidad','org.idorganization as identidad')
            ->whereIn('org.idorganizationcategory', [3,5])
            ->orderby('org.name')
            ->get();

    return response()->json($data);
    }
}