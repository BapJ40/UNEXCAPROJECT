document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const newVisitButton = document.getElementById('new-visit-button');
    const visitorRegistrationView = document.getElementById('visitor-registration-view');
    const visitorsListView = document.getElementById('visitors-list-view');
    const visitorsTableBody = document.querySelector('.pending-visits-table tbody');
    let backToListButton; // Declarar backToListButton aquí para que esté en el scope correcto

    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('sidebar-collapsed');
    });


    // Función para mostrar la vista de la lista de visitantes
    function showVisitorsListView() {
        visitorRegistrationView.style.display = 'none';
        visitorsListView.style.display = 'block';
    }

    // Función para cargar los datos de visitas
    function cargarDatosVisitas() {
        fetch('./api/obtener_datos.php')
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data); // Mensaje de depuración
                mostrarDatosVisitas(data);
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
                <td>${visita.nombre_visitante} ${visita.apellido_visitante} </td>
                <td>${visita.cedula_identidad}</td>
                <td>${visita.motivo_visita}</td>
                <td>${visita.fecha_hora_cita}</td>
                <td>${visita.departamento_o_persona}</td>
                <td>${visita.nombre_estado_visita}</td>
                <td><button class="action-button view-button">Ver Detalle</button></td>
            `;
            visitorsTableBody.appendChild(row);
        });
    }

    // Cargar los datos de visitas al cargar la página
    cargarDatosVisitas();

    newVisitButton.addEventListener('click', function() {
        
    });

    // Mostrar la vista de la lista de visitantes al cargar la página (vista por defecto)
    showVisitorsListView();

});