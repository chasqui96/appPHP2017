<?php
require_once '../../clases/conexion.php';

$page = $_GET['page'];
$fila= $_GET['rows'];
$sord = $_GET['sord'];
$sql = "SELECT * FROM v_cobros ";
$sqlCount = "SELECT count(*) FROM v_cobros";

$sql .= " ORDER BY 1 ".$sord; 
$offset = ($page - 1) * $fila;

// Modifica la consulta SQL para incluir la limitación de resultados
$sql .= " LIMIT $fila OFFSET $offset";
$result = consultas::get_datos($sql);
$resultCount = consultas::get_datos($sqlCount);


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
                number_format($row['cob_efectivo'] , 0, ',', '.')// Monto
                // Agrega más campos aquí según sea necesario
            )
        );
    }

    $totalPages = ceil($resultCount[0]["count"] / (int)$fila);
    // Construir el objeto de respuesta completo
    $response = array(
        "page" => $page, // Número de página actual (puedes cambiarlo si implementas la paginación)
        "total" =>$totalPages, // Número total de páginas (puedes cambiarlo si implementas la paginación)
        "records" => $resultCount[0]["count"], // Número total de registros
        "rows" => $data // Array de filas de datos
    );
    // Convertir el array a formato JSON y devolver los datos
    echo json_encode($response);
} else {
    // Manejo de error si no se obtuvieron resultados
    echo json_encode(array("error" => "No se encontraron resultados."));
}
?>
