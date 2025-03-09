document.getElementById('login-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar envío del formulario

    const nombreUsuario = document.getElementById('nombre_usuario').value;
    const contraseña = document.getElementById('contraseña').value;
    const errorMessage = document.getElementById('error-message');

    // Validación básica
    if (!nombreUsuario || !contraseña) {
        errorMessage.textContent = "Por favor, complete todos los campos.";
        return;
    }

    // Enviar datos al servidor
    fetch('login.php', {
        method: 'POST',
        body: new FormData(document.getElementById('login-form')),
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("incorrecta") || data.includes("encontrado")) {
            errorMessage.textContent = data;
        } else {
            window.location.href = "dashboard.php"; // Redirigir al dashboard
        }
    })
    .catch(error => {
        errorMessage.textContent = "Error al conectar con el servidor.";
    });
});