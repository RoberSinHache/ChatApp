
/**
 * Script que se ejecuta cuando se cambia el estado del archivo a enviar, es decir
 * cuando el usuario selecciona un archivo para enviar o cuando ya lo ha hecho pero 
 * lo cambia clicando el icono y seleccionando otro archivo de nuevo.
 * Detecta si el archivo es una imágen o vídeo. En caso de no ser ninguno de los dos
 * se vacia el campo del archivo.
 * 
 * @param {Event} event : Evento que ha llamado a la función
 */
document.getElementById('archivo').addEventListener('change', function(event) {
    var archivo = event.target.files[0];

    if (archivo.type.startsWith('image/')) {
        document.getElementById('tipo_contenido').value = 'imagen'; 

    } else if (archivo.type.startsWith('video/')) {
        document.getElementById('tipo_contenido').value = 'video'; 
        
    } else {
        document.getElementById('tipo_contenido').value = 'texto'; 
        document.getElementById('archivo').value = '';

    }
});