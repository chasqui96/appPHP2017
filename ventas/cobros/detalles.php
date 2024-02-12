<?php

//session_start();
require_once '../../clases/conexion.php';
$filtro = $_POST['fil'];
$sql = "select * from v_det_cobros where id_cobros = $filtro order by 1 desc;";
$result = consultas::get_datos($sql);
//echo $sql;
foreach ($result as $r) {
    
    echo "<tr>";
        echo "<td class=\"hidden\">";
        echo $r['id_venta'];
        echo "</td>";
        echo "<td class=\"text-center\">";
        echo $r['cta_nro'];
        echo "</td>";
        echo "<td class=\"text-center\">";
        echo $r['ven_nrofactura_f'];
        echo "</td>";
        echo "<td class=\"text-center\">";
        echo $r['cta_vencimiento_f'];
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['detc_monto'],0,",",".");
        echo "</td>";
        echo "<td class=\"text-right\" onclick=\"eliminarfila($(this).parent())\"><button type=\"button\" class=\"btn btn-danger  btn-sm\">x</button></td>";
    echo "</tr>";
}
//echo $sql;
?>
