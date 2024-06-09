document.getElementById('formulario-registro').addEventListener('input', validarFormulario);
document.getElementById('formulario-registro').addEventListener('submit', function(event) {
    if (!validarFormulario()) {
        event.preventDefault();
    }
});

function validarFormulario() {
    const nombre = document.getElementById('nombre');
    const email = document.getElementById('email');
    const contrasenia = document.getElementById('contrasenia');
    const repetirContrasenia = document.getElementById('repetir_contrasenia');
    const mensajeError = document.getElementById('mensaje-error-registro');

    let errores = [];

    // Resetea los campos
    mensajeError.innerHTML = '';
    mensajeError.style.display = 'none';
    nombre.classList.remove('campo-invalido');
    email.classList.remove('campo-invalido');
    contrasenia.classList.remove('campo-invalido');
    repetirContrasenia.classList.remove('campo-invalido');

    // Valida el email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        errores.push('El correo electrónico no es válido.');
        email.classList.add('campo-invalido');
    }

    // Valida la contraseña
    const contraseniaPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
    if (!contraseniaPattern.test(contrasenia.value)) {
        errores.push('La contraseña debe tener al menos 8 caracteres, incluyendo una letra y un número.');
        contrasenia.classList.add('campo-invalido');
    }

    // Valida que las contraseñas coinciden
    if (contrasenia.value !== repetirContrasenia.value) {
        errores.push('Las contraseñas no coinciden.');
        repetirContrasenia.classList.add('campo-invalido');
    }

    // Musetra mensajes de error si hay
    if (errores.length > 0) {
        mensajeError.innerHTML = errores.join('<br>');
        mensajeError.style.display = 'block';
        return false;
    }

    return true;
}

// Musetra errores de sesión si existen
window.addEventListener('load', function() {
    const mensajeError = document.getElementById('mensaje-error-registro');
    if (mensajeError.innerHTML.trim() !== '') {
        mensajeError.style.display = 'block';
    }
});