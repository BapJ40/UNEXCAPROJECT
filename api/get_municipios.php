<?php

// api/get_municipios.php

require_once '../config/config.php'; // Asegúrate de que la ruta a config.php sea correcta

try {
    // Verificar si se recibió el estado_id por GET
    if (!isset($_GET['estado_id']) || empty($_GET['estado_id'])) {
        throw new Exception("Parámetro 'estado_id' es requerido.");
    }
    $estadoId = $_GET['estado_id'];

    // Validar que estado_id sea un número entero positivo (opcional, pero recomendado)
    if (!is_numeric($estadoId) || $estadoId <= 0) {
        throw new Exception("Valor inválido para 'estado_id'. Debe ser un número entero positivo.");
    }

    // Construir la cadena de conexión
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);

    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }

    // Consulta SQL para obtener municipios por estado_id
    $query = "SELECT municipio_id, nombre_municipio FROM municipios WHERE estado_id = $1 ORDER BY nombre_municipio";
    $params = [$estadoId]; // Parámetro para la consulta preparada
    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }

    $municipios = [];
    while ($row = pg_fetch_assoc($result)) {
        $municipios[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($municipios);

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