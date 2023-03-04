<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

//Consulta para listar los turnos generados en la App
if (isset($_GET['listaTurnos'])) {
    $consulta = mysqli_query($enlace, "SELECT
	producto.producto, 
	usuario.usuario as cedula, 
	pedido.total, 
	pedido.estado, 
    pedido.fecha,
    pedido.hora as horaIni,
	ADDTIME(pedido.hora, '00:30:00') as horaFin,
	persona.latitud,
	persona.longitud,
	pedido.domicilio,
	pedido.id, 
	CONCAT_WS(' ',persona.nombres,persona.apellidos) as cliente,
	pedido.idEmpleado
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
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario");
    while ($row = mysqli_fetch_assoc($consulta)) {
		if($row['idEmpleado']!=""){
			$consultaDos = mysqli_query($enlace, "SELECT
			CONCAT_WS(' ',persona.nombres,persona.apellidos) as empleado
			FROM
			usuario
			INNER JOIN
			persona
			ON 
			usuario.id = persona.idUsuario
			WHERE usuario.id='".$row['idEmpleado']."'");
			while ($row2 = mysqli_fetch_assoc($consultaDos)) {
				$row['empleado'] = $row2['empleado'];
			}
		} else {
			$row['empleado'] = 'Sin asignar';
		}		
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para listar los turnos generados en la App
if (isset($_GET['listaTurnosGenerados'])) {
    $consulta = mysqli_query($enlace, "SELECT
	producto.producto, 
	usuario.usuario as cedula, 
	pedido.total, 
	pedido.estado, 
    pedido.fecha,
    pedido.hora,
	pedido.id, 
	CONCAT_WS(' ',persona.nombres,persona.apellidos) as cliente
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
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario
	WHERE pedido.estado='GENERADO'");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para cambiar el estado del pedido a EN PROCESO
if (isset($_GET['comenzarPedido'])) {    
    $consulta = mysqli_query($enlace, "UPDATE pedido SET estado='EN PROCESO',idEmpleado='".$_POST['idEmpleado']."' WHERE id='".$_POST['idPedido']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }     
}

//Consulta para listar los turnos EN PROCESO en la App
if (isset($_GET['listaTurnosProceso'])) {
    $consulta = mysqli_query($enlace, "SELECT
	producto.producto, 
	usuario.usuario as cedula, 
	pedido.total, 
	pedido.estado, 
    pedido.fecha,
    pedido.hora,
	pedido.id, 
	CONCAT_WS(' ',persona.nombres,persona.apellidos) as cliente
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
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario
	WHERE pedido.estado='EN PROCESO'");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para listar los turnos EN PROCESO en la App
if (isset($_GET['listaTurnosProcesoEmpleado'])) {
	session_start();	
    $consulta = mysqli_query($enlace, "SELECT
	producto.producto, 
	usuario.usuario as cedula, 
	pedido.total, 
	pedido.estado, 
    pedido.fecha,
    pedido.hora,
	pedido.id, 
	CONCAT_WS(' ',persona.nombres,persona.apellidos) as cliente
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
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario
	WHERE pedido.estado='EN PROCESO' AND pedido.idEmpleado='".$_SESSION['id']."'");
    while ($row = mysqli_fetch_assoc($consulta)) {
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para cambiar el estado del pedido a FINALIZADO
if (isset($_GET['finalizarPedido'])) {    
    $consulta = mysqli_query($enlace, "UPDATE pedido SET estado='FINALIZADO' WHERE id='".$_POST['idPedido']."'");
    if (mysqli_affected_rows($enlace) == "1") {
        echo "exito";
    }else{
        echo mysqli_error($enlace);
    }     
}

//Consulta para eliminar un turno desde el Administrador
if (isset($_GET['eliminarPedido'])) {
    $consulta = mysqli_query($enlace, "DELETE FROM pedidodt WHERE idPedido='" . $_POST['idPedido'] . "'");    
    if (mysqli_affected_rows($enlace) > "0") {
        $consulta = mysqli_query($enlace, "DELETE FROM pedido WHERE id='" . $_POST['idPedido'] . "'");
        if (mysqli_affected_rows($enlace) > "0") {
            echo "exito";
        } else {
            echo mysqli_error($enlace);
        }        
    } else {
        echo mysqli_error($enlace);
    }
}