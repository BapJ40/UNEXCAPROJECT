<?php
// config.php - Archivo de configuración para la conexión a la base de datos

// **¡IMPORTANTE!**
// Reemplaza los siguientes valores con la información de tu base de datos.
// No compartas este archivo públicamente, ya que contiene credenciales sensibles.

// Datos de conexión a la base de datos MySQL
define('DB_HOST', 'localhost'); // Servidor de la base de datos (ej: localhost, 127.0.0.1, tu_dominio.com)
define('DB_NAME', 'unexca'); // Nombre de la base de datos
define('DB_USER', 'postgres'); // Nombre de usuario de la base de datos
define('DB_PASSWORD', '123'); // Contraseña del usuario de la base de datos
define('DB_PORT', 5432); // Puerto de MySQL (normalmente 3306, déjalo así si no estás seguro)
define('DB_CHARSET', 'utf8mb4'); // Codificación de caracteres (recomendado utf8mb4 para la mayoría de los casos)

// Opcional: Puedes definir constantes adicionales si necesitas más configuración
// Por ejemplo, para diferentes entornos (desarrollo, producción)

// ¡IMPORTANTE!
// Reemplaza los valores de ejemplo ('localhost', 'nombre_de_tu_base_de_datos', etc.)
// con tus credenciales reales de la base de datos.

// Para usar estas constantes en tu código PHP, puedes hacer:
// require_once 'config.php'; // Incluye este archivo de configuración
// $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
// ... (resto de tu código)
?>