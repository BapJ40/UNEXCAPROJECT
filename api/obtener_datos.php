<?php

// api/obtener_datos.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controllers/ObtenerDatosController.php';

$controller = new ObtenerDatosController();
$controller->obtenerDatosAction();

?>