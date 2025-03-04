<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        <h3 class="mb-2 text-4xl font-medium leading-tight">{{ $space->name }}</h3>
        <h5 class="mb-2 text-xl font-medium leading-tight">Número de Registro: {{ $space->regNumber }}</h5>
        @if ($space->spaceType)
            <h5 class="mb-2 text-xl font-medium leading-tight">Tipo de Espacio: {{ $space->spaceType->name }}</h5>
        @endif
        @if ($space->modalities)
            <h5 class="mb-2 text-xl font-medium leading-tight">Modalidades: {{ $space->modalities->pluck('name')->implode(', ') }}</h5>
        @endif
        @if ($space->services)
            <h5 class="mb-2 text-xl font-medium leading-tight">Servicios: {{ $space->services->pluck('name')->implode(', ') }}</h5>
        @endif
        <p class="mb-4 text-base">Descripción en Catalán: {!! $space->observation_CA !!}</p>
        <p class="mb-4 text-base">Descripción en Español: {!! $space->observation_ES !!}</p>
        <p class="mb-4 text-base">Descripción en Inglés: {!! $space->observation_EN !!}</p>
        @if ($space->address)
            <h5 class="mb-2 text-xl font-medium leading-tight">Dirección: {{ $space->address->name . ", " . $space->address->municipality->name . ", " . $space->address->zone->name . ", " . $space->address->municipality->island->name }}</h5>
        @endif
        <h5 class="mb-2 text-xl font-medium leading-tight">Tipo de Acceso: {{ $space->accessType }}</h5>
        <h5 class="mb-2 text-xl font-medium leading-tight">Correo Electrónico: {{ $space->email }}</h5>
        <h5 class="mb-2 text-xl font-medium leading-tight">Número de Teléfono: {{ $space->phone }}</h5>
        <h5 class="mb-2 text-xl font-medium leading-tight">Página Web: {{ $space->website }}</h5>
        @if (isset($score))
            <h5 class="mb-2 text-xl font-medium leading-tight">Puntuación Media: {{ number_format($score, 2) }}</h5>
        @else
            <h5 class="mb-2 text-xl font-medium leading-tight">Puntuación Media: {{ number_format($space->puntuacióMitjana, 2) }}</h5>
        @endif
        <p class="mb-4 text-sm">Creado por: {{ $space->user->name }}</p>
        <p class="mb-4 text-sm">Creado el: {{ $space->created_at }}</p>
        <p class="mb-4 text-sm">Modificado el: {{ $space->updated_at }}</p>
        <a href="{{route('spaceCRUD.show' , ['spaceCRUD' => $space->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('spaceCRUD.edit' , ['spaceCRUD' => $space->id])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('spaceCRUD.destroy' , ['spaceCRUD' => $space->id])}}" method="POST" class="float-right">
           @method('DELETE')
           @csrf
           <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
        </form>
    </div>
    @if (isset($comments))
        @if ($comments->count() > 0)
            <div class="p-6 text-surface">
                @foreach ($comments as $comment)
                    <div class="mt-2">
                        <a href="{{route('userCRUD.show' , ['userCRUD' => $comment->user->id])}}"><h3 class="mb-2 text-4xl font-medium leading-tight">Comentario de {{ $comment->user->name . " " . $comment->user->lastName }}</h3></a>
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
                <h3 class="mb-2 text-4xl font-medium leading-tight">No hay comentarios</h3>
            </div>
        @endif
    @endif
</div>