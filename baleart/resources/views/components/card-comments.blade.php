<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
    <h5 class="mb-2 text-xl font-medium leading-tight">Comentario: {!! $comment->comment !!}</h5>
        <a href="{{route('spaceCRUD.show' , ['spaceCRUD' => $comment->space->id])}}"><h3 class="mb-2 text-xl font-medium leading-tight">Espacio: {{ $comment->space->name }}</h3></a>
        <h5 class="mb-2 text-xl font-medium leading-tight">Puntuación: {{ $comment->score }}</h5>
        <h5 class="mb-2 text-xl font-medium leading-tight">Estado de Publicación: {{ $comment->status == 'Y' ? 'Publicado' : 'No Publicado' }}</h5>
        <p class="mb-4 text-sm">Creado por: {{ $comment->user->name . " " . $comment->user->lastName }}</p>
        <p class="mb-4 text-sm">Creado el: {{ $comment->created_at }}</p>
        <p class="mb-4 text-sm">Modificado el: {{ $comment->updated_at }}</p>
        <a href="{{route('commentCRUD.show' , ['commentCRUD' => $comment->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('commentCRUD.edit' , ['commentCRUD' => $comment->id])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('commentCRUD.destroy' , ['commentCRUD' => $comment->id])}}" method="POST" class="float-right">
            @method('DELETE')
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
        </form>
    </div>
</div>