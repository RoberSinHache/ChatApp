
/* En el momento en el que carga el home, se asigna la función enviarMensaje de este archivo
al botón para enviar mensaje de la interfaz */
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('datos-envio-form').addEventListener('submit', enviarMensaje);
});

// LLama a la función enviar mensaje cuando el usuario pulsa enter sin el shift
document.getElementById('contenido').addEventListener('keypress', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        enviarMensaje(event);
    }
});

/**
 * Envía un mensaje.
 * Sólo lo hace en caso de que haya contenido, ya sea texto, algún archivo, o ambos.
 * Obitene el contenido del mensaje y los archivos del formulario, que es el apartado 
 * donde se escribe el mensaje en la interfaz y los mete en el array FormData.
 * Esos datos los manda al php que realiza el guardado del mensaje en la base de datos
 * y una vez guardado vacía el campo de mensaje y el archivo para que no se quede ahí lo que ya se ha 
 * enviado y llama a la función cargarMensajes (Que está en el archivo cargar_informacion.js
 * al que se llama antes en el home.php) para que se actualize la interfaz y se vea el mensaje nuevo. 
 * Al final baja hasta abajo de la conversación para que se pueda ver el último mensaje.
 * 
 */
function enviarMensaje(event) {
    event.preventDefault();
    var datosEnvio = new FormData(document.getElementById('datos-envio-form'));

    if (datosEnvio.get('contenido').trim() !== '' || datosEnvio.get('archivo').size > 0) {
        const archivo = datosEnvio.get('archivo');
        const tipo_contenido = document.getElementById('tipo_contenido');

        if (archivo.size > 0) {
            const mimeType = archivo.type;

            if (mimeType.startsWith('image/')) {
                tipo_contenido.value = 'imagen';

            } else if (mimeType.startsWith('video/')) {
                tipo_contenido.value = 'video';
                
            } else {
                tipo_contenido.value = 'archivo';
            }
        }

        console.log(tipo_contenido.value);

        fetch('envio_mensaje.php', {
            method: 'POST',
            body: datosEnvio
        })
        .then(response => response.json())
        .then(datos => {
            console.log(datos);
            if (datos.status === 'ok') {
                const destinatario = datosEnvio.get('id_destinatario') || datosEnvio.get('id_grupo');
                const tipo = datosEnvio.get('id_destinatario') ? 'usuario' : 'grupo';

                cargarMensajes(destinatario, tipo);
                vaciarMensaje();
                quitarPrevisualizacion();
            }
        })
        .catch(error => console.error('Error enviando el mensaje:', error));

        var ventanaChat = document.getElementById('contenido-chat');
        setTimeout(() => {
            ventanaChat.scrollTo({ top: ventanaChat.scrollHeight, behavior: 'smooth' });
        }, 500);
    }
}







/**
 * Vacía todo el contenido que puedise tener el apartado
 * para enviar un mensaje
 */
function vaciarMensaje() {
    document.getElementById('contenido').value = '';
    document.getElementById('tipo_contenido').value = 'texto';
    document.getElementById('archivo').value = '';
}

document.getElementById('archivo').addEventListener('change', function(event) {
    const archivo = event.target.files[0];
    if (archivo) {
        const vistaPrevia = document.getElementById('vista-previa');
        const nombreArchivo = document.getElementById('nombre-archivo');
        const previsualizacionArchivo = document.getElementById('previsualizacion-archivo');
        const tipoContenido = document.getElementById('tipo_contenido');

        nombreArchivo.textContent = archivo.name;

        vistaPrevia.innerHTML = '';

        const mimeType = archivo.type;
        if (mimeType.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(archivo);
            vistaPrevia.appendChild(img);
            tipoContenido.value = 'imagen';

        } else if (mimeType.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(archivo);
            video.controls = true;
            vistaPrevia.appendChild(video);
            tipoContenido.value = 'video';

        } else {
            const imgArchivoDescargable = document.createElement('img');
            imgArchivoDescargable.src = 'subidos/app/iconos/icono-carpeta.png';
            imgArchivoDescargable.alt = 'Archivo descargable';
            vistaPrevia.appendChild(imgArchivoDescargable);

            tipoContenido.value = 'archivo';
        }

        // Muestro el contenedor de previsualización
        previsualizacionArchivo.style.display = 'block';
    }
});

document.getElementById('eliminar-archivo').addEventListener('click', function() {
    quitarPrevisualizacion();
});

function quitarPrevisualizacion() {
    const archivoInput = document.getElementById('archivo');
    const previsualizacionArchivo = document.getElementById('previsualizacion-archivo');
    const vistaPrevia = document.getElementById('vista-previa');
    const nombreArchivo = document.getElementById('nombre-archivo');
    const tipoContenido = document.getElementById('tipo_contenido');

    // Limpio el input de archivo
    archivoInput.value = '';
    tipoContenido.value = 'texto';

    // Oculto y limpio el contenedor de previsualización
    previsualizacionArchivo.style.display = 'none';
    vistaPrevia.innerHTML = '';
    nombreArchivo.textContent = '';
}


