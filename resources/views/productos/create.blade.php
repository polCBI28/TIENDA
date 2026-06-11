<x-layouts.app>
    <x-layouts.app.sidebar>
        <flux:main>

        <div class="mb-6">
            <nav class="flex items-center gap-2 mb-2 text-sm text-gray-400">
                <a href="{{ route('productos.index') }}" class="hover:text-blue-700">Inventario</a>
                <span>›</span>
                <span class="text-gray-700">Nuevo Producto</span>
            </nav>
            <h2 class="text-2xl font-bold text-gray-900">Nuevo Producto</h2>
        </div>

        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Columna principal --}}
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-4">Información General</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Nombre del Producto *</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 block mb-1">SKU *</label>
                                <input type="text" name="sku" value="{{ old('sku') }}" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Categoría *</label>
                                <select name="categoria_id" required
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                                    <option value="">Seleccionar...</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Subcategoría</label>
                                <select name="subcategoria_id"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                                    <option value="">Ninguna</option>
                                    @foreach($subcategorias as $sub)
                                        <option value="{{ $sub->id }}" {{ old('subcategoria_id') == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Descripción</label>
                                <textarea name="descripcion" rows="3"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none resize-none">{{ old('descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-4">Precios y Stock</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Precio de Venta (S/) *</label>
                                <input type="number" name="precio_venta" value="{{ old('precio_venta') }}" step="0.01" min="0" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Precio de Costo (S/)</label>
                                <input type="number" name="precio_costo" value="{{ old('precio_costo') }}" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Stock Inicial *</label>
                                <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 block mb-1">Stock Mínimo *</label>
                                <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 5) }}" min="0" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Columna lateral --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-4">Imagen</h3>
                        <input type="file" name="imagen" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100">
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-4">Estado</h3>
                        <select name="estado"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                            <option value="en_stock">En Stock</option>
                            <option value="bajo_stock">Bajo Stock</option>
                            <option value="agotado">Agotado</option>
                        </select>
                        <div class="mt-3 flex items-center gap-2">
                            <input type="checkbox" name="activo" id="activo" value="1" checked class="rounded">
                            <label for="activo" class="text-sm text-gray-600">Producto activo</label>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit"
                                class="w-full py-3 bg-blue-700 text-white rounded-xl font-bold hover:bg-blue-800 transition shadow-sm">
                            Guardar Producto
                        </button>
                        <a href="{{ route('productos.index') }}"
                           class="w-full py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold text-center hover:bg-gray-200 transition text-sm">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>

            @if($errors->any())
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

        </form>

        </flux:main>
    </x-layouts.app.sidebar>
</x-layouts.app>