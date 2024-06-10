
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
 * y una vez guardado vacía el campo de mensaje y el archivo para que no se quede ahí lo que ya se ha 
 * enviado y llama a la función cargarMensajes (Que está en el archivo cargar_informacion.js
 * al que se llama antes en el home.php) para que se actualize la interfaz y se vea el mensaje nuevo. 
 * Al final baja hasta abajo de la conversación para que se pueda ver el último mensaje.
 * 
 * @param {Event} e : El evento que llama a esta función 
 */
function enviarMensaje(e) {
    e.preventDefault();
    var datosEnvio = new FormData(document.getElementById('datos-envio-form'));
    console.log(datosEnvio);

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

                cargarMensajes(destinatario, tipo);
                vaciarMensaje();
                
            }
        });

        var ventanaChat = document.getElementById('contenido-chat');
        setTimeout(() => {
            ventanaChat.scrollTo({ top: ventanaChat.scrollHeight, behavior: 'smooth' });
        }, 500);
    }
}

function vaciarMensaje() {
    var contenido = document.getElementById('contenido');
    document.getElementById('tipo_contenido').value = 'texto';
    document.getElementById('archivo').value = '';

    contenido.value = '';
}