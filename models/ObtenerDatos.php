<?php

// models/ObtenerDatosModel.php

require_once '../config/config.php'; // Incluye el archivo de configuración de la base de datos

class ObtenerDatosModel {

    public function obtenerDatosDeLaBaseDeDatos() {
        $conn = null; // Inicializa la conexión a null

        try {
            // Construir la cadena de conexión
            $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;

            // Crear una conexión PostgreSQL
            $conn = pg_connect($conn_string);

            if (!$conn) {
                throw new Exception("Error al conectar a la base de datos PostgreSQL: " . pg_last_error());
            }

            // **¡IMPORTANTE!**
            // Consulta SQL con JOIN para obtener datos de visitas y visitantes
            $query = "
                SELECT
                    visitas.visita_id,
                    visitantes.nombre_visitante,
                    visitantes.apellido_visitante,
                    visitantes.cedula_identidad,
                    visitas.motivo_visita,
                    visitas.fecha_hora_cita,
                    visitas.departamento_o_persona, /* Asumiendo que 'departamento_o_persona' existe en 'visitas', si no, ajústalo */
                    estados_visita.nombre_estado_visita
                FROM visitas
                INNER JOIN visitantes ON visitas.visitante_id = visitantes.visitante_id
                INNER JOIN estados_visita ON visitas.estado_visita_id = estados_visita.estado_visita_id
                ORDER BY visitas.fecha_hora_cita DESC;
            ";

            $result = pg_query($conn, $query);

            if (!$result) {
                throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
            }

            $datos = pg_fetch_all($result); // Obtiene todos los resultados como un array asociativo

            if ($datos === false) {
                // pg_fetch_all devuelve false si no hay filas que recuperar o en caso de error.
                // En este caso, si no hay filas, devolvemos un array vacío en lugar de lanzar una excepción.
                return []; // No hay datos en la tabla (o error, pero ya se manejó el error de consulta arriba)
            }

            return $datos; // Devuelve los datos obtenidos de la base de datos

        } catch (Exception $e) {
            // Manejo de excepciones (puedes loggear el error, mostrar un mensaje genérico, etc.)
            error_log("Error en ObtenerDatosModel: " . $e->getMessage()); // Loggea el error para depuración
            return false; // Indica que hubo un error al obtener los datos
        } finally {
            if ($conn) {
                pg_close($conn); // Cierra la conexión en el bloque finally para asegurar que se cierre siempre
            }
        }
    }
}

?>