<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Posts</title>
</head>
<body>

    <h3>Index Post</h3>

    <table border='1'>
        @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
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