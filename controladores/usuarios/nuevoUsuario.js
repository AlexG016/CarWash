async function guardarUsuario(){
    var nombres = document.getElementById("nombreUs").value
    var apellido = document.getElementById("apellidoUs").value
    var telefono = document.getElementById("telefonoUs").value
    var cedula = document.getElementById("cedulaUs").value
    var celular = document.getElementById("celularUs").value
    var direccion = document.getElementById("direccionUs").value
    var rol = document.getElementById("rolUs").value
    var clave = document.getElementById("claveUs").value
    if(validarCedula(cedula)){
        await $.ajax({
            url: "./consultas/usuarios.php",
            data: { crearUsuario: true, nombres: nombres, apellidos: apellido, telefono: telefono, cedula: cedula, celular: celular, direccion: direccion, rol: rol, clave: clave },
            type: "POST",
            success: function (data) {
                if(data == "exito"){
                    mensajeExito("Usuario ingresado exitosamente", "Recuerde la contraseña del Usuario es: " + clave)
                    $('#limpiarUs').click()
                } else if(data == "registrado"){
                    mensajeError("El número de cédula o RUC ingresado ya existe", "Por favor inténtelo con una cédula o RUC distinto")
                    document.getElementById("cedulaUs").value = ""
                } else {
                    console.log(data)
                }
            },
        });
    }else{
        mensajeError("El número de cédula o RUC ingresado es incorrecto", "Por favor ingrese una cédula o RUC válido")
        document.getElementById("cedulaUs").value = ""
    }    
}