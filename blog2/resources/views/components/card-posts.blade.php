<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        <h5 class="mb-2 text-xl font-medium leading-tight">{{ $post->title }}</h5>
        <h3 class="mb-2 text-xl font-medium leading-tight">{{ $post->url_clean }}</h3>
        <p class="mb-4 text-base">{{ $post->content }}</p>
        <p class="mb-4 text-sm">posted: {{ $post->posted }}</p>
        <p class="mb-4 text-sm">created at: {{ $post->created_at }}</p>
        <p class="mb-4 text-sm">updated at: {{ $post->updated_at }}</p>
        <a href="{{route('postCRUD.show' , ['postCRUD' => $post->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('postCRUD.edit' , ['postCRUD' => $post->id ])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('postCRUD.destroy' , ['postCRUD' => $post->id ])}}" method="POST" class="float-right">
           @method('DELETE')
           @csrf
           <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" >Delete</button>
           <!--<button type="submit" class="bg-gradient-to-r from-red-100 to-red-500 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded" >Delete</button>-->
        </form>
    </div>
</div>