<?php
$client_id = '2432165001c9413cab60a48eb68c0109';
$redirect_uri = 'http://localhost:3000/TFG/callback.php';
$scope = 'user-read-private user-read-email user-top-read'; // Añadí el permiso para obtener información sobre tus gustos musicales

// Generamos la URL de autenticación
$auth_url = "https://accounts.spotify.com/authorize?response_type=code&client_id=$client_id&redirect_uri=" . urlencode($redirect_uri) . "&scope=" . urlencode($scope);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        body {
            background-color: black;
            font-family: Arial, sans-serif;
        }
        div{
            background-color: lightgreen;
            background-image: url('https://developer.spotify.com/images/avatars/spotify.png');
            background-repeat: no-repeat;
            background-position: center;
            width: 600px;
            height: 600px;
            margin: 0 auto;
            margin-top: 100px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        button{
            background-color:rgb(139, 211, 81);
            text-align: center;
            padding: 20px;
            margin: 8px auto;
            margin-top: 100px;
            border-radius: 10px;
            color: white;
            font-size: 20px;
            text-decoration: none;
        }
        button:hover{
            background-color:rgb(15, 148, 62);
            cursor: pointer;
        }
        h1{
            text-align: center;
            font-size: 50px;
            color: white;
            padding: 10px;
        }
    </style>
    </head>
<body>
    <div>
        <h1>Token de Acceso</h1>
        <button > 
        <!-- Enlace para que el usuario obtenga el código de autorización -->
            <?php echo "<a href='$auth_url'>Obtener Código de Autorización</a>";
            ?>
        </button>
    
</body>
</html>
