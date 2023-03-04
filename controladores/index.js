cargarUsuario()
//document.oncontextmenu = function(){return false}

$.fn.dataTable.ext.errMode = 'none'

//Cerrar Sesion de usuario
function cerrarSesion() {
    Swal.fire({
        title: 'Está seguro',
        text: "que desea salir del sistema",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, salir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "./consultas/auth.php",
                data: { cerrarSesion: true },
                type: "POST",
                success: function () {
                    window.location = "login/index.html"
                },
            });
        }
    })
}

//Cargar datos del usuario que inicio sesion
function cargarUsuario() {
    $.ajax({
        url: "./consultas/auth.php",
        data: { cargarUsuario: true },
        type: "POST",
        success: function (data) {
            if (data == "mal") {
                window.location = "login/index.html"
            } else if (data == "sindatos") {
                window.location = "login/index.html"
            } else {
                var datos = JSON.parse(data)
                document.getElementById('nombreUsuarioPrincipal').innerHTML = datos[0].nombres + " " + datos[0].apellidos
                document.getElementById('rolUsuarioPrincipal').innerHTML = datos[0].rol
                comprobarClave(datos[0].clave)
                if(datos[0].rol == "ADMINISTRADOR"){
                    $('#menuTurnosAsignados').addClass('hidden')
                    $('#menuUsuarios').removeClass('hidden')
                    $('#menuClientes').removeClass('hidden')
                    $('#menuProductos').removeClass('hidden')
                    $('#menuTurnos').removeClass('hidden')
                    $('#menuReportes').removeClass('hidden')
                } else if(datos[0].rol == "EMPLEADO"){
                    $('#menuTurnosAsignados').removeClass('hidden')
                    $('#menuUsuarios').addClass('hidden')
                    $('#menuClientes').addClass('hidden')
                    $('#menuProductos').removeClass('hidden')
                    $('#menuTurnos').addClass('hidden')
                    $('#menuReportes').addClass('hidden')
                } else if(datos[0].rol == "CLIENTE") {
                    window.location = "login/index.html"
                }
            }
        },
    });
}

function comprobarClave(clave){
    if(clave == "carwash123"){
        Swal.fire({
            type: 'warning',
            title: 'Seguridad',
            text: 'Por su seguridad le sugerimos que cambie su contraseña por defecto desde el perfil de usuario'
          })
    }
}

//Cargar menu en el contenedor principal
function cargarVista(menu) {
    $('#contenedor').load('vistas/' + menu)
}

//Mostrar un mensaje de exito
function mensajeExito(msj, msj1) {
    const Toast = Swal.mixin({
        //toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 4000
    })
    Toast.fire({
        type: 'success',
        title: msj,
        text: msj1
    });
}

//Mostrar un mensaje de error
function mensajeError(msj, msj1) {
    const Toast = Swal.mixin({
        //toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 4000
    })
    Toast.fire({
        type: 'error',
        title: msj,
        text: msj1
    });
}

//Funcion para validar una cedula o RUC
function validarCedula(cedula) {

    //Preguntamos si la cedula consta de 10 digitos
    if (cedula.length == 10) {

        //Obtenemos el digito de la region que sonlos dos primeros digitos
        var digito_region = cedula.substring(0, 2);

        //Pregunto si la region existe ecuador se divide en 24 regiones
        if (digito_region >= 1 && digito_region <= 24) {

            // Extraigo el ultimo digito
            var ultimo_digito = cedula.substring(9, 10);

            //Agrupo todos los pares y los sumo
            var pares = parseInt(cedula.substring(1, 2)) + parseInt(cedula.substring(3, 4)) + parseInt(cedula.substring(5, 6)) + parseInt(cedula.substring(7, 8));

            //Agrupo los impares, los multiplico por un factor de 2, si la resultante es > que 9 le restamos el 9 a la resultante
            var numero1 = cedula.substring(0, 1);
            var numero1 = (numero1 * 2);
            if (numero1 > 9) {
                var numero1 = (numero1 - 9);
            }

            var numero3 = cedula.substring(2, 3);
            var numero3 = (numero3 * 2);
            if (numero3 > 9) {
                var numero3 = (numero3 - 9);
            }

            var numero5 = cedula.substring(4, 5);
            var numero5 = (numero5 * 2);
            if (numero5 > 9) {
                var numero5 = (numero5 - 9);
            }

            var numero7 = cedula.substring(6, 7);
            var numero7 = (numero7 * 2);
            if (numero7 > 9) {
                var numero7 = (numero7 - 9);
            }

            var numero9 = cedula.substring(8, 9);
            var numero9 = (numero9 * 2);
            if (numero9 > 9) {
                var numero9 = (numero9 - 9);
            }

            var impares = numero1 + numero3 + numero5 + numero7 + numero9;

            //Suma total
            var suma_total = (pares + impares);

            //extraemos el primero digito
            var primer_digito_suma = String(suma_total).substring(0, 1);

            //Obtenemos la decena inmediata
            var decena = (parseInt(primer_digito_suma) + 1) * 10;

            //Obtenemos la resta de la decena inmediata - la suma_total esto nos da el digito validador
            var digito_validador = decena - suma_total;

            //Si el digito validador es = a 10 toma el valor de 0
            if (digito_validador == 10)
                var digito_validador = 0;

            //Validamos que el digito validador sea igual al de la cedula
            if (digito_validador == ultimo_digito) {
                return true;
            } else {
                return false;
            }

        } else {
            // imprimimos en consola si la region no pertenece
            return false;
        }
    } else if (cedula.length == 13) {

        //Obtenemos el digito de la region que sonlos dos primeros digitos
        var digito_region = cedula.substring(0, 2);

        //Pregunto si la region existe ecuador se divide en 24 regiones
        if (digito_region >= 1 && digito_region <= 24) {

            // Extraigo el ultimo digito
            var ultimo_digito = cedula.substring(9, 10);

            //Agrupo todos los pares y los sumo
            var pares = parseInt(cedula.substring(1, 2)) + parseInt(cedula.substring(3, 4)) + parseInt(cedula.substring(5, 6)) + parseInt(cedula.substring(7, 8));

            //Agrupo los impares, los multiplico por un factor de 2, si la resultante es > que 9 le restamos el 9 a la resultante
            var numero1 = cedula.substring(0, 1);
            var numero1 = (numero1 * 2);
            if (numero1 > 9) {
                var numero1 = (numero1 - 9);
            }

            var numero3 = cedula.substring(2, 3);
            var numero3 = (numero3 * 2);
            if (numero3 > 9) {
                var numero3 = (numero3 - 9);
            }

            var numero5 = cedula.substring(4, 5);
            var numero5 = (numero5 * 2);
            if (numero5 > 9) {
                var numero5 = (numero5 - 9);
            }

            var numero7 = cedula.substring(6, 7);
            var numero7 = (numero7 * 2);
            if (numero7 > 9) {
                var numero7 = (numero7 - 9);
            }

            var numero9 = cedula.substring(8, 9);
            var numero9 = (numero9 * 2);
            if (numero9 > 9) {
                var numero9 = (numero9 - 9);
            }

            var impares = numero1 + numero3 + numero5 + numero7 + numero9;

            //Suma total
            var suma_total = (pares + impares);

            //extraemos el primero digito
            var primer_digito_suma = String(suma_total).substring(0, 1);

            //Obtenemos la decena inmediata
            var decena = (parseInt(primer_digito_suma) + 1) * 10;

            //Obtenemos la resta de la decena inmediata - la suma_total esto nos da el digito validador
            var digito_validador = decena - suma_total;

            //Si el digito validador es = a 10 toma el valor de 0
            if (digito_validador == 10)
                var digito_validador = 0;

            //Validamos que el digito validador sea igual al de la cedula
            if (digito_validador == ultimo_digito) {
                return true;
            } else {
                return false;
            }

        } else {
            // imprimimos en consola si la region no pertenece
            return false;
        }
    } else {
        //imprimimos en consola si la cedula tiene mas o menos de 10 digitos
        return false;
    }
}

function soloLetras(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
    especiales = [8, 37, 39, 46];

    tecla_especial = false
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if(letras.indexOf(tecla) == -1 && !tecla_especial)
        return false;
}

function soloNumeros(evt){    
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}