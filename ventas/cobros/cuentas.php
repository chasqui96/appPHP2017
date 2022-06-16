<?php

session_start();
if ($_SESSION) {
    require_once '../../clases/conexion.php';

    $sqlfun = "SELECT * from v_cuentas_cobrar where id_cliente = ".$_POST['id_cliente']." and cta_saldo > 0;";
    
    $result = consultas::get_datos($sqlfun);
    foreach ($result as $res) {
        echo '<option value="'.$res['id_venta'].'~'.$res['cta_nro'].'~'.$res['cta_saldo'].'~'.$res['ven_nrofactura_f'].'~'.$res['cta_vencimiento_f'].'">Cuenta nro.: <strong>'.$res['cta_nro'].'</strong> de la factura nro.: <strong>'.$res['ven_nrofactura_f'].'</strong> Vence el: '.$res['cta_vencimiento_f'].' Saldo: '. $res['cta_saldo'] .'</option>';
    }
} else {
    header('location:../../../index.php');
}