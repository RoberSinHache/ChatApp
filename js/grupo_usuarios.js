document.addEventListener('DOMContentLoaded', () => {
    const seccionUsuariosGrupo = document.getElementById('seccion-usuarios-grupo');
    const listaUsuariosGrupo = document.querySelector('.lista-usuarios-grupo');
    const botonAgregarUsuarioGrupo = document.getElementById('boton-agregar-usuario-grupo');
    const agregarUsuarioGrupoModal = document.getElementById('agregar-usuario-grupo-modal');
    const formAgregarUsuarioGrupo = document.getElementById('form-agregar-usuario-grupo');
    const cerrarAgregarUsuarioGrupoModal = document.getElementById('cerrar-agregar-usuario-grupo-modal');
    const entradaChat = document.querySelector('.entrada-chat');
    const seccionChatsGrupos = document.querySelector('.seccion-chats-grupos');
    const mensajeError = document.getElementById('mensaje-error');
    const campoUsuarioAgregar = document.getElementById('nombre-usuario-agregar-grupo');
    const ventanaChat = document.getElementById('ventana-chat');

    // Función para cargar los usuarios del grupo
    function cargarUsuariosGrupo(idGrupo) {
        fetch(`obtener_usuarios_grupo.php?id_grupo=${idGrupo}`)
            .then(response => response.json())
            .then(data => {
                listaUsuariosGrupo.innerHTML = '';

                if (data.status === 'ok' && data.usuarios.length > 0) {
                    data.usuarios.forEach(usuario => {
                        const usuarioElemento = document.createElement('div');

                        if (usuario.id === data.idActual) {
                            const botonAbandonar = document.createElement('button');
                            botonAbandonar.textContent = 'Abandonar grupo';
                            botonAbandonar.id = 'abandonar-grupo-btn';
                            botonAbandonar.addEventListener('click', () => {
                                eliminarUsuarioDelGrupo(idGrupo, usuario.id, true);
                                setTimeout(function() {
                                    location.reload();
                                }, 200);
                            });
                            usuarioElemento.appendChild(botonAbandonar);

                        } else if (data.es_admin) {
                            usuarioElemento.className = 'usuario-grupo';
                            usuarioElemento.innerHTML = `
                                <img src="${usuario.imagen_perfil}" alt="${usuario.nombre}" class="icono-usuario">
                                <span>${usuario.nombre}</span>
                            `;

                            const botonEliminar = document.createElement('button');
                            botonEliminar.textContent = 'Eliminar';
                            botonEliminar.addEventListener('click', () => {
                                eliminarUsuarioDelGrupo(idGrupo, usuario.id);
                            });
                            usuarioElemento.appendChild(botonEliminar);
                        }

                        listaUsuariosGrupo.appendChild(usuarioElemento);
                    });
    
                    if (data.es_admin) {
                        botonAgregarUsuarioGrupo.style.display = 'block';

                    } else {
                        botonAgregarUsuarioGrupo.style.display = 'none';
                    }
    
                } else {
                    listaUsuariosGrupo.textContent = 'No hay usuarios en este grupo.';
                }
            })
            .catch(error => {
                console.error('Error al cargar los usuarios del grupo:', error);
            });
        }
    

    // Función para eliminar un usuario del grupo
    function eliminarUsuarioDelGrupo(idGrupo, idUsuario, abandono = false) {
        fetch(`eliminar_usuario_grupo.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_grupo: idGrupo, id_usuario: idUsuario, abandono: abandono })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                cargarUsuariosGrupo(idGrupo);
            } else {
                console.error('Error al eliminar el usuario del grupo:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar el usuario del grupo:', error);
        });
    }

    // Evento para abrir el modal de agregar usuario al grupo
    botonAgregarUsuarioGrupo.addEventListener('click', () => {
        agregarUsuarioGrupoModal.style.display = 'block';
    });

    // Evento para cerrar el modal de agregar usuario al grupo
    cerrarAgregarUsuarioGrupoModal.addEventListener('click', () => {
        agregarUsuarioGrupoModal.style.display = 'none';
        campoUsuarioAgregar.value = '';
    });

    // Evento para agregar un usuario al grupo
    formAgregarUsuarioGrupo.addEventListener('submit', (event) => {
        event.preventDefault();
        const nombreUsuario = document.getElementById('nombre-usuario-agregar-grupo').value;
        const idGrupo = document.querySelector('.seccion-grupos .grupo-seleccionado').dataset.idGrupo;
        agregarUsuarioAlGrupo(idGrupo, nombreUsuario);
    });

    // Función para agregar un usuario al grupo
    function agregarUsuarioAlGrupo(idGrupo, nombreUsuario) {
        fetch('agregar_usuario_grupo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_grupo: idGrupo, nombre_usuario: nombreUsuario })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                cargarUsuariosGrupo(idGrupo);
                agregarUsuarioGrupoModal.style.display = 'none';
            } else {
                console.error('Error al agregar el usuario al grupo:', data.message);
                mensajeError.style.display = 'block';
                mensajeError.textContent = 'El usuario no existe';
                setTimeout(() => {
                    mensajeError.style.display = 'none';
                }, 4000);
            }
        })
        .catch(error => {
            console.error('Error al agregar el usuario al grupo:', error);
            mensajeError.style.display = 'block';
            mensajeError.textContent = 'El usuario no existe';
            setTimeout(() => {
                mensajeError.style.display = 'none';
            }, 4000);
        });
    }

    // Espero un poco para intentar cargar los usuarios ya que tienen que cargarse primero los grupos
    setTimeout(() => {
        const grupos = document.querySelectorAll('.grupo');
        grupos.forEach(grupo => {
            grupo.addEventListener('click', () => {

                grupos.forEach(g => g.classList.remove('grupo-seleccionado'));
                grupo.classList.add('grupo-seleccionado');

                entradaChat.style.width = '66%';
                entradaChat.style.left = '49.5%';
                seccionChatsGrupos.style.width = '15%';

                const idGrupo = grupo.dataset.idGrupo;
                cargarUsuariosGrupo(idGrupo);

                seccionUsuariosGrupo.style.display = 'block';
            });
        });
    }, 400); 
    

    // Ocultar la sección de usuarios del grupo cuando se selecciona un usuario para chatear
    setTimeout(() => {
        const usuarios = document.querySelectorAll('.seccion-usuarios .usuario');
        usuarios.forEach(usuario => {
            usuario.addEventListener('click', () => {
                seccionUsuariosGrupo.style.display = 'none';
                entradaChat.style.width = '76.5%';
                entradaChat.style.left = '60%';
                seccionChatsGrupos.style.width = '20%';
            });
        });
    }, 400); 

});


