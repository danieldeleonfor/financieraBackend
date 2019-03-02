<?php

namespace App\Http\Controllers;

use App\Prestamo;
use App\Pago;
use Illuminate\Http\Request;

class FinanciamientosController extends Controller
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
        $financiamientos = Prestamo::with('prestamista');
        $financiamientos->withCount([
            'pagos as total_pagos_pagados' => function ($query) {
                $query->where('estado', 'pago_atrasado')->orWhere('estado', 'pago');
            },
            'pagos as total_pagos_atrasados' => function ($query) {
                $query->where('estado', 'atrasado');
            }
            ]);
        if ($request->cliente_id) {
            $financiamientos->where('cliente_id', $request->cliente_id);
        }

        if ($request->montoDesde) {
            $financiamientos->where('monto_total', '>=', $request->montoDesde);
        }

        if ($request->montoHasta) {
            $financiamientos->where('monto_total', '<=', $request->montoHasta);
        }

        if ($request->estado) {
            $financiamientos->where('estado', $request->estado);
        }

        if ($request->fechaDesde) {
            $financiamientos->where('created_at', '>=', $request->fechaDesde);
        }

        if ($request->fechaHasta) {
            $financiamientos->where('created_at', '<=', $request->fechaHasta);
        }

        return response()->json($financiamientos->get());
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

    public function activar($id) {
        $financiamiento = Prestamo::findOrFail($id);
        $financiamiento->update(['estado' => 'activo']);
        return response()->json(['success' => true, 'data' => $financiamiento]);
    }

    public function inactivar($id) {
        $financiamiento = Prestamo::findOrFail($id);
        $financiamiento->update(['estado' => 'inactivo']);
        return response()->json(['success' => true, 'data' => $financiamiento]);
    }

    public function cancelar($id) {
        $financiamiento = Prestamo::findOrFail($id);
        $financiamiento->update(['estado' => 'cancelado']);
        return response()->json(['success' => true, 'data' => $financiamiento]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Prestamo  $prestamo
     * @return \Illuminate\Http\Response
     */
    public function show(Prestamo $prestamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prestamo  $prestamo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestamo  $prestamo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestamo $prestamo)
    {
        //
    }
}
