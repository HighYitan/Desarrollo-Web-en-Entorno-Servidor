<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>
<body>
    <!-- Primero comprobamos si esta pantalla es llamada por consecuencia de un error-->
    @if (count($errors->all()) === 1)
        <h2>Tenim 1 error</h2>
    @elseif (count($errors->all()) > 1)
        <h2>Tenim multiples errors</h2>
    @else
        <h2>No tenim cap error</h2> 
    @endif
    
    <!--@if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif-->

    @php 
        $componentName = ''; 
        if ($errors->any()) {
            $componentName = 'alert'; 
        } else {
            $componentName = 'success'; 
        }
    @endphp 

    <x-dynamic-component :component="$componentName">
    </x-dynamic-component>

    <!-- Comprobamos si tenemos que mostrar un mensaje de status -->
    <!-- el if es necesario puesto que la primera vez no tendremos status -->
    @if (session('status'))
        <div class="alert alert-primary role='alert'">
            {!! session('status') !!}
        </div>
    @endif
    
     <!-- En caso contrario, mostramos el formulario, es llamada inicial -->
    <h3>Create Post</h3>
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

    <!-- para ver el contenido de $errors, toda la info en pantalla -->
    <!--@dd($errors)-->
    
</body>
</html>