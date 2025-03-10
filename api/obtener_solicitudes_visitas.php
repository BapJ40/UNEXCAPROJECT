<?php
// obtener_solicitudes_visitas.php

require_once '../config/config.php';

// Conectar a la base de datos
$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
$conn = pg_connect($conn_string);

if (!$conn) {
    echo json_encode(['error' => 'Error al conectar a la base de datos']);
    exit;
}

// Obtener las solicitudes de visita pendientes
$query = "
    SELECT 
        v.visita_id,
        vi.nombre_visitante as nombre_visitante,
        vi.apellido_visitante AS apellido_visitante,
        vi.cedula_identidad,
        v.motivo_visita,
        v.fecha_hora_cita,
        ev.nombre_estado_visita AS nombre_estado_visita
    FROM visitas v
    JOIN visitantes vi ON v.visitante_id = vi.visitante_id
    JOIN estados_visita ev ON v.estado_visita_id = ev.estado_visita_id
    WHERE ev.estado_visita_id = '2'
";
$result = pg_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Error al ejecutar la consulta']);
    exit;
}

$solicitudes = pg_fetch_all($result);

if (!$solicitudes) {
    echo json_encode(['error' => 'No hay solicitudes pendientes']);
    exit;
}

// Devolver las solicitudes en formato JSON
echo json_encode($solicitudes);
?>