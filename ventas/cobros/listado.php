<?php

//session_start();
require_once '../../clases/conexion.php';
$filtro = $_POST['fil'];
$sql = "select * from v_cobros where cliente||cob_fecha_f ilike '%$filtro%' order by 1 desc;";
$result = consultas::get_datos($sql);

foreach ($result as $r) {
    echo "<tr onclick=\"seleccion($(this));\">";
        echo "<td class=\"text-center\">";//0
        echo "<a style=\"color:blue;\" target=\"blank\" href=\"imprimir_recibo.php?valor=$r[id_cobros]\">";
        echo $r['id_cobros'];
        echo "</a>";
        echo "</td>";
        echo "<td>";//1
        echo "</td>";
        echo "<td>";//2
        echo $r['cliente'];
        echo "</td>";
        echo "<td>";//3
        echo $r['ruc'];
        echo "</td>";
        echo "<td>";//4
        echo $r['cob_fecha_f'];
        echo "</td>";
        echo "<td class=\"hidden\">";//5
        echo $r['id_cliente'];
        echo "</td>";
        echo "<td class=\"hidden\">";//6
        echo $r['cob_efectivo'];
        echo "</td>";
        echo "<td class=\"hidden\">";//7
        echo $r['id_apercierre'];
        echo "</td>";
        echo "<td class=\"hidden\">";//8
        echo $r['fechanormal'];
        echo "</td>";
    echo "</tr>";
}
//echo $sql;
?>
