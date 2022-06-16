<?php
session_start();
require_once 'conexion.php';
$sql = $_POST['sql'];
$result = consultas::get_datos($sql);
if($result){
    echo pg_last_notice(consultas::con());
}else{
    echo pg_last_error();
}

?>
