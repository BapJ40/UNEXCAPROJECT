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
    <title>Visitantes</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <button id="menu-toggle" class="menu-toggle-button">
                    <i class="fas fa-bars"></i>
                </button>
                <img src="INTU_LOGO.png" alt="Logo INTU" class="intu-logo">
            </div>
            <nav class="menu">
                <ul>
                    <a href="dashboard.php">
                        <li>
                            <i class="fas fa-home"></i>
                            <span>Bienvenida</span>
                        </li>
                    </a>
                    <li>
                        <i class="far fa-calendar-alt"></i>
                        <span>Calendario de Visitas</span>
                    </li>
                </ul>
            </nav>

            <div class="noti">
                <a href="notificaciones.php">
                <i class="fas fa-bell"></i>
                <span class="noti-counter"></span>
                </a>
            </div>

            <div class="user-info">
                <p><?php echo $nombre . ' ' . $apellido; ?></p>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </aside>
        <main class="content" id="content">
            <!-- Vista de Lista de Visitantes Pendientes -->
            <div id="visitors-list-view" class="visitors-view">
                <h2>Visitantes</h2>

                <div class="pending-visits-section">
                    <h3>Visitas Pendientes</h3>
                    <div class="pending-visits-table-container">
                        <table class="pending-visits-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Motivo</th>
                                    <th>Fecha y Hora</th>
                                    <th>Departamento/Persona</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Las filas de visitas se agregarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="new-visit-section">
                <a href="FormVisitantes.php" target="_blank" rel="noopener noreferrer">
                    <button id="new-visit-button" class="new-visit-button">
                        <i class="fas fa-plus"></i> Crear Nueva Solicitud de Visita
                    </button>
                </a>
            </div>

            <!-- Contenedor vacío para el formulario de registro -->
            <div id="visitor-registration-view" style="display: none;">
                <!-- El formulario se cargará aquí dinámicamente desde visitor-form.html -->
            </div>

            <div id="modalDetalleVisitante" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Detalles del Visitante</h2>
                    <div id="detalleVisitanteContent">
                        <!-- Aquí se cargará la información del visitante -->
                    </div>
                </div>
            </div>
        </main>
        
    </div>
    <script src="script.js"></script>
</body>
</html>