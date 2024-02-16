<?php

session_start();
if ($_SESSION) {
    require_once '../../clases/conexion.php';
    $sqldepositos = "select * from v_depositos where id_sucursal = " . $_SESSION['id_sucursal'] . " order by 2";
    $rsdepositos = consultas::get_datos($sqldepositos);
    $primeraVez = true;
    foreach ($rsdepositos as $rsdeposito) { 
    if($primeraVez){
       echo '<option value="0">Seleccione</option>';
       $primeraVez= false;
    }
       echo  '<option value="'.$rsdeposito['id_deposito'].'">'.$rsdeposito['dep_descripcion'].'</option>';
     }
} else {
    header('location:../../../index.php');
}