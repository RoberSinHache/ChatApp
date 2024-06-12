<div class="aplicacion-chat">
    <div class="seccion-chats-grupos">
        <div class='contenedor-seccion-usuarios'>
            <h3>Usuarios</h3>
            <div class="seccion-usuarios">
                <!-- Usuarios -->
            </div>
        </div>
        <div class='contenedor-seccion-grupos'>
            <h3>Grupos</h3>
            <div class="seccion-grupos">
                <!-- Grupos -->
            </div>
        </div>
        <hr class="separador-izquierdo">

        <button id="boton-agregar-usuario">Agregar usuario</button>
        <button id="boton-crear-grupo">Crear grupo</button>

        <form action="cerrar_sesion.php">
            <button class="cerrar-sesion" type="submit">Cerrar sesión</button>
        </form>
    </div>
    <div class="ventana-chat">
        <div id="encabezado-chat" class="encabezado-chat">
            <!-- Encabezado del chat -->
        </div>
        <div id="contenido-chat" class="contenido-chat">
            <!-- Mensajes del chat -->
        </div>
        
        <div class="entrada-chat">
            <form id="datos-envio-form" enctype="multipart/form-data" action="envio_mensaje.php">
                <input type="hidden" name="id_destinatario" id="id_destinatario" value="">
                <input type="hidden" name="id_grupo" id="id_grupo" value="">
                <input type="hidden" name="tipo_contenido" id="tipo_contenido" value="texto">
                <textarea name="contenido" id="contenido" placeholder="Mensaje a enviar..."></textarea>
                <label for="archivo" id="archivo-label">
                    <span id="archivo-icono-imagen" style="cursor: pointer;"><i class="icono-imagen"></i></span>
                    <input type="file" id="archivo" name="archivo" accept="*/*"> 
                </label>
                <button type="submit" id="enviar-btn"><i class="icono-enviar"></i></button>
            </form>


            <div id="previsualizacion-archivo" style="display: none;">
                <p id="nombre-archivo"></p>
                <div id="vista-previa"></div>
                <button type="button" id="eliminar-archivo"></button>
            </div>
        </div>
    </div>

    <!-- Nueva sección para mostrar usuarios del grupo -->
    <div id="seccion-usuarios-grupo" class="seccion-usuarios-grupo" style="display: none;">
        <h3>Usuarios en el Grupo</h3>
        <div class="lista-usuarios-grupo">
            <!-- Lista de usuarios del grupo -->
        </div>
        <button id="boton-agregar-usuario-grupo" style="display: none;">Agregar usuario al grupo</button>
    </div>

    <!-- Modal para agregar usuario al grupo -->
    <div id="agregar-usuario-grupo-modal" class="modal">
        <div class="contenedor-formulario-previo" style='width: 100%; text-align: center;'>
            <div class="contenido-modal">
                <h1>Añadir usuario al grupo</h1>
                <form id="form-agregar-usuario-grupo" class='formulario-previo' style='align-items: center;'>
                    <button class="boton-cerrar-modal" id="cerrar-agregar-usuario-grupo-modal"></button>
                    <input type="text" id="nombre-usuario-agregar-grupo" name="nombre-usuario-agregar-grupo" placeholder='Nombre de usuario' required>
                    <input type="submit" value='Agregar'>
                </form>
                <div id="mensaje-error" style="color: red;"></div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar un usuario de manera individual -->
    <div id="agregar-usuario-modal" class="modal">
        <div class="contenedor-formulario-previo" style='width: 100%; text-align: center;'>
            <div class="contenido-modal">
                <h1>Añadir usuario</h1>
                <form id="form-agregar-usuario" class='formulario-previo' style='align-items: center;'>
                    <button class="boton-cerrar-modal" id="cerrar-agregar-usuario-modal"></button>
                    <input type="text" id="nombre-agregar-usuario" name="nombre-agregar-usuario" placeholder='Nombre de usuario' required>
                    <input type="submit" value='Agregar'>
                </form>
                <div id="mensaje-error-individual" style="color: red;"></div>
            </div>
        </div>
    </div>

    <!-- Modal para crear un grupo -->
    <div id="crear-grupo-modal" class="modal">
        <div class="contenedor-formulario-previo" style='width: 100%; text-align: center;'>
            <div class="contenido-modal">
                <h1>Nuevo grupo</h1>
                <form id="formulario-crear-grupo" class='formulario-previo' style='align-items: center;'>
                    <button class="boton-cerrar-modal" id="cerrar-crear-grupo-modal"></button>
                    <input type="text" id="nombre-grupo" name="nombre-grupo" placeholder='Nombre' required>
                    <label for="icono-grupo">Icono:</label>
                    <input type="file" id="icono-grupo" name="icono-grupo" accept="image/*">
                    <input type="submit" value="Crear">
                </form>
            </div>
        </div>
    </div>

    <script src="js/cargar_informacion.js"></script>
    <script src="js/envio_mensaje.js"></script>
    <script src="js/modales.js"></script>
    <script src="js/grupo_usuarios.js"></script>
</div>
