<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>
<body>
    <h3>Create Post</h3>
    <?php
    @if (count($errors->all()) === 1)
        'Mensaje de error'
    @elseif (count($errors->all()) > 1)
        'Mensaje de error'
    @else
        'Mensaje NO error'
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif
    ?>
    
    <form action="{{ route('postCRUD.store') }}" method="post">
        @csrf <!-- Security Token -->	
        
        <label for="title">Títol</label>
        <!-- <input type="text" name="title" /> -->
        <input type="text" style="@error('title') border-color:RED; @enderror" name="title" />
        @error('title')
            <div>{{$message}}</div>
        @enderror     

        <label for="url_clean">Url neta</label>
        <input type="text" style="@error('url_clean') border-color:RED; @enderror" name="url_clean" />
        @error('url_clean')
            <div>{{$message}}</div>
        @enderror
        
        <label for="content">Contingut</label>
        <textarea style="@error('content') border-color:RED; @enderror" name="content" col="3" ></textarea>
        @error('content')
            <div>{{$message}}</div>
        @enderror

        <input type="submit" value="Crear" >
    </form>
</body>
</html>