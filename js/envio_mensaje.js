
/* En el momento en el que carga el home, se asigna la función enviarMensaje de este archivo
al botón para enviar mensaje de la interfaz */
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('datos-envio-form').addEventListener('submit', enviarMensaje);
});

/**
 * Envía un mensaje.
 * Sólo lo hace en caso de que haya contenido, ya sea texto, algún archivo, o ambos.
 * Obitene el contenido del mensaje y los archivos del formulario, que es el apartado 
 * donde se escribe el mensaje en la interfaz y los mete en el array FormData.
 * Esos datos los manda al php que realiza el guardado del mensaje en la base de datos
 * y una vez guardado vacía el campo de mensaje para que no se quede ahí lo que ya está 
 * enviado y llama a la función cargarMensajes (Que está en el archivo cargar_informacion.js
 * al que se llama antes en el home.php) para que se actualize la interfaz y se vea el mensaje nuevo. 
 * 
 * @param {Event} e : El evento que llama a esta función 
 */
function enviarMensaje(e) {
    e.preventDefault();
    const datosEnvio = new FormData(document.getElementById('datos-envio-form'));

    if (datosEnvio.get('contenido').trim() !== '' || datosEnvio.get('archivo').size > 0) {
        fetch('envio_mensaje.php', {
            method: 'POST',
            body: datosEnvio
        })
        .then(response => response.json())
        .then(datos => {
            if (datos.status === 'success') {
                const destinatario = datosEnvio.get('id_destinatario') || datosEnvio.get('id_grupo');
                const tipo = datosEnvio.get('id_destinatario') ? 'usuario' : 'grupo';
                document.getElementById('contenido').value = '';
                cargarMensajes(destinatario, tipo);
            }
        });
    }
}