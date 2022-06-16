<?php

// Include the main TCPDF library (search for installation path).
require_once '../../tcpdf/tcpdf.php';
require_once '../../clases/conexion.php';
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$sqlventas = "select *,(select numero_letras((total_exenta+total_grav5+total_grav10)::numeric)) as total_letra from v_ventas where id_venta = " . $_GET['valor'] . " order by 1";



$tipoventa = 'CONTADO';

$rsventas = consultas::get_datos($sqlventas);

if ($rsventas[0]['ven_tipo'] != 'CONTADO') {
    $tipoventa = 'CREDITO';
}

// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4');
$pdf->SetMargins(5, 5, 5, 0);
$pdf->SetTitle($rsventas[0]['ven_nrofactura_f']);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();

$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 18);

//datos de cabecera
$pdf->Ln(22);
//Fecha





$img_file = 'Factura.png';
$pdf->Image($img_file, 8, 5, 0, 0, '', '', '', false, 360, '', false, false, 0);





$pdf->SetFont('Times', '', 8);
$pdf->Text(37, 34.5, $rsventas[0]['ven_fecha_f'],false,false,true,0,0,'L',false,null,1,false,'T','M',true,80);
if($tipoventa == 'CONTADO'){
    $pdf->Text(168, 34, 'X',false,false,true,0,0,'L',false,null,1,false,'T','M',true,4);
}else{    
    $pdf->Text(190.5, 34, 'X',false,false,true,0,0,'L',false,null,1,false,'T','M',true,4);
}
$pdf->Text(47, 40.1, $rsventas[0]['cli_razonsocial'],false,false,true,0,0,'L',false,null,1,false,'T','M',true,93);
$pdf->Text(161, 40.1, $rsventas[0]['cli_ruc'],false,false,true,0,0,'L',false,null,1,false,'T','M',true,40);
$pdf->Text(26.5, 45.7, $rsventas[0]['cli_direccion'],false,false,true,0,0,'L',false,null,1,false,'T','M',true,115);
$pdf->Text(161, 45.7, $rsventas[0]['cli_telefono'],false,false,true,0,0,'L',false,null,1,false,'T','M',true,40);






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

    
$pdf->SetFont('Times', '', 10);
$pdf->Text(55, 259, 'Son Gs. '.ucfirst(strtolower ($rsventas[0]['total_letra'])));
// Close and output PDF document
// This method has several options, check the source code documentation for more information.

ob_end_clean(); 
$pdf->Output($rsventas[0]['ven_nrofactura_f'] . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

