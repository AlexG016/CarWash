<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

//Consulta para el crear un nuevo Producto
if (isset($_POST['crearProducto'])) {
    $consulta = mysqli_query($enlace, "INSERT INTO producto(producto,descripcion,cantidad,precioCosto,pvp,estado,imagen,tipo) VALUES ('" . $_POST['nombre'] . "','" . $_POST['descripcion'] . "','" . $_POST['cantidad'] . "','" . $_POST['precioCosto'] . "','" . $_POST['pvp'] . "','ACTIVO','" . $_POST['imagen'] . "','1')");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    } else {
        echo mysqli_error($enlace);
    }
}

//Consulta para el crear un nuevo Servicio
if (isset($_POST['crearServicio'])) {
    $consulta = mysqli_query($enlace, "INSERT INTO producto(producto,descripcion,cantidad,precioCosto,pvp,estado,imagen,tipo) VALUES ('" . $_POST['nombre'] . "','" . $_POST['descripcion'] . "','" . $_POST['cantidad'] . "','" . $_POST['precioCosto'] . "','" . $_POST['pvp'] . "','ACTIVO','" . $_POST['imagen'] . "','2')");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    } else {
        echo mysqli_error($enlace);
    }
}

//Consulta para listar el inventario de productos y servicios
if (isset($_GET['inventario'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM producto");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para editar un Producto o servicio
if (isset($_GET['editarProducto'])) {    
    $consulta = mysqli_query($enlace, "UPDATE producto SET producto='".$_POST['nombre']."', descripcion='".$_POST['descripcion']."', cantidad='".$_POST['cantidad']."', precioCosto='".$_POST['precioCosto']."', pvp='".$_POST['pvp']."' WHERE id='".$_POST['idProducto']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }     
}

//Consulta para eliminar un Producto o servicio
if (isset($_GET['eliminarProducto'])) {
    $consulta = mysqli_query($enlace, "DELETE FROM producto WHERE id='".$_POST['idProducto']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }   
}
