<?php

// controllers/ObtenerDatosController.php

require_once '../models/ObtenerDatos.php'; // Incluye el modelo

class ObtenerDatosController {

    public function obtenerDatosAction() {
        $modelo = new ObtenerDatosModel(); // Crea una instancia del modelo
        $datos = $modelo->obtenerDatosDeLaBaseDeDatos(); // Llama al método del modelo para obtener los datos

        // Establecer el header Content-Type para indicar que la respuesta es JSON
        header('Content-Type: application/json');

        if ($datos === false) {
            // Hubo un error al obtener los datos
            http_response_code(500); // Establecer código de error HTTP 500 (Internal Server Error)
            echo json_encode(['error' => 'Error al obtener datos de la base de datos.']); // Enviar mensaje de error en JSON
        } else {
            // Datos obtenidos exitosamente
            echo json_encode($datos); // Enviar los datos en formato JSON
        }
    }
}

?>