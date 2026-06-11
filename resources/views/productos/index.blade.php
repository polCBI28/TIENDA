<x-layouts.app>
    <x-layouts.app.sidebar>
        <flux:main>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Control de Inventario</h2>
                <p class="text-gray-500">Gestione su stock, precios y estados de productos en tiempo real.</p>
            </div>
            <div class="flex gap-3">
                <a href="#" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-all text-sm">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Exportar CSV
                </a>
                <a href="{{ route('productos.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-pink-700 text-white rounded-lg font-semibold hover:bg-pink-800 shadow-sm transition-all text-sm">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Nuevo Producto
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl font-semibold text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Productos</p>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold">{{ $totalProductos }}</span>
                    <span class="text-blue-700 text-xs font-semibold">+{{ $nuevosEsteMes }} este mes</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Bajo Stock</p>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold text-pink-700">{{ $bajoStock }}</span>
                    <span class="text-pink-700 text-xs font-semibold">Acción requerida</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Valor Total</p>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold">S/ {{ number_format($valorTotal, 0) }}</span>
                    <span class="text-teal-600 text-xs font-semibold">Actualizado hoy</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Agotados</p>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-bold">{{ $agotados }}</span>
                    <span class="text-gray-400 text-xs font-semibold">Inactivos</span>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="bg-white rounded-t-xl border-x border-t border-gray-200 p-4 flex flex-wrap items-center gap-4">
            <form method="GET" action="{{ route('productos.index') }}" class="flex flex-wrap items-center gap-4 w-full">
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500 font-semibold">Categoría:</label>
                    <select name="categoria_id" onchange="this.form.submit()"
                            class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-blue-700">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500 font-semibold">Estado:</label>
                    <select name="estado" onchange="this.form.submit()"
                            class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-blue-700">
                        <option value="">Todos los estados</option>
                        <option value="en_stock"   {{ request('estado') == 'en_stock'   ? 'selected' : '' }}>En Stock</option>
                        <option value="bajo_stock" {{ request('estado') == 'bajo_stock' ? 'selected' : '' }}>Bajo Stock</option>
                        <option value="agotado"    {{ request('estado') == 'agotado'    ? 'selected' : '' }}>Agotado</option>
                    </select>
                </div>
                <div class="ml-auto text-sm text-gray-400">
                    Mostrando {{ $productos->firstItem() }}-{{ $productos->lastItem() }} de {{ $productos->total() }}
                </div>
            </form>
        </div>

        {{-- Tabla --}}
        <div class="bg-white border border-gray-200 rounded-b-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Producto</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Categoría</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Stock Actual</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Precio de Venta</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Estado</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($productos as $producto)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($producto->imagen)
                                            <img src="{{ asset('storage/' . $producto->imagen) }}"
                                                 class="w-full h-full object-cover"
                                                 alt="{{ $producto->nombre }}">
                                        @else
                                            <span class="material-symbols-outlined text-gray-400">inventory_2</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $producto->nombre }}</p>
                                        <p class="text-xs text-gray-400">SKU: {{ $producto->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                    {{ $producto->categoria->nombre ?? '--' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono
                                {{ $producto->stock == 0 ? 'text-red-600' : ($producto->stock <= $producto->stock_minimo ? 'text-pink-700 font-bold' : 'text-gray-900') }}">
                                {{ $producto->stock }} unidades
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">
                                S/ {{ number_format($producto->precio_venta, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($producto->estado == 'en_stock')
                                    <div class="flex items-center gap-1.5 text-teal-600">
                                        <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                                        <span class="text-xs font-semibold">En Stock</span>
                                    </div>
                                @elseif($producto->estado == 'bajo_stock')
                                    <div class="flex items-center gap-1.5 text-pink-700">
                                        <span class="w-2 h-2 rounded-full bg-pink-600"></span>
                                        <span class="text-xs font-bold">Bajo Stock</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 text-red-600">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        <span class="text-xs font-semibold">Agotado</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('productos.show', $producto) }}"
                                       class="p-2 text-gray-400 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all" title="Ver Historial">
                                        <span class="material-symbols-outlined text-[20px]">history</span>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto) }}"
                                       class="p-2 text-gray-400 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all" title="Editar">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este producto?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Eliminar">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-gray-400 font-semibold">
                                No hay productos aún.
                                <a href="{{ route('productos.create') }}" class="text-blue-700 hover:underline ml-1">Agregar uno</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="p-4 bg-white border-t border-gray-100 flex items-center justify-between">
                <span class="text-sm text-gray-400">
                    {{ $productos->appends(request()->query())->links() }}
                </span>
            </div>
        </div>

        {{-- Gráfico + Alertas --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Tendencia de Movimientos</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-700"></span>
                            <span class="text-xs text-gray-500">Entradas</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-pink-600"></span>
                            <span class="text-xs text-gray-500">Salidas</span>
                        </div>
                    </div>
                </div>
                <div class="h-48 w-full flex items-end gap-2 relative">
                    @php
                        $barras = [
                            ['h'=>'40%','color'=>'bg-blue-200 hover:bg-blue-700'],
                            ['h'=>'65%','color'=>'bg-pink-200 hover:bg-pink-600'],
                            ['h'=>'55%','color'=>'bg-blue-200 hover:bg-blue-700'],
                            ['h'=>'30%','color'=>'bg-pink-200 hover:bg-pink-600'],
                            ['h'=>'85%','color'=>'bg-blue-200 hover:bg-blue-700'],
                            ['h'=>'70%','color'=>'bg-pink-200 hover:bg-pink-600'],
                            ['h'=>'45%','color'=>'bg-blue-200 hover:bg-blue-700'],
                            ['h'=>'90%','color'=>'bg-pink-200 hover:bg-pink-600'],
                            ['h'=>'60%','color'=>'bg-blue-200 hover:bg-blue-700'],
                            ['h'=>'50%','color'=>'bg-pink-200 hover:bg-pink-600'],
                        ];
                    @endphp
                    @foreach($barras as $barra)
                    <div class="flex-1 {{ $barra['color'] }} transition-all rounded-t-sm"
                         style="height: {{ $barra['h'] }}"></div>
                    @endforeach
                    <div class="absolute bottom-0 left-0 w-full h-px bg-gray-200"></div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Alertas Recientes</h3>
                <div class="space-y-4">
                    @forelse($alertas as $alerta)
                    <div class="flex gap-3 p-3 bg-red-50 rounded-lg border border-red-100">
                        <span class="material-symbols-outlined text-red-600">warning</span>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Stock Crítico</p>
                            <p class="text-xs text-gray-500">{{ $alerta->nombre }} ({{ $alerta->stock }} u.)</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">Sin alertas activas</p>
                    @endforelse
                </div>
            </div>
        </div>

        </flux:main>
    </x-layouts.app.sidebar>
</x-layouts.app>