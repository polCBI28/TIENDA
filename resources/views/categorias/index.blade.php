<x-layouts.app>
    <x-layouts.app.sidebar>
        <flux:main>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <nav class="flex items-center gap-2 mb-2 text-sm text-gray-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-700 transition-colors">ShopMaster</a>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-gray-900">Categorías</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900">Catálogo de Categorías</h2>
                <p class="text-gray-500 mt-1">Organiza y gestiona tu inventario a través de las divisiones principales del negocio.</p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                <a href="{{ route('categorias.create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-700 text-white rounded-xl font-bold shadow-lg hover:scale-[1.02] active:scale-95 transition-all">
                    <span class="material-symbols-outlined">add</span>
                    Agregar Categoría
                </a>
                <a href="{{ route('subcategorias.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-pink-700 text-white rounded-xl font-bold shadow-lg hover:scale-[1.02] active:scale-95 transition-all">
                    <span class="material-symbols-outlined">settings_suggest</span>
                    Gestionar Sub-tipos
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl font-semibold">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Grid Bento de Categorías --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6">
            @php
                $spans   = [7, 5, 5, 7];
                $heights = ['h-72', 'h-72', 'h-60', 'h-60'];
                $overlays = [
                    'bg-gradient-to-t from-black/80 via-black/20 to-transparent',
                    'bg-gradient-to-t from-blue-900/90 via-blue-700/30 to-transparent',
                    'bg-gradient-to-t from-teal-900/90 via-teal-700/30 to-transparent',
                    'bg-gradient-to-t from-pink-900/90 via-pink-700/20 to-transparent',
                ];
                $icons = ['local_cafe','smart_toy','account_balance_wallet','redeem'];
                $iconBgs = ['bg-pink-700/80','bg-blue-700/80','bg-teal-700/80','bg-pink-600/90'];
            @endphp

            @forelse($categorias as $index => $categoria)
            @php
                $i       = $index % 4;
                $span    = $spans[$i];
                $height  = $heights[$i];
                $overlay = $overlays[$i];
                $icon    = $icons[$i];
                $iconBg  = $iconBgs[$i];
            @endphp
            <div class="lg:col-span-{{ $span }} group category-card relative overflow-hidden rounded-3xl bg-white border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-xl cursor-pointer"
                 style="transform: scale(1);">
                <div class="absolute inset-0 z-0">
                    @if($categoria->imagen)
                        <img class="category-image w-full h-full object-cover transition-transform duration-700"
                             src="{{ asset('storage/' . $categoria->imagen) }}"
                             alt="{{ $categoria->nombre }}">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-300 to-pink-300"></div>
                    @endif
                    <div class="absolute inset-0 {{ $overlay }}"></div>
                </div>
                <div class="relative z-10 {{ $height }} flex flex-col justify-end p-8 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="p-3 {{ $iconBg }} rounded-2xl" style="background:rgba(255,255,255,0.2);backdrop-filter:blur(10px)">
                                <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                            </div>
                            <h3 class="text-2xl font-bold">{{ $categoria->nombre }}</h3>
                        </div>
                        <span class="bg-white/20 backdrop-blur-md px-4 py-1 rounded-full text-sm font-semibold border border-white/30">
                            {{ $categoria->productos_count }} artículos
                        </span>
                    </div>
                    @if($categoria->descripcion)
                        <p class="text-white/80 text-sm max-w-md">{{ $categoria->descripcion }}</p>
                    @endif
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('categorias.edit', $categoria) }}"
                           class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-white text-xs font-semibold hover:bg-white/30 transition border border-white/20">
                            <span class="material-symbols-outlined text-sm align-middle">edit</span> Editar
                        </a>
                        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta categoría?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-red-500/50 backdrop-blur-md rounded-lg text-white text-xs font-semibold hover:bg-red-500/70 transition border border-white/20">
                                <span class="material-symbols-outlined text-sm align-middle">delete</span> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="lg:col-span-12 py-16 text-center text-gray-400 font-semibold text-lg bg-white rounded-3xl border border-gray-200">
                No hay categorías aún.
                <a href="{{ route('categorias.create') }}" class="text-blue-700 hover:underline ml-1">Agregar una</a>
            </div>
            @endforelse
        </div>

        {{-- Estadísticas --}}
        <section class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-6 bg-white border border-gray-200 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">inventory</span>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase">Total Productos</p>
                    <p class="text-2xl font-bold leading-none">{{ $categorias->sum('productos_count') }}</p>
                </div>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 bg-pink-100 text-pink-700 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">auto_awesome</span>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase">Nuevos Ingresos</p>
                    <p class="text-2xl font-bold leading-none">--</p>
                </div>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 bg-teal-100 text-teal-700 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">low_priority</span>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase">Stock Crítico</p>
                    <p class="text-2xl font-bold leading-none text-red-600">{{ $stockCritico ?? '--' }}</p>
                </div>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">layers</span>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase">Categorías Activas</p>
                    <p class="text-2xl font-bold leading-none">{{ $categorias->where('activo', true)->count() }}</p>
                </div>
            </div>
        </section>

        </flux:main>
    </x-layouts.app.sidebar>
</x-layouts.app>

<style>
    .category-card:hover .category-image { transform: scale(1.05); }
</style>

<script>
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mousedown', () => card.style.transform = 'scale(0.98)');
        card.addEventListener('mouseup',   () => card.style.transform = 'scale(1)');
        card.addEventListener('mouseleave',() => card.style.transform = 'scale(1)');
        card.addEventListener('mousemove', (e) => {
            const img = card.querySelector('.category-image');
            if (!img) return;
            const rect = card.getBoundingClientRect();
            img.style.transformOrigin = `${(e.clientX - rect.left) / rect.width * 100}% ${(e.clientY - rect.top) / rect.height * 100}%`;
        });
    });
</script>