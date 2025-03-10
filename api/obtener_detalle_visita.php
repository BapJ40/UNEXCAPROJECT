<?php
// obtener_detalle_visita.php

require_once '../config/config.php';

// Obtener el ID de la visita desde la solicitud
$visitaId = $_GET['id'] ?? null;

if (!$visitaId) {
    echo json_encode(['error' => 'ID de la visita no proporcionado']);
    exit;
}

// Conectar a la base de datos
$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
$conn = pg_connect($conn_string);

if (!$conn) {
    echo json_encode(['error' => 'Error al conectar a la base de datos']);
    exit;
}

// Consulta SQL para obtener los detalles de la visita y el visitante relacionado
$query = "
    SELECT 
        v.motivo_visita,
        v.fecha_hora_cita AS fecha_visita,
        v.departamento_o_persona,
        vi.nombre_visitante,
        vi.apellido_visitante,
        vi.cedula_identidad,
        vi.telefono_visitante,
        vi.direccion_visitante
    FROM visitas v
    JOIN visitantes vi ON v.visitante_id = vi.visitante_id
    WHERE v.visita_id = $1
";
$result = pg_query_params($conn, $query, [$visitaId]);

if (!$result) {
    echo json_encode(['error' => 'Error al ejecutar la consulta']);
    exit;
}

$visita = pg_fetch_assoc($result);

if (!$visita) {
    echo json_encode(['error' => 'Visita no encontrada']);
    exit;
}

// Devolver los detalles de la visita y el visitante en formato JSON
echo json_encode($visita);
?>