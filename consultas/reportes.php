<?php

require('../conexion/conexion.php');
error_reporting(E_ALL ^ E_NOTICE);

//Consulta para generar reporte de productos
if (isset($_GET['reporteProductos'])) {
    $consulta = mysqli_query($enlace, "SELECT
	pedidodt.idProducto as id, 
	producto.producto as nombre,
    producto.tipo, 
	producto.cantidad as existencia, 
	SUM(pedidodt.cantidad) as vendidos,
	SUM(pedidodt.cantidad) * producto.pvp as gananciaTotal,
	SUM(pedidodt.cantidad) * (producto.pvp - producto.precioCosto) as gananciaReal
    FROM
	pedidodt
	INNER JOIN
	producto
	ON 
	pedidodt.idProducto = producto.id
	GROUP BY pedidodt.idProducto");
    while ($row = mysqli_fetch_assoc($consulta)) {
        if($row['tipo']==1){
            $row['tipo'] = "PRODUCTO";
        } else {
            $row['tipo'] = "SERVICIO";
        }
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}

//Consulta para generar reporte de Clientes
if (isset($_GET['reporteClientes'])) {
    $consulta = mysqli_query($enlace, "SELECT
	pedido.idCliente AS id, 
	persona.nombres, 
	persona.apellidos, 
	persona.celular, 
	SUM(pedido.total) AS facturado, 
	MAX(pedido.fecha) AS ultimoServicio
    FROM
	pedido
	INNER JOIN
	usuario
	ON 
	pedido.idCliente = usuario.id
	INNER JOIN
	persona
	ON 
	usuario.id = persona.idUsuario
	WHERE pedido.estado = 'FINALIZADO'
    GROUP BY
	pedido.idCliente");
    while ($row = mysqli_fetch_assoc($consulta)) {        
        $datos['data'][] = $row;
    }
    echo json_encode($datos);
}