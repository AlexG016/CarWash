<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

if (isset($_GET['subirImagen'])) {
    if (is_array($_FILES) && count($_FILES) > 0) {
        if (($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/png")
            || ($_FILES["file"]["type"] == "image/gif")) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], "../dist/img/".$_FILES['file']['name'])) {
                //more code here...
                echo $_FILES['file']['name'];
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}

//Consulta para actualizar imagen de un producto
if (isset($_GET['actualizarProducto'])) {
    if (is_array($_FILES) && count($_FILES) > 0) {
        if (($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/png")
            || ($_FILES["file"]["type"] == "image/gif")) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], "../dist/img/".$_FILES['file']['name'])) {
                $consulta = mysqli_query($enlace, "UPDATE producto SET imagen='".$_FILES['file']['name']."' WHERE id='".$_GET['idProducto']."'");
                if (mysqli_affected_rows($enlace) == "1") {
                    echo $_FILES['file']['name'];
                }else{
                    echo mysqli_error($enlace);
                }
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}