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
        const imagenUsuario = document.createElement('img');
        imagenUsuario.src = usuario.imagen_perfil;
        imagenUsuario.alt = `Imagen de perfil de ${usuario.nombre}`;
        imagenUsuario.classList.add('imagen-usuario'); 
        elementoUsuario.appendChild(imagenUsuario);
        
        const nombreUsuario = document.createElement('span');
        nombreUsuario.textContent = usuario.nombre;
        elementoUsuario.appendChild(nombreUsuario);
        
        elementoUsuario.addEventListener('click', () => cargarMensajes(usuario.id, 'usuario', usuario));
        seccionUsuarios.appendChild(elementoUsuario);
    });

    datos.grupos.forEach(grupo => {
        const elementoGrupo = document.createElement('div');
        const iconoGrupo = document.createElement('img');
        iconoGrupo.src = grupo.icono;
        iconoGrupo.alt = `Icono de ${grupo.nombre}`;
        iconoGrupo.classList.add('icono-grupo'); 
        elementoGrupo.appendChild(iconoGrupo);
        
        const nombreGrupo = document.createElement('span');
        nombreGrupo.textContent = grupo.nombre;
        elementoGrupo.appendChild(nombreGrupo);
        
        elementoGrupo.addEventListener('click', () => cargarMensajes(grupo.id, 'grupo', grupo));
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
var campoIdDestinatario = document.getElementById('id_destinatario');
var campoIdGrupo = document.getElementById('id_grupo');

function cargarMensajes(destinatario, tipo, info) {
    console.log(destinatario);
    console.log(tipo);

    fetch(`cargar_conversacion.php?destinatario=${destinatario}&tipo=${tipo}`)
        .then(response => response.json())
        .then(mensajes => {
            console.log(mensajes);
            if (tipo === 'usuario') {
                campoIdDestinatario.value = destinatario;
                campoIdGrupo.value = ''; 

            } else {
                campoIdGrupo.value = destinatario;
                campoIdDestinatario.value = ''; 
            }

            if (info) {
                mostrarEncabezado(tipo, info);                
            }

            mostrarMensajes(mensajes);
        })
        .catch(error => console.error('Ocurrió un error cargando los mensajes', error)
    );
}

function mostrarEncabezado(tipo, info) {
    const encabezadoChat = document.getElementById('encabezado-chat');
    encabezadoChat.innerHTML = '';

    if (tipo === 'usuario') {
        const imagenUsuario = document.createElement('img');
        imagenUsuario.src = info.imagen_perfil;
        imagenUsuario.alt = `Imagen de perfil de ${info.nombre}`;
        imagenUsuario.classList.add('imagen-usuario'); 
        
        const nombreUsuario = document.createElement('span');
        nombreUsuario.textContent = info.nombre;

        encabezadoChat.appendChild(imagenUsuario);
        encabezadoChat.appendChild(nombreUsuario);

    } else{
        const iconoGrupo = document.createElement('img');
        iconoGrupo.src = info.icono;
        iconoGrupo.alt = `Icono de ${info.nombre}`;
        iconoGrupo.classList.add('icono-grupo'); 
        
        const nombreGrupo = document.createElement('span');
        nombreGrupo.textContent = info.nombre;

        encabezadoChat.appendChild(iconoGrupo);
        encabezadoChat.appendChild(nombreGrupo);
    }
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
        `;

        const contenidoMensaje = document.createElement('div');
        contenidoMensaje.className = 'contenido';

        // Agregar el contenido textual del mensaje
        const textoMensaje = document.createElement('div');
        textoMensaje.className = 'contenido-mensaje';
        textoMensaje.textContent = mensaje.contenido;
        contenidoMensaje.appendChild(textoMensaje);

        // Agregar el archivo si existe
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
            const contenedorArchivo = document.createElement('div');
            contenedorArchivo.className = 'contenedor-archivo-descargable';
            
            const nombreArchivo = document.createElement('p');
            nombreArchivo.textContent = mensaje.nombre_archivo;
            contenedorArchivo.appendChild(nombreArchivo);

            const imgArchivo = document.createElement('img');
            imgArchivo.src = 'subidos/iconos/icono-carpeta.png';
            imgArchivo.alt = 'Descargar';
            contenedorArchivo.appendChild(imgArchivo);

            const elementoArchivo = document.createElement('a');
            elementoArchivo.href = mensaje.ruta_archivo;
            elementoArchivo.textContent = `Descargar`;
            elementoArchivo.setAttribute('download', ''); // Esto hace que se descargue.
            contenedorArchivo.appendChild(elementoArchivo);

            contenidoMensaje.appendChild(contenedorArchivo);

        }

        elementoMensaje.appendChild(informacionMensaje);
        elementoMensaje.appendChild(contenidoMensaje);
        contenidoChat.appendChild(elementoMensaje);
    });

    // Scrolleo la ventana hasta abajo para que se vea el último mensaje
    setTimeout(() => {
        var ventanaChat = document.getElementById('contenido-chat');
        ventanaChat.scrollTo({ top: ventanaChat.scrollHeight, behavior: 'smooth' });
    }, 500);
}










