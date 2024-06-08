
document.addEventListener('DOMContentLoaded', function() {
    
    cargarConversacionesYGrupos();
    document.getElementById('datos-envio-form').addEventListener('submit', enviarMensaje);

});

function cargarConversacionesYGrupos() {
    fetch('cargar_informacion.php')
        .then(response => response.json())
        .then(datos => {
            mostrarListaChats(datos);
        });
}

function enviarMensaje(e) {
    e.preventDefault();
    const datosEnvio = new FormData(document.getElementById('datos-envio-form'));
    console.log(datosEnvio.get('contenido').trim());
    console.log(datosEnvio.get('archivo').size);

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
    } else{
        console.log("No se envia");
    }
}

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

document.getElementById('formulario-crear-grupo').addEventListener('submit', function(event) {
    event.preventDefault();
    const datosFormulario = new FormData(document.getElementById('formulario-crear-grupo'));
    fetch('crear_grupo.php', {
        method: 'POST',
        body: datosFormulario
    })
    .then(response => response.json())
    .then(datos => {
        if (datos.status === 'success') {
            document.getElementById('crear-grupo-modal').style.display = 'none';
            cargarConversacionesYGrupos();
        } else {

        }
    });
});


/**
 * Esta funci
 * 
 * 
 * @param {array} datos : Array con usuarios y grupos. Dentro
 * de esos arrays se encuentran los datos de cada usuario y grupo con los
 * que el usuario ha hablado, respectivamente
 */
function mostrarListaChats(datos) {
    const seccionUsuarios = document.querySelector('.seccion-usuarios');
    const seccionGrupos = document.querySelector('.seccion-grupos');
    seccionUsuarios.innerHTML = '';
    seccionGrupos.innerHTML = '';

    datos.usuarios.forEach(usuario => {
        const elementoUsuario = document.createElement('div');
        elementoUsuario.textContent = usuario.nombre;
        elementoUsuario.addEventListener('click', () => cargarMensajes(usuario.id, 'usuario'));
        seccionUsuarios.appendChild(elementoUsuario);
    });

    datos.grupos.forEach(grupo => {
        const elementoGrupo = document.createElement('div');
        elementoGrupo.textContent = grupo.nombre;
        elementoGrupo.addEventListener('click', () => cargarMensajes(grupo.id, 'grupo'));
        seccionGrupos.appendChild(elementoGrupo);
    });
}

const campoIdDestinatario = document.getElementById('id_destinatario');
function cargarMensajes(destinatario, tipo) {
    fetch(`cargar_conversacion.php?destinatario=${destinatario}&tipo=${tipo}`)
        .then(response => response.json())
        .then(mensajes => {
            campoIdDestinatario.value = destinatario;
            mostrarMensajes(mensajes);
        })
        .catch(error => console.error('Ocurrió un error cargando los mensajes', error)
    );
}

function mostrarMensajes(mensajes) {
    document.querySelector('.entrada-chat').style.display = 'flex';
    const contenidoChat = document.querySelector('.contenido-chat');
    contenidoChat.innerHTML = '';      

    mensajes.forEach(mensaje => {
        const elementoMensaje = document.createElement('div');
        elementoMensaje.className = 'mensaje';

        const informacionMensaje = document.createElement('div');
        informacionMensaje.className = 'info';
        informacionMensaje.innerHTML = `
            <span class="nombre-remitente">${mensaje.nombre_remitente}</span>
            <span class="fecha-envio">${mensaje.fecha_envio}</span>
            <div class="contenido-mensaje">${mensaje.contenido}</div>
        `;


        const contenidoMensaje = document.createElement('div');
        contenidoMensaje.className = 'contenido';
        contenidoMensaje.textContent = mensaje.content;

        if (mensaje.tipo_contenido === 'imagen') {
            const elementoImagen = document.createElement('img');
            elementoImagen.src = mensaje.ruta_archivo;
            contenidoMensaje.appendChild(elementoImagen);

        } else if (mensaje.tipo_contenido === 'video') {
            const elementoVideo = document.createElement('video');
            elementoVideo.src = mensaje.ruta_archivo;
            elementoVideo.controls = true;
            contenidoMensaje.appendChild(elementoVideo);

        } else if (mensaje.tipo_contenido === 'archivo') {
            const elementoArchivo = document.createElement('a');
            elementoArchivo.href = mensaje.ruta_archivo;
            elementoArchivo.textContent = `Download ${mensaje.nombre_archivo}`;
            contenidoMensaje.appendChild(elementoArchivo);
        }

        elementoMensaje.appendChild(informacionMensaje);
        elementoMensaje.appendChild(contenidoMensaje);
        contenidoChat.appendChild(elementoMensaje);
    });
}

