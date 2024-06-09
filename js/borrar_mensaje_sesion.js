
/*
    Borra en 3 segundos y con una animación el mensaje
    que viene por sesión y se muesetra en cualquier elemento
    que tenga como id "mensaje_sesion"
*/
setTimeout(function() {
    var mensaje = document.getElementById('mensaje_sesion');
    if (mensaje) {
        mensaje.classList.add('ocultar');
    }
}, 3000);    
