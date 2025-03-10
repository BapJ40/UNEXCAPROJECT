document.addEventListener('DOMContentLoaded', function() {

// Función para cargar las solicitudes de visita
function cargarSolicitudesVisitas() {
    fetch('./api/obtener_solicitudes_visitas.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error); // Mostrar error si lo hay
            } else {
                mostrarSolicitudesVisitas(data);
            }
        })
        .catch(error => {
            console.error('Error al cargar las solicitudes de visita:', error);
            alert('Error al cargar las solicitudes de visita.');
        });
}

// Función para mostrar las solicitudes de visita en la tabla
function mostrarSolicitudesVisitas(solicitudes) {
    const tbody = document.getElementById('notificaciones-table-body');
    tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos

    solicitudes.forEach(solicitud => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${solicitud.nombre_visitante} ${solicitud.apellido_visitante}</td>
            <td>${solicitud.cedula_identidad}</td>
            <td>${solicitud.motivo_visita}</td>
            <td>${solicitud.fecha_hora_cita}</td>
            <td>${solicitud.nombre_estado_visita}</td>
            <td>
                <button class="action-button aprobar-button" data-id="${solicitud.visita_id}">Aprobar</button>
                <button class="action-button concluir-button" data-id="${solicitud.visita_id}">Concluir</button>
                <button class="action-button cancelar-button" data-id="${solicitud.visita_id}">Cancelar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Asignar eventos a los botones de acción
    asignarEventosBotonesAccion();
}

// Función para actualizar el estado de la solicitud de visita
function actualizarEstadoVisita(visitaId, nuevoEstado) {
    fetch('./api/actualizar_estado_visita.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            visita_id: visitaId,
            nuevo_estado: nuevoEstado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Estado actualizado correctamente.');
            cargarSolicitudesVisitas(); // Recargar las solicitudes
        } else {
            alert(`Error al actualizar el estado: ${data.error}`);
        }
    })
    .catch(error => {
        console.error('Error al actualizar el estado:', error);
        alert('Error al actualizar el estado.');
    });
}

// Función para asignar eventos a los botones de acción
function asignarEventosBotonesAccion() {
    // Botón "Aprobar"
    document.querySelectorAll('.aprobar-button').forEach(button => {
        button.addEventListener('click', function () {
            const visitaId = this.getAttribute('data-id');
            actualizarEstadoVisita(visitaId, 'aprobada');
        });
    });

    // Botón "Concluir"
    document.querySelectorAll('.concluir-button').forEach(button => {
        button.addEventListener('click', function () {
            const visitaId = this.getAttribute('data-id');
            actualizarEstadoVisita(visitaId, 'concluida');
        });
    });

    // Botón "Cancelar"
    document.querySelectorAll('.cancelar-button').forEach(button => {
        button.addEventListener('click', function () {
            const visitaId = this.getAttribute('data-id');
            actualizarEstadoVisita(visitaId, 'cancelada');
        });
    });
}

document.getElementById('notificaciones-button').addEventListener('click', function () {
    document.getElementById('notificaciones-view').style.display = 'block';
    cargarSolicitudesVisitas(); // Cargar las solicitudes al mostrar la vista
});

});