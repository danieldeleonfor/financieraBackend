<?php

namespace App\Http\Controllers;

use App\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PagosController extends Controller
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

    private function validarPagos($pagos) {
        $today = Carbon::now();
        foreach ($pagos as $pago) {
            if ($pago->fecha_limite_pago < $today && $pago->estado == 'pendiente') {
                $prestamo = $pago->prestamo;

                $pago->update([
                    'mora' => number_format($pago->monto * (($prestamo->mora_porciento / 100)), 2, '.', ''),
                    'monto_a_pagar' => number_format($pago->monto * (($prestamo->mora_porciento / 100) + 1), 2, '.', ''),
                    'estado' => 'atrasado'
                ]);
            }
        }

        return $pagos;
    }

    public function buscar(Request $request) {
        $pagos = Pago::whereNotNull('estado');
        
        if ($request->cliente_id) {
            $pagos->where('cliente_id', $request->cliente_id);
        }

        if ($request->prestamo_id) {
            $pagos->where('prestamo_id', $request->prestamo_id);
        }

        if ($request->estado) {
            $pagos->where('estado', $request->estado);
        }

        if ($request->fechaDesde) {
            $pagos->where('fecha_limite_pago', '>=', $request->fechaDesde);
        }

        if ($request->fechaHasta) {
            $pagos->where('fecha_limite_pago', '<=', $request->fechaHasta);
        }
        $allPagos = $this->validarPagos($pagos->get());
        return response()->json($allPagos);
    }

    public function pagar($id) {
        $pago = Pago::findOrFail($id);
        $today = Carbon::now();
        $estado = $pago->fecha_limite_pago > $today ? 'pago' : 'pago_atrasado';
        $pago->update(['estado' => $estado, 'fecha_pago' => $today]);
        return response()->json(['success' => true, 'data' => $pago]);
    }

    public function deshacer($id) {
        $pago = Pago::findOrFail($id);
        $today = Carbon::now();
        $estado = $pago->fecha_limite_pago > $today ? 'pendiente' : 'atrasado';
        $pago->update(['estado' => $estado, 'fecha_pago' => $today]);
        return response()->json(['success' => true, 'data' => $pago]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
