var idUsuario = 0
var tblusuarios = $('#tblusuarios').DataTable({
    "ajax": "consultas/usuarios.php?listarUsuarios=true",
    "columns": [
        {
            "data": null,
            render: function (data, type, row) {
                return '<button type="button" class="btn btn-warning" onclick="llenarUsuario(\'' + data['idUsuario'] + '\',\'' + data['nombres'] + '\',\'' + data['apellidos'] + '\',\'' + data['telefono'] + '\',\'' + data['celular'] + '\',\'' + data['direccion'] + '\',\'' + data['idRol'] + '\',\'' + data['cedula'] + '\')"><i class="fa fa-pen"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarUsuario(' + data['idUsuario'] + ')"><i class="fa fa-trash"></i></button>';
            },
            "targets": -1
        },
        { "data": "id", visible: false },
        { "data": "idUsuario", visible: false },
        { "data": "idRol", visible: false },
        { "data": "cedula" },
        { "data": "nombres" },
        { "data": "apellidos" },
        { "data": "telefono" },
        { "data": "celular" },
        { "data": "direccion" },
        { "data": "rol" },
        { "data": "clave", visible: false }
    ],
    "order": [[4, "desc"]],
    responsive: true,
    "language": {
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    }
});      

function llenarUsuario(id, nombres, apellidos, telefono, celular, direccion, rol, cedula){ 
    idUsuario = id   
    document.getElementById("nombreUs").value = nombres
    document.getElementById("apellidoUs").value = apellidos
    document.getElementById("telefonoUs").value = telefono
    document.getElementById("cedulaUs").value = cedula
    document.getElementById("celularUs").value = celular
    document.getElementById("direccionUs").value = direccion
    document.getElementById("rolUs").value = rol
    $('#modalUsuario').modal("show")
}

function editarUsuario(){
    Swal.fire({
        title: 'Esta seguro',
        text: "que desea guardar los cambios realizados?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, guardar',
        cancelButtonText: 'Cancelar los cambios'
    }).then(async (result) => {
        if (result.value) {
            var nombre = document.getElementById("nombreUs").value
            var apellido = document.getElementById("apellidoUs").value
            var telefono = document.getElementById("telefonoUs").value            
            var celular = document.getElementById("celularUs").value
            var direccion = document.getElementById("direccionUs").value
            var rol = document.getElementById("rolUs").value
            await $.ajax({
                url: "consultas/usuarios.php?editarUsuario=true",
                data: { idUsuario: idUsuario, nombres: nombre, apellidos: apellido, telefono: telefono, direccion: direccion, celular: celular, rol: rol },
                type: "POST",
                success: function (data) {
                    if (data == "exito") {
                        mensajeExito("Exito", "El usuario ha sido actualizado correctamente")
                        $('#tblusuarios').DataTable().ajax.reload()
                        $('#modalUsuario').modal('hide')
                    } else {
                        mensajeError("Error", data)
                    }
                },
            });
        } else {
            $('#modalUsuario').modal('hide')
        }
    })
}

function eliminarUsuario(id){
    Swal.fire({
        title: 'Esta seguro',
        text: "que desea eliminar el usuario?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.value) {
            await $.ajax({
                url: "consultas/usuarios.php?eliminarUsuario=true",
                data: { idUsuario: id },
                type: "POST",
                success: function (data) {
                    if (data == "exito" || data == "") {
                        mensajeExito("Exito", "El usuario ha sido eliminado correctamente")
                        $('#tblusuarios').DataTable().ajax.reload()                        
                    } else {
                        mensajeError("Error", data)
                    }
                },
            });
        }
    })
}