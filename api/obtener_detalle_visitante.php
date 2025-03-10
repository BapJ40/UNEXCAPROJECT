<?php
require_once '../config/config.php';

// Obtener el ID del visitante desde la solicitud
$visitanteId = $_GET['id'] ?? null;

if (!$visitanteId) {
    echo json_encode(['error' => 'ID del visitante no proporcionado']);
    exit;
}

// Conectar a la base de datos
$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
$conn = pg_connect($conn_string);

if (!$conn) {
    echo json_encode(['error' => 'Error al conectar a la base de datos']);
    exit;
}

// Obtener los detalles del visitante
$query = "
    SELECT 
        v.visitante_id,
        v.nombre_visitante AS nombre, 
        v.apellido_visitante AS apellido, 
        v.cedula_identidad AS cedula, 
        v.telefono_visitante AS telefono, 
        v.direccion_visitante AS direccion, 
        e.nombre_estado AS estado, 
        m.nombre_municipio AS municipio, 
        p.nombre_parroquia AS parroquia, 
        vi.motivo_visita, 
        vi.fecha_hora_cita AS fecha_visita
    FROM visitantes v
    LEFT JOIN visitas vi ON v.visitante_id = vi.visitante_id
    LEFT JOIN estados e ON v.estado_id = e.estado_id
    LEFT JOIN municipios m ON v.municipio_id = m.municipio_id
    LEFT JOIN parroquias p ON v.parroquia_id = p.parroquia_id
    WHERE v.visitante_id = $1
";
$result = pg_query_params($conn, $query, [$visitanteId]);

if (!$result) {
    echo json_encode(['error' => 'Error al ejecutar la consulta']);
    exit;
}

$visitante = pg_fetch_assoc($result);

if (!$visitante) {
    echo json_encode(['error' => 'Visitante no encontrado']);
    exit;
}

// Devolver los detalles del visitante en formato JSON
echo json_encode($visitante);
?>