<?php
// api/get_circuitos_comunales.php
require_once '../config/config.php';
try {
    if (!isset($_GET['alcaldia_id']) || empty($_GET['alcaldia_id'])) {
        throw new Exception("Parámetro 'alcaldia_id' es requerido.");
    }
    $alcaldiaId = $_GET['alcaldia_id'];
    if (!is_numeric($alcaldiaId) || $alcaldiaId <= 0) {
        throw new Exception("Valor inválido para 'alcaldia_id'. Debe ser un número entero positivo.");
    }
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }
    $query = "SELECT circuito_comunal_id, nombre_circuito_comunal FROM circuitos_comunales WHERE alcaldia_id = $1 ORDER BY nombre_circuito_comunal";
    $params = [$alcaldiaId];
    $result = pg_query_params($conn, $query, $params);
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }
    $circuitosComunales = [];
    while ($row = pg_fetch_assoc($result)) {
        $circuitosComunales[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($circuitosComunales);
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