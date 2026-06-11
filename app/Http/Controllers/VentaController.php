<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleVenta;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('cliente', 'user')->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

public function create()
{
    $clientes   = Cliente::all();
    $categorias = \App\Models\Categoria::where('activo', true)->get();
    $productos  = Producto::where('activo', true)
                    ->where('stock', '>', 0)
                    ->with('categoria')
                    ->get();

    return view('ventas.create', compact('clientes', 'categorias', 'productos'));
}

    public function store(Request $request)
    {
        $request->validate([
            'fecha_venta' => 'required|date',
            'productos'   => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            foreach ($request->productos as $item) {
                $subtotal += $item['precio_unitario'] * $item['cantidad'];
            }

            $impuesto = $subtotal * 0.18;
            $total    = $subtotal + $impuesto;

            $venta = Venta::create([
                'cliente_id'    => $request->cliente_id,
                'user_id'       => auth()->id(),
                'numero_boleta' => 'B001-' . str_pad(Venta::count() + 1, 6, '0', STR_PAD_LEFT),
                'fecha_venta'   => $request->fecha_venta,
                'subtotal'      => $subtotal,
                'impuesto'      => $impuesto,
                'total'         => $total,
                'estado'        => $request->estado ?? 'completado',
            ]);

            foreach ($request->productos as $item) {
                DetalleVenta::create([
                    'venta_id'       => $venta->id,
                    'producto_id'    => $item['producto_id'],
                    'cantidad'       => $item['cantidad'],
                    'precio_unitario'=> $item['precio_unitario'],
                    'subtotal'       => $item['precio_unitario'] * $item['cantidad'],
                ]);

                // Descontar stock
                $producto = Producto::find($item['producto_id']);
                $producto->decrement('stock', $item['cantidad']);

                // Registrar movimiento
                Movimiento::create([
                    'producto_id' => $item['producto_id'],
                    'user_id'     => auth()->id(),
                    'tipo'        => 'salida',
                    'cantidad'    => $item['cantidad'],
                    'motivo'      => 'Venta ' . $venta->numero_boleta,
                ]);
            }
        });

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta)
    {
        $venta->load('cliente', 'user', 'detalles.producto');
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        return view('ventas.edit', compact('venta'));
    }

    public function update(Request $request, Venta $venta)
    {
        $venta->update(['estado' => $request->estado]);
        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }
}