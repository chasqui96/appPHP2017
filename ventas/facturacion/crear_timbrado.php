<?php
require_once '../../clases/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibir los datos del formulario
    $timbrado_numero = $_POST["timbrado_numero"];
    $timbrado_vigencia_desde = $_POST["timbrado_vigencia_desde"];
    $timbrado_vigencia_hasta = $_POST["timbrado_vigencia_hasta"];
    $timbrado_estado = 'VIGENTE';
    $operacion = 1;
    
    // Ejecutar la consulta SQL
    $sql = "SELECT public.sp_crear_timbrado(
        0,
        $timbrado_numero,
        '$timbrado_vigencia_desde', 
        '$timbrado_vigencia_hasta',
        '$timbrado_estado',
        $operacion
    )";
    $result = consultas::get_datos($sql);
    
    // Verificar si se obtuvo un resultado
    if ($result !== false) {
       
        // El procedimiento almacenado ejecutó correctamente
        // Manejar el resultado obtenido (si es necesario)
         ECHO $result[0]["sp_crear_timbrado"];
    } else {
        // Error al ejecutar el procedimiento almacenado
        ECHO $result;
    }
} else {
         echo "NO ES UN METODO POST";
}
?>
