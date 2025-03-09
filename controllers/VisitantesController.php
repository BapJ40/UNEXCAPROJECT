<?php

// controllers/VisitantesController.php

require_once '../models/VisitantesModel.php'; // Modelo para 'visitantes'
require_once '../models/VisitasModel.php';    // ¡NUEVO! Modelo para 'visitas'

class VisitantesController {

    public function registrarVisitanteAction() {
        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        // ... (Validación de datos como antes, incluyendo estado_id) ...

        // **SEPARAR DATOS PARA TABLAS 'visitantes' y 'visitas'**

        // Datos para la tabla 'visitantes'
        $visitanteData = [
            'cedula_identidad' => $data['cedula_identidad'],
            'nombre_visitante' => $data['nombre_visitante'],
            'apellido_visitante' => $data['apellido_visitante'],
            'telefono_visitante' => $data['telefono_visitante'],
            'direccion_visitante' => $data['direccion_visitante'],
            'ruta_foto' => $data['ruta_foto'],
            'estado_id' => $data['estado_id'],
            'municipio_id' => $data['municipio_id'],
            'parroquia_id' => $data['parroquia_id'],
            'sector_id' => $data['sector_id'],
            'alcaldia_id' => $data['alcaldia_id'],
            'circuito_comunal_id' => $data['circuito_comunal_id'],
            'comuna_id' => $data['comuna_id'],
            'consejo_comunal_id' => $data['consejo_comunal_id'],
            'ctu_id' => $data['ctu_id'],
        ];

        // Datos para la tabla 'visitas'
        $visitaData = [
            'visitante_id' => null, // ¡¡¡¡¡ visitante_id se asignará después de registrar el visitante !!!!!
            'categoria_visita_id' => $data['categoria_visita_id'],
            'motivo_visita' => $data['motivo_visita'],
            'fecha_visita' => $data['fecha_visita'], // Fecha (YYYY-MM-DD)
            'hora_visita' => $data['hora_visita'],   // Hora (HH:MM)
            'departamento_o_persona' => $data['departamento_o_persona'], // Usar el nombre de campo correcto
        ];


        // Crear instancias de los modelos
        $modeloVisitante = new VisitantesModel();
        $modeloVisita = new VisitasModel(); // ¡NUEVO! Instancia del modelo VisitasModel

        // 1. Registrar visitante y obtener visitante_id
        $visitanteId = $modeloVisitante->registrarVisitante($visitanteData);

        if ($visitanteId) { // Si el registro de visitante fue exitoso
            // 2. Registrar visita, pasando los datos de la visita y el visitante_id
            $resultadoVisita = $modeloVisita->registrarVisita($visitaData, $visitanteId); // ¡PASAMOS visitanteId!

            if ($resultadoVisita) { // Si el registro de visita también fue exitoso
                $this->sendJsonResponse(['success' => true, 'message' => 'Visitante y visita registrados correctamente.']);
            } else {
                $this->sendJsonResponse(['success' => false, 'error' => 'Error al registrar la información de la visita.']);
            }
        } else {
            $this->sendJsonResponse(['success' => false, 'error' => 'Error al registrar la información del visitante.']);
        }
    }

    private function sendJsonResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}