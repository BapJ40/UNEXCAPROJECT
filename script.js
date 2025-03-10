document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const newVisitButton = document.getElementById('new-visit-button');
    const visitorRegistrationView = document.getElementById('visitor-registration-view');
    const visitorsListView = document.getElementById('visitors-list-view');
    const visitorsTableBody = document.querySelector('.pending-visits-table tbody');
    let backToListButton; // Declarar backToListButton aquí para que esté en el scope correcto

    menuToggle.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('sidebar-collapsed');
    });

    // Función para mostrar la vista de la lista de visitantes
    function showVisitorsListView() {
        visitorRegistrationView.style.display = 'none';
        visitorsListView.style.display = 'block';
    }

    // Función para abrir el modal y cargar los detalles de la visita y el visitante
    function verDetalleVisitante(visitaId) {
        // Hacer una solicitud AJAX para obtener los detalles de la visita y el visitante
        fetch(`./api/obtener_detalle_visita.php?id=${visitaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error); // Mostrar error si lo hay
                } else {
                    // Llenar el modal con la información de la visita y el visitante
                    const contenido = `
                        <h3>Información de la Visita</h3>
                        <p><strong>Motivo:</strong> ${data.motivo_visita}</p>
                        <p><strong>Fecha:</strong> ${data.fecha_visita}</p>
                        <p><strong>Departamento/Persona:</strong> ${data.departamento_o_persona}</p>

                        <h3>Información del Visitante</h3>
                        <p><strong>Nombre:</strong> ${data.nombre_visitante}</p>
                        <p><strong>Apellido:</strong> ${data.apellido_visitante}</p>
                        <p><strong>Cédula:</strong> ${data.cedula_identidad}</p>
                        <p><strong>Teléfono:</strong> ${data.telefono_visitante}</p>
                        <p><strong>Dirección:</strong> ${data.direccion_visitante}</p>
                    `;
                    document.getElementById('detalleVisitanteContent').innerHTML = contenido;

                    // Mostrar el modal
                    document.getElementById('modalDetalleVisitante').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error al obtener los detalles de la visita:', error);
                alert('Error al cargar los detalles de la visita.');
            });
    }

    // Función para cargar los datos de visitas
    function cargarDatosVisitas() {
        fetch('./api/obtener_datos.php')
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data); // Mensaje de depuración
                mostrarDatosVisitas(data);
                // Asignar eventos a los botones "Ver Detalle" después de cargar los datos
                asignarEventosVerDetalle();
            })
            .catch(error => {
                console.error(`Error al cargar los datos de visitas: ${error}`);
            });
    }

    // Función para mostrar los datos de visitas en la tabla
    function mostrarDatosVisitas(datos) {
        visitorsTableBody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos
        datos.forEach(visita => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${visita.nombre_visitante} ${visita.apellido_visitante}</td>
                <td>${visita.cedula_identidad}</td>
                <td>${visita.motivo_visita}</td>
                <td>${visita.fecha_hora_cita}</td>
                <td>${visita.departamento_o_persona}</td>
                <td>${visita.nombre_estado_visita}</td>
                <td><button class="action-button view-button" data-id="${visita.visita_id}">Ver Detalle</button></td>
            `;
            visitorsTableBody.appendChild(row);
        });
    }

    // Función para asignar eventos a los botones "Ver Detalle"
    function asignarEventosVerDetalle() {
        document.querySelectorAll('.view-button').forEach(button => {
            button.addEventListener('click', function () {
                const visitaId = this.getAttribute('data-id');
                console.log("ID de la visita:", visitaId); // Depuración
                verDetalleVisitante(visitaId);
            });
        });
    }

    // Cargar los datos de visitas al cargar la página
    cargarDatosVisitas();

    newVisitButton.addEventListener('click', function () {
        // Lógica para el botón "Nueva Visita"
    });

    // Mostrar la vista de la lista de visitantes al cargar la página (vista por defecto)
    showVisitorsListView();

    // Cerrar el modal cuando se hace clic en la "X"
    document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('modalDetalleVisitante').style.display = 'none';
    });

    // Cerrar el modal cuando se hace clic fuera de él
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('modalDetalleVisitante');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});