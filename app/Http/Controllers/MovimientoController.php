<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimientos = Movimiento::with('producto', 'user')->paginate(10);
        return view('movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $productos = Producto::where('activo', true)->get();
        return view('movimientos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo'        => 'required|in:entrada,salida',
            'cantidad'    => 'required|integer|min:1',
            'motivo'      => 'nullable|string|max:255',
        ]);

        $movimiento = $request->only(['producto_id', 'tipo', 'cantidad', 'motivo']);
        $movimiento['user_id'] = auth()->id();

        Movimiento::create($movimiento);

        // Actualizar stock
        $producto = Producto::find($request->producto_id);
        if ($request->tipo === 'entrada') {
            $producto->increment('stock', $request->cantidad);
        } else {
            $producto->decrement('stock', $request->cantidad);
        }

        return redirect()->route('movimientos.index')->with('success', 'Movimiento registrado correctamente.');
    }

    public function show(Movimiento $movimiento)
    {
        return view('movimientos.show', compact('movimiento'));
    }

    public function edit(Movimiento $movimiento)
    {
        return view('movimientos.edit', compact('movimiento'));
    }

    public function update(Request $request, Movimiento $movimiento)
    {
        return redirect()->route('movimientos.index');
    }

    public function destroy(Movimiento $movimiento)
    {
        $movimiento->delete();
        return redirect()->route('movimientos.index')->with('success', 'Movimiento eliminado.');
    }
}