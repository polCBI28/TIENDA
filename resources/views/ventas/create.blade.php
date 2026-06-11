<x-layouts.app>
    <x-layouts.app.sidebar>
        <flux:main>

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Registro de Venta Manual</h2>
                <p class="text-sm text-gray-400">Sesión Iniciada: {{ now()->format('h:i A') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Panel izquierdo --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Datos del Comprobante --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-base font-bold text-blue-700 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                        Datos del Comprobante
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 block mb-1">N° Recibo / Boleta</label>
                            <input type="text" id="numero_boleta" readonly
                                   value="B001-{{ str_pad(\App\Models\Venta::count() + 1, 6, '0', STR_PAD_LEFT) }}"
                                   class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-500">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 block mb-1">Fecha de Venta</label>
                            <input type="date" id="fecha_venta"
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 block mb-1">Cliente (Opcional)</label>
                            <select id="cliente_id" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-700 outline-none">
                                <option value="">Ej: Juan Pérez</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Búsqueda de Productos --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-base font-bold text-blue-700 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        Búsqueda Rápida de Productos
                        <span class="ml-auto text-xs text-gray-400 font-normal">Teclas: [F2] Buscar | [Enter] Agregar</span>
                    </h3>
                    <input type="text" id="buscar-producto" placeholder="Buscar por nombre, código o categoría..."
                           class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl text-sm focus:border-blue-700 outline-none mb-4">

                    {{-- Tabs por categoría --}}
                    <div class="flex gap-2 mb-4 border-b border-gray-100 pb-2" id="tabs-categorias">
                        <button onclick="filtrarCategoria('')"
                                class="tab-btn px-4 py-1.5 text-sm font-semibold text-blue-700 border-b-2 border-blue-700">
                            Frecuentes
                        </button>
                        @foreach($categorias as $cat)
                        <button onclick="filtrarCategoria('{{ $cat->id }}')"
                                class="tab-btn px-4 py-1.5 text-sm font-semibold text-gray-400 hover:text-blue-700 border-b-2 border-transparent hover:border-blue-700 transition">
                            {{ $cat->nombre }}
                        </button>
                        @endforeach
                    </div>

                    {{-- Grid de productos --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="grid-productos">
                        @foreach($productos as $producto)
                        <div class="producto-card border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-blue-400 hover:shadow-md transition-all"
                             data-id="{{ $producto->id }}"
                             data-nombre="{{ $producto->nombre }}"
                             data-precio="{{ $producto->precio_venta }}"
                             data-stock="{{ $producto->stock }}"
                             data-categoria="{{ $producto->categoria_id }}"
                             onclick="agregarAlCarrito(this)">
                            <p class="text-xs font-semibold text-gray-700 truncate">{{ Str::limit($producto->nombre, 15) }}</p>
                            <p class="text-xs text-gray-400 mt-1">Stock: {{ $producto->stock }} und.</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-bold text-blue-700">S/ {{ $producto->precio_venta }}</span>
                                <span class="material-symbols-outlined text-[18px] text-blue-400">add_circle</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Panel derecho: Carrito --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sticky top-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900">Carrito de Ventas</h3>
                        <button onclick="limpiarCarrito()" class="text-gray-300 hover:text-red-500 transition">
                            <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mb-4" id="items-count">0 items seleccionados</p>

                    {{-- Items del carrito --}}
                    <div id="carrito-items" class="space-y-3 max-h-64 overflow-y-auto mb-4">
                        <p class="text-sm text-gray-300 text-center py-6" id="carrito-vacio">
                            Agrega productos al carrito
                        </p>
                    </div>

                    {{-- Totales --}}
                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span id="subtotal">S/ 0.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Impuestos (18%)</span>
                            <span id="impuesto">S/ 0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-100">
                            <span>Total</span>
                            <span id="total" class="text-blue-700">S/ 0.00</span>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="mt-6 space-y-3">
                        <button onclick="guardarVenta('borrador')"
                                class="w-full py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Guardar Borrador
                        </button>
                        <button onclick="guardarVenta('completado')"
                                class="w-full py-3 bg-blue-700 text-white rounded-lg text-sm font-bold hover:bg-blue-800 transition shadow-sm">
                            ✓ REGISTRAR VENTA (F10)
                        </button>
                    </div>

                    {{-- Botones abajo --}}
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('ventas.index') }}"
                           class="flex-1 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold text-center hover:bg-gray-200 transition">
                            ESC Cancelar
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Form oculto para submit --}}
        <form id="form-venta" action="{{ route('ventas.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="fecha_venta" id="input-fecha">
            <input type="hidden" name="cliente_id" id="input-cliente">
            <input type="hidden" name="estado" id="input-estado">
            <div id="input-productos"></div>
        </form>

        </flux:main>
    </x-layouts.app.sidebar>
</x-layouts.app>

<script>
let carrito = [];

// Obtener categorías del servidor para tabs
const categorias = @json($categorias->pluck('nombre', 'id'));

function agregarAlCarrito(el) {
    const id     = el.dataset.id;
    const nombre = el.dataset.nombre;
    const precio = parseFloat(el.dataset.precio);
    const stock  = parseInt(el.dataset.stock);

    const existente = carrito.find(i => i.id == id);
    if (existente) {
        if (existente.cantidad < stock) existente.cantidad++;
    } else {
        carrito.push({ id, nombre, precio, stock, cantidad: 1 });
    }
    renderCarrito();
}

function cambiarCantidad(id, delta) {
    const item = carrito.find(i => i.id == id);
    if (!item) return;
    item.cantidad += delta;
    if (item.cantidad <= 0) carrito = carrito.filter(i => i.id != id);
    renderCarrito();
}

function limpiarCarrito() {
    carrito = [];
    renderCarrito();
}

function renderCarrito() {
    const container = document.getElementById('carrito-items');
    const vacio     = document.getElementById('carrito-vacio');
    const count     = document.getElementById('items-count');

    if (carrito.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-300 text-center py-6">Agrega productos al carrito</p>';
        count.textContent = '0 items seleccionados';
        actualizarTotales();
        return;
    }

    count.textContent = carrito.length + ' items seleccionados';
    container.innerHTML = carrito.map(item => `
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-gray-700 truncate">${item.nombre}</p>
                <p class="text-xs text-gray-400">P. Unit: S/ ${item.precio.toFixed(2)}</p>
            </div>
            <div class="flex items-center gap-2 ml-2">
                <button onclick="cambiarCantidad('${item.id}', -1)"
                        class="w-6 h-6 rounded bg-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-300 transition">-</button>
                <span class="text-sm font-bold w-6 text-center">${item.cantidad}</span>
                <button onclick="cambiarCantidad('${item.id}', 1)"
                        class="w-6 h-6 rounded bg-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-300 transition">+</button>
            </div>
            <span class="text-sm font-bold text-gray-900 ml-2">S/ ${(item.precio * item.cantidad).toFixed(2)}</span>
        </div>
    `).join('');

    actualizarTotales();
}

function actualizarTotales() {
    const subtotal = carrito.reduce((sum, i) => sum + i.precio * i.cantidad, 0);
    const impuesto = subtotal * 0.18;
    const total    = subtotal + impuesto;

    document.getElementById('subtotal').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('impuesto').textContent = 'S/ ' + impuesto.toFixed(2);
    document.getElementById('total').textContent    = 'S/ ' + total.toFixed(2);
}

function guardarVenta(estado) {
    if (carrito.length === 0) {
        alert('Agrega al menos un producto al carrito.');
        return;
    }

    document.getElementById('input-fecha').value   = document.getElementById('fecha_venta').value;
    document.getElementById('input-cliente').value = document.getElementById('cliente_id').value;
    document.getElementById('input-estado').value  = estado;

    const container = document.getElementById('input-productos');
    container.innerHTML = '';
    carrito.forEach((item, i) => {
        container.innerHTML += `
            <input type="hidden" name="productos[${i}][producto_id]"     value="${item.id}">
            <input type="hidden" name="productos[${i}][cantidad]"         value="${item.cantidad}">
            <input type="hidden" name="productos[${i}][precio_unitario]"  value="${item.precio}">
        `;
    });

    document.getElementById('form-venta').submit();
}

// Búsqueda en tiempo real
document.getElementById('buscar-producto').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.producto-card').forEach(card => {
        card.style.display = card.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
    });
});

// Filtro por categoría
function filtrarCategoria(catId) {
    document.querySelectorAll('.producto-card').forEach(card => {
        card.style.display = (!catId || card.dataset.categoria == catId) ? '' : 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-blue-700', 'border-blue-700');
        btn.classList.add('text-gray-400', 'border-transparent');
    });
    event.target.classList.add('text-blue-700', 'border-blue-700');
    event.target.classList.remove('text-gray-400', 'border-transparent');
}

// F10 para registrar venta
document.addEventListener('keydown', (e) => {
    if (e.key === 'F10') { e.preventDefault(); guardarVenta('completado'); }
});
</script>