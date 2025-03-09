<?php

// models/VisitasModel.php

require_once '../config/config.php';

class VisitasModel {

    public function registrarVisita($data, $visitanteId) {
        $conn = null;

        try {
            // Construir la conexión
            $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
            $conn = pg_connect($conn_string);

            if (!$conn) {
                throw new Exception("Error al conectar a la base de datos PostgreSQL: " . pg_last_error());
            }

            // Obtener el ID del usuario responsable desde la sesión
            session_start(); // Iniciar la sesión si no está iniciada
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Usuario no autenticado.");
            }
            $responsableVisitaId = $_SESSION['usuario_id'];

            // Verificar si 'es_cita_programada' está definido en $data
            $esCitaProgramada = $data['es_cita_programada'] ?? false;

            // Depuración: Verificar el valor de es_cita_programada
            error_log("Valor de es_cita_programada recibido: " . ($esCitaProgramada ? 'true' : 'false'));

            // Consulta SQL INSERT PREPARADA para 'visitas'
            $query = "
                INSERT INTO visitas (
                    visitante_id,
                    categoria_visita_id,
                    estado_visita_id,
                    responsable_visita_id,
                    motivo_visita,
                    es_cita_programada,
                    fecha_hora_cita,
                    departamento_o_persona
                ) VALUES (
                    $1, $2, $3, $4, $5, $6, $7, $8
                )
            ";

            // Parámetros para la consulta
            $params = [
                $visitanteId,
                $data['categoria_visita_id'],
                2,             // estado_visita_id (VALOR POR DEFECTO - ¡AJUSTA!)
                $responsableVisitaId, // responsable_visita_id (ID del usuario autenticado)
                $data['motivo_visita'],
                $esCitaProgramada ? 'TRUE' : 'FALSE', // es_cita_programada
                $data['fecha_visita'] . ' ' . $data['hora_visita'], // fecha_hora_cita
                $data['departamento_o_persona'],
            ];

            // Ejecutar la consulta preparada
            $result = pg_query_params($conn, $query, $params);

            if (!$result) {
                throw new Exception("Error al ejecutar la consulta SQL de inserción en 'visitas': " . pg_last_error($conn));
            }

            return true; // Registro exitoso

        } catch (Exception $e) {
            error_log("Error en VisitasModel::registrarVisita: " . $e->getMessage());
            return false; // Error al registrar
        } finally {
            if ($conn) {
                pg_close($conn);
            }
        }
    }
}