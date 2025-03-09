<?php

// api/get_categorias_visita.php

require_once '../config/config.php'; // Asegúrate de que la ruta a config.php sea correcta

try {
    // Construir la cadena de conexión
    $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
    $conn = pg_connect($conn_string);

    if (!$conn) {
        throw new Exception("Error de conexión a la base de datos PostgreSQL: " . pg_last_error());
    }

    $query = "SELECT categoria_visita_id, nombre_categoria_visita FROM categorias_visita ORDER BY categoria_visita_id";
    $result = pg_query($conn, $query);

    if (!$result) {
        throw new Exception("Error al ejecutar la consulta SQL: " . pg_last_error($conn));
    }

    $categoriasVisita = [];
    while ($row = pg_fetch_assoc($result)) {
        $categoriasVisita[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($categoriasVisita);

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