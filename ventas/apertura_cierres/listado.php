<?php

//session_start();
require_once '../../clases/conexion.php';
$filtro = $_POST['fil'];
$sql = "select * from v_aperturas where caja_descripcion||funcionario||fecha_aperformat ilike '%$filtro%' order by 1 desc;";
$result = consultas::get_datos($sql);

foreach ($result as $r) {
    echo "<tr onclick=\"seleccion($(this));\">";
        echo "<td class=\"text-center\">";
        echo "<a style=\"color:blue;\" target=\"blank\" href=\"arqueo.php?id=$r[id_apercierre]\">";
        echo $r['id_apercierre'];
        echo "</a>";
        echo "</td>";
        echo "<td>";
        echo $r['fecha_aperformat'];
        echo "</td>";
        echo "<td>";
        echo $r['fecha_cierreformat'];
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['aper_monto'], 0, ',', '.');
        echo "</td>";
        echo "<td class=\"hidden\">";
        echo $r['id_caja'];
        echo "</td>";
        echo "<td>";
        echo $r['caja_descripcion'];
        echo "</td>";
        echo "<td>";
        echo $r['siguiente_factura'];
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['monto_efectivo'], 0, ',', '.');
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['monto_cheque'], 0, ',', '.');
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['monto_tarjeta'], 0, ',', '.');
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['monto_efectivo']+$r['monto_cheque']+$r['monto_tarjeta'], 0, ',', '.');
        echo "</td>";
    echo "</tr>";
}
//echo $sql;
?>
