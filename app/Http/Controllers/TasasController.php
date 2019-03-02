<?php

namespace App\Http\Controllers;

use App\Tasa;
use Illuminate\Http\Request;

class TasasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo)
    {
        return response()->json(Tasa::where('tipo', $tipo)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tasa = Tasa::create($request->all());

        return response()->json(['success' => true, 'data' => $tasa]);
    }

    public static function obtenerTasaPorMonto($tipo, $monto) {
        return Tasa::where([
            ['tipo', $tipo], 
            ['monto_inicio', '<=', $monto],
            ['monto_final', '>=', $monto],
            ])->first();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tasa  $tasa
     * @return \Illuminate\Http\Response
     */
    
    public function show($tipo, $monto)
    {
        $tasa = self::obtenerTasaPorMonto($tipo, $monto);
        
        return response()->json($tasa);
    }

    public function calcular(Request $request) {
        $interes = self::obtenerTasaPorMonto('interes', $request->monto);
        $mora = self::obtenerTasaPorMonto('mora', $request->monto);
        $montoTotal = $request->monto * (($interes->tasa_porciento / 100) + 1); 
        $cuotaMensual = $montoTotal / $request->meses;
        return response()->json([
            'success' => true,
            'data' => [
                'interes' => [
                    'porciento' => $interes->tasa_porciento . '%',
                    'monto' => number_format($request->monto * ($interes->tasa_porciento / 100), 2, '.', ''),
                ],
                'mora' => [
                    'porciento' => $mora->tasa_porciento . '%',
                    'monto' => number_format($request->monto * ($mora->tasa_porciento / 100), 2, '.', ''),
                ],
                'monto_total' => number_format($montoTotal, 2, '.', ''),
                'cuota_mensual' => number_format($cuotaMensual, 2, '.', '') 
                ]
            ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tasa  $tasa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tasa $tasa)
    {
        $tasa->update($request->all());
        return response()->json(['success' => true, 'data' => $tasa]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tasa  $tasa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tasa $tasa)
    {
        $tasa->delete();
        return response()->json(['success' => true, 'data' => 'Tasa eliminada']);
    }
}
