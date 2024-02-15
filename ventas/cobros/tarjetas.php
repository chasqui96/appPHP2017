<?php

//session_start();
require_once '../../clases/conexion.php';
$filtro = $_POST['fil'];
$sql = "select * from v_cobro_tarjetas where id_cobros = $filtro order by 1 desc;";
$result = consultas::get_datos($sql);
//echo $sql;
if(($result)){
foreach ($result as $r) {
    
    echo "<tr>";
        echo "<td class=\"hidden\" style=\"display:none\">";
        echo $r['id_tarjeta'];
        echo "</td>";
        echo "<td class=\"text-left\">";
        echo $r['tarj_tipo']." ".$r['mar_descripcion'];
        echo "</td>";
        echo "<td class=\"hidden\" style=\"display:none\">";
        echo $r['id_entidad'];
        echo "</td>";
        echo "<td class=\"text-left\">";
        echo $r['ent_descripcion'];
        echo "</td>";
        echo "<td class=\"text-center\">";
        echo $r['nro_tarjeta'];
        echo "</td>";
        echo "<td class=\"text-center\">";
        echo $r['cod_auto'];
        echo "</td>";
        echo "<td class=\"text-right\">";
        echo number_format($r['importe'],0,",",".");
        echo "</td>";
        echo "<td class=\"text-right\" onclick=\"eliminarfila($(this).parent())\"><button type=\"button\" class=\"btn btn-danger btn-sm\"><span class=\"glyphicon glyphicon-remove\"></span></button></td>";
    echo "</tr>";
}
}
?>
