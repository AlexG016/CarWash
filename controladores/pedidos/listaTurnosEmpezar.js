var idPedido = 0

var tblturnos = $('#tblturnos').DataTable({
    "ajax": "consultas/pedidos.php?listaTurnosGenerados=true",
    "columns": [    
        {
            "data": null,
            render: function (data, type, row) {
                return '<button type="button" class="btn btn-success" onclick="comenzarTurno(' + data['id'] + ')"><i class="fa fa-play"></i></button>';
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

async function llenarSelect(){
    await $.ajax({
        url: "consultas/usuarios.php?listaEmpleados=true",
        data: {},
        type: "POST",
        success: function (data) {
            var selectEmpleados = document.getElementById('selectEmpleado')
            selectEmpleados.innerHTML = data                       
        },
    });
} 

llenarSelect()

function comenzarTurno(id) {
    idPedido = id
    $('#modalEmpleado').modal('show')
} 

async function asignarEmpleado(){
    var idEmpleado = document.getElementById('selectEmpleado').value
    if(idEmpleado == 0 || idEmpleado == null){
        mensajeError('Error', 'Debe seleccionar un empleado para continuar')
    } else {
        await $.ajax({
            url: "consultas/pedidos.php?comenzarPedido=true",
            data: { idPedido: idPedido, idEmpleado: idEmpleado },
            type: "POST",
            success: function (data) {
                if (data == "exito") {
                    mensajeExito("Exito", "El empleado ha sido asignado con éxito")
                    $('#tblturnos').DataTable().ajax.reload()     
                    $('#modalEmpleado').modal('hide')         
                } else {
                    mensajeError("Error", data)
                }
            },
        });
    }    
}