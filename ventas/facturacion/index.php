<?php
require_once '../../clases/conexion.php';
session_start();


$sqlaper = "select * from v_aperturas where id_usuario = " . $_SESSION['id_usuario'] . " and cierre_fecha is null order by 1";
$resultaper = consultas::get_datos($sqlaper);
if ($resultaper) {
    $_SESSION['idapertura'] = $resultaper[0]['id_apercierre'];
} else {
    $_SESSION['idapertura'] = null;
    header('location:../apertura_cierres/index.php');
}

$sqltim = "select * from timbrados where tim_fechavence >= current_date";
$resultim = consultas::get_datos($sqltim);
if (!$resultim) {
    header('location:../../referenciales/ref_empresa/timbrados/index.php');
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Factura</title>

    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../../src/bootstrap-5.3.2/css/bootstrap.min.css">

    <!-- <link rel="stylesheet" type="text/css" href="../../src/jqgrid/css/ui.jqgrid-bootstrap5.css"> -->

    <link rel="stylesheet" type="text/css" href="../../src/jqgrid/css/ui.jqgrid-bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="../../src/jqgrid/css/addons/ui.multiselect.css">

    <link rel="stylesheet" type="text/css" href="../../src/css/icon.css">

    <link rel="stylesheet" type="text/css" href="../../src/css/chosenselect.css">
    <style>
        body {
            background: #2aabd2;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #204d74, #2aabd2);
            background: -moz-linear-gradient(right, #204d74, #2aabd2);
            background: -o-linear-gradient(right, #204d74, #2aabd2);
            background: linear-gradient(to left, #204d74, #2aabd2);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        #formulario {
            background: #A9D0F5;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #A9D0F5, #E0F8F1);
            background: -moz-linear-gradient(right, #A9D0F5, #E0F8F1);
            background: -o-linear-gradient(right, #A9D0F5, #E0F8F1);
            background: linear-gradient(to left, #A9D0F5, #E0F8F1);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding-top: 15px;
            border-radius: 15px;
            padding-left: 15px;
            padding-right: 15px;
            padding-bottom: 1px;
        }

        #contentdetalle {}

        #bodyBuscador {
            /*                 bottom: 5%;
                position: fixed;
                _position:absolute;
                clip:inherit;*/
            background: #A9D0F5;
            /* fallback for old browsers */
            background: #A9D0F5;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(left, #A9D0F5, #E0F8F1);
            background: -moz-linear-gradient(left, #A9D0F5, #E0F8F1);
            background: -o-linear-gradient(left, #A9D0F5, #E0F8F1);
            background: linear-gradient(to right, #A9D0F5, #E0F8F1);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;

        }

        #panelBotones {
            right: 0%;
            top: 5%;
            position: fixed;
            _position: absolute;
            clip: inherit;
        }
    </style>
</head>

<body>


    <div class="container" style="padding-top: 30px;">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../menu.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gestionar Ventas</li>
                <li class="breadcrumb-item active" aria-current="page">Facturacion</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" id="formulario" role="form">
                    <div class="card m-2">
                        <div class="card-header">Cabecera</div>
                        <div class="card-body">
                            <div class="row m-1">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2">
                                    <label for="txtnrofactura" class="control-label">Numero de Factura</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtnrofactura" value="<?php echo $resultaper[0]['siguiente_factura']; ?>" disabled>
                                </div>
                                <div class="col-sm-1">
                                    <label for="txttimbrado" class="control-label">Timbrado</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txttimbrado" value="<?php echo $resultim[0]['tim_numero']; ?>" disabled>
                                </div>
                                <div class="col-sm-1">
                                    <label for="txtfecha" class="control-label">Fecha</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="txtfecha" value="<?php echo date('d/m/Y'); ?>" disabled>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2">
                                    <label for="cboidfuncionario" class="control-label">Vendedor</label>
                                </div>
                                <div class="col-sm-4">
                                    <select class="form-control chosen-select" id="cboidfuncionario" disabled="">
                                        <?php
                                        $sqlvendedores = "select * from v_funcionarios where car_descripcion = 'VENDEDOR' and id_sucursal = " . $_SESSION['id_sucursal'] . " order by 2";
                                        $rsvendedores = consultas::get_datos($sqlvendedores);

                                        foreach ($rsvendedores as $rsvendedor) {
                                        ?>
                                            <option value="<?php echo $rsvendedor['id_funcionario']; ?>"><?php echo $rsvendedor['fun_nombres'] . ' ' . $rsvendedor['fun_apellidos'] . ' - CI: ' . $rsvendedor['fun_ci']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- <div class="col-sm-1">
                                    <label for="cboiddeposito" class="control-label">Deposito</label>
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control chosen-select" id="cboiddeposito" onchange="getMercaderias();" disabled="">
                                        <?php
                                        $sqldepositos = "select * from v_depositos where id_sucursal = " . $_SESSION['id_sucursal'] . " order by 2";
                                        $rsdepositos = consultas::get_datos($sqldepositos);
                                        foreach ($rsdepositos as $rsdeposito) {
                                        ?>
                                            <option value="<?php echo $rsdeposito['id_deposito']; ?>"><?php echo $rsdeposito['dep_descripcion']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div> -->
                            </div>
                            <div class="row m-1">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2">
                                    <label for="cboventipo" class="control-label">Tipo Factura</label>
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control chosen-select" id="cboventipo" onchange="tiposelect();" disabled="">

                                        <option value="CONTADO">CONTADO</option>
                                        <option value="CREDITO">CREDITO</option>

                                    </select>
                                </div>

                                <div class="col-sm-2">
                                    <label for="txtvenintervalo" class="control-label">Intervalo Cuotas</label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="number" class="form-control" value="0" id="txtvenintervalo" onblur="if (this.value === '') {
                                            this.value = 0;
                                        }" disabled="" />
                                </div>
                                <div class="col-sm-2">
                                    <label for="txtvencantcuotas" class="control-label">Cantidad de Cuotas</label>
                                </div>
                                <div class="col-sm-1">
                                    <input min="1" type="number" class="form-control" value="1" required id="txtvencantcuotas" onblur="if (this.value === '') {
                                            this.value = 1;
                                        }" disabled="" />
                                </div>
                            </div>

                            <div class="row m-1">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2">
                                    <label for="cboidcliente" class="control-label">Clientes</label>
                                </div>
                                <div class="col-sm-6">
                                    <select class="form-control chosen-select" id="cboidclientes" disabled="">
                                        <?php
                                        $sqlclientes = "select * from clientes order by 1 desc";
                                        $rsclientes = consultas::get_datos($sqlclientes);
                                        foreach ($rsclientes as $rscliente) {
                                        ?>
                                            <option value="<?php echo $rscliente['id_cliente']; ?>"><?php echo $rscliente['cli_razonsocial'] . ' - Ruc: ' . $rscliente['cli_ruc']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <small><a class="btn btn-sm btn-info" role="button" data-title="Agregar" data-bs-toggle="modal" data-bs-target="#modalCliente"><span class="glyphicon glyphicon glyphicon-plus"></span></a>
                                    </small>
                                </div>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" class="form-control" id="operacion" value="1">
                    <input type="hidden" class="form-control" id="txtnrofactura" value="0"><!-- Ya se calcula en el sp_ventas -->
                    <input type="hidden" class="form-control" id="txtidaper" value="<?php echo $resultaper[0]['id_apercierre']; ?>">
                    <input type="hidden" class="form-control" id="txtidtimbrado" value="<?php echo $resultim[0]['id_timbrado']; ?>">
                    <input type="hidden" class="form-control" id="txtdetalles">
                    <input type="hidden" class="form-control" id="txtusuario" value="<?php echo $_SESSION['id_usuario']; ?>">
                    <input type="hidden" class="form-control" id="txtcodigo" value="0"> <!-- Se calcula en el sp_ventas -->
                    <div class="card m-3">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">

                            <button type="button" id="habilitarEdicion" class="btn btn-outline-info" disabled><span class="glyphicon glyphicon-pencil"></span></button>
                            <button type="button" id="cerrarEdicion" class="btn btn-outline-info" disabled><span class="glyphicon glyphicon-refresh"></span></button>
                            <button type="button" id="guardarVenta" class="btn btn-outline-info" disabled><span class="glyphicon glyphicon-floppy-disk"></span></button>
                            <button type="button" id="cobrarVenta" class="btn btn-outline-info" disabled><span class="	glyphicon glyphicon-credit-card"></span></button>

                        </div>
                    </div>
                    <div class="card m-2" id="contentdetalle">

                        <div class="card-header"><strong>Detalles</strong></div>
                        <div class="card-body align-self-center">
                            <div class="row ">

                                <div class="col ">
                                    <!-- Contenido de tu div -->
                                    <table id="gridDetalleVenta"></table>

                                </div>
                                <div class="col">
                                    <div class="col-sm-1">
                                        <small><a class="btn btn-sm btn-info" role="button" data-title="Agregar" id="openModalBtn"><span class="glyphicon glyphicon glyphicon-plus"></span></a>
                                        </small>
                                    </div>


                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- <div class="row">
                                <div class="col-sm-1">
                                    <label for="cbomercaderias" class="control-label">Mercaderia</label>
                                </div>
                                <div class="col-sm-6">

                                    <select class="form-control chosen-select" id="cbomercaderias" onchange="merselect();" disabled="">

                                    </select>
                                    <div id="prog">

                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <label for="txtcantidad" class="control-label">Cantidad</label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="number" min="1" class="form-control" id="txtcantidad" onfocus="merselect()" disabled="">
                                </div>
                                <div class="col-sm-1">
                                    <label for="txtprecio" class="control-label">Precio</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" id="txtprecio" disabled="">
                                </div>


                            </div>



                        </div>

                        <table class="table" id="grilladetalle">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th>Mercadería</th>
                                    <th style="text-align: center;">Cantidad</th>
                                    <th style="text-align: right;">Precio</th>
                                    <th style="text-align: right;">Exenta</th>
                                    <th style="text-align: right;">Grav. 5%</th>
                                    <th style="text-align: right;">Grav. 10%</th>
                                    <th style="text-align: right;"></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table> -->
            </div>


            </form>
        </div>
    </div>
    </div>


    <div class="modal fade " id="panelBuscador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">BUSCADOR PRODUCTO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body align-self-center">
                    <div class="row">
                        <div class="col-md-3">

                            <label class="control-label">Depósito</label>

                            <select class="form-control chosen-select" id="cboiddepositobuscador" onchange="filtrarPorDeposito()">
                                <option value="0">Seleccione</option>
                            </select>
                        </div>

                    </div>
                    <div class="row mt-2">

                        <div class="col-md-12">
                            <table id="productosGrid"></table>
                            <div id="productosPager"></div>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="agregarDetalleBtn" data-bs-dismiss="modal">Agregar Detalle</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalClienteLabel">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body align-content-center">
                  
                    <div class="card">
                        <div class="card-header">
                            Cliente
                        </div>
                        <div class="card-body">
                            <form id="formCrearCliente" method="post">
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="numero_documento" placeholder="Ingrese RUC" aria-label="Recipient's username" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-primary" type="button" id="buscarClienteBtn"><span class="glyphicon glyphicon-search"></span></button>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div id="resultadoCliente">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="inputCity" class="form-label">RUC/CI</label>
                                            <input type="text" class="form-control" name="ruc_numero" id="ruc_numero">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="inputState" class="form-label">DV</label>   
                                            <input type="text" class="form-control" name="dv"  id="dv" >
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputZip" class="form-label">Razon Social:</label>
                                            <input type="text" class="form-control" name="razon_social"  id="razon_social">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="inputCity" class="form-label">Correo</label>
                                        <input type="text" class="form-control" name="correo"  id="correo" >
                                    </div>
                                    <div class="col-md-4">
                                    <label for="inputCity" class="form-label">Ciudad</label>
                                        <select id="id_ciudad" name="id_ciudad" class="form-control chosen-select">
                                            <?php
                                            $sqlsuc = "select * from ciudades order by 1 desc";
                                            $result = consultas::get_datos($sqlsuc);
                                            foreach ($result as $rs) {
                                            
                                            ?>
                                            <option value="<?php echo $rs['id_ciudad'];?>">
                                                <?php echo $rs['ciu_descripcion'];?>
                                            </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                  
                                   
                                </div>
                                <div class="row">
                                   <div class="col-md-6">
                                        <label for="inputCity" class="form-label">Direccion</label>
                                        <input type="text" class="form-control" name="direccion"  id="direccion" >
                                    </div>
                                    <div class="col-md-3">
                                   
                                        <label for="inputCity" class="form-label">Telefono</label>
                                        <input type="text" class="form-control" name="telefono" id="telefono" >
                            
                                    </div>
                                   <div class="col-md-3" style="margin-top: 2rem!important;">
                                        <label for="clienteFisico">Física</label>
                                        <input type="checkbox" id="clienteFisico" name="tipoCliente" value="1" >
                                        
                                        <label for="clienteJuridico">Jurídica</label>
                                        <input type="checkbox" id="clienteJuridico" name="tipoCliente" value="2" >
                                   </div>
                                </div>
                               
                            </form>


                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="agregarClienteBtn" >Guardar</button>
                    <button type="button" class="btn btn-secondary" onclick="limpiarFormulario();" id="limpiarClienteBtn">Limpiar</button>

                </div>
            </div>
        </div>
    </div>



    <div id='panelBotones' style="text-align: right;">
        <div class="row">
            <div class="col-md-1">
                <div class="form-group">
                    <div class="col-md-1 text-right">
                        <button onclick="agregar();" id="btnAgregar" class="btn btn-success" title="Agregar"><span class="glyphicon glyphicon-plus"></span></button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-1 text-right">
                        <button onclick="anular(); $('#btnBuscador').click();" id="btnAnular" class="btn btn-danger" title="Anular"><span class="glyphicon glyphicon-minus"></span></button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-1 text-right">
                        <button onclick="location.reload();" id="btnCancelar" class="btn btn-warning" title="Cancelar" disabled=""><span class="glyphicon glyphicon-remove"></span></button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-1 text-right">
                        <button onclick="grabar();" id="btnGrabar" class="btn btn-info" title="Grabar" disabled=""><span class="glyphicon glyphicon-floppy-disk"></span></button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-1 text-right">
                        <button onclick="" id="btnSalir" class="btn btn-primary" title="Salir"><span class="glyphicon glyphicon-home"></span></button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-1 text-right">
                        <button data-toggle="modal" data-target="#panelBuscador" class="btn btn-info" title="Buscar" id="btnBuscador"><span class="glyphicon glyphicon-search"></span></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Librería jQuery requerida por los plugins de JavaScript -->
    <script src="../../src/js/jquery-3.7.1.min.js"></script>
    <script src="../../src/js/autoNumeric.js"></script>
    <script src="../../src/bootstrap-5.3.2/js/bootstrap.min.js"></script>
    <script src="../../src/jqgrid/js/jquery.jqgrid.min.js"></script>
    <script src="../../src/jqgrid/js/jquery.sortable.js"></script>
    <script src="../../src/jqgrid/js/grid.formedit.js"></script>
    <script src="../../src/jqgrid/js/i18n/grid.locale-es.js"></script>


    <script src="../../js/chosenselect.js"></script>
    <script src="../../src/bootbox/bootbox.all.js"></script>
    <script src="metodosjs.js"></script>
  <style>


/* Estilo del checkbox personalizado cuando está marcado */


/* Estilo del indicador (marcado) dentro del checkbox */
input[type="checkbox"] {

    width: 25px; /* Tamaño del indicador */
    height: 25px; /* Tamaño del indicador */

}




  </style>
</body>

</html>