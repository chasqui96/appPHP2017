<?php
require_once '../../clases/conexion.php';

$sql = "SELECT * FROM v_cobros ORDER BY id_cobros DESC;";
$result = consultas::get_datos($sql);

if ($result) {
    $data = array(); // Inicializa un array para almacenar los resultados

    // Itera sobre los resultados y agrégalos al array
    foreach ($result as $row) {
        $data[] = array(
            "id" => $row['id_cobros'], // Se agrega un campo "id" para identificar de manera única cada fila
            "cell" => array(
                $row['id_cobros'], // Recibo
                $row['cliente'],   // Cliente
                $row['ruc'],       // Ruc
                $row['cob_fecha_f'], // Fecha
                $row['cob_efectivo'] // Monto
                // Agrega más campos aquí según sea necesario
            )
        );
    }

    // Construir el objeto de respuesta completo
    $response = array(
        "page" => 1, // Número de página actual (puedes cambiarlo si implementas la paginación)
        "total" => 1, // Número total de páginas (puedes cambiarlo si implementas la paginación)
        "records" => count($data), // Número total de registros
        "rows" => $data // Array de filas de datos
    );

    // Convertir el array a formato JSON y devolver los datos
    echo json_encode($response);
} else {
    // Manejo de error si no se obtuvieron resultados
    echo json_encode(array("error" => "No se encontraron resultados."));
}
?>
