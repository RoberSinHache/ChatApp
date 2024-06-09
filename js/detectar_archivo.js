
/*
    Función que se ejecuta cuando se cambia l estado del archivo a enviar, es decir
    cuando el usuario selecciona un archivo para enviar o cuando ya lo ha hecho pero 
    lo cambia clicando el icono y seleccionando otro archivo de nuevo.

    Detecta si el archivo es una imágen o vídeo. En caso de no ser ninguno de los dos
    se elimina el input html que contiene el archivo por otro vació para que no se pueda
    enviar un archivo que no sea imagen o vídeo.
*/
document.getElementById('archivo').addEventListener('change', function(event) {
    var archivo = event.target.files[0];

    if (archivo.type.startsWith('image/')) {
        document.getElementById('tipo_contenido').value = 'imagen'; 

    } else if (archivo.type.startsWith('video/')) {
        document.getElementById('tipo_contenido').value = 'video'; 
        
    } else {
        var nuevoInput = document.createElement('input');
        nuevoInput.type = 'file';
        nuevoInput.id = 'archivo';
        nuevoInput.name = 'archivo';
        
        var inputAnterior = document.getElementById('archivo');
        inputAnterior.parentNode.replaceChild(nuevoInput, inputAnterior);
    }
});