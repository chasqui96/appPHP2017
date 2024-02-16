<?php
require_once '../../clases/conexion.php';

// Parámetros de búsqueda enviados por jqGrid
session_start();
$mercaderiaCodigo = isset($_GET['id']) ? $_GET['id'] : '';
$mercaderiaDescripcion = isset($_GET['mer_descripcion']) ? $_GET['mer_descripcion'] : '';
$mercaderiaPrecio = isset($_GET['mer_precio']) ? $_GET['mer_precio'] : '';
$mercaderiaStock = isset($_GET['stk_cantidad']) ? $_GET['stk_cantidad'] : '';
$depositoDescripcion = isset($_GET['dep_descripcion']) ? $_GET['dep_descripcion'] : '';

$page = $_GET['page'];
$fila= $_GET['rows'];
$sord = $_GET['sord'];
$condiciones = array();
$id_deposito = (isset($_GET['id_deposito']) ? $_GET['id_deposito'] : 0 );
if($id_deposito != 0){
    $sql = "SELECT * FROM v_stock";
    $condiciones[] =  "id_deposito = ".$id_deposito;
}else{
    $sql = "SELECT * FROM v_stock ";
}
// Obtener el número total de filas sin paginación
$sqlCount = "SELECT COUNT(*) FROM v_stock";
// Establecer la consulta SQL base

$condiciones[]=" stk_cantidad > 0";
if (!empty($mercaderiaCodigo)) {
    $condiciones[] = "stk_cantidad=". $mercaderiaCodigo;
}
// Si hay un término de búsqueda para siguiente_factura, agregamos la condición correspondiente
if (!empty($mercaderiaDescripcion)) {
    $condiciones[] = "mer_descripcion LIKE '%$mercaderiaDescripcion%'";
}
if (!empty($mercaderiaPrecio)) {
    $condiciones[] = "mer_precio=". $mercaderiaPrecio;
}
// Si hay un término de búsqueda para caja_descripcion, agregamos la condición correspondiente
if (!empty($mercaderiaStock)) {
    $condiciones[] = "stk_cantidad=". $mercaderiaStock;
}
if (!empty($depositoDescripcion)) {
    $condiciones[] = "dep_descripcion LIKE '%$depositoDescripcion%'";
}

if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}
if (!empty($condiciones)) {
    $sqlCount .= " WHERE " . implode(" AND ", $condiciones);
}

$offset = ($page - 1) * $fila;

// Modifica la consulta SQL para incluir la limitación de resultados
$sql .= " LIMIT $fila OFFSET $offset";





$offset = ($page - 1) * $fila;
$result = consultas::get_datos($sql);  
$resultCount = consultas::get_datos($sqlCount); 

if ($result) {
    $data = array(); // Inicializa un array para almacenar los resultados

    // Itera sobre los resultados y agrégalos al array
    foreach ($result as $row) {
        $data[] = array(
            "id" => $row['codigo'], // Se agrega un campo "id" para identificar de manera única cada fila
            "cell" => array(
                $row['codigo'],
                $row['mer_descripcion'],
                1,
                number_format($row['mer_precio'], 0, ',', '.'),
                $row['mer_tipoimpuesto'],
                $row['stk_cantidad'],
                $row['dep_descripcion'],
                $row['id_deposito'],
                $row['id_mercaderia'],
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
