<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil User</title>
</head>
<body>
    
    <h1>Hola Mundo Cruel</h1>
    <h3>Esto es perfiluser.blade.php</h3>

    <h3>Hola toda tu información en formato raw es :{{$user}}</h3>

    <h2>Tu ID: {{$user->id}}</h2>
    <h2>Hola Señor/a: {{$user->name}}</h2>
    <h2>Tu correo es: {{$user->email}}</h2>
    <h2>Tu rol es: {{$user->role}}</h2>

</body>
</html>