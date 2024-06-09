document.getElementById('formulario-nueva-contrasenia').addEventListener('input', validarFormulario);
document.getElementById('formulario-nueva-contrasenia').addEventListener('submit', function(event) {
    if (!validarFormulario()) {
        event.preventDefault();
    }
});

function validarFormulario() {
    const contrasenia = document.getElementById('contrasenia');
    const repetirContrasenia = document.getElementById('confirmar_contrasenia');
    const mensajeError = document.getElementById('mensaje-error-registro');

    let errores = [];

    // Resetea los campos
    mensajeError.innerHTML = '';
    mensajeError.style.display = 'none';
    contrasenia.classList.remove('campo-invalido');
    repetirContrasenia.classList.remove('campo-invalido');
    
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