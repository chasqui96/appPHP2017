<?php
require_once '../../clases/conexion.php';

// Parámetros de búsqueda enviados por jqGrid
$id_apercierre = isset($_GET['rowId']) ? $_GET['rowId'] : '';

$sql = "SELECT * FROM v_aperturas WHERE id_apercierre = " . $id_apercierre . " ORDER BY 1 DESC;";
$result = consultas::get_datos($sql);   

if ($result) {
    $data = array(); // Inicializa un array para almacenar los resultados

    // Itera sobre los resultados y agrégalos al array
    foreach ($result as $row) {
        $data[] = array(
            "id" => $row['id_apercierre'], // Se agrega un campo "id" para identificar de manera única cada fila
            "cell" => array(
                $row['id_apercierre'],
                number_format($row['monto_efectivo'], 0, ',', '.'),
                number_format($row['monto_cheque'], 0, ',', '.'),
                number_format($row['monto_tarjeta'], 0, ',', '.')
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
