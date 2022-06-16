<?php

// Include the main TCPDF library (search for installation path).
require_once'../../tcpdf/tcpdf.php';
require_once'../../clases/conexion.php';


$sqlventas = "select * from v_cobros where id_cobros = " . $_GET['valor'] . " order by 1";


$rsventas = consultas::get_datos($sqlventas);



// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4');
$pdf->SetMargins(15, 15, 18);
$pdf->SetTitle($rsventas[0]['recibo']);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();

$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 18);

$pdf->Cell(85, 1, 'DESARROLLOSPY', 0, 0, 'C',null,null,1);

$pdf->Cell(100, 1, 'RECIBO DE DINERO', 0, 1, 'C');

$pdf->SetFont('Times', 'B', 14);

$pdf->Cell(85, 1, 'DESARROLLOS DE SISTEMAS WEB', 0, 0, 'C');

$pdf->Cell(100, 1, 'NRO.: ' . $rsventas[0]['recibo'], 0, 1, 'C');

$pdf->SetFont('Times', '', 12);

$pdf->Cell(85, 1, 'Dirección: George Washington c/Santa Librada', 0, 0, 'C');

$pdf->Cell(100, 1, '', 0, 1, 'C');

$pdf->Cell(85, 1, 'Teléfono: 0985 154 346', 0, 0, 'C');

$pdf->Cell(100, 1, '', 0, 1, 'C');

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
$pdf->Cell(/*1*/90, /*2*/1, /*3*/$rsventas[0]['cob_fecha_f'], /*4*/0, /*5*/1, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);
$pdf->Ln(3);
//nombre cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(30, 1, '   CLIENTE: ', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(/*1*/90, /*2*/1, /*3*/$rsventas[0]['cliente'], /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);

//ruc cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(20, 1, 'RUC: ', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(90, 1, $rsventas[0]['ruc'], 0, 1, 'L');

$pdf->Ln(3);
//dirección cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(30, 1, '', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(/*1*/90, /*2*/1, /*3*/'', /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);


//telefono cliente
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(20, 1, '', 0, 0, 'L');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(/*1*/37, /*2*/1, /*3*/'', /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);

//cuadro de detalles
$pdf->RoundedRect(15, 92, 177, 140, 5.0, '1111', '', $style6, array(200, 200, 200));

//datos de detalles
$sqldetalle = "select * from v_det_cobros where id_cobros = " . $rsventas[0]['id_cobros'] . " order by 3";
$rsdetalles = consultas::get_datos($sqldetalle);

$pdf->Ln(20);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(15, 1, '#', 0, 0, 'C');
$pdf->Cell(51+85, 1, 'Concepto', 0, 0, 'L');
$pdf->Cell(25, 1, 'Monto', 0, 1, 'R');
$totgral = 0;
foreach ($rsdetalles as $rsdetalle) {
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(15, 1, $rsdetalle['cta_nro'], 0, 0, 'C');
    $pdf->Cell(/*1*/51+85, /*2*/1, /*3*/$rsdetalle['concepto'], /*4*/0, /*5*/0, /*6*/'L', /*7*/null, /*8*/null, /*9*/1, /*10*/null, /*11*/null, /*12*/null);

    $pdf->Cell(25, 1, number_format($rsdetalle['detc_monto'],0,',','.'), 0, 1, 'R');
    $totgral += $rsdetalle['detc_monto'];
}
$posicion = $pdf->GetY();
$pdf->Line(190,230,15,$posicion);


//cuadro de subtotales
$pdf->RoundedRect(15, 232, 177, 35, 4.0, '1111', '', $style6, array(200, 200, 200));




$pdf->SetFont('Times', 'B', 10);
$pdf->Text(18, 251, 'TOTAL');
$pdf->SetFont('Times', '', 10);

$pdf->MultiCell(
        25,//ancho
 	0,//alto
 	number_format($totgral,0,',','.'),
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
$sqltotletra = "select numero_letras($totgral) as total_letra;";
$rstotletra = consultas::get_datos($sqltotletra);
$pdf->SetFont('Times', 'B', 10);
$pdf->Text(18, 259, 'TOTAL EN LETRAS');
$pdf->SetFont('Times', '', 10);
$pdf->Text(55, 259, 'Son Gs. '.ucfirst(strtolower ($rstotletra[0]['total_letra'])));
    error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors',0);
ini_set('log_errors',1)  ;
ob_end_clean(); 
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($rsventas[0]['recibo'] . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

