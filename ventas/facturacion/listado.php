<?php

//session_start();
require_once '../../clases/conexion.php';
$filtro = $_POST['fil'];
$sql = "select * from v_ventas where ven_nrofactura_f||cli_razonsocial||cli_ruc ilike '%$filtro%' order by 1 desc;";
$result = consultas::get_datos($sql);

foreach ($result as $r) {
    echo "<tr onclick=\"seleccion($(this));\">";
        echo "<td class=\"text-center\">";
        echo "<a style=\"color:blue;\" target=\"blank\" href=\"imprimir_factura.php?valor=$r[id_venta]\">";
        echo $r['id_venta'];
        echo "</a>";
        echo "</td>";
        echo "<td>";
        echo $r['ven_nrofactura_f'];
        echo "</td>";
        echo "<td>";
        echo $r['cli_razonsocial'];
        echo "</td>";
        echo "<td>";
        echo $r['cli_ruc'];
        echo "</td>";
        echo "<td>";
        echo $r['ven_fecha_f'];
        echo "</td>";
        echo "<td>";
        echo $r['ven_tipo'];
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['ven_total'], 0, ',', '.');
        echo "</td>";
        echo "<td class=\"hidden\">";
        echo $r['id_funcionario'];
        echo "</td>";
        echo "<td class=\"hidden\">";
        echo $r['ven_intervalo'];
        echo "</td>";
        echo "<td class=\"hidden\">";
        echo $r['ven_cantcuotas'];
        echo "</td>";
        echo "<td class=\"hidden\">";
        echo $r['id_cliente'];
        echo "</td>";
        echo "<td class=\"hidden\">";
        echo date_format(date_create($r['ven_fecha']), 'd/m/Y');
        echo "</td>";
    echo "</tr>";
}
//echo $sql;
?>
