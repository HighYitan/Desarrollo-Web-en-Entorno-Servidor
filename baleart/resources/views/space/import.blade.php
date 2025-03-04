<x-app-layout>
    <!-- Header de listado de Spaces -->

    <!--
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Importar Espacios') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('spaceCRUD.importJson') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="json_text" class="block text-gray-700 text-sm font-bold mb-2">
                                Pegar JSON aquí
                            </label>
                            <textarea id="json_text" name="json_text" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Importar JSON</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    -->
</x-app-layout>