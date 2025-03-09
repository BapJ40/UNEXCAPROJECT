<?php
// api/get_comunas.php
require_once '../config/config.php';
try {
    if (!isset($_GET['circuito_comunal_id']) || empty($_GET['circuito_comunal_id'])) {
        throw new Exception("Parámetro 'circuito_comunal_id' es requerido.");
    }
    $circuitoComunalId = $_GET['circuito_comunal_id'];
    if (!is_numeric($circuitoComunalId) || $circuitoComunalId <= 0) {
        throw new Exception("Valor inválido para 'circuito_comunal_id'. Debe ser un número entero positivo.");
    }
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }
    $query = "SELECT comuna_id, nombre_comuna FROM comunas WHERE circuito_comunal_id = $1 ORDER BY nombre_comuna";
    $params = [$circuitoComunalId];
    $result = pg_query_params($conn, $query, $params);
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }
    $comunas = [];
    while ($row = pg_fetch_assoc($result)) {
        $comunas[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($comunas);
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