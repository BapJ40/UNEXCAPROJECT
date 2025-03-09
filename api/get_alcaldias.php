<?php
// api/get_alcaldias.php
require_once '../config/config.php';
try {
    if (!isset($_GET['municipio_id']) || empty($_GET['municipio_id'])) {
        throw new Exception("Parámetro 'municipio_id' es requerido.");
    }
    $municipioId = $_GET['municipio_id'];
    if (!is_numeric($municipioId) || $municipioId <= 0) {
        throw new Exception("Valor inválido para 'municipio_id'. Debe ser un número entero positivo.");
    }
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }
    $query = "SELECT alcaldia_id, nombre_alcaldia FROM alcaldias WHERE municipio_id = $1 ORDER BY nombre_alcaldia";
    $params = [$municipioId];
    $result = pg_query_params($conn, $query, $params);
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }
    $alcaldias = [];
    while ($row = pg_fetch_assoc($result)) {
        $alcaldias[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($alcaldias);
} catch (Exception $e) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if ($conn) {
        pg_close($conn);
    }
}
?>