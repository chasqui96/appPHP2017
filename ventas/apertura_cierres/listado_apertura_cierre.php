<?php
require_once '../../clases/conexion.php';

// Parámetros de búsqueda enviados por jqGrid
$siguiente_factura = isset($_GET['siguiente_factura']) ? $_GET['siguiente_factura'] : '';
$caja_descripcion = isset($_GET['caja_descripcion']) ? $_GET['caja_descripcion'] : '';
$fecha_aperformat = isset($_GET['fecha_aperformat']) ? $_GET['fecha_aperformat'] : '';
$page = $_GET['page'];
$fila = $_GET['rows'];
$sord = $_GET['sord'];

//$records = $_GET['record'];
$aper_monto = isset($_GET['aper_monto']) ? $_GET['aper_monto'] : '';

// Consulta SQL base
$sql = "SELECT * FROM v_aperturas";
$sqlCount = "SELECT count(*) FROM v_aperturas";

// Inicializamos un array para almacenar las condiciones de búsqueda
$condiciones = array();

// Si hay un término de búsqueda para siguiente_factura, agregamos la condición correspondiente
if (!empty($siguiente_factura)) {
    $condiciones[] = "siguiente_factura LIKE '%$siguiente_factura%'";
}

// Si hay un término de búsqueda para caja_descripcion, agregamos la condición correspondiente
if (!empty($caja_descripcion)) {
    $condiciones[] = "caja_descripcion LIKE '%$caja_descripcion%'";
}

// Si hay un término de búsqueda para fecha_aperformat, agregamos la condición correspondiente
if (!empty($fecha_aperformat)) {
    $condiciones[] = "fecha_aperformat = '$fecha_aperformat'";
}
if (!empty($aper_monto)) {
    $condiciones[] = "aper_monto = $aper_monto";
}

// Si hay alguna condición de búsqueda, agregamos la cláusula WHERE a la consulta SQL
if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}
if (!empty($condiciones)) {
    $sqlCount .= " WHERE " . implode(" AND ", $condiciones);
}

$sql .= " ORDER BY 1 ".$sord; // Ordenar resultados por algún campo, por ejemplo, id_apercierre
// Calcula el offset para la página actual
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
            "id" => $row['id_apercierre'], // Se agrega un campo "id" para identificar de manera única cada fila
            "cell" => array(
                $row['id_apercierre'],
                $row['fecha_aperformat'], 
                $row['fecha_cierreformat'],   
                number_format($row['aper_monto'], 0, ',', '.'),       
                $row['caja_descripcion'], 
                $row['siguiente_factura'],
                $row['id_caja'],
                number_format($row['monto_efectivo']+$row['monto_cheque']+$row['monto_tarjeta'], 0, ',', '.')
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
