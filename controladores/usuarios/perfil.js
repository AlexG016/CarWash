//Funcion para cargar el perfil del usuario
async function cargarPerfil() {
    await $.ajax({
        url: "./consultas/auth.php",
        data: {
            cargarPerfil: true
        },
        type: "POST",
        success: function (data) {
            var datos = JSON.parse(data)
            document.getElementById("nombrePerfil").value = datos[0].nombres
            document.getElementById("apellidoPerfil").value = datos[0].apellidos
            document.getElementById("telefonoPerfil").value = datos[0].telefono
            document.getElementById("cedulaPerfil").value = datos[0].usuario
            document.getElementById("celularPerfil").value = datos[0].celular
            document.getElementById("direccionPerfil").value = datos[0].direccion
            document.getElementById("clavePerfil").value = datos[0].clave
        },
    });
}

cargarPerfil()


async function guardarPerfil(){
    var nombres = document.getElementById("nombrePerfil").value
    var apellido = document.getElementById("apellidoPerfil").value
    var telefono = document.getElementById("telefonoPerfil").value
    var celular = document.getElementById("celularPerfil").value
    var direccion = document.getElementById("direccionPerfil").value
    var clave = document.getElementById("clavePerfil").value   
    var cedula = document.getElementById("cedulaPerfil").value
    await $.ajax({
        url: "./consultas/usuarios.php",
        data: { actualizarPerfil: true, nombres: nombres, apellidos: apellido, telefono: telefono, celular: celular, direccion: direccion, clave: clave, cedula: cedula },
        type: "POST",
        success: function (data) {
            if(data == "exito"){
                mensajeExito("Perfil actualizado", "Los cambios fueron guardados con éxito")
                $('#limpiarUs').click()
                cargarPerfil()
            } else {
                console.log(data)
            }
        },
    });
      
}
