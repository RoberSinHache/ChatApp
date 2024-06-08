document.getElementById('archivo').addEventListener('change', function(event) {
    var archivo = event.target.files[0];

    if (archivo.type.startsWith('image/')) {
        // Si es una imagen, cambio el valor de tipo_contenido a 'imagen'
        document.getElementById('tipo_contenido').value = 'imagen'; 

    } else if (archivo.type.startsWith('video/')) {
        // Si es un video, cambio el valor de tipo_contenido a 'video'
        document.getElementById('tipo_contenido').value = 'video'; 
    } else {
        // Creo un nuevo input para sustituir al anterior
        var nuevoInput = document.createElement('input');
        nuevoInput.type = 'file';
        nuevoInput.id = 'archivo';
        nuevoInput.name = 'archivo';
        
        // Pillo el anterior y lo sustituyo
        var inputAnterior = document.getElementById('archivo');
        inputAnterior.parentNode.replaceChild(nuevoInput, inputAnterior);
    }
});