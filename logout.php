<?php
session_start();
session_destroy(); // Destruir la sesión
header("Location: login.html"); // Redirigir al login
exit();
?>