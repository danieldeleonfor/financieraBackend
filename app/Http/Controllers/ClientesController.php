<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\User;
use App\Prestamo;
use App\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::withCount([
            'financiamientos as total_financiamientos_activos' => function ($query) {
                $query->where('estado', 'activo');
            },
            'solicitudes as total_solicitudes_pendientes' => function ($query) {
                $query->where('estado', 'pendiente');
            }
            ]);
        return response()->json($clientes->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cliente = Cliente::create($request->all());

        User::create([
            'cliente_id' => $cliente->id,
            'nombre' => $cliente->nombre . ' ' . $cliente->apellido,
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password),
            'rol' => 'cliente'
        ]);
        return response()->json(['success' => true, 'data' => $cliente]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::withCount([
            'financiamientos as total_financiamientos_activos' => function ($query) {
                $query->where('estado', 'activo');
            },
            'solicitudes as total_solicitudes_pendientes' => function ($query) {
                $query->where('estado', 'pendiente');
            },
            ])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $cliente]);
    }

    public function solicitudes(Request $request, $clienteId) {
        $solicitudes = Solicitud::where('cliente_id', $clienteId);
        if ($request->estado) {
            $solicitudes->where('estado', $request->estado);
        }
        return response()->json($solicitudes->get());
    }

    public function financiamientos(Request $request, $clienteId) {
        $prestamos = Prestamo::where('cliente_id', $clienteId);
        $prestamos->with('prestamista');
        if ($request->estado) {
            $prestamos->where('estado', $request->estado);
        }

        return response()->json($prestamos->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());

        return response()->json(['success' => true, 'data' => $cliente]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json(['success' => true, 'data' => 'Cliente eliminado']);
    }
}
