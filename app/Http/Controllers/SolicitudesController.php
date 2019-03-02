<?php

namespace App\Http\Controllers;

use App\Solicitud;
use App\Http\Controllers;
use App\Cliente;
use App\Prestamo;
use App\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function buscar(Request $request) {
        $solicitudes = Solicitud::with('cliente');
        if ($request->cliente_id) {
            $solicitudes->where('cliente_id', $request->cliente_id);
        }

        if ($request->monto) {
            $solicitudes->where('monto', $request->monto);
        }

        if ($request->meses) {
            $solicitudes->where('meses', $request->meses);
        }

        if ($request->estado) {
            $solicitudes->where('estado', $request->estado);
        }

        if ($request->fechaDesde) {
            $solicitudes->where('created_at', '>=', $request->fechaDesde);
        }

        if ($request->fechaHasta) {
            $solicitudes->where('created_at', '<=', $request->fechaHasta);
        }

        return response()->json($solicitudes->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $solicitud = Solicitud::create(['estado' => 'pendiente'] + $request->all());
        return response()->json(['success' => true, 'data' => $solicitud]);
    }

    public function aprobar($id) {
        $solicitud = Solicitud::findOrFail($id);
        $interes = TasasController::obtenerTasaPorMonto('interes', $solicitud->monto);
        $mora = TasasController::obtenerTasaPorMonto('mora', $solicitud->monto);
        $solicitud->update(['estado' => 'aprobado']);
        
        $prestamo = Prestamo::create([
            'solicitud_id' => $solicitud->id,
            'prestamista_id' => auth()->id(),
            'cliente_id' => $solicitud->cliente_id,
            'monto_total' => number_format($solicitud->monto * (($interes->tasa_porciento / 100) + 1), 2, '.', ''),
            'mora_porciento' => $mora->tasa_porciento,
            'meses' => $solicitud->meses,
            'interes_porciento' => $interes->tasa_porciento,
            'estado' => 'activo'
        ]);

        $today = Carbon::today();
        $montoMensual = number_format($prestamo->monto_total / $prestamo->meses, 2, '.', '');

        for ($i=1; $i <= $prestamo->meses; $i++) { 
            Pago::create([
                'cliente_id' => $prestamo->cliente_id,
                'prestamo_id' => $prestamo->id,
                'monto' => $montoMensual,
                'mes' => $i,
                'monto_a_pagar' => $montoMensual,
                'fecha_limite_pago' => $today->addMonths(1),
                'estado' => 'pendiente'
            ]);
        }
        $solicitud->prestamo;
        return response()->json(['success' => true, 'data' => $solicitud]);
    }

    public function cancelar($id) {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->update(['estado' => 'cancelado']);

        return response()->json(['success' => true, 'data' => $solicitud]);
    }

    public function declinar($id) {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->update(['estado' => 'declinado']);

        return response()->json(['success' => true, 'data' => $solicitud]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->cliente;

        return response()->json(['success' => true, 'data' => $solicitud]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->update($request->all());

        return response()->json(['success' => true, 'data' => $solicitud]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return response()->json(['success' => true, 'data' => 'Solicitud eliminada']);
    }
}
