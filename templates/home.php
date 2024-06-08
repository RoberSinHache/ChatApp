<div class="aplicacion-chat">
    <div class="seccion-chats-grupos">
        <div>
            <h3>Usuarios</h3>
            <div class="seccion-usuarios">
                <!-- Usuarios -->
            </div>
        </div>
        <div>
            <h3>Grupos</h3>
            <div class="seccion-grupos">
                <!-- Grupos -->
            </div>
        </div>
        <button id="boton-agregar-usuario">Agregar usuario</button>
        <button id="boton-crear-grupo">Crear grupo</button>
    </div>
    <div class="ventana-chat">
        <div class="encabezado-chat">
            <!-- Encabezado del chat -->
        </div>
        <div class="contenido-chat">
            <!-- Mensajes del chat -->
        </div>
        
        <div class="entrada-chat">
            <form id="datos-envio-form" enctype="multipart/form-data" action="envio_mensaje.php">
                <input type="hidden" name="id_destinatario" id="id_destinatario" value="">
                <input type="hidden" name="id_grupo" id="id_grupo" value="">
                <input type="text" name="tipo_contenido" id="tipo_contenido" value="texto">
                <textarea name="contenido" id="contenido" placeholder="Mensaje a enviar..."></textarea>
                <input type="file" name="archivo" id="archivo">
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>



    <div id="agregar-usuario-modal" class="modal">
        <div class="contenido-modal">
            <span class="boton-cerrar" id="cerrar-agregar-usuario-modal">&times;</span>
            <h2>Añadir usuario</h2>
            <form id="form-agregar-usuario">
                <label for="nombre-agregar-usuario">Nombre de usuario:</label>
                <input type="text" id="nombre-agregar-usuario" name="nombre-agregar-usuario" required>
                <button type="submit">Agregar</button>
            </form>
        </div>
    </div>

    <div id="crear-grupo-modal" class="modal">
        <div class="contenido-modal">
            <span class="boton-cerrar" id="cerrar-crear-grupo-modal">&times;</span>
            <h2>Nuevo grupo</h2>
            <form id="formulario-crear-grupo" enctype="multipart/form-data">
                <label for="nombre-grupo">Nombre:</label>
                <input type="text" id="nombre-grupo" name="nombre-grupo" required>
                <label for="icono-grupo">Icono:</label>
                <input type="file" id="icono-grupo" name="icono-grupo" accept="image/*" required>
                <button type="submit">Crear</button>
            </form>
        </div>
    </div>

    <form action="cerrar_sesion.php">
        <button type="submit">Cerrar sesión</button>
    </form>

    <script src="js/scripts.js"></script>
</div>
