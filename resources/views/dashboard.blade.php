<x-layouts.app>

    <x-layouts.app.sidebar>
        <flux:main>

            {{-- Welcome Header --}}
            <header class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Panel de Control</h2>
                <p class="text-gray-500 mt-1">Bienvenido de nuevo, {{ auth()->user()->name }}. Aquí tienes el resumen de hoy.</p>
            </header>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-700">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">+12.5%</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">Ventas del Día</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-900">S/ {{ number_format($ventasHoy ?? 0, 2) }}</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-pink-50 rounded-lg text-pink-700">
                            <span class="material-symbols-outlined">inventory</span>
                        </div>
                        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">Acción Requerida</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">Productos Bajo Stock</h3>
                    <span class="text-2xl font-bold text-gray-900">{{ $bajoStock ?? 0 }} Artículos</span>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-teal-50 rounded-lg text-teal-700">
                            <span class="material-symbols-outlined">pending_actions</span>
                        </div>
                        <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-2 py-1 rounded">En Proceso</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 mb-1">Pedidos Pendientes</h3>
                    <span class="text-2xl font-bold text-gray-900">{{ $ventasPendientes ?? 0 }}</span>
                </div>

            </div>

            {{-- Gráfico + Accesos Directos --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <div class="lg:col-span-8 bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Ventas Semanales</h3>
                            <p class="text-sm text-gray-500">Rendimiento de los últimos 7 días</p>
                        </div>
                    </div>
                    <div class="relative h-64 flex items-end justify-between gap-2 px-4">
                        @php
                            $dias = ['Lun','Mar','Mié','Jue','Vie','Hoy','Dom'];
                            $alturas = ['40%','65%','55%','85%','70%','95%','45%'];
                        @endphp
                        @foreach($dias as $i => $dia)
                        <div class="flex flex-col items-center flex-1 group {{ $i === 6 ? 'opacity-40' : '' }}">
                            <div class="w-full bg-blue-100 rounded-t-lg flex items-end overflow-hidden"
                                 style="height: {{ $alturas[$i] }}">
                                @if($i < 6)
                                <div class="w-full bg-blue-700 chart-bar" style="height: 100%"></div>
                                @endif
                            </div>
                            <span class="mt-4 text-xs text-gray-500 {{ $i === 5 ? 'font-bold' : '' }}">{{ $dia }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm h-full">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Accesos Directos</h3>
                        <div class="space-y-4">
                            <a href="{{ route('ventas.create') }}"
                               class="w-full flex items-center justify-between p-4 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition group">
                                <div class="flex items-center gap-4">
                                    <span class="material-symbols-outlined">add_shopping_cart</span>
                                    <span class="font-semibold">Registrar Venta</span>
                                </div>
                                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
                            </a>
                            <a href="{{ route('productos.create') }}"
                               class="w-full flex items-center justify-between p-4 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition group">
                                <div class="flex items-center gap-4">
                                    <span class="material-symbols-outlined text-pink-600">add_box</span>
                                    <span class="font-semibold">Agregar Producto</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 group-hover:translate-x-1 transition-transform">chevron_right</span>
                            </a>
                            <a href="{{ route('productos.index') }}"
                               class="w-full flex items-center justify-between p-4 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition group">
                                <div class="flex items-center gap-4">
                                    <span class="material-symbols-outlined text-blue-700">list_alt</span>
                                    <span class="font-semibold">Ver Inventario</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 group-hover:translate-x-1 transition-transform">chevron_right</span>
                            </a>
                        </div>
                        <div class="mt-8 p-4 bg-pink-50 rounded-xl border border-pink-100 flex items-start gap-4">
                            <div class="bg-pink-700 text-white p-2 rounded-lg shrink-0">
                                <span class="material-symbols-outlined">auto_awesome</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-pink-700">¿Nuevo en ShopMaster?</h4>
                                <p class="text-xs text-gray-500 mt-1">Configura tus alertas automáticas de bajo stock en ajustes.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Últimos Movimientos --}}
            <div class="mt-8 bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Últimos Movimientos</h3>
                    <a href="{{ route('ventas.index') }}" class="text-blue-700 font-semibold hover:underline">Ver todo</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs text-gray-400 border-b border-gray-100 uppercase">
                                <th class="pb-4 font-semibold">Producto / Servicio</th>
                                <th class="pb-4 font-semibold">Fecha</th>
                                <th class="pb-4 font-semibold">Estado</th>
                                <th class="pb-4 font-semibold text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($ultimasVentas ?? [] as $venta)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400">receipt</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">
                                                {{ $venta->detalles->first()->producto->nombre ?? 'Venta' }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $venta->numero_boleta }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-sm text-gray-500">{{ $venta->created_at->diffForHumans() }}</td>
                                <td class="py-4">
                                    @if($venta->estado === 'completado')
                                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-full font-medium">Completado</span>
                                    @elseif($venta->estado === 'pendiente')
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full font-medium">Pendiente</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">{{ ucfirst($venta->estado) }}</span>
                                    @endif
                                </td>
                                <td class="py-4 text-right font-mono text-sm text-gray-900">S/ {{ number_format($venta->total, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 font-semibold">
                                    No hay movimientos aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </flux:main>
    </x-layouts.app.sidebar>

</x-layouts.app>