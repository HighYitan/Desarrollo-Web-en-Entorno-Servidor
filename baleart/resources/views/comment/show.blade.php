<x-app-layout>
    <!-- Header del Comentario -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mostrar Comentario') }}
        </h2>
    </x-slot>
    <!-- Comentario -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-card-comments :comment="$comment" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>