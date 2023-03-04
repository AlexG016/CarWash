<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

//Consulta para el crear un nuevo Usuario
if (isset($_POST['crearUsuario'])) {
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

//Consulta para listar los Usuarios
if (isset($_GET['listarUsuarios'])) {
    $consulta = mysqli_query($enlace, "SELECT
	persona.id, 
	persona.idUsuario, 
    rol.id as idRol,
	persona.nombres, 
	persona.apellidos, 
	persona.telefono, 
	persona.celular, 
	persona.direccion, 
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
    WHERE usuario.idRol!=3");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para editar un Usuario
if (isset($_GET['editarUsuario'])) {
    $consulta = mysqli_query($enlace, "UPDATE persona SET nombres='".$_POST['nombres']."', apellidos='".$_POST['apellidos']."', direccion='".$_POST['direccion']."', telefono='".$_POST['telefono']."', celular='".$_POST['celular']."' WHERE idUsuario='".$_POST['idUsuario']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }     
}

//Consulta para eliminar un Usuario
if (isset($_GET['eliminarUsuario'])) {
    $consulta = mysqli_query($enlace, "DELETE FROM persona WHERE idUsuario='".$_POST['idUsuario']."'");
    $consulta = mysqli_query($enlace, "DELETE FROM usuario WHERE id='".$_POST['idUsuario']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }   
}

//Consulta para editar un Usuario
if (isset($_POST['actualizarPerfil'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM usuario WHERE usuario='" . $_POST['cedula'] . "'");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $idUsuario = $row['id'];
    }
    $consulta = mysqli_query($enlace, "UPDATE persona SET nombres='".$_POST['nombres']."', apellidos='".$_POST['apellidos']."', direccion='".$_POST['direccion']."', telefono='".$_POST['telefono']."', celular='".$_POST['celular']."' WHERE idUsuario='".$idUsuario."'");
    $consulta = mysqli_query($enlace, "UPDATE usuario SET clave='".$_POST['clave']."' WHERE id='".$idUsuario."'");
    
    echo "exito";
        
}

//Consulta para llenar select de empleados
if (isset($_GET['listaEmpleados'])) {
    $datos = "<option value='0'>Escoge una opcion</option>";
    $consulta = mysqli_query($enlace, "SELECT
	CONCAT_WS(' ',persona.nombres,persona.apellidos) as empleado, 
	usuario.id
    FROM
	usuario
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario WHERE usuario.idRol=2");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos = $datos . "<option value=".$row['id'].">".$row['empleado']."</option>";        
    }
    echo $datos;          
}



