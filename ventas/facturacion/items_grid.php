<?php
require_once '../../clases/conexion.php';

// Parámetros de búsqueda enviados por jqGrid

$page = $_GET['page'];
$fila= $_GET['rows'];
$sord = $_GET['sord'];
$sql = "SELECT * from v_stock where  stk_cantidad > 0 order by mer_descripcion";
$sqlCount = "SELECT count(*) FROM v_cobros WHERE cob_estado ='PENDIENTE' ";

$offset = ($page - 1) * $fila;
$result = consultas::get_datos($sql);  
$resultCount = consultas::get_datos($sqlCount); 

if ($result) {
    $data = array(); // Inicializa un array para almacenar los resultados

    // Itera sobre los resultados y agrégalos al array
    foreach ($result as $row) {
        $data[] = array(
            "id" => $row['id_mercaderia'], // Se agrega un campo "id" para identificar de manera única cada fila
            "cell" => array(
                $row['id_mercaderia'],
                $row['mer_descripcion'],
                1,
                number_format($row['mer_precio'], 0, ',', '.'),
                $row['mer_tipoimpuesto'],
                $row['stk_cantidad'],
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
