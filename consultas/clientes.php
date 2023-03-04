<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

//Consulta para el crear un nuevo Cliente
if (isset($_POST['crearCliente'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM usuario WHERE usuario='" . $_POST['cedula'] . "'");
    if (mysqli_num_rows($consulta) == 0) {
        $consulta = mysqli_query($enlace, "INSERT INTO usuario(usuario,clave,idRol,estado) VALUES ('" . $_POST['cedula'] . "','" . $_POST['clave'] . "','" . $_POST['rol'] . "', 'ACTIVO')");
        if (mysqli_affected_rows($enlace) == "1") {
            $idUsuario = mysqli_insert_id($enlace);
            $consulta = mysqli_query($enlace, "INSERT INTO persona(idUsuario,nombres,apellidos,telefono,celular,direccion) VALUES ('" . $idUsuario . "','" . $_POST['nombres'] . "','" . $_POST['apellidos'] . "','" . $_POST['telefono'] . "','" . $_POST['celular'] . "','" . $_POST['direccion'] . "')");
            if (mysqli_affected_rows($enlace) == "1") {
                echo "exito";
            } else {
                echo mysqli_error($enlace);
            }
        } else {
            echo mysqli_error($enlace);
        }
    } else {
        echo "registrado";
    }
}

//Consulta para listar los Clientes
if (isset($_GET['listarClientes'])) {
    $consulta = mysqli_query($enlace, "SELECT
	persona.id, 
	persona.idUsuario, 
    rol.id as idRol,
	persona.nombres, 
	persona.apellidos, 
	persona.telefono, 
	persona.celular, 
	persona.direccion, 
    persona.latitud,
    persona.longitud,
	usuario.usuario as cedula, 
	rol.rol,
    usuario.clave
    FROM
	persona
	INNER JOIN
	usuario
	ON 
	persona.idUsuario = usuario.id
	INNER JOIN
	rol
	ON 
	usuario.idRol = rol.id
    WHERE usuario.idRol=3");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para editar un Cliente
if (isset($_GET['editarCliente'])) {
    $consulta = mysqli_query($enlace, "UPDATE persona SET nombres='".$_POST['nombres']."', apellidos='".$_POST['apellidos']."', direccion='".$_POST['direccion']."', telefono='".$_POST['telefono']."', celular='".$_POST['celular']."' WHERE idUsuario='".$_POST['idUsuario']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }     
}

//Consulta para eliminar un Cliente
if (isset($_GET['eliminarCliente'])) {
    $consulta = mysqli_query($enlace, "DELETE FROM persona WHERE idUsuario='".$_POST['idUsuario']."'");
    $consulta = mysqli_query($enlace, "DELETE FROM usuario WHERE id='".$_POST['idUsuario']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }   
}