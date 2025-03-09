<?php
// api/get_consejos_comunales.php
require_once '../config/config.php';
try {
    if (!isset($_GET['comuna_id']) || empty($_GET['comuna_id'])) {
        throw new Exception("Parámetro 'comuna_id' es requerido.");
    }
    $comunaId = $_GET['comuna_id'];
    if (!is_numeric($comunaId) || $comunaId <= 0) {
        throw new Exception("Valor inválido para 'comuna_id'. Debe ser un número entero positivo.");
    }
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }
    $query = "SELECT consejo_comunal_id, nombre_consejo_comunal FROM consejos_comunales WHERE comuna_id = $1 ORDER BY nombre_consejo_comunal";
    $params = [$comunaId];
    $result = pg_query_params($conn, $query, $params);
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }
    $consejosComunales = [];
    while ($row = pg_fetch_assoc($result)) {
        $consejosComunales[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($consejosComunales);
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