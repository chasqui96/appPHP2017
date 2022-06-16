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
        <link rel="stylesheet" type="text/css" href="../../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" type="text/css" href="../../css/chosenselect.css" media="screen">
        <style>
            body {
                background: #2aabd2; /* fallback for old browsers */
                background: -webkit-linear-gradient(right, #204d74, #2aabd2);
                background: -moz-linear-gradient(right, #204d74, #2aabd2);
                background: -o-linear-gradient(right, #204d74, #2aabd2);
                background: linear-gradient(to left, #204d74, #2aabd2);
                font-family: "Roboto", sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;      
            }
            form {
                background: #A9D0F5; /* fallback for old browsers */
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
            #contentdetalle {
                background: #A9D0F5; /* fallback for old browsers */
                background: #A9D0F5; /* fallback for old browsers */
                background: -webkit-linear-gradient(left, #A9D0F5, #E0F8F1);
                background: -moz-linear-gradient(left, #A9D0F5, #E0F8F1);
                background: -o-linear-gradient(left, #A9D0F5, #E0F8F1);
                background: linear-gradient(to right, #A9D0F5, #E0F8F1);
                font-family: "Roboto", sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;   
                padding-top: 10px; 
                border-radius: 10px; 
                padding-left: 10px; 
                padding-right: 10px;
                padding-bottom: 10px;
            }

            #bodyBuscador {
/*                 bottom: 5%;
                position: fixed;
                _position:absolute;
                clip:inherit;*/
                background: #A9D0F5; /* fallback for old browsers */
                background: #A9D0F5; /* fallback for old browsers */
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
                _position:absolute;
                clip:inherit;
            }

        </style>
    </head>
    <body> 


        <div class="container" style="padding-top: 30px;">


            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="formulario" role="form">
                        <div class="form-group">
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






                        <div class="form-group">
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
                            <div class="col-sm-1">
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
                            </div>
                        </div>





                        <div class="form-group">
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
                                        }" disabled=""/>
                            </div>
                            <div class="col-sm-2">
                                <label for="txtvencantcuotas" class="control-label">Cantidad de Cuotas</label>
                            </div>
                            <div class="col-sm-1">
                                <input min="1" type="number" class="form-control" value="1" required id="txtvencantcuotas" onblur="if (this.value === '') {
                                            this.value = 1;
                                        }" disabled=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-2">
                                <label for="cboidcliente" class="control-label">Clientes</label>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control chosen-select" id="cboidclientes" disabled="">
                                    <?php
                                    $sqlclientes = "select * from clientes order by 2";
                                    $rsclientes = consultas::get_datos($sqlclientes);
                                    foreach ($rsclientes as $rscliente) {
                                        ?>
                                        <option value="<?php echo $rscliente['id_cliente']; ?>"><?php echo $rscliente['cli_razonsocial'] . ' - Ruc: ' . $rscliente['cli_ruc']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <small><a class="btn btn-sm btn-info" role="button" data-title="Agregar"
                                          target="_blank" href="../../referenciales/ref_ventas/clientes/"><span class="glyphicon glyphicon glyphicon-plus"></span></a>
                                </small>
                            </div>
                        </div>


                        <input type="hidden" class="form-control" id="operacion" value="1">
                        <input type="hidden" class="form-control" id="txtnrofactura" value="0"><!-- Ya se calcula en el sp_ventas -->
                        <input type="hidden" class="form-control" id="txtidaper" value="<?php echo $resultaper[0]['id_apercierre']; ?>">
                        <input type="hidden" class="form-control" id="txtidtimbrado" value="<?php echo $resultim[0]['id_timbrado']; ?>">
                        <input type="hidden" class="form-control" id="txtdetalles">
                        <input type="hidden" class="form-control" id="txtusuario" value="<?php echo $_SESSION['id_usuario']; ?>">
                        <input type="hidden" class="form-control" id="txtcodigo" value="0"> <!-- Se calcula en el sp_ventas -->

                        <div class="panel panel-primary" id="contentdetalle">

                            <div class="panel-heading"><strong>Detalles</strong></div>
                            <div class="panel-body text-info">

                                <div class="form-group">
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

                            <table class="table"  id="grilladetalle">
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
                            </table>
                        </div>


                    </form>
                </div>
            </div>
        </div>


        <div id="panelBuscador" class="modal" style="top: 5%;">
            <div class="modal-lg" style="width: 100%">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Buscador de facturas</h4>
                    </div>
                    <div class="modal-body" id="bodyBuscador">
                        <div class="section">
                            <div class="container"  style="width: 100%;">
                                        <div class="form-group">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-4">
                                                <input type="text" name="txtbuscador" id="txtbuscador" class="form-control" placeholder="Ingrese datos a buscar"/>
                                            </div>
                                            <div class="col-md-2">
                                                <input id="btnBuscar" type="button" class="form-control btn-primary" value="Buscar" onclick="get_datos($('#txtbuscador').val());$('#txtbuscador').val('');">
                                            </div>
                                        </div>
                                                <table class="table table-hover" id="grillaBusc">
                                                    <thead>
                                                        <tr>
                                                            <th class="info text-center">#</th>
                                                            <th class="info text-center">Factura</th>
                                                            <th class="info">Cliente</th>
                                                            <th class="info">Ruc</th>
                                                            <th class="info">Fecha</th>
                                                            <th class="info">Tipo Factura</th>
                                                            <th class="info text-right">Monto</th>
                                                            <th class="hidden">idVend</th>
                                                            <th class="hidden">idDep</th>
                                                            <th class="hidden">IntCuota</th>
                                                            <th class="hidden">CantCtas</th>
                                                            <th class="hidden">idClie</th>
                                                        </tr>

                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                </div>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>





        <div id='panelBotones'  style="text-align: right;">
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
        <script src="../../js/jquery-1.12.2.min.js"></script>

        <!-- Todos los plugins JavaScript de Bootstrap (también puedes
             incluir archivos JavaScript individuales de los únicos
             plugins que utilices) -->
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/menuresponsive.js"></script>
        <script src="../../js/chosenselect.js"></script>
        <script src="../../js/bootbox.min.js"></script>
        <script src="metodosjs.js"></script>

    </body>
</html>