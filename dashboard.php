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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Desplegable</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <button id="menu-toggle" class="menu-toggle-button">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Reemplazamos el div del logo de texto por la imagen -->
                <img src="INTU_LOGO.png" alt="Logo INTU" class="intu-logo">
            </div>
            <nav class="menu">
                <ul>
                    <a href="visitantes.php">
                        <li>
                            <i class="fas fa-users"></i>
                            <span>Visitantes</span>
                        </li>
                    </a>
                    
                    <li>
                        <i class="far fa-calendar-alt"></i>
                        <span>Calendario de Visitas</span>
                    </li>
                </ul>
            </nav>
        </aside>
        <main class="content" id="content">
            <h1>Dashboard Principal</h1>
            <p>Este es el contenido principal del dashboard. El menú lateral puede desplegarse y replegarse haciendo clic en el icono de menú en la parte superior izquierda.</p>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>