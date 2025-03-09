<?php
// api/get_ctu.php
require_once '../config/config.php';
try {
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);
    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }
    $query = "SELECT ctu_id, nombre_ctu FROM ctu ORDER BY nombre_ctu";
    $result = pg_query($conn, $query);
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }
    $ctu = [];
    while ($row = pg_fetch_assoc($result)) {
        $ctu[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($ctu);
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if ($conn) {
        pg_close($conn);
    }
}
?>