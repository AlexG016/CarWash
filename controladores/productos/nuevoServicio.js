var imagen = "-"

async function guardarServicio(){
    var nombre = document.getElementById("nombreServicio").value
    var descripcion = document.getElementById("descripcionServicio").value
    var precioCosto = document.getElementById("precioCosto").value
    var pvp = document.getElementById("precioVenta").value  
    if(Number(precioCosto)>0 && Number(pvp)>0){
        await $.ajax({
            url: "./consultas/productos.php",
            data: { crearServicio: true, nombre: nombre, descripcion: descripcion, cantidad: 99999, precioCosto: precioCosto, pvp: pvp, imagen: imagen },
            type: "POST",
            success: function (data) {
                if(data == "exito"){
                    mensajeExito("Productos", "El servicio se ha guardado con éxito")
                    $('#limpiarServicio').click()
                    $('#imagen').removeClass('btn-success')
                } else {
                    console.log(data)
                }
            },
        });  
    } else {
        mensajeError("El precio de Costo y PVP", "No pueden ser menores a 0")
    }          
}

async function guardarImagen() {
    var formData = new FormData()
    var files = $('#imagen')[0].files[0]
    formData.append('file', files)
    await $.ajax({
        url: './consultas/subirImagen.php?subirImagen=true',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response != 0) {
                imagen = response
                $('#imagen').addClass('btn-success')
            } else {
                mensajeError("La imagen no se ha guardado", "Porfavor, inténtalo con una imagen de formato jpg, jpeg o png.")
            }
        }
    });
}