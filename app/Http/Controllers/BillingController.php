<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BillingController extends Controller
{

    /**
     *  Importe mensual por almacenes
     * @OA\Get(
     *      path="indicadores/facturacion-almacen",
     *      tags={" Importe mensual por almacenes"},
     *      summary="  Importe mensual por almacenes",
     *      description="Retorna los Importes mensuales por almacenes ",
     *      @OA\Response(
     *          response=200,
     *          description="Listado de los Importes mensuales por almacenes",
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
    public function getFacturacionAlmacen()
    {
        $result = DB::connection('pgsql')->select("
			( SELECT 
M.codalmc AS codalmacen,
                SUM ( ( M.IMPORTE + M.IMPORTE_MN ) ) importe,
                EXTRACT ( MONTH FROM M.FECHA ) AS mes,
                EXTRACT ( YEAR FROM M.FECHA ) AS anno 
                FROM
                               insumos.movimiento
                               M INNER JOIN insumos.productosentidades r ON r.codigo = M.codigoproducto 
                WHERE
                               M.CODMOVIMIENTO = 'VENT' 
                               AND EXTRACT ( YEAR FROM M.FECHA ) = EXTRACT ( YEAR FROM CURRENT_DATE ) 
                               AND M.codentidad = '111' 
                               AND M.codalmc IN ( '8', '9' ) 
                GROUP BY
                               M.CODALMc,
                               EXTRACT ( MONTH FROM M.FECHA ),
                               EXTRACT ( YEAR FROM M.FECHA ) 
                ORDER BY
                               codalmc,
                               ( EXTRACT ( MONTH FROM M.FECHA ) ) 
                ) UNION ALL
                (
                SELECT 
M.codalmc AS codalmacen,
                               SUM ( ( M.IMPORTE + M.IMPORTE_MN ) ) importe,
                               EXTRACT ( MONTH FROM M.FECHA ) AS mes,
                               EXTRACT ( YEAR FROM M.FECHA ) AS anno 
                FROM
                               insumos.movimiento
                               M INNER JOIN insumos.serviciosentidades r ON r.codigo = M.codigoproducto 
                WHERE
                               M.CODMOVIMIENTO = 'VENT' 
                               AND EXTRACT ( YEAR FROM M.FECHA ) = EXTRACT ( YEAR FROM CURRENT_DATE ) 
                               AND M.codentidad = '111' 
                               AND M.codalmc IN ( '8', '21', '23', '24', '25', '9' ) 
                GROUP BY
                               M.CODALMc,
                               EXTRACT ( MONTH FROM M.FECHA ),
                               EXTRACT ( YEAR FROM M.FECHA ) 
                ORDER BY
                               codalmc,
                ( EXTRACT ( MONTH FROM M.FECHA ) ) 
                )

	");
        return response()->json($result);
    }




    /**
     *  Importe acumulado por servicios
     * @OA\Get(
     *      path="indicadores/facturacion-servicio",
     *      tags={" Importe acumulado por servicios"},
     *      summary="  Importe acumulado por servicios",
     *      description="Retorna los Importes acumulados por servicios ",
     *      @OA\Response(
     *          response=200,
     *          description="Listado de los Importes acumulados por servicios",
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
    public function getFacturacionServicio(){

        $result = DB::connection('pgsql')->select("
		SELECT
			r.descripcion AS descripcionserv,
			r.codigo AS codigoserv,
			SUM ( ( M.IMPORTE + M.IMPORTE_MN ) ) importes,
			EXTRACT ( YEAR FROM M.FECHA ) AS anno 
		FROM
			insumos.movimiento
			M INNER JOIN insumos.serviciosentidades r ON r.codigo = M.codigoproducto 
		WHERE
			M.CODMOVIMIENTO = 'VENT' 
			AND EXTRACT ( YEAR FROM M.FECHA ) = EXTRACT ( YEAR FROM CURRENT_DATE ) 
			AND M.codentidad = '111' 
			AND M.codalmc IN ( '8', '21', '23', '24', '25' ) 
		GROUP BY
			r.descripcion,
			r.codigo,
			EXTRACT ( YEAR FROM M.FECHA ) 
		ORDER BY
			importes DESC LIMIT 10
	");
        return response()->json($result);
    }

}
