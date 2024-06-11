
/**
 * Se asigna la función a los botones de crear o agregar usuario para que 
 * "abran" el sus respectivos modales, y sus botones para cerrarlos.
 */

document.getElementById('boton-agregar-usuario').addEventListener('click', function() {
    document.getElementById('agregar-usuario-modal').style.display = 'block';
});

document.getElementById('cerrar-agregar-usuario-modal').addEventListener('click', function() {
    document.getElementById('agregar-usuario-modal').style.display = 'none';
});

document.getElementById('boton-crear-grupo').addEventListener('click', function() {
    document.getElementById('crear-grupo-modal').style.display = 'block';
});

document.getElementById('cerrar-crear-grupo-modal').addEventListener('click', function() {
    document.getElementById('crear-grupo-modal').style.display = 'none';
});


/**
 * Script que se ejecuta cuando se envía el formulario del modal para agregar un
 * usuario. Llama al php que gestiona esta adición enviándole a través de POST el
 * nombre del usuario al que se quiere agregar
 */
document.getElementById('form-agregar-usuario').addEventListener('submit', function(event) {
    event.preventDefault();
    const nombreAgregar = document.getElementById('nombre-agregar-usuario').value;
    fetch('agregar_usuario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ nombre: nombreAgregar })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Ocurrió un error al intentar agregar al usuario');
        }
        return response.json();
    })
    .then(response => {
        if (response.status === 'success') {
            document.getElementById('agregar-usuario-modal').style.display = 'none';
            cargarConversacionesYGrupos();
        } else {

        }
    })
    .catch(error => {
        console.error('No se pudo agregar al usuario', error);
    });
});

/**
 * Llama al php crear_grupo que gestiona la creación del grupo que se indica en el modal 
 * pasándole a través de POST el array datosFormulario, que contiene el nombre del grupo
 * y la imagen que tendrá el mismo
 */
document.getElementById('formulario-crear-grupo').addEventListener('submit', function(event) {
    console.log('buenas')
    event.preventDefault();
    const datosFormulario = new FormData(document.getElementById('formulario-crear-grupo'));
    console.log('estos son los datos: ' + datosFormulario);
    fetch('crear_grupo.php', {
        method: 'POST',
        body: datosFormulario
    })
    .then(response => response.json())
    .then(datos => {
        console.log(datos);
        if (datos.status === 'success') {
            document.getElementById('crear-grupo-modal').style.display = 'none';
            cargarConversacionesYGrupos();
        } else {

        }
    });
});