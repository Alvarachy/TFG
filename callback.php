<?php
// Configuración de Spotify
$client_id = '2432165001c9413cab60a48eb68c0109';
$client_secret = '62796c3fa6d042e483f63b608fbc8f4e'; // Asegúrate de poner tu client secret aquí
$redirect_uri = 'http://localhost:3000/TFG/callback.php';

// Verificar si el código de autorización está presente
if (isset($_GET['code'])) {
    // Obtener el código de autorización
    $auth_code = $_GET['code'];

    // Construir la solicitud para obtener el token de acceso
    $url = "https://accounts.spotify.com/api/token";

    // Los datos a enviar en la solicitud POST
    $data = [
        'grant_type' => 'authorization_code',
        'code' => $auth_code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ];

    // Usar cURL para hacer la solicitud POST
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta JSON
    $response_data = json_decode($response, true);

    // Verificar si la respuesta contiene el token
    if (isset($response_data['access_token'])) {
        // Guardar el token de acceso para futuras solicitudes
        $access_token = $response_data['access_token'];
        
        // Aquí puedes usar el token de acceso para hacer solicitudes a la API de Spotify
        echo "¡Autenticación exitosa! El token de acceso es: " . $access_token;
    } else {
        // Si no hay token, muestra el error
        echo "Error al obtener el token de acceso: " . $response_data['error'];
    }
} else {
    // Si no hay código de autorización, mostrar un error
    echo "No se recibió el código de autorización.";
}
// API URL para obtener los datos del usuario
$api_url = 'https://api.spotify.com/v1/me';

// Configurar la solicitud con el token de acceso
$options = [
    'http' => [
        'header' => "Authorization: Bearer $access_token"
    ]
];
$context = stream_context_create($options);

// Realizar la solicitud GET a la API de Spotify
$response = file_get_contents($api_url, false, $context);

// Decodificar la respuesta JSON
$user_data = json_decode($response, true);

// Mostrar algunos datos del usuario (como nombre y correo)
if (isset($user_data['display_name'])) {
    echo "<br>" . "Nombre de usuario: " . $user_data['display_name'] . "<br>";
    echo "<br>" . "Correo electrónico: " . $user_data['email'] . "<br>";
} else {
    echo "No se pudo obtener la información del usuario.";
}


// API URL para obtener los artistas más escuchados
$api_url = 'https://api.spotify.com/v1/me/top/artists?limit=20';

// Realizar la solicitud GET con el token de acceso
$response = file_get_contents($api_url, false, $context);

// Decodificar la respuesta JSON
$top_artists = json_decode($response, true);

// Mostrar los artistas con a href directo a su perfil
echo "<ol>";
    echo "<br>" . "Los artistas más escuchados son:<br>";
    foreach ($top_artists['items'] as $artist) {
        // Mostrar el nombre del artista y la URL con clases CSS
        echo "<li class='artist'>";
        echo "<a href='" . $artist['external_urls']['spotify'] . "'>" . $artist['name'] . "</a><br>";
        echo "</li>";
    }
echo "</ol>";




//Mostrar las canciones mas escuchadas
// API URL para obtener los top tracks del usuario
$api_url = 'https://api.spotify.com/v1/me/top/tracks?limit=10'; // Puedes ajustar el límite a más si lo necesitas

// Configurar la solicitud con el token de acceso
$options = [
    'http' => [
        'header' => "Authorization: Bearer $access_token"
    ]
];
$context = stream_context_create($options);

// Realizar la solicitud GET a la API de Spotify
$response = file_get_contents($api_url, false, $context);

// Decodificar la respuesta JSON
$top_tracks = json_decode($response, true);

// Verificar si se obtuvieron los datos de las canciones
if (isset($top_tracks['items'])) {
    echo "Tus canciones más escuchadas:<br>";
    foreach ($top_tracks['items'] as $track) {
        // Mostrar el nombre de la canción, el artista y la URL con clases CSS
        echo "<div class='track'>";
        echo "<div class='cancion'>Canción: " . $track['name'] . "</div>";
        echo "<div class='artista'>Artista: " . $track['artists'][0]['name'] . "</div>";
        echo "<div class='url'>URL de la canción: <a href='" . $track['external_urls']['spotify'] . "'>Enlace a Spotify</a></div>";
        echo "</div><br>";
    }
    
} else {
    echo "No se pudo obtener la información de tus canciones más escuchadas.";
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        body{
            background-color: grey ;
            font-size: 30px;
        }
        .track {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;

        }
        .cancion {
            font-weight: bold;
            margin-bottom: 5px;
            color: lightgreen;

        }
        .artista {
            font-style: italic;
            margin-bottom: 5px;
            color: red;

        }
        .url {
            color: blue;

        }
        ol{
            font-size: 20px;
            color: cyan;
        }
        a {
            text-decoration: none;
            color: cyan;
        }
    </style>

</head>
<body>
    <h2>Informacion ordenada</h2>
    <ul>
        <?php
            // Ordenar la informacion de los artistas y canciones por nombre
            $top_artists = array_combine(array_column($top_artists['items'], 'name'), $top_artists['items']);
            ksort($top_artists);
            $top_tracks = array_combine(array_column($top_tracks['items'], 'name'), $top_tracks['items']);
            ksort($top_tracks);

            echo "<br> <hr> <br> ";
            // Mostrar la informacion ordenada
            foreach ($top_artists as $artist) {
                echo "<li>Artista: " . $artist['name'] . "</li>";
            }
            foreach ($top_tracks as $track) {
                echo "<li>Cancion: " . $track['name'] . "</li>";
            }
        ?>
    </ul>
</body>
</html>