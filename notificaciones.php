<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html"); // Redirigir si no hay sesión
    exit();
}

$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estiloNoti.css">
</head>
<body>
    <!-- Vista de Notificaciones -->
<div id="notificaciones-view" class="view" style="display: none;">
    <h2>Notificaciones de Visitas</h2>
    <table class="notificaciones-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Motivo</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="notificaciones-table-body">
            <!-- Aquí se cargarán las solicitudes de visita -->
        </tbody>
    </table>
</div>

<button id="notificaciones-button">Ver Notificaciones</button>

<script src="script_noti.js"></script>
</body>
</html>