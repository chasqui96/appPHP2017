<?php
require_once '../../clases/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibir los datos del formulario
    $ruc = $_POST["ruc_numero"];
    $dv = $_POST["dv"];
    $razon_social = $_POST["razon_social"];
    $correo = $_POST["correo"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $tipo_persona = $_POST["tipoCliente"];
    $idciudad = $_POST["id_ciudad"];
    $operacion = 1;
    
    // Ejecutar la consulta SQL
    $sql = "SELECT public.sp_crear_clientes(
        0,
        '$ruc',
        '$razon_social',
        '$telefono',
        '$direccion', 
        '$correo', 
        $idciudad, 
        $tipo_persona, 
        $operacion
    )";
    $result = consultas::get_datos($sql);
    
    // Verificar si se obtuvo un resultado
    if ($result !== false) {
       
        // El procedimiento almacenado ejecutó correctamente
        // Manejar el resultado obtenido (si es necesario)
         ECHO $result[0]["sp_crear_clientes"];
    } else {
        // Error al ejecutar el procedimiento almacenado
        ECHO $result;
    }
} else {
         echo "NO ES UN METODO POST";
}
?>
