<?php

// models/VisitantesModel.php

require_once '../config/config.php'; // Asegúrate de que la ruta sea correcta

class VisitantesModel {

    public function registrarVisitante($data) {
        $conn = null;

        try {
            // Construir la cadena de conexión
            $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
            $conn = pg_connect($conn_string);

            if (!$conn) {
                throw new Exception("Error al conectar a la base de datos PostgreSQL: " . pg_last_error());
            }

            // Verificar si el visitante ya existe por cédula
            $cedula = $data['cedula_identidad'];
            $query = "SELECT visitante_id FROM visitantes WHERE cedula_identidad = $1";
            $result = pg_query_params($conn, $query, array($cedula));

            if (!$result) {
                throw new Exception("Error al buscar el visitante: " . pg_last_error($conn));
            }

            if (pg_num_rows($result) > 0) {
                // Visitante ya existe: obtener su ID
                $row = pg_fetch_assoc($result);
                $visitanteId = $row['visitante_id'];
            } else {
                // Visitante no existe: insertar nuevo visitante
                $query = "
                    INSERT INTO visitantes (
                        cedula_identidad,
                        nombre_visitante,
                        apellido_visitante,
                        telefono_visitante,
                        direccion_visitante,
                        ruta_foto,
                        estado_id,
                        municipio_id,
                        parroquia_id,
                        sector_id,
                        alcaldia_id,
                        circuito_comunal_id,
                        comuna_id,
                        consejo_comunal_id,
                        ctu_id
                    ) VALUES (
                        $1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15
                    )
                    RETURNING visitante_id;
                ";

                // Parámetros para la consulta
                $params = [
                    $data['cedula_identidad'],
                    $data['nombre_visitante'],
                    $data['apellido_visitante'],
                    $data['telefono_visitante'],
                    $data['direccion_visitante'],
                    $data['ruta_foto'] ?? null, // Campo opcional
                    empty($data['estado_id']) ? null : $data['estado_id'],
                    empty($data['municipio_id']) ? null : $data['municipio_id'],
                    empty($data['parroquia_id']) ? null : $data['parroquia_id'],
                    empty($data['sector_id']) ? null : $data['sector_id'],
                    empty($data['alcaldia_id']) ? null : $data['alcaldia_id'],
                    empty($data['circuito_comunal_id']) ? null : $data['circuito_comunal_id'],
                    empty($data['comuna_id']) ? null : $data['comuna_id'],
                    empty($data['consejo_comunal_id']) ? null : $data['consejo_comunal_id'],
                    empty($data['ctu_id']) ? null : $data['ctu_id']
                ];

                // Ejecutar la consulta preparada
                $result = pg_query_params($conn, $query, $params);

                if (!$result) {
                    throw new Exception("Error al ejecutar la consulta SQL de inserción: " . pg_last_error($conn));
                }

                // Obtener el ID del nuevo visitante
                $row = pg_fetch_assoc($result);
                $visitanteId = $row['visitante_id'];
            }

            return $visitanteId; // Devolver el ID del visitante (existente o nuevo)

        } catch (Exception $e) {
            error_log("Error en VisitantesModel::registrarVisitante: " . $e->getMessage());
            return false; // Error al registrar
        } finally {
            if ($conn) {
                pg_close($conn);
            }
        }
    }
}