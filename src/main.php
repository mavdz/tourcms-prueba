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

// Declarar las variables que vamos a utilizar para realizar la búsqueda en la API y para mostrar los resultados de la misma en la web
$results_html = "";
$search_term = "";

// Configurar los parámetros de búsqueda para que la API solo busque tours/viajes/actividades/atracciones
// de un solo día (sin estancia nocturna) y que solo tengan lugar en España
$params = "product_type=4&country=ES&search=" . urlencode($search_term);

// Hacer la call a la API utilizando la función search_tours
$result = $tourcms->search_tours($params, $channel_id);

    // Verificar si la respuesta de la API es un string
    // Si es así, convertir la respuesta en SimpleXMLElement
    // Todo esto es porque $result me estaba dando error en la línea 34 porque no podía acceder a ->tour 
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

    <h2>Aquí tienes una lista con los tours, viajes, actividades y atracciones de un solo día en España:</h2>
    <div class="tour-container">
    <?php echo $results_html; ?>

</body>
</html>