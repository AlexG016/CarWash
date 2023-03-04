<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

//Consulta para el inicio de Sesion
if (isset($_GET['iniciarSesion'])) {
    session_start();  
    $consulta = mysqli_query($enlace, "SELECT
	*,
    usuario.id as idUsuario
    FROM
	usuario
	INNER JOIN
	rol
	ON 
	usuario.idRol = rol.id  WHERE usuario.usuario='".$_POST['cedula']."' AND usuario.clave='".$_POST['clave']."'");
    if (mysqli_num_rows($consulta) > 0){
        while ($row = mysqli_fetch_assoc($consulta)) {            
            $_SESSION['cedula'] = $row['usuario'];
            $_SESSION['id'] = $row['idUsuario'];
            $_SESSION['clave'] = $row['clave']; 
            $_SESSION['rol'] = $row['rol'];               
            echo "exito";            
        }           
    }else{
        echo "mal";
    }    
}

//Consulta para cargar los datos del usuario que inicio sesion
if (isset($_POST['cargarUsuario'])) {
    session_start();
    if (isset($_SESSION['cedula'])) {
        $consulta = mysqli_query($enlace, "SELECT
        persona.nombres, 
        persona.apellidos, 
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
        usuario.idRol = rol.id WHERE usuario.usuario='" . $_SESSION['cedula'] . "'");
        if (mysqli_num_rows($consulta) > 0) {
            while ($row = mysqli_fetch_assoc($consulta)) {
                $data[] = $row;                
            }
            echo json_encode($data);
        } else {
            echo "sindatos";
        }
    } else {
        echo "mal";
    } 
}

//Consulta para eliminar las variables de sesion
if (isset($_POST['cerrarSesion'])) {
    session_start();
    session_destroy();
    echo "exito";
}

//Consulta para cargar los datos del usuario que inicio sesion
if (isset($_POST['cargarPerfil'])) {
    session_start();
    if (isset($_SESSION['cedula'])) {
        $consulta = mysqli_query($enlace, "SELECT
        persona.nombres, 
        persona.apellidos, 
        persona.celular,
        persona.direccion,
        persona.telefono,
        usuario.usuario,
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
        WHERE usuario.usuario='" . $_SESSION['cedula'] . "'");
        while ($row = mysqli_fetch_assoc($consulta)) {
            $data[] = $row;                
        }        
        echo json_encode($data);
    }

}