<?php
//Cargar el archivo TourCMS.php para importar las funciones, clases, etc. ya definidas en dicho archivo
require_once "TourCMS.php";

//Insertar credenciales a continuación (las he borrado intencionadamente antes de cada commit y cada push por motivos de seguridad) para conectarme a la API
$marketplace_id = ;
$api_key = ;
$channel_id = ;

// Crear la instancia de TourCMS usando un namespace
use TourCMS\Utils\TourCMS as TourCMS;
$tourcms = new TourCMS($marketplace_id, $api_key, "simplexml");

// Meter en un array todos los países que tienen tours en la API y sus respectivos códigos
// asignando a cada código el nombre de su país para que la API reconozca el nombre del país
// cuando el usuario lo busque en la barra de búsqueda, en vez de obligar al usuario a usar el código
$country_codes = [
    'Alemania' => 'DE',
    'Arabia Saudita' => 'SA',
    'Argentina' => 'AR',
    'Australia' => 'AU',
    'Austria' => 'AT',
    'Bélgica' => 'BE',
    'Brasil' => 'BR',
    'Bulgaria' => 'BG',
    'Canadá' => 'CA',
    'Chile' => 'CL',
    'China' => 'CN',
    'Colombia' => 'CO',
    'Croacia' => 'HR',
    'Emiratos Árabes Unidos' => 'AE',
    'Eslovaquia' => 'SK',
    'España' => 'ES',
    'Estados Unidos' => 'US',
    'Filipinas' => 'PH',
    'Francia' => 'FR',
    'Reino Unido' => 'GB',
    'Grecia' => 'GR',
    'Guatemala' => 'GT',
    'Países Bajos' => 'NL',
    'Hungría' => 'HU',
    'Indonesia' => 'ID',
    'Irlanda' => 'IE',
    'Islandia' => 'IS',
    'Israel' => 'IL',
    'Italia' => 'IT',
    'Jordania' => 'JO',
    'Liechtenstein' => 'LI',
    'Marruecos' => 'MA',
    'México' => 'MX',
    'Mónaco' => 'MC',
    'Namibia' => 'NA',
    'Noruega' => 'NO',
    'Nueva Zelanda' => 'NZ',
    'Omán' => 'OM',
    'Panamá' => 'PA',
    'Paraguay' => 'PY',
    'Perú' => 'PE',
    'Polonia' => 'PL',
    'Portugal' => 'PT',
    'Puerto Rico' => 'PR',
    'República Dominicana' => 'DO',
    'República Checa' => 'CZ',
    'Rumanía' => 'RO',
    'Singapur' => 'SG',
    'Sudáfrica' => 'ZA',
    'Suecia' => 'SE',
    'Suiza' => 'CH',
    'Turquía' => 'TR',
    'Uruguay' => 'UY',
    'Vietnam' => 'VN',
];

// Declarar las variables que vamos a utilizar para realizar la búsqueda en la barra de búsqueda y para mostrar los resultados de la misma en la web
$results_html = "";
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : "";

// Validar y obtener el código en la API del país buscado por el usuario
$country_code = isset($country_codes[$search_term]) ? $country_codes[$search_term] : "";

// Si el país se encuentra en el array, realizar la búsqueda en la API
if ($country_code) {

    // Configurar los parámetros de búsqueda para que la API solo busque tours/viajes/actividades/atracciones
    // de un solo día (sin estancia nocturna) y que solo tengan lugar en España
    $params = "product_type=4&country=" . urlencode($country_code);

    // Hacer la call a la API utilizando la función search_tours
    $result = $tourcms->search_tours($params, $channel_id);

        // Verificar si la respuesta de la API es un string
        // Si es así, convertir la respuesta en SimpleXMLElement
        // Todo esto es porque $result me estaba dando error en la línea 99 porque no podía acceder a ->tour
        if (is_string($result)) {
            $result = simplexml_load_string($result);
        }

        // Procesar los resultados obtenidos y escribir el código de HTML para mostrarlos en la web
        if (!empty($result) && isset($result->tour)) {
            foreach ($result->tour as $tour) {
                // Pedir a la API todos los detalles necesarios de cada tour para que aparezcan en la web
                $tour_name = (string) $tour->tour_name;
                $tour_url = (string) $tour->tour_url;
                $summary = (string) $tour->summary;
                $thumbnail_image = (string) $tour->thumbnail_image;
                $duration_desc = (string) $tour->duration_desc;
                $location = (string) $tour->location;
                $from_price = (string) $tour->from_price;

                // Utilizar la variable results_html para mostrar en la web cada uno de los resultados obtenidos de la búsqueda
                $results_html .= "<div class='tour-item'>";
                $results_html .= "<h3><a href='{$tour_url}' target='_blank'>{$tour_name}</a></h3>";
                $results_html .= "<p><strong>Descripción:</strong> {$summary}</p>";
                $results_html .= "<p><strong>Ubicación:</strong> {$location}</p>";
                $results_html .= "<p><strong>Duración:</strong> {$duration_desc}</p>";
                $results_html .= "<p><strong>Desde:</strong> {$from_price} EUR</p>";
                $results_html .= "<img src='{$thumbnail_image}' alt='{$tour_name}' style='width: 200px; height: auto;'>";
                $results_html .= "</div>";
                }
        } else {
            $results_html = "<p>No se encontraron resultados para '{$search_term}'.</p>";
        }
    // Añadir otro else para que, si el país no está en el array de los códigos de países, salte el mensaje de error
    } else {
        $results_html = "<p>No se encontraron resultados para '{$search_term}'.</p>";
    }
?>

<!-- Aquí dejo el código de una web sencilla que mostrará los resultados nada más abrirse-->
<!DOCTYPE html>
<html lang="en_GB">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Tours, viajes, actividades y atracciones de un solo día en España</title>
</head>
<body>

    <h2>Busca tours, viajes, actividades y atracciones de un solo día en el país que quieras</h2>
    <div class="tour-container">
        <!-- Código de la barra de búsqueda que permite al usuario elegir el país del que quiere ver los diferentes tours -->
        <form method="GET" action="">
            <label for="search_term" class="search-label">Escribe el nombre del país que quieres buscar:</label>
            <input type="text" id="search_term" name="search_term" placeholder="Escribe aquí..." value="<?php echo htmlspecialchars($search_term); ?>" required>
            <button type="submit">Buscar</button>
        </form>

    <?php echo $results_html; ?>

</body>
</html>