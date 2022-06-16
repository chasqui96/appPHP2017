<?php

// Include the main TCPDF library (search for installation path).
require_once'../../tcpdf/tcpdf.php';
require_once'../../clases/conexion.php';


$sqlventas = "select *,(select numero_letras((total_exenta+total_grav5+total_grav10)::numeric)) as total_letra from v_ventas where id_venta = " . $_GET['valor'] . " order by 1";



$tipoventa = 'CONTADO';

$rsventas = consultas::get_datos($sqlventas);

if ($rsventas[0]['ven_tipo'] != 'CONTADO') {
    $tipoventa = 'CREDITO';
}


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4');
$pdf->SetMargins(15, 15, 18);
$pdf->SetTitle($rsventas[0]['ven_nrofactura_f']);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();

$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 18);

$pdf->Cell(85, 1, 'DESARROLLOSPY', 0, 0, 'C',null,null,1);

$pdf->Cell(100, 1, 'FACTURA ' . $tipoventa, 0, 1, 'C');

$pdf->SetFont('Times', 'B', 14);

$pdf->Cell(85, 1, 'DESARROLLOS DE SISTEMAS WEB', 0, 0, 'C');

$pdf->Cell(100, 1, 'NRO.: ' . $rsventas[0]['ven_nrofactura_f'], 0, 1, 'C');

$pdf->SetFont('Times', '', 12);

$pdf->Cell(85, 1, 'Dirección: George Washington c/Santa Librada', 0, 0, 'C');

$pdf->Cell(100, 1, 'Timbrado: ' . $rsventas[0]['tim_numero'], 0, 1, 'C');

$pdf->Cell(85, 1, 'Teléfono: 0985 154 346', 0, 0, 'C');

$pdf->Cell(100, 1, 'Vigencia: ' . date_format(date_create($rsventas[0]['tim_fechavence']), 'd/m/Y'), 0, 1, 'C');

//$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(200, 200, 200));

//cuadros de arriba
$pdf->RoundedRect(15, 12, 90, 50, 4.0, '1111', '', $style6, array(200, 200, 200));
$pdf->RoundedRect(105, 12, 87, 50, 4.0, '1111', '', $style6, array(200, 200, 200));

//cuadro de cabecera
$pdf->RoundedRect(15, 62, 177, 30, 4.0, '1111', '', $style6, array(200, 200, 200));

//datos de cabecera
$pdf->Ln(22);
//Fecha
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(30, 1, '   FECHA: ', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(/*1*/90, /*2*/1, /*3*/$rsventas[0]['ven_fecha_f'], /*4*/0, /*5*/1, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);
$pdf->Ln(3);
//nombre cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(30, 1, '   CLIENTE: ', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(/*1*/90, /*2*/1, /*3*/$rsventas[0]['cli_razonsocial'], /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);

//ruc cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(20, 1, 'RUC: ', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(90, 1, $rsventas[0]['cli_ruc'], 0, 1, 'L');

$pdf->Ln(3);
//dirección cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(30, 1, '   DIRECCION: ', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(/*1*/90, /*2*/1, /*3*/$rsventas[0]['cli_direccion'], /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);


//telefono cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(20, 1, 'TELEFONO: ', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(/*1*/37, /*2*/1, /*3*/$rsventas[0]['cli_telefono'], /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);

//cuadro de detalles
$pdf->RoundedRect(15, 92, 177, 140, 5.0, '1111', '', $style6, array(200, 200, 200));

//datos de detalles
$sqldetalle = "select * from v_detalle_ventas where id_venta = " . $rsventas[0]['id_venta'] . " order by 3";
$rsdetalles = consultas::get_datos($sqldetalle);

$pdf->Ln(20);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(15, 1, '#', 0, 0, 'C');
$pdf->Cell(51, 1, 'Mercadería', 0, 0, 'L');
$pdf->Cell(15, 1, 'Cant.', 0, 0, 'C');
$pdf->Cell(20, 1, 'Precio', 0, 0, 'R');
$pdf->Cell(25, 1, 'Exenta', 0, 0, 'R');
$pdf->Cell(25, 1, 'Grav. 5%', 0, 0, 'R');
$pdf->Cell(25, 1, 'Grav. 10%', 0, 1, 'R');
foreach ($rsdetalles as $rsdetalle) {
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(15, 1, $rsdetalle['id_mercaderia'], 0, 0, 'C');
    $pdf->Cell(/*1*/51, /*2*/1, /*3*/$rsdetalle['mer_descripcion'], /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);
    $pdf->Cell(15, 1, $rsdetalle['dv_cantidad'], 0, 0, 'C');
    $pdf->Cell(20, 1, number_format($rsdetalle['dv_precio'],0,',','.'), 0, 0, 'R');
    $pdf->Cell(25, 1, number_format($rsdetalle['exenta'],0,',','.'), 0, 0, 'R');
    $pdf->Cell(25, 1, number_format($rsdetalle['grav5'],0,',','.'), 0, 0, 'R');
    $pdf->Cell(25, 1, number_format($rsdetalle['grav10'],0,',','.'), 0, 1, 'R');
}
$posicion = $pdf->GetY();
$pdf->Line(190,230,15,$posicion);


//cuadro de subtotales
$pdf->RoundedRect(15, 232, 177, 35, 4.0, '1111', '', $style6, array(200, 200, 200));

$pdf->SetFont('Times', 'B', 10);
$pdf->Text(18, 235, 'SUBTOTALES');
$pdf->SetFont('Times', '', 10);

$pdf->MultiCell(
        25,//ancho
 	0,//alto
 	number_format($rsventas[0]['total_exenta'],0,',','.'),
 	0,//borde
 	'R',//alineacion horizontal
 	false,
 	0,
 	117,//posición x
 	235,//posición y
 	true,
 	0,
 	false,
 	true,
 	0,
 	'T',
 	false 
);	
$pdf->MultiCell(
        25,//ancho
 	0,//alto
 	number_format($rsventas[0]['total_grav5'],0,',','.'),
 	0,//borde
 	'R',//alineacion horizontal
 	false,
 	0,
 	117+25,//posición x
 	235,//posición y
 	true,
 	0,
 	false,
 	true,
 	0,
 	'T',
 	false 
);	
$pdf->MultiCell(
        25,//ancho
 	0,//alto
 	number_format($rsventas[0]['total_grav10'],0,',','.'),
 	0,//borde
 	'R',//alineacion horizontal
 	false,
 	0,
 	117+25+25,//posición x
 	235,//posición y
 	true,
 	0,
 	false,
 	true,
 	0,
 	'T',
 	false 
);	



//$pdf->Text(135, 235, number_format($rsventas[0]['total_exenta'],0,',','.'));
//$pdf->Text(155, 235, number_format($rsventas[0]['total_grav5'],0,',','.'));
//$pdf->Text(173, 235, number_format($rsventas[0]['total_grav10'],0,',','.'));

$pdf->SetFont('Times', 'B', 10);
$pdf->Text(18, 243, 'LIQUIDACIÓN DE IVA');
$pdf->SetFont('Times', '', 10);
$pdf->Text(98, 243, "5%  ".number_format($rsventas[0]['iva5'],0,',','.')."              10%  ".number_format($rsventas[0]['iva10'],0,',','.'));

$pdf->MultiCell(
        25,//ancho
 	0,//alto
 	"Total: ".number_format($rsventas[0]['iva5']+$rsventas[0]['iva10'],0,',','.'),
 	0,//borde
 	'R',//alineacion horizontal
 	false,
 	0,
 	117+25+25,//posición x
 	243,//posición y
 	true,
 	0,
 	false,
 	true,
 	0,
 	'T',
 	false 
);

$pdf->SetFont('Times', 'B', 10);
$pdf->Text(18, 251, 'TOTAL GENERAL');
$pdf->SetFont('Times', '', 10);

$pdf->MultiCell(
        25,//ancho
 	0,//alto
 	'Gs. '.number_format(($rsventas[0]['total_exenta']+$rsventas[0]['total_grav5']+$rsventas[0]['total_grav10']),0,',','.'),
 	0,//borde
 	'R',//alineacion horizontal
 	false,
 	0,
 	117+25+25,//posición x
 	251,//posición y
 	true,
 	0,
 	false,
 	true,
 	0,
 	'T',
 	false 
);

$pdf->SetFont('Times', 'B', 10);
$pdf->Text(18, 259, 'TOTAL EN LETRAS');
$pdf->SetFont('Times', '', 10);
$pdf->Text(55, 259, 'Son Gs. '.ucfirst(strtolower ($rsventas[0]['total_letra'])));
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($rsventas[0]['ven_nrofactura_f'] . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

