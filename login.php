<?php
session_start();
require_once 'clases/conexion.php';
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

$sql = "select * from v_usuarios where usu_nombre = '$usuario' and usu_clave = md5('$clave');";
$result = consultas::get_datos($sql);
if ($result[0]['id_usuario']) {
    $_SESSION['id_usuario'] = $result[0]['id_usuario'];
    $_SESSION['usu_nombre'] = $result[0]['usu_nombre'];
    $_SESSION['id_perfil'] = $result[0]['id_perfil'];
    $_SESSION['per_descripcion'] = $result[0]['per_descripcion'];
    $_SESSION['fun_nombres'] = $result[0]['fun_nombres'];
    $_SESSION['fun_apellidos'] = $result[0]['fun_apellidos'];
    $_SESSION['suc_descripcion'] = $result[0]['suc_descripcion'];
    $_SESSION['id_sucursal'] = $result[0]['id_sucursal'];
  
    echo 'OK';
} else {
    echo "USUARIO O CONTRASEÑA INCORRECTA";
}
?>
