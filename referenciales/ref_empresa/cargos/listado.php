<?php

//session_start();
require_once '../../../clases/conexion.php';
$filtro = $_POST['fil'];
$sql = "select * from cargos where car_descripcion ilike '%$filtro%' order by 2;";
$result = consultas::get_datos($sql);

foreach ($result as $r) {
    echo "<tr onclick=\"seleccion($(this));\">";
        echo "<td class=\"info text-center\">";
        echo $r['id_cargo'];
        echo "</td>";
        echo "<td>";
        echo $r['car_descripcion'];
        echo "</td>";
    echo "</tr>";
}
//echo $sql;
?>
