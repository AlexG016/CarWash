var tblturnos = $('#tblturnos').DataTable({
    "ajax": "consultas/pedidos.php?listaTurnos=true",
    "columns": [  
        {
            "data": null,
            render: function (data, type, row) {
                if(data['domicilio']=="SI"){
                    return '<button type="button" class="btn btn-primary" onclick="abrirUbicacion(\'' + data['latitud'] + '\',\'' + data['longitud'] + '\')"><i class="fa fa-map"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarPedido('+ data['id'] +')"><i class="fa fa-trash"></i></button>';
                } else {
                    return '<button type="button" class="btn btn-danger" onclick="eliminarPedido('+ data['id'] +')"><i class="fa fa-trash"></i></button>';
                }                
            },
            "targets": -1
        },      
        { "data": "id", visible: false },
        { "data": "cedula" },
        { "data": "cliente" },
        { "data": "producto" },
        { "data": "fecha" },        
        { "data": "total" },
        { "data": "estado" },
        { "data": "latitud", visible: false },
        { "data": "longitud", visible: false },
        { "data": "domicilio" },
        { "data": "horaIni" },
        { "data": "horaFin" },
        { "data": "empleado" }
    ],
    "order": [[1, "desc"]],
    responsive: true,
    "language": {
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
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

async function eliminarPedido(id){
    Swal.fire({
        title: 'Esta seguro',
        text: "que desea eliminar el turno del Cliente?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.value) {
            await $.ajax({
                url: "consultas/pedidos.php?eliminarPedido=true",
                data: { idPedido: id },
                type: "POST",
                success: function (data) {
                    if (data == "exito") {
                        mensajeExito("Exito", "El Turno ha sido eliminado correctamente")
                        $('#tblturnos').DataTable().ajax.reload()                        
                    } else {
                        mensajeError("Error", data)
                    }
                },
            });
        }
    })
}

function abrirUbicacion(latitud, longitud) {
    if(latitud!=null && longitud!=null){
        var urlMAPS = "https://maps.google.com/?q=" + latitud + ","+ longitud
        window.open(urlMAPS, '_blank')
    } else {
        mensajeError("La ubicación del Cliente", "No está disponible")
    }
}