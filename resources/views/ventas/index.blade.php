<x-layouts.app>
    <x-layouts.app.sidebar>
        <flux:main>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Ventas</h2>
            <a href="{{ route('ventas.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg font-semibold text-sm hover:bg-blue-800 transition">
                + Nueva Venta
            </a>
        </flux:main>
    </x-layouts.app.sidebar>
</x-layouts.app>