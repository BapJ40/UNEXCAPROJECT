<?php

// api/get_estados.php (CORRECTED FOR POSTGRESQL)

require_once '../config/config.php'; // Ensure correct path to config.php

try {
    // Construct connection string from config constants
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);

    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }

    $query = "SELECT estado_id, nombre_estado FROM estados ORDER BY nombre_estado";
    $result = pg_query($conn, $query);

    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }

    $estados = [];
    while ($row = pg_fetch_assoc($result)) { // Use pg_fetch_assoc for PostgreSQL
        $estados[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($estados);

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if ($conn) {
        pg_close($conn); // Use pg_close for PostgreSQL
    }
}

?>