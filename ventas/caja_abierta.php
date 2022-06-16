<?php

session_start();
require_once '../clases/conexion.php';

$sql = "select * from v_aperturas where id_usuario = ". $_SESSION['id_usuario'] ." and cierre_fecha isnull;";
$result = consultas::get_datos($sql);
//echo $sql;
if($result[0]['id_apercierre']){
     $_SESSION['idapertura'] = $result[0]['id_apercierre'];
     $_SESSION['idcaja'] = $result[0]['id_caja'];
echo "LA <strong>". $result[0]['caja_descripcion'] ."</strong> ESTÁ ABIERTA CON MONTO ACTUAL DE <strong>".  number_format(($result[0]['monto_efectivo']+$result[0]['monto_cheque']+$result[0]['monto_tarjeta']), 0, ",", ".")."</strong>";
}else{
    echo "0";
}
//echo $sql;
?>
