<?php
// api/get_sectores.php
require_once '../config/config.php';
try {
    if (!isset($_GET['parroquia_id']) || empty($_GET['parroquia_id'])) {
        throw new Exception("Parámetro 'parroquia_id' es requerido.");
    }
    $parroquiaId = $_GET['parroquia_id'];
    if (!is_numeric($parroquiaId) || $parroquiaId <= 0) {
        throw new Exception("Valor inválido para 'parroquia_id'. Debe ser un número entero positivo.");
    }
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }
    $query = "SELECT sector_id, nombre_sector FROM sectores WHERE parroquia_id = $1 ORDER BY nombre_sector";
    $params = [$parroquiaId];
    $result = pg_query_params($conn, $query, $params);
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }
    $sectores = [];
    while ($row = pg_fetch_assoc($result)) {
        $sectores[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($sectores);
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