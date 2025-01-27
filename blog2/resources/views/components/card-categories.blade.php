<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface " >
        <h5 class="mb-2 text-xl font-medium leading-tight">{{ $category->title }}</h5>
        <h3 class="mb-2 text-xl font-medium leading-tight">{{ $category->url_clean }}</h3>
        <p class="mb-4 text-sm">created at: {{ $category->created_at }}</p>
        <p class="mb-4 text-sm">updated at: {{ $category->updated_at }}</p>
        <a href="{{route('categoryCRUD.show' , ['categoryCRUD' => $category->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('categoryCRUD.edit' , ['categoryCRUD' => $category->id])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('categoryCRUD.destroy' , ['categoryCRUD' => $category->id ])}}" method="POST" class="float-right">
           @method('DELETE')
           @csrf
           <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" >Delete</button>
        </form>
    </div>
</div>