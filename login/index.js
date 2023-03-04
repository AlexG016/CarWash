document.oncontextmenu = function(){return false}

//Funcion para permitir unicamente números
function valideKey(evt) {
    var code = (evt.which) ? evt.which : evt.keyCode;
    if (code == 8) {
        return true;
    } else if (code >= 48 && code <= 57) {
        return true;
    } else {
        return false;
    }
}

async function iniciarSesion() {
    var cedula = document.getElementById("inputCedula").value
    var clave = document.getElementById("inputClave").value
    await $.ajax({
        url: "../consultas/auth.php?iniciarSesion=true",
        data: { cedula: cedula, clave: clave },
        type: "POST",
        success: function (data) {
            if (data == "exito") {
                location.href = '../index.html'
            } else if (data == "mal") {
                const Toast = Swal.mixin({
                    position: 'center',
                    showConfirmButton: false,
                    timer: 4000
                })
                Toast.fire({
                    type: 'error',
                    title: 'Seguridad',
                    text: 'Las credenciales ingresadas son incorrectas'
                });
                document.getElementById("inputCedula").value = ""
                document.getElementById("inputClave").value = ""
            } else{
                console.log(data)
            }
        }
    });
}

function recuperarPassword(){
    const Toast = Swal.mixin({
        position: 'center',
        showConfirmButton: false,
        timer: 4000
    })
    Toast.fire({
        type: 'error',
        title: 'Seguridad',
        text: 'Módulo en mantenimiento'
    });
}