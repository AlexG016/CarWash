<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

//Consulta para el inicio de Sesion desde la app
if (isset($_GET['login'])) {  
    $consulta = mysqli_query($enlace, "SELECT
	usuario.id,
	usuario.clave,
	usuario.usuario, 
	persona.email,
    persona.idAPI
    FROM
	usuario
	INNER JOIN
	rol
	ON 
	usuario.idRol = rol.id
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario  WHERE usuario.usuario='".$_GET['cedula']."' AND usuario.clave='".$_GET['clave']."'");
    if (mysqli_num_rows($consulta) > 0){
        while ($row = mysqli_fetch_assoc($consulta)) {           
            echo $row['idAPI'];            
        }           
    }else{
        echo "mal";
    }    
}

//Consulta para el crear un nuevo Cliente desde la app
if (isset($_GET['nuevoRegistro'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM usuario WHERE usuario='" . $_GET['cedula'] . "'");
    if (mysqli_num_rows($consulta) == 0) {
        $consulta = mysqli_query($enlace, "INSERT INTO usuario(usuario,clave,idRol,estado) VALUES ('" . $_GET['cedula'] . "','" . $_GET['clave'] . "','3', 'ACTIVO')");
        if (mysqli_affected_rows($enlace) == "1") {
            $idUsuario = mysqli_insert_id($enlace);
            $consulta = mysqli_query($enlace, "INSERT INTO persona(idUsuario,nombres,apellidos,telefono,celular,direccion,latitud,longitud,email) VALUES ('" . $idUsuario . "','" . $_GET['nombres'] . "','" . $_GET['apellidos'] . "','" . $_GET['telefono'] . "','" . $_GET['celular'] . "','" . $_GET['direccion'] . "','" . $_GET['latitud'] . "','" . $_GET['longitud'] . "','" . $_GET['email'] . "')");
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

//Consulta para los datos del usuario
if (isset($_GET['buscarUsuario'])) {
    $consulta = mysqli_query($enlace, "SELECT 
	persona.nombres, 
	persona.apellidos, 
	persona.telefono, 
	persona.celular, 
	persona.direccion
    FROM
	usuario
	INNER JOIN
	rol
	ON 
	usuario.idRol = rol.id
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario  WHERE usuario.usuario='".$_GET['cedula']."'");
    if (mysqli_num_rows($consulta) > 0){
        while ($row = mysqli_fetch_assoc($consulta)) {           
            $datos[] = $row;
        }   
        echo json_encode($datos);        
    }else{
        echo "mal";
    }    
}

//Consulta para el actualizar el perfil de usuario desde la APP
if (isset($_GET['guardarPerfil'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM usuario WHERE usuario='" . $_GET['cedula'] . "'");
    if (mysqli_num_rows($consulta) > 0) {
        while ($row = mysqli_fetch_assoc($consulta)) {
            $idUsuario = $row['id'];
        }       
        $consulta2 = mysqli_query($enlace, "UPDATE persona SET nombres='".$_GET['nombres']."', apellidos='".$_GET['apellidos']."', direccion='".$_GET['direccion']."', telefono='".$_GET['telefono']."', celular='".$_GET['celular']."' WHERE idUsuario='".$idUsuario."'");
        $consulta2 = mysqli_query($enlace, "UPDATE usuario SET clave='".$_GET['clave']."'WHERE id='".$idUsuario."'");
        echo "exito";
    }
}

//Consulta para listar el inventario de productos y servicios desde la APP
if (isset($_GET['listaProductos'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM producto");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para buscar un producto y servicio desde la APP
if (isset($_GET['buscarProducto'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM producto WHERE producto='".$_GET['producto']."'");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para el crear un nuevo turno desde la app
if (isset($_GET['guardarTurno'])) {    
    $consulta = mysqli_query($enlace, "SELECT * FROM usuario WHERE usuario='" . $_GET['cliente'] . "'");
    if (mysqli_num_rows($consulta) > 0) {
        while ($row = mysqli_fetch_assoc($consulta)) {
            $idUsuario = $row['id'];
        }
        $consulta = mysqli_query($enlace, "SELECT * FROM pedido WHERE fecha='".$_GET['fecha']."' AND hora='".$_GET['hora']."'");
        if (mysqli_num_rows($consulta) > 0) {
            echo "existe";
        } else {
            $consulta = mysqli_query($enlace, "SELECT * FROM pedido WHERE estado!='FINALIZADO' AND idCliente='".$idUsuario."'");
            if (mysqli_num_rows($consulta) > 0) {
                echo "pendiente";
            } else {
                $iva = floatval($_GET['precio']) * 0.12;
                $subtotal = floatval($_GET['precio']) - $iva;
                $total = floatval($iva) + floatval($subtotal);
                $consulta = mysqli_query($enlace, "INSERT INTO pedido(idCliente,idResponsable,subtotal,iva,total,fecha,estado,hora,domicilio) VALUES ('" . $idUsuario . "','0','".$subtotal."', '".$iva."', '".$total."', '".$_GET['fecha']."', 'GENERADO', '".$_GET['hora']."', '".$_GET['domicilio']."')");
                if (mysqli_affected_rows($enlace) > "0") {
                    $idPedido = mysqli_insert_id($enlace);
                    $consulta = mysqli_query($enlace, "INSERT INTO pedidodt(idPedido,idProducto,cantidad,subtotal) VALUES ('" . $idPedido . "','" . $_GET['producto'] . "','1', '" . $_GET['precio'] . "')");
                    if (mysqli_affected_rows($enlace) > "0") {
                        echo "exito";
                    }else{
                        echo mysqli_error($enlace);
                    }
                }else{
                    echo mysqli_error($enlace);
                } 
            }              
        }             
    }
}

//Consulta para listar el inventario de productos y servicios desde la APP
if (isset($_GET['historialPedidos'])) {
    $consulta = mysqli_query($enlace, "SELECT
	producto.producto, 
	pedido.total, 
	pedido.estado, 
	pedido.id,
    pedido.fecha,
    pedido.hora
    FROM
	pedido
	INNER JOIN
	pedidodt
	ON 
	pedido.id = pedidodt.idPedido
	INNER JOIN
	usuario
	ON 
	pedido.idCliente = usuario.id
	INNER JOIN
	producto
	ON 
	pedidodt.idProducto = producto.id
    WHERE usuario.usuario = '".$_GET['cliente']."'
    ORDER BY pedido.fecha DESC");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para el actualizar la ubicacion del usuario desde la APP
if (isset($_GET['actualizarUbicacion'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM usuario WHERE usuario='" . $_GET['cedula'] . "'");
    if (mysqli_num_rows($consulta) > 0) {
        while ($row = mysqli_fetch_assoc($consulta)) {
            $idUsuario = $row['id'];
        }       
        $consulta2 = mysqli_query($enlace, "UPDATE persona SET latitud='".$_GET['latitud']."', longitud='".$_GET['longitud']."' WHERE idUsuario='".$idUsuario."'");
        echo "exito";
    }
}

//Consulta para el actualizar la ubicacion del usuario desde la APP
if (isset($_GET['enviarEmail'])) {
    $consulta = mysqli_query($enlace, "SELECT * FROM usuario WHERE usuario='" . $_GET['cedula'] . "'");
    if (mysqli_num_rows($consulta) > 0) { 
        while ($row = mysqli_fetch_assoc($consulta)) {
            envioEmail($_GET['email'], "Recuperacion de clave", $row['clave']);
        } 
        echo "exito";
    } else {
        echo "noexiste";
    }
}

function envioEmail($destinoUsuario, $asuntoEmail, $clave){
    $destinatario = $destinoUsuario; 
    $asunto = $asuntoEmail; 
    $cuerpo = ' 
    <html> 
    <head> 
    <title>Sistema CarWASH</title> 
    </head> 
    <body> 
    <h1>Hola</h1> 
    <p> 
    <b>Bienvenidos a la recuperacion de clave del Sistema CarWash</b>. Gracias por trabajar con nosotros tu clave de usuario es: '.$clave.'. 
    </p> 
    </body> 
    </html> 
    '; 
    //para el envío en formato HTML 
    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $from = 'seguridad@lavadora-carwash.com';
    $returnpath = "-f" . $from;
    mail($destinatario,$asunto,$cuerpo,$headers,$returnpath);
}

//Consulta para eliminar un turno desde la APP
if (isset($_GET['eliminarPedido'])) {
    $consulta = mysqli_query($enlace, "DELETE FROM pedidodt WHERE idPedido='" . $_GET['idPedido'] . "'");    
    if (mysqli_affected_rows($enlace) > "0") {
        $consulta = mysqli_query($enlace, "DELETE FROM pedido WHERE id='" . $_GET['idPedido'] . "'");
              
    } else {
        echo mysqli_error($enlace);
    }
}

//Consulta para el actualizar la ID del API en la BD
if (isset($_GET['actualizarIdAPI'])) {
    $consulta2 = mysqli_query($enlace, "UPDATE persona SET idAPI='".$_POST['idAPI']."' WHERE email='".$_POST['email']."'");
    if (mysqli_affected_rows($enlace) > "0") {
        echo "exito";
    } else {
        echo mysqli_error($enlace);
    }  
}