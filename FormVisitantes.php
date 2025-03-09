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
    <title>Registro de Nuevo Visitante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="estiloRegistro.css">
</head>
<body>
    <div class="form-container">
        <h1>Registro de Nuevo Visitante</h1>

        <!-- Formulario con ID "registro-visitante-form" -->
        <form id="registro-visitante-form">
            <!-- Sección de Datos Personales -->
            <section class="form-section">
                <h2><i class="fas fa-user"></i> Datos Personales</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="cedula_identidad">Cédula de Identidad:</label>
                        <input type="text" id="cedula_identidad" name="cedula_identidad" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre_visitante">Nombre:</label>
                        <input type="text" id="nombre_visitante" name="nombre_visitante" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_visitante">Apellido:</label>
                        <input type="text" id="apellido_visitante" name="apellido_visitante" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono_visitante">Teléfono:</label>
                        <input type="tel" id="telefono_visitante" name="telefono_visitante" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion_visitante">Dirección:</label>
                        <textarea id="direccion_visitante" name="direccion_visitante"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ruta_foto">Foto (URL):</label>
                        <input type="file" id="ruta_foto" name="ruta_foto">
                    </div>
                </div>
            </section>

            <!-- Sección de Dirección -->
            <section class="form-section">
                <h2><i class="fas fa-map-marker-alt"></i> Dirección</h2>
                <div class="form-grid direccion-grid">
                    <div class="form-group">
                        <label for="estado_id">Estado:</label>
                        <select id="estado_id" name="estado_id">
                            <option value="">Selecciona un estado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="municipio_id">Municipio:</label>
                        <select id="municipio_id" name="municipio_id">
                            <option value="">Selecciona un municipio</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="parroquia_id">Parroquia:</label>
                        <select id="parroquia_id" name="parroquia_id">
                            <option value="">Selecciona una parroquia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sector_id">Sector (Opcional):</label>
                        <select id="sector_id" name="sector_id">
                            <option value="">Selecciona un sector</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alcaldia_id">Alcaldía (Opcional):</label>
                        <select id="alcaldia_id" name="alcaldia_id">
                            <option value="">Selecciona una alcaldía</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="circuito_comunal_id">Circuito Comunal (Opcional):</label>
                        <select id="circuito_comunal_id" name="circuito_comunal_id">
                            <option value="">Selecciona un circuito</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comuna_id">Comuna (Opcional):</label>
                        <select id="comuna_id" name="comuna_id">
                            <option value="">Selecciona una comuna</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="consejo_comunal_id">Consejo Comunal (Opcional):</label>
                        <select id="consejo_comunal_id" name="consejo_comunal_id">
                            <option value="">Selecciona un consejo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ctu_id">CTU (Opcional):</label>
                        <select id="ctu_id" name="ctu_id">
                            <option value="">Selecciona un CTU</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- Sección de Datos de la Visita -->
            <section class="form-section">
                <h2><i class="fas fa-calendar-alt"></i> Datos de la Visita</h2>
                <div class="form-grid">
                    <!-- <div class="form-group">
                        <label for="es_cita_programada">Es cita programada:</label>
                        <input type="checkbox" id="es_cita_programada" name="es_cita_programada" value="true">
                    </div> -->
                    <div class="form-group">
                        <label for="categoria_visita_id">Categoría de la Visita:</label>
                        <select id="categoria_visita_id" name="categoria_visita_id">
                            <option value="">Selecciona una categoría</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="motivo_visita">Motivo de la Visita:</label>
                        <input type="text" id="motivo_visita" name="motivo_visita" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_visita">Fecha de la Visita:</label>
                        <input type="date" id="fecha_visita" name="fecha_visita" required>
                    </div>
                    <div class="form-group">
                        <label for="hora_visita">Hora de la Visita:</label>
                        <input type="time" id="hora_visita" name="hora_visita" required>
                    </div>
                    <div class="form-group">
                        <label for="departamento_o_persona">Departamento o Persona a Visitar:</label>
                        <input type="text" id="departamento_o_persona" name="departamento_o_persona">
                    </div>
                </div>
            </section>

            <!-- Botón de Envío -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Registrar Visitante
                </button>
            </div>
        </form>
    </div>
<script src="script_registro.js"></script>
</body>
</html>