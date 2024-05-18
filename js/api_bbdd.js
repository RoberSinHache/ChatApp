document.addEventListener('DOMContentLoaded', function() {
    
    cargarConversacionesYGrupos();

    document.getElementById('datos-envio-form').addEventListener('submit', enviarMensaje);

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
                throw new Error('La respuesta del servidor fue erronea');
            }
            return response.json();
        })
        .then(datos => {
            console.log(datos);
            if (datos.status === 'success') {
                cargarConversacionesYGrupos();
                document.getElementById('agregar-usuario-modal').style.display = 'none';
            } else {
                alert(datos.mensaje);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('An error occurred while adding the user. Please try again.' + error);
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
                mostrarListaChats();
                document.getElementById('crear-grupo-modal').style.display = 'none';
            } else {
                alert(datos.mensaje);
            }
        });
    });

    function cargarConversacionesYGrupos() {
        fetch('cargar_informacion.php')
            .then(response => response.json())
            .then(datos => {
                mostrarListaChats(datos);
            });
    }

    function mostrarListaChats(datos) {
        const seccionChats = document.querySelector('.seccion-chats-grupos');
        seccionChats.innerHTML = '';

        datos.usuarios.forEach(usuario => {
            const elementoUsuario = document.createElement('div');
            elementoUsuario.textContent = usuario.nombre;
            elementoUsuario.addEventListener('click', () => cargarMensajes(usuario.id, 'usuario'));
            seccionChats.appendChild(elementoUsuario);
        });

        datos.grupos.forEach(grupo => {
            const elementoGrupo = document.createElement('div');
            elementoGrupo.textContent = grupo.nombre;
            elementoGrupo.addEventListener('click', () => cargarMensajes(grupo.id, 'grupo'));
            seccionChats.appendChild(elementoGrupo);
        });

        const botonAgregarUsuario = document.createElement('button');
        botonAgregarUsuario.textContent = 'Agregar Usuario';
        botonAgregarUsuario.id = 'boton-agregar-usuario';
        botonAgregarUsuario.addEventListener('click', function() {
            document.getElementById('agregar-usuario-modal').style.display = 'block';
        });
        seccionChats.appendChild(botonAgregarUsuario);

        const botonCrearGrupo = document.createElement('button');
        botonCrearGrupo.textContent = 'Crear Grupo';
        botonCrearGrupo.id = 'boton-crear-grupo';
        botonCrearGrupo.addEventListener('click', function() {
            document.getElementById('crear-grupo-modal').style.display = 'block';
        });
        seccionChats.appendChild(botonCrearGrupo);
    }

    function cargarMensajes(destinatario, tipo) {
        fetch(`cargar_conversacion.php?destinatario=${destinatario}&tipo=${tipo}`)
            .then(response => response.json())
            .then(mensajes => {
                mostrarMensajes(mensajes);
            });
    }

    function mostrarMensajes(mensajes) {
        const ventanaChat = document.querySelector('.ventana-chat');
        ventanaChat.innerHTML = '';

        mensajes.forEach(mensaje => {
            const elementoMensaje = document.createElement('div');
            elementoMensaje.className = 'mensaje';

            const informacionMensaje = document.createElement('div');
            informacionMensaje.className = 'info';
            informacionMensaje.textContent = `${mensaje.nombre_remitente} (${mensaje.fecha_envio}): ${mensaje.contenido}`;

            const contenidoMensaje = document.createElement('div');
            contenidoMensaje.className = 'contenido';

            if (mensaje.tipo_contenido === 'texto') {
                contenidoMensaje.textContent = mensaje.content;

            } else if (mensaje.tipo_contenido === 'imagen') {
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
            ventanaChat.appendChild(elementoMensaje);
        });
    }

    function enviarMensaje(e) {
        e.preventDefault();
        const datosEnvio = new FormData(document.getElementById('datos-envio-form'));
        console.log(datosEnvio);

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
            }
        });
    }
});
