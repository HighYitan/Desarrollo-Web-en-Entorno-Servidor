<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Posts</title>
    <!-- Mostramos estructura en formato Json vía consola para debug -->
    <script>
        var app = @json($posts);
        console.log(app); 
    </script>
</head>
<body>

    <h3>Index Post</h3>

    @for ($i = 0; $i < 10; $i++)
          <li>The current value is {{ $i }}</li>
    @endfor

    @if (session('status'))
        <div class="alert alert-primary role='alert'">
            {{ session('status') }}
        </div>
    @endif
    <!--<div>
        <span>{{time()}}</span>
        <span>{{json_encode($posts)}}</span> 
    </div>-->

    <table border='1'>
        @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $loop->index }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->posted }}</td>
                <td>{{ $post->content }}</td>
                <td>{{ $post->created_at }}</td>
                <td>{{ $post->updated_at }}</td>
                <td>
                    <form action="{{route('postCRUD.destroy', ['postCRUD' => $post->id ])}}" method="POST">
                      @method('DELETE')
                      @csrf
                      <button type="submit" class="btn tbn-danger btn-sm">Delete</button>
                    </form> 
                </td> 
                <td>
                    <form action="{{route('postCRUD.show', ['postCRUD' => $post->id ])}}" method="GET">
                      @method('GET')
                      @csrf
                      <button type="submit" class="btn tbn-danger btn-sm">Show</button>
                    </form> 
                </td> 
            </tr>
        @endforeach
    </table>

</body>
</html>