<?php

require_once '../config/config.php';

try {
    // Verificar si se recibió el municipio_id por GET
    if (!isset($_GET['municipio_id']) || empty($_GET['municipio_id'])) {
        throw new Exception("Parámetro 'municipio_id' es requerido.");
    }
    $municipio_id = $_GET['municipio_id'];

    // Validar que estado_id sea un número entero positivo (opcional, pero recomendado)
    if (!is_numeric($municipio_id) || $municipio_id <= 0) {
        throw new Exception("Valor inválido para 'municipio_id'. Debe ser un número entero positivo.");
    }

    // Construir la cadena de conexión
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);

    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }

    // Consulta SQL para obtener municipios por estado_id
    $query = "SELECT parroquia_id, nombre_parroquia FROM parroquias WHERE municipio_id = $1 ORDER BY nombre_parroquia";
    $params = [$municipio_id]; // Parámetro para la consulta preparada
    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }

    $parroquias = [];
    while ($row = pg_fetch_assoc($result)) {
        $parroquias[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($parroquias);

} catch (Exception $e) {
    http_response_code(400); // Bad Request - Error del lado del cliente (parámetro incorrecto, etc.)
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if ($conn) {
        pg_close($conn);
    }
}

?>