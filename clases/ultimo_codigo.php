<?php
session_start();
require_once 'conexion.php';
$tabla = $_POST['tabla'];
$pk = $_POST['pk'];

$sql = "select coalesce(max($pk),0)+1 as codigo from $tabla;";
$result = consultas::get_datos($sql);
echo $result[0]['codigo'];
//echo $sql;
?>
