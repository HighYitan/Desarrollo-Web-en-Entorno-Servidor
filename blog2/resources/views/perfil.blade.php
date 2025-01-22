<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device- width, initial- scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Hola Mundo Cruel</h1>
    <h3>Esto es perfil.blade.php</h3>

    <?php
        echo "<h2> Tu nombre es = ".$nom."</h2>"; //Nos permite código php el que queramos
    ?>

    <!-- No obstante, hay una manera mejor de evitar incrustar php en el html --> 
    <h3>Hola {{$nom}}</h3>

</body>
</html>