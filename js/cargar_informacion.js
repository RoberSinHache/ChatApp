// Nada mas cargar el home se llama a la función cargarConversacionesYGrupos
// para que se muesteren los usuarios y gurpos disponibles 
document.addEventListener('DOMContentLoaded', function() {
    cargarConversacionesYGrupos();

});

/**
 * Llama al php cargar_informacion que devuelve un 
 * json con todos los usuarios y grupos que tiene agregados
 * el usuario
 */
function cargarConversacionesYGrupos() {
    fetch('cargar_informacion.php')
        .then(response => response.json())
        .then(datos => {
            mostrarListaChats(datos);
        });
}

/**
 * Esta función muestra todos los usuarios y grupos disponibles 
 * para el usuario obetiéndolos del array datos que recibe como argumento
 * 
 * @param {Array} datos : Array con usuarios y grupos. Dentro
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

/**
 * Llama a la función php a través de get para traer un json 
 * con todos los mensajes que existen en el chat que ha clicado el usuario
 * desde el menú izquierdo, ya sea un grupo o usuario.
 * No los hace visibles, solo los trae, para mostrarlos llama a la función
 * mostrarMensajes con todos los mensajes y sus datos
 * 
 * @param {Integer} destinatario : El id del usuario o grupo que se ha clicado 
 * @param {String} tipo : Puede ser 'usuario' o 'grupo'
 */
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

/**
 * Muestra los mensajes que le vienen por argumento creando 
 * elementos html por cada mensaje en la conversación.
 * Es el responsable de mostrar las imágenes y vídeos
 * 
 * @param {Array} mensajes : Todos los mensajes y sus respectivos datos
 * de la conversación que el usuario ha elegido para abrir 
 */
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

    setTimeout(() => {
        var ventanaChat = document.getElementById('contenido-chat');
        ventanaChat.scrollTo({ top: ventanaChat.scrollHeight, behavior: 'smooth' });
    }, 500);
}







