<?php
// actualizar_estado_visita.php

require_once '../config/config.php';

// Obtener los datos de la solicitud
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$visitaId = $data['visita_id'] ?? null;
$nuevoEstado = $data['nuevo_estado'] ?? null;

if (!$visitaId || !$nuevoEstado) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Conectar a la base de datos
$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
$conn = pg_connect($conn_string);

if (!$conn) {
    echo json_encode(['error' => 'Error al conectar a la base de datos']);
    exit;
}

// Depuración: Verificar el valor de $nuevoEstado
error_log("Nuevo estado recibido: " . $nuevoEstado);

// Obtener el ID del estado correspondiente
$queryEstado = "SELECT estado_visita_id FROM estados_visita WHERE LOWER(nombre_estado_visita) = LOWER($1::varchar)"; // Normalizar mayúsculas y minúsculas
$resultEstado = pg_query_params($conn, $queryEstado, [$nuevoEstado]);

if (!$resultEstado) {
    echo json_encode(['error' => 'Error al obtener el estado']);
    exit;
}

$estado = pg_fetch_assoc($resultEstado);

if (!$estado) {
    echo json_encode(['error' => 'Estado no válido']);
    exit;
}

$estadoId = $estado['estado_visita_id'];

// Actualizar el estado de la visita
$query = "UPDATE visitas SET estado_visita_id = $1 WHERE visita_id = $2";
$result = pg_query_params($conn, $query, [$estadoId, $visitaId]);

if (!$result) {
    echo json_encode(['error' => 'Error al actualizar el estado']);
    exit;
}

echo json_encode(['success' => true]);
?>