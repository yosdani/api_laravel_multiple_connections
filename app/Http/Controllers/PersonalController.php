<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{

    /**
     *  cantidad de Trabajadores
     * @OA\Get(
     *      path="personal/trabajadores",
     *      tags={" Trabajadores"},
     *      summary="  cantidad de trabajadores activos en la entidad",
     *      description="Retorna la cantidad de trabajadores ",
     *      @OA\Response(
     *          response=200,
     *          description="La cantidad de trabajadores activos",
     *          @OA\JsonContent,
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     */
    public function getCantidadTrabajadores(){
        $result['cantidadTrabajadores'] = DB::connection('pgsql_2')->table('personal')
            ->where('baja','=',false)->count();

        return response()->json($result);

    }
}
