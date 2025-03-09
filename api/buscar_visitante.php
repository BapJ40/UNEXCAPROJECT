<?php
session_start();
require_once '../config/config.php'; // Asegúrate de que la ruta sea correcta

// Obtener la cédula del visitante desde la solicitud
$cedula = $_GET['cedula'];

// Conectar a la base de datos
$conn = pg_connect("host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD);

if (!$conn) {
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

// Consulta para buscar al visitante por cédula
$query = "
    SELECT 
        visitante_id,
        cedula_identidad,
        nombre_visitante AS nombre,
        apellido_visitante AS apellido,
        telefono_visitante AS telefono,
        direccion_visitante AS direccion,
        ruta_foto,
        estado_id,
        municipio_id,
        parroquia_id,
        sector_id,
        alcaldia_id,
        circuito_comunal_id,
        comuna_id,
        consejo_comunal_id,
        ctu_id
    FROM visitantes 
    WHERE cedula_identidad = $1
";
$result = pg_query_params($conn, $query, array($cedula));

if (!$result) {
    die(json_encode(['error' => 'Error en la consulta']));
}

if (pg_num_rows($result) > 0) {
    // Visitante encontrado
    $visitante = pg_fetch_assoc($result);
    echo json_encode($visitante); // Devolver datos del visitante en formato JSON
} else {
    // Visitante no encontrado
    echo json_encode(['error' => 'Visitante no encontrado']);
}

pg_close($conn);
?>