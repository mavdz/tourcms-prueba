<?php
//Cargar el archivo TourCMS.php para importar las funciones, clases, etc. ya definidas en dicho archivo
require_once "TourCMS.php";

//Insertar credenciales a continuación (las he borrado intencionadamente antes del commit y el push por motivos de seguridad) para conectarme a la API


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