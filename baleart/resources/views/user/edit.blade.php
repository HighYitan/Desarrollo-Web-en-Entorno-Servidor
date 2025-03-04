<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Usuario: ') }}  {{ $user->name . " " . $user->lastName }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <form action="{{ route('userCRUD.update', ['userCRUD' => $user->id ]) }}" method="post">
                        @csrf  <!-- Security Token -->
                        @method('PUT') 
                        <div class="mb-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="mt-1 block w-full" style="@error('name') border-color:RED; @enderror" value="{{ $user->name }}" name="name" />
                            @error('name')
                                <div>{{$message}}</div>
                            @enderror   
                        </div>
                        <div class="mb-3">
                            <label for="lastName">Apellidos</label>
                            <input type="text" class="mt-1 block w-full" style="@error('lastName') border-color:RED; @enderror" value="{{$user->lastName}}" name="lastName" />
                            @error('lastName')
                                <div>{{$message}}</div>
                            @enderror                
                        </div>
                        <div class="mb-3">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="mt-1 block w-full" style="@error('email') border-color:RED; @enderror" value="{{ $user->email }}" name="email" />
                            @error('email')
                                <div>{{$message}}</div>
                            @enderror                
                        </div>
                        <div class="mb-3">
                            <label for="phone">Número de Teléfono</label>
                            <input type="text" class="mt-1 block w-full" style="@error('phone') border-color:RED; @enderror" value="{{ $user->phone }}" name="phone" />
                            @error('phone')
                                <div>{{$message}}</div>
                            @enderror                
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>