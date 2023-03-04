var tblreportes = $('#tblreportes').DataTable({
    "ajax": "consultas/reportes.php?reporteClientes=true",
    "columns": [ 
        { "data": "id" },
        { "data": "nombres" },
        { "data": "apellidos" },
        { "data": "celular" },
        { "data": "facturado" },        
        { "data": "ultimoServicio" }
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
    },
    dom: 'Bfrtip',
    buttons: [
        'excel', 'pdf', 'copy', 'print'
    ]
}); 