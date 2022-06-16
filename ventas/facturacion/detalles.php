<?php

//session_start();
require_once '../../clases/conexion.php';
$filtro = $_POST['fil'];
$sql = "select * from v_detalle_ventas where id_venta = $filtro order by 3 desc;";
$result = consultas::get_datos($sql);
echo $result[0]['id_deposito']."|";
foreach ($result as $r) {
    
    echo "<tr>";
        echo "<td class=\"text-center\">";
        echo $r['id_mercaderia'];
        echo "</td>";
        echo "<td>";
        echo $r['mer_descripcion'];
        echo "</td>";
        echo "<td class=\"text-center\">";
        echo number_format($r['dv_cantidad'],0,",",".");
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['dv_precio'],0,",",".");
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['exenta'],0,",",".");
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['grav5'],0,",",".");
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['grav10'], 0, ",", ".");
        echo "</td>";
        echo "<td class=\"text-right\" onclick=\"eliminarfila($(this).parent())\"><button type=\"button\" class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-remove\"></span></button></td>";
    echo "</tr>";
}
//echo $sql;
?>
