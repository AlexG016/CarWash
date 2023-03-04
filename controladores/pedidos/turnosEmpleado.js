var tblturnos = $('#tblturnos').DataTable({
    "ajax": "consultas/pedidos.php?listaTurnosProcesoEmpleado=true",
    "columns": [    
        {
            "data": null,
            render: function (data, type, row) {
                return '<button type="button" class="btn btn-danger" onclick="finalizarTurno(' + data['id'] + ')"><i class="fa fa-check"></i></button>';
            },
            "targets": -1
        },  
        { "data": "id", visible: false },
        { "data": "cedula" },
        { "data": "cliente" },
        { "data": "producto" },
        { "data": "fecha" },
        { "data": "hora" },
        { "data": "total" },
        { "data": "estado" }        
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

function finalizarTurno(id) {
    Swal.fire({
        title: 'Desea finalizar el pedido',
        text: "solicitado por el cliente?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Finalizarlo',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.value) {
            await $.ajax({
                url: "consultas/pedidos.php?finalizarPedido=true",
                data: { idPedido: id },
                type: "POST",
                success: function (data) {
                    if (data == "exito") {
                        mensajeExito("Exito", "El pedido ha finalizado con éxito")
                        $('#tblturnos').DataTable().ajax.reload()                        
                    } else {
                        mensajeError("Error", data)
                    }
                },
            });
        }
    })
} 