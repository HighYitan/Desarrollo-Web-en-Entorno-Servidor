<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
</head>
<body>

    <h3>Edit Post</h3>

    <!-- Gestión de errores -->
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <form action="{{ route('postCRUD.update', ['postCRUD' => $post->id ]) }}" method="post">
        @csrf <!-- Security Token -->	
        @method('PUT') <!-- Cambio de method a 'PUT', en caso contrario llamaría al show -->
        
        <label for="title">Títol</label>
        <input type="text" style="@error('title') border-color:RED; @enderror" value="{{$post->title}}" name="title" />
        @error('title')
            <div>{{$message}}</div>
        @enderror
      
        <label for="url_clean">Url neta</label>
        <input type="text" value="{{$post->url_clean}}" name="url_clean" />
        @error('url_clean')
            <div>{{$message}}</div>
        @enderror
                
        <label for="content">Contingut</label>
        <textarea style="@error('content') border-color:RED; @enderror" name="content" col="3" >{{$post->content}}</textarea>
        @error('content')
            <div>{{$message}}</div>
        @enderror

        <input type="submit" value="Update">
    </form>

</body>
</html>