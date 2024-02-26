<?php
require_once '../../clases/conexion.php';

// Realizar la consulta SQL para obtener los datos de clientes
$sqlclientes = "SELECT * FROM clientes ORDER BY 1 DESC";
$rsclientes = consultas::get_datos($sqlclientes);

// Verificar si se obtuvieron datos
if ($rsclientes) {
    // Crear un array para almacenar los datos de clientes
    $clientes = array();
    
    // Recorrer los resultados y agregar cada fila al array
    foreach ($rsclientes as $cliente) {
        $clientes[] = array(
            'value' => $cliente['id_cliente'],
            'text' => $cliente['cli_razonsocial'] . ' - Ruc: ' . $cliente['cli_ruc'] // Cambia 'nombre' por el nombre real del campo en tu tabla
            // Agrega más campos según sea necesario
        );
    }
    
    // Imprimir los datos en formato JSON
    echo json_encode($clientes);
} else {
    // Si no se obtuvieron datos, imprimir un mensaje de error o un array vacío, según lo prefieras
    echo json_encode(array()); // Por ejemplo, devolver un array vacío
}
?>
