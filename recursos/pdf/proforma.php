<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');
require '../../consultas/conexion.php';

$respuesta = mysqli_query($enlace, "SELECT
proformas.id,
clientes.nombre,
clientes.cedula,
clientes.telefono,
clientes.direccion,
clientes.celular,
proformas.fecha,
proformas.fechaFin,
proformas.total,
proformas.subtotal,
proformas.iva
FROM
proformas
INNER JOIN clientes ON proformas.idCliente = clientes.id WHERE proformas.id='".$_GET['idProforma']."'");

while($row= mysqli_fetch_assoc($respuesta)){
    $numProforma = $row['id'];
    $direccion = $row['direccion'];
    $nombreCliente = $row['nombre'];
    $cedula = $row['cedula'];
    $telefono = $row['telefono'];
    $fechaProforma = $row['fecha'];
    $iva = $row['iva'];
    $subtotal = $row['subtotal'];
    $fechaFinal = $row['fechaFin'];
    $totalPagar = $row['total'];
    $celular = $row['celular'];
}

$datosCliente = '<table border="1" style="border-collapse: collapse; width: 100%;">
<tbody>
<tr>
<td style="width: 50%;"><b>Nombre:</b> '.$nombreCliente.'</td>
<td style="width: 50%;"><b>Direcci&oacute;n:</b> '.$direccion.'</td>
</tr>
<tr>
<td style="width: 50%;"><b>Fecha:</b> '.$fechaProforma.'</td>
<td style="width: 50%;"><b>Convencional:</b> '.$telefono.'</td>
</tr>
<tr>
<td style="width: 50%;"><b>C&eacute;dula:</b> '.$cedula.'</td>
<td style="width: 50%;"><b>Celular:</b> '.$celular.'</td>
</tr>
</tbody>
</table><br><br>';

$consulta = mysqli_query($enlace, "SELECT
productos.nombre,
(productos.pvp*proformasdt.cantidad) as pvp,
productos.pvp as punitario,
proformasdt.cantidad
FROM
proformasdt
INNER JOIN productos ON proformasdt.idProducto = productos.id
WHERE proformasdt.idProforma='".$numProforma."'");

$cuerpoTabla = '';

while($row= mysqli_fetch_assoc($consulta)){
    $cuerpoTabla .= '<tr style="height: 39px;">
    <td style="width: 10%; text-align: center; height: 39px;">'.$row['cantidad'].'</td>
    <td style="width: 50%; height: 39px;">'.$row['nombre'].'</td>
    <td style="width: 20%; text-align: center; height: 39px;">'.$row['punitario'].'</td>
    <td style="width: 20%; text-align: center; height: 39px;">'.$row['pvp'].'</td>
    </tr>';
}

$datosFactura = '<table border="1" style="border-collapse: collapse; width: 100%; height: 80px;">
<tbody>
<tr style="height: 41px;">
<td style="width: 10%; text-align: center; height: 41px;"><b>Cant</b></td>
<td style="width: 50%; text-align: center; height: 41px;"><b>Detalle</b></td>
<td style="width: 20%; text-align: center; height: 41px;"><b>Precio</b></td>
<td style="width: 20%; text-align: center; height: 41px;"><b>Total</b></td>
</tr>'.$cuerpoTabla.'</tbody>
</table>';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SOLUMEC');
$pdf->SetTitle("SOLUMEC-Proforma");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default header data
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set default font subsetting mode
$pdf->setFontSubsetting(true);
$pdf->SetMargins(20, 20, 20, false);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetFont('Helvetica', '', 12);
$pdf->addPage();

$content = '';
setlocale(LC_TIME, 'es_ES');

$content .= '<div id="factura" class="row">
<div class="col-12">
    <div id="cabecera" style="background-image: url("./assets/engranes.png"); background-repeat: no-repeat; width: 100%; height: 200px;">

    </div>
    <div style="height: 400;">

    </div>
    <div id="pie" style="background-image: url("./assets/pie.png"); background-repeat: no-repeat; width: 100%; height: 150px;">

    </div>
</div>   
</div>';

$pdf->writeHTML($content, true, 0, true, 0);
$pdf->lastPage();
//$pdf->output('Reporte.pdf', 'I');
$pdf->Output("Proforma_".$nombreCliente.'_'.$fechaProforma.'.pdf','D');
echo "<script>window.close();</script>";
exit();

?>