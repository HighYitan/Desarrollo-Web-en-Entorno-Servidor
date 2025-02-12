<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Publicación :') }}  {{ $post->title  }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">

                    <form action="{{ route('postCRUD.update', ['postCRUD' => $post->id ]) }}" method="post">

                        @csrf
                        @method('PUT') 

                        <div class="mb-3">
                            <label for="title">Títol</label>
                            <input type="text" class="mt-1 block w-full" style="@error('title') border-color:RED; @enderror" value="{{ $post->title }}" name="title" />
                            @error('title')
                                <div>{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="url_clean">Url neta</label>
                            <input type="text" class="mt-1 block w-full" value="{{$post->url_clean}}" name="url_clean" />
                            @error('url_clean')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content">Content</label>
                            <textarea name="content" minlength="5" maxlength="500" style="@error('content') border-color:RED; @enderror" rows="5" class="mt-1 block w-full">{{$post->content}}</textarea>
                            @error('content')
                                <div>{{$message}}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="category_id">Categories</label>
                            <select name="category_id" class="mt-1 block w-full">
                                @foreach ($categories as $title => $id)
                                    @if ($post->category_id == $id)
                                        <option selected value="{{$id}}">{{$title}}</option>                                       
                                    @else
                                        <option value="{{$id}}">{{$title}}</option>  
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="posted" class="form-label">Publicat</label>
                            <select name="posted" class="mt-1 block w-full">
                                @if ($post->posted == 'yes')
                                    <option selected value="yes">Si</option>
                                    <option value="not">No</option>
                                @else
                                    <option value="yes">Si</option>
                                    <option selected value="not">No</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>