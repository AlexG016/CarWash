var idProducto = 0
var tblinventario = $('#tblinventario').DataTable({
    "ajax": "consultas/productos.php?inventario=true",
    "columns": [
        {
            "data": null,
            render: function (data, type, row) {
                return '<button type="button" class="btn btn-warning" onclick="llenarProducto(\'' + data['id'] + '\',\'' + data['producto'] + '\',\'' + data['descripcion'] + '\',\'' + data['cantidad'] + '\',\'' + data['precioCosto'] + '\',\'' + data['pvp'] + '\',\'' + data['imagen'] + '\',\'' + data['tipo'] + '\')"><i class="fa fa-pen"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarProducto(' + data['id'] + ')"><i class="fa fa-trash"></i></button>';
            },
            "targets": -1
        },
        { "data": "id", visible: false },
        { "data": "producto" },
        { "data": "descripcion" },
        { "data": "cantidad" },
        { "data": "precioCosto" },
        { "data": "pvp" },
        { "data": "imagen", visible: false },
        { "data": "tipo", visible: false },
        {
            "data": null,
            render: function (data, type, row) {
                if(data['imagen'] != "-"){
                    return '<img src="dist/img/' + data['imagen'] + '" height="120px" width="120px">';
                }else{
                    return '<img src="dist/img/iconoFondo.png" height="120px" width="120px">';
                }                
            },
            "targets": -1
        }
    ],
    "order": [[2, "desc"]],
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

function llenarProducto(id, producto, descripcion, cantidad, precioCosto, pvp, imagen, tipo){ 
    idProducto = id
    document.getElementById("nombreProducto").value = producto
    document.getElementById("descripcionProducto").value = descripcion
    document.getElementById("cantidadProducto").value = cantidad
    if(tipo == 1){
        document.getElementById("cantidadProducto").disabled = false
    }else{
        document.getElementById("cantidadProducto").disabled = true
    }    
    document.getElementById("precioCosto").value = precioCosto
    document.getElementById("precioVenta").value = pvp
    if(imagen != "-"){
        document.getElementById("imagenProducto").src = "dist/img/" + imagen
    }else{
        document.getElementById("imagenProducto").src = "dist/img/iconoFondo.png"
    }    
    $('#modalProducto').modal("show")
}

function editarProducto(){
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
            var nombre = document.getElementById("nombreProducto").value
            var descripcion = document.getElementById("descripcionProducto").value
            var cantidad = document.getElementById("cantidadProducto").value
            var precioCosto = document.getElementById("precioCosto").value
            var pvp = document.getElementById("precioVenta").value  
            await $.ajax({
                url: "consultas/productos.php?editarProducto=true",
                data: { idProducto: idProducto, nombre: nombre, descripcion: descripcion, cantidad: cantidad, precioCosto: precioCosto, pvp: pvp },
                type: "POST",
                success: function (data) {
                    if (data == "exito") {
                        mensajeExito("Exito", "El producto ha sido actualizado correctamente")
                        $('#tblinventario').DataTable().ajax.reload()   
                        $('#modalProducto').modal('hide')
                    } else {
                        mensajeError("Error", data)
                    }
                },
            });
        } else {
            $('#modalProducto').modal('hide')
        }
    })
}

function eliminarProducto(id){
    Swal.fire({
        title: 'Esta seguro',
        text: "que desea eliminar el producto o servicio?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.value) {
            await $.ajax({
                url: "consultas/productos.php?eliminarProducto=true",
                data: { idProducto: id },
                type: "POST",
                success: function (data) {
                    if (data == "exito") {
                        mensajeExito("Exito", "El producto ha sido eliminado correctamente")
                        $('#tblinventario').DataTable().ajax.reload()                        
                    } else {
                        mensajeError("Error", data)
                    }
                },
            });
        }
    })
}

async function guardarImagen() {
    var formData = new FormData()
    var files = $('#imagen')[0].files[0]
    formData.append('file', files)
    await $.ajax({
        url: './consultas/subirImagen.php?actualizarProducto=true&idProducto=' + idProducto,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response != 0) {
                $('#tblinventario').DataTable().ajax.reload()  
                document.getElementById("imagenProducto").src = "dist/img/" + response
                document.getElementById("imagen").value = "" 
                mensajeExito("Productos", "Imagen modificada con éxito")
            } else {
                console.log(response)
            }
        }
    });
}