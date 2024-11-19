document.getElementById("registroPersonaForm").addEventListener("submit", function(event) {
    var cedula = document.getElementById("cedula").value;
    var telefono = document.getElementById("telefono").value;
    var correo = document.getElementById("correo").value;
    var primerNombre = document.getElementById("primer_nombre").value;
    var primerApellido = document.getElementById("primer_apellido").value;
    var ciudad = document.getElementById("ciudad").value;
    var rol = document.getElementById("rol").value;

    // Validación de cédula (debe tener al menos 8 dígitos)
    var cedulaRegex = /^\d{8,}$/;
    if (!cedulaRegex.test(cedula)) {
        alert("La cédula debe tener al menos 8 dígitos.");
        event.preventDefault();
        return false;
    }

    // Validación de campos obligatorios: Primer nombre, primer apellido, ciudad, rol y correo
    if (!primerNombre || !primerApellido || !ciudad || !rol || !correo) {
        alert("Por favor, complete todos los campos obligatorios.");
        event.preventDefault();
        return false;
    }

    // Validación de teléfono (opcional, pero si se ingresa, debe ser numérico)
    if (telefono && !/^\d{7,10}$/.test(telefono)) {
        alert("El teléfono debe ser un número entre 7 y 10 dígitos.");
        event.preventDefault();
        return false;
    }

    // Validación de correo electrónico
    var correoRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!correoRegex.test(correo)) {
        alert("Por favor, ingresa un correo electrónico válido.");
        event.preventDefault();
        return false;
    }

    return true;
});



