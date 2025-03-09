<?php
session_start(); // Iniciar la sesión

require_once 'config/config.php'; // Incluir archivo de configuración

$conn = null;

$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

// Obtener datos del formulario
$nombre_usuario = $_POST['nombre_usuario'];
$contraseña = $_POST['contraseña'];

// Consulta para verificar las credenciales
$query = "SELECT usuario_id, nombre, apellido, contraseña FROM usuarios WHERE nombre_usuario = $1";
$result = pg_query_params($conn, $query, array($nombre_usuario));

if (!$result) {
    die("Error en la consulta: " . pg_last_error());
}

if (pg_num_rows($result) > 0) {
    $usuario = pg_fetch_assoc($result);

    // Comparar contraseñas directamente (en texto plano)
    if ($contraseña === $usuario['contraseña']) {
        // Credenciales válidas
        $_SESSION['usuario_id'] = $usuario['usuario_id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['apellido'] = $usuario['apellido'];
        header("Location: dashboard.php"); // Redirigir al dashboard
        exit();
    } else {
        // Contraseña incorrecta
        echo "Contraseña incorrecta.";
    }
} else {
    // Usuario no encontrado
    echo "Usuario no encontrado.";
}

// Cerrar la conexión
pg_close($conn);
?>