<?php
//Cargar el archivo TourCMS.php para importar las funciones, clases, etc. ya definidas en dicho archivo
require_once "TourCMS.php";

//Insertar credenciales a continuación (las he borrado intencionadamente antes del commit y el push por motivos de seguridad) para conectarme a la API


// Crear la instancia de TourCMS usando un namespace
use TourCMS\Utils\TourCMS as TourCMS;
$tourcms = new TourCMS($marketplace_id, $api_key, "simplexml");
