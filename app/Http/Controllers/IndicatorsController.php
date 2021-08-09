<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class IndicatorsController extends Controller
{

    /**
     * Lista de las Ventas
     * @OA\Get(
     *      path="indicadores/ventas",
     *      tags={"Ventas"},
     *      summary="Lista de Ventas",
     *      description="Retorna las ventas ",
     *      @OA\Response(
     *          response=200,
     *          description="Listado de las ventas",
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
    public function getVentas(){
        $result = DB::connection('pgsql')->select("
            ( SELECT 1 AS tipogastoingreso,( CASE WHEN mesactual.importe IS NULL THEN 0 ELSE mesactual.importe END ) AS importe1,( CASE WHEN mesanterior.importe IS NULL THEN 0 ELSE mesanterior.importe END ) AS importe2,	( CASE WHEN mesactual.descripcion IS NULL THEN mesanterior.descripcion ELSE mesactual.descripcion END ) AS descripcion FROM	(SELECT CAST( importe AS FLOAT ) AS importe, TRIM ( descripcion ) AS descripcion, cuenta FROM	contabilidad.resumen_diario WHERE	( cuenta BETWEEN 900 AND 919 ) AND anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) AND mes = 
( SELECT EXTRACT ( MONTH FROM CURRENT_DATE )  ) AND codentidad = '111' ) AS mesactual FULL JOIN (SELECT CAST( importe AS FLOAT ) AS importe,	TRIM ( descripcion ) AS descripcion, cuenta FROM	contabilidad.resumen_diario WHERE	( cuenta BETWEEN 900 AND 919 ) AND anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) AND mes = ( SELECT EXTRACT ( MONTH FROM CURRENT_DATE ) - 1 )AND codentidad = '111' ) AS mesanterior ON mesactual.cuenta = mesanterior.cuenta AND mesactual.descripcion = mesanterior.descripcion ORDER BY tipogastoingreso, importe2 DESC ) 
	");

        return response()->json($result);
    }
    /**
     * Gastos
     * @OA\Get(
     *      path="indicadores/gastos",
     *      tags={"Gastos"},
     *      summary=" Gastos",
     *      description="Retorna los Gastos ",
     *      @OA\Response(
     *          response=200,
     *          description="Listado de los Gastos",
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
    public function getGastos()
    {
        $result = DB::connection('pgsql')->select("
        SELECT tipogastoingreso, importe1, importe2, descripcion from 
      ((SELECT 1 as tipogastoingreso, (case when mesactual.importe is null then 0 else mesactual.importe end) as importe1, (case when mesanterior.importe is null then 0 else mesanterior.importe end) as importe2, (case when mesactual.descripcion is null then mesanterior.descripcion else mesactual.descripcion end) as descripcion from (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 110001 AND 230999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) ) AND codentidad='111') as mesactual full join (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 110001 AND 230999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) - 1) AND codentidad='111') as mesanterior on mesactual.cuenta = mesanterior.cuenta and mesactual.subcuenta = mesanterior.subcuenta order by tipogastoingreso,importe2 desc ) 
      union all 
      (SELECT 2 as tipogastoingreso,(case when mesactual.importe is null then 0 else mesactual.importe end) as importe1, (case when mesanterior.importe is null then 0 else mesanterior.importe end) as importe2, (case when mesactual.descripcion is null then mesanterior.descripcion else mesactual.descripcion end) as descripcion from (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 300001 AND 300600) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) ) AND codentidad='111') as mesactual full join (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 300001 AND 300600) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) - 1) AND codentidad='111') as mesanterior on mesactual.cuenta = mesanterior.cuenta and mesactual.subcuenta = mesanterior.subcuenta order by tipogastoingreso,importe2 desc ) 
      union all 
      (SELECT 3 as tipogastoingreso, (case when mesactual.importe is null then 0 else mesactual.importe end) as importe1, (case when mesanterior.importe is null then 0 else mesanterior.importe end) as importe2, (case when mesactual.descripcion is null then mesanterior.descripcion else mesactual.descripcion end) as descripcion from (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 400001 AND 400400) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) ) AND codentidad='111') as mesactual full join (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 400001 AND 400400) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) - 1) AND codentidad='111') as mesanterior on mesactual.cuenta = mesanterior.cuenta and mesactual.subcuenta = mesanterior.subcuenta order by tipogastoingreso,importe2 desc ) 
      union all 
      (SELECT 4 as tipogastoingreso, (case when mesactual.importe is null then 0 else mesactual.importe end) as importe1, (case when mesanterior.importe is null then 0 else mesanterior.importe end) as importe2, (case when mesactual.descripcion is null then mesanterior.descripcion else mesactual.descripcion end) as descripcion from (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 500001 AND 500999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) ) AND codentidad='111') as mesactual full join (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 500001 AND 500999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) - 1) AND codentidad='111') as mesanterior on mesactual.cuenta = mesanterior.cuenta and mesactual.subcuenta = mesanterior.subcuenta order by tipogastoingreso,importe2 desc ) 
      union all 
      (SELECT 5 as tipogastoingreso, (case when mesactual.importe is null then 0 else mesactual.importe end) as importe1, (case when mesanterior.importe is null then 0 else mesanterior.importe end) as importe2, (case when mesactual.descripcion is null then mesanterior.descripcion else mesactual.descripcion end) as descripcion from (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 700001 AND 700999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) ) AND codentidad='111') as mesactual full join (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 700001 AND 700999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) - 1) AND codentidad='111') as mesanterior on mesactual.cuenta = mesanterior.cuenta and mesactual.subcuenta = mesanterior.subcuenta order by tipogastoingreso,importe2 desc ) 
      union all
      (SELECT 6 as tipogastoingreso, (case when mesactual.importe is null then 0 else mesactual.importe end) as importe1, (case when mesanterior.importe is null then 0 else mesanterior.importe end) as importe2, (case when mesactual.descripcion is null then mesanterior.descripcion else mesactual.descripcion end) as descripcion from (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 800001 AND 800999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) ) AND codentidad='111') as mesactual full join (SELECT CAST(importe as float) as importe, trim(descripcion) as descripcion, subcuenta, cuenta FROM contabilidad.resumen_diario where (subcuenta BETWEEN 800001 AND 800999) AND anno=(select extract(Year FROM CURRENT_DATE)) AND mes=(select EXTRACT(MONTH FROM CURRENT_DATE) - 1) AND codentidad='111') as mesanterior on mesactual.cuenta = mesanterior.cuenta and mesactual.subcuenta = mesanterior.subcuenta order by tipogastoingreso,importe2 desc )) as gastos 
      order by importe1 desc ");



        return response()->json($result);

    }

    public  function getIngresos()
    {
        $result = DB::connection('pgsql')->select("
          (SELECT 1 AS tipogastoingreso,( CASE WHEN mesactual.importe IS NULL THEN 0 ELSE mesactual.importe END ) AS importe1,( CASE WHEN mesanterior.importe IS NULL THEN 0 ELSE mesanterior.importe END ) AS importe2,( CASE WHEN mesactual.descripcion IS NULL THEN mesanterior.descripcion ELSE mesactual.descripcion END ) AS descripcion 
    FROM (SELECT CAST ( importe AS FLOAT ) AS importe,TRIM ( descripcion ) AS descripcion,cuenta FROM contabilidad.resumen_diario WHERE ( cuenta BETWEEN 920 AND 939 ) AND anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) AND mes = ( SELECT EXTRACT ( MONTH FROM CURRENT_DATE ) ) AND codentidad = '111' ) AS mesactual 
    FULL JOIN (SELECT CAST ( importe AS FLOAT ) AS importe, TRIM ( descripcion ) AS descripcion, cuenta FROM contabilidad.resumen_diario WHERE ( cuenta BETWEEN 920 AND 939 ) AND anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) AND mes = ( SELECT EXTRACT ( MONTH FROM CURRENT_DATE ) - 1 ) AND codentidad = '111' 
    ) AS mesanterior ON mesactual.cuenta = mesanterior.cuenta AND mesactual.cuenta = mesanterior.cuenta ORDER BY tipogastoingreso, importe2 DESC ) UNION ALL ( SELECT 2 AS tipogastoingreso, ( CASE WHEN mesactual.importe IS NULL THEN 0 ELSE mesactual.importe END ) AS importe1, ( CASE WHEN mesanterior.importe IS NULL THEN 0 ELSE mesanterior.importe END ) AS importe2, 
    ( CASE WHEN mesactual.descripcion IS NULL THEN mesanterior.descripcion ELSE mesactual.descripcion END ) AS descripcion FROM ( SELECT CAST ( importe AS FLOAT ) AS importe, TRIM ( descripcion ) AS descripcion, cuenta FROM contabilidad.resumen_diario WHERE ( cuenta BETWEEN 950 AND 953 ) AND anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) 
    AND mes = ( SELECT EXTRACT ( MONTH FROM CURRENT_DATE ) ) AND codentidad = '111') AS mesactual FULL JOIN (SELECT CAST( importe AS FLOAT ) AS importe, TRIM ( descripcion ) AS descripcion, cuenta FROM contabilidad.resumen_diario WHERE ( cuenta BETWEEN 950 AND 953 ) AND anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) 
    AND mes = ( SELECT EXTRACT ( MONTH FROM CURRENT_DATE ) - 1 ) AND codentidad = '111' ) AS mesanterior ON mesactual.cuenta = mesanterior.cuenta AND mesactual.cuenta = mesanterior.cuenta ORDER BY tipogastoingreso, importe2 DESC)  
        ");


        return response()->json($result);
    }

    public function getUtilidades()
    {
        $result = DB::connection('pgsql')->select("
          (SELECT SUM  	( real_acumulado ) AS real_acumulado,	mes AS mes_no, CASE
		
		WHEN mes = 1 THEN
		'Enero' 
		WHEN mes = 2 THEN
		'Febrero' 
		WHEN mes = 3 THEN
		'Marzo' 
		WHEN mes = 4 THEN
		'Abril' 
		WHEN mes = 5 THEN
		'Mayo' 
		WHEN mes = 6 THEN
		'Junio' 
		WHEN mes = 7 THEN
		'Julio' 
		WHEN mes = 8 THEN
		'Agosto' 
		WHEN mes = 9 THEN
		'Septiembre' 
		WHEN mes = 10 THEN
		'Octubre' 
		WHEN mes = 11 THEN
		'Noviembre' 
		WHEN mes = 12 THEN
		'Diciembre' 
	END AS mes,
CASE
		
		WHEN mes BETWEEN 1 
		AND 3 THEN
			1 
			WHEN mes BETWEEN 4 
			AND 6 THEN
				2 
				WHEN mes BETWEEN 7 
				AND 9 THEN
					3 
					WHEN mes BETWEEN 10 
					AND 12 THEN
						4 
						END AS trimestre 
				FROM
					(
					SELECT SUM
						( importe ) AS real_acumulado,
						mes 
					FROM
						contabilidad.resumen_diario 
					WHERE
						anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) 
						AND codentidad = '111' 
						AND ( ( cuenta BETWEEN 900 AND 939 ) OR ( cuenta BETWEEN 950 AND 953 ) ) 
					GROUP BY
						mes UNION ALL
					SELECT
						- SUM ( importe ) AS real_acumulado,
						mes 
					FROM
						contabilidad.resumen_diario 
					WHERE
						anno = ( SELECT EXTRACT ( YEAR FROM CURRENT_DATE ) ) 
						AND codentidad = '111' 
						AND (
							( cuenta BETWEEN 800 AND 841 ) 
							OR ( cuenta = 843 ) 
							OR ( cuenta BETWEEN 845 AND 854 ) 
							OR ( cuenta BETWEEN 855 AND 867 ) 
							OR ( cuenta = 873 ) 
						) 
					GROUP BY
						mes 
					) AS mensual 
			GROUP BY
	mes)  
        ");

        return response()->json($result);
    }
    /**
     *  Gastos Trimestrales
     * @OA\Get(
     *      path="indicadores/gastostrimestrales",
     *      tags={"Gastos Trimestrales"},
     *      summary=" Gastos Trimestrales",
     *      description="Retorna los Gastos por trimestre ",
     *      @OA\Response(
     *          response=200,
     *          description="Listado de los Gastos por Trimestre",
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
    public function getGastosTrimestrales(){

        $result = DB::connection('pgsql')->select("
            select sum(importe), trimestre from
(SELECT 
sum(CAST(importe as float)) as importe, 
mes,
CASE WHEN mes BETWEEN 1 AND 3 THEN 1 WHEN mes BETWEEN 4 AND 6 THEN 2 WHEN mes BETWEEN 7 AND 9 THEN 3 WHEN mes BETWEEN 10 AND 12 THEN 4 END AS trimestre 
FROM contabilidad.resumen_diario where ((subcuenta BETWEEN 110001 AND 230999) OR (subcuenta BETWEEN 300001 AND 300600) OR (subcuenta BETWEEN 400001 AND 400400) OR (subcuenta BETWEEN 500001 AND 500999) OR (subcuenta BETWEEN 700001 AND 700999) OR (subcuenta BETWEEN 800001 AND 800999))  AND anno=(select extract(Year FROM CURRENT_DATE)) AND codentidad='111' GROUP BY mes) as gastomesnual group by trimestre");

        return response()->json($result);
    }

    public function getVentasTrimestrales(){

        $result = DB::connection('pgsql')->select("
select sum(importe), trimestre from
(SELECT 
sum(CAST(importe as float)) as importe, 
mes,
CASE WHEN mes BETWEEN 1 AND 3 THEN 1 WHEN mes BETWEEN 4 AND 6 THEN 2 WHEN mes BETWEEN 7 AND 9 THEN 3 WHEN mes BETWEEN 10 AND 12 THEN 4 END AS trimestre 
FROM contabilidad.resumen_diario where ((cuenta BETWEEN 900 AND 919) )  AND anno=(select extract(Year FROM CURRENT_DATE)) AND codentidad='111' GROUP BY mes) as gastomensual group by trimestre
");
        return response()->json($result);
    }


    public function getIngresosTrimestrales(){

        $result = DB::connection('pgsql')->select("
select sum(importe), trimestre from
(SELECT 
sum(CAST(importe as float)) as importe, 
mes,
CASE WHEN mes BETWEEN 1 AND 3 THEN 1 WHEN mes BETWEEN 4 AND 6 THEN 2 WHEN mes BETWEEN 7 AND 9 THEN 3 WHEN mes BETWEEN 10 AND 12 THEN 4 END AS trimestre 
FROM contabilidad.resumen_diario where ((cuenta BETWEEN 920 AND 939) OR (cuenta BETWEEN 950 AND 953))  AND anno=(select extract(Year FROM CURRENT_DATE)) AND codentidad='111' GROUP BY mes) as gastomensual group by trimestre
");
        return response()->json($result);
    }
}
