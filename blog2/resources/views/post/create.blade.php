<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear una Publicación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    
                    <form action="{{ route('postCRUD.store') }}" method="post">
                        @csrf  <!-- Security Token -->

                        <div class="mb-3">
                            <label for="title">Título</label>
                            <input type="text" class="mt-1 block w-full" style="@error('title') border-color:RED; @enderror" name="title" />
                            @error('title')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="url_clean">Url Clean</label>
                            <input type="text" class="mt-1 block w-full" style="@error('url_clean') border-color:RED; @enderror" name="url_clean" />
                            @error('url_clean')
                                <div>{{$message}}</div>
                            @enderror                  
                        </div>

                        <div class="mb-3">
                            <label for="content">Contingut</label>
                            <textarea style="@error('content') border-color:RED; @enderror" name="content" col="3" class="mt-1 block w-full"></textarea>
                            @error('content')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="posted" class="form-label">Publicat</label>
                            <select name="posted" class="mt-1 block w-full">
                                <option value="yes">Si</option>
                                <option value="not">No</option>
                            </select>
                        </div>
                       
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>