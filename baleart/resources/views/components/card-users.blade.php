<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        <h3 class="mb-2 text-4xl font-medium leading-tight">{{ $user->name . " " . $user->lastName }}</h3>
        <h5 class="mb-2 text-xl font-medium leading-tight">Correo Electrónico: {{ $user->email }}</h5>
        <h5 class="mb-2 text-xl font-medium leading-tight">Número de Teléfono: {{ $user->phone }}</h5>
        <h5 class="mb-2 text-xl font-medium leading-tight">Rol del Usuario: {{ $user->role->name }}</h5>
        <p class="mb-4 text-sm">Creado el: {{ $user->created_at }}</p>
        <p class="mb-4 text-sm">Modificado el: {{ $user->updated_at }}</p>
        <a href="{{route('userCRUD.show' , ['userCRUD' => $user->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('userCRUD.edit' , ['userCRUD' => $user->id])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        @if ($user->role_id == 3)
            <form action="{{route('userCRUD.destroy' , ['userCRUD' => $user->id])}}" method="POST" class="float-right">
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
            </form>
        @endif
    </div>
    @if (isset($comments))
        @if ($comments->count() > 0)
            <div class="p-6 text-surface">
                <h3 class="mb-2 text-4xl font-medium leading-tight">Los comentarios de {{ $user->name . " " . $user->lastName }}</h3>
                @foreach ($comments as $comment)
                    <div class="mt-2">
                        <h5 class="mb-2 text-xl font-medium leading-tight">Comentario: {!! $comment->comment !!}</h5>
                        <a href="{{route('spaceCRUD.show' , ['spaceCRUD' => $comment->space->id])}}"><h3 class="mb-2 text-xl font-medium leading-tight">Espacio: {{ $comment->space->name }}</h3></a>
                        <h5 class="mb-2 text-xl font-medium leading-tight">Puntuación: {{ $comment->score }}</h5>
                        <h5 class="mb-2 text-xl font-medium leading-tight">Estado de Publicación: {{ $comment->status == 'Y' ? 'Publicado' : 'No Publicado' }}</h5>
                        <p class="mb-4 text-sm">Creado el: {{ $comment->created_at }}</p>
                        <p class="mb-4 text-sm">Modificado el: {{ $comment->updated_at }}</p>
                        <a href="{{route('commentCRUD.show' , ['commentCRUD' => $comment->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
                        <a href="{{route('commentCRUD.edit' , ['commentCRUD' => $comment->id])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit/Delete</a>
                    </div>
                @endforeach
            </div>
            {{ $comments->links() }} <!-- Paginación -->
        @else
            <div class="p-6 text-surface">
                <h3 class="mb-2 text-4xl font-medium leading-tight">No hay comentarios de {{ $user->name . " " . $user->lastName }}</h3>
            </div>
        @endif
    @endif
</div>