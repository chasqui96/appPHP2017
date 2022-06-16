<?php

session_start();
if ($_SESSION) {
    require_once '../../clases/conexion.php';

    $sqlfun = "SELECT * from v_stock where id_deposito = ".$_POST['id_deposito']." and stk_cantidad > 0 order by mer_descripcion;";
    
    $result = consultas::get_datos($sqlfun);
    foreach ($result as $res) {
        echo '<option value="'.$res['id_mercaderia'].'~'.$res['mer_precio'].'~'.$res['mer_tipoimpuesto'].'~'.$res['stk_cantidad'].'">'.$res['mer_descripcion'].' - Existencia: '.$res['stk_cantidad'].'</option>';
    }
} else {
    header('location:../../../index.php');
}