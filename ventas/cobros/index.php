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
        <meta charset="utf-8"><!-- CODIFICACION DE CARACTERES (ACENTOS, Ñ, ETC.) -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cobros</title>

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
            
            #imagenFlotante {

                right: 1%;
                bottom: 1%;
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
                    <form class="form-horizontal" id="formulario" role="form" action="grabar.php" method="post">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <label for="nrofactura" class="control-label">Numero de Recibo</label>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="nrofactura" value="<?php echo $resultaper[0]['siguiente_recibo']; ?>" disabled>
                            </div>
                            <div class="col-sm-1">
                                <label for="timbrado" class="control-label">Timbrado</label>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="timbrado" value="<?php echo $resultim[0]['tim_numero']; ?>" disabled>
                            </div>
                            <div class="col-sm-1">
                                <label for="fecha" class="control-label">Fecha</label>
                            </div>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="fecha" value="<?php echo date('Y-m-d'); ?>" disabled>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-2">
                                <label for="idcliente" class="control-label">Clientes</label>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control chosen-select" name="idcliente" id="idclientes" onchange="getCuentas();">
                                    <?php
                                    $sqlclientes = "select * from clientes where id_cliente in (select id_cliente from v_cuentas_cobrar where cta_saldo > 0) order by 2";
                                    $rsclientes = consultas::get_datos($sqlclientes);
                                    foreach ($rsclientes as $rscliente) {
                                        ?>
                                        <option value="<?php echo $rscliente['id_cliente']; ?>"><?php echo $rscliente['cli_razonsocial'] . ' - Ruc: ' . $rscliente['cli_ruc']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                        </div>


                        <input type="hidden" class="form-control" id="operacion" value="1">
                        <input type="hidden" class="form-control" id="nrofactura" value="0"><!-- Ya se calcula en el sp_ventas -->
                        <input type="hidden" class="form-control" id="idaper" value="<?php echo $resultaper[0]['id_apercierre']; ?>">
                        <input type="hidden" class="form-control" id="idtimbrado" value="<?php echo $resultim[0]['id_timbrado']; ?>">
                        <input type="hidden" class="form-control" id="detallecobro" id="detallecobro">
                        <input type="hidden" class="form-control" id="detallecheque" id="detallecheque">
                        <input type="hidden" class="form-control" id="detalletarjeta" id="detalletarjeta">
                        <input type="hidden" class="form-control" id="codigo" value="0"> <!-- Se calcula en el sp_ventas -->
                        <input type="hidden" class="form-control" id="totalcobrar" id="totalcobrar" value="0"> <!-- Se calcula en el sp_ventas -->
                        <input type="hidden" class="form-control" id="totalcheques" id="totalcheques" value="0"> <!-- Se calcula en el sp_ventas -->
                        <input type="hidden" class="form-control" id="totaltarjetas" id="totaltarjetas" value="0"> <!-- Se calcula en el sp_ventas -->

                        <div class="panel panel-primary">

                            <div class="panel-heading"><strong>Detalles</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="col-sm-1">
                                        <label for="cuentas" class="control-label">Cuentas</label>
                                    </div>
                                    <div class="col-sm-8">

                                        <select class="form-control chosen-select" name="cuentas" id="cuentas" onchange="cuentaselect();">

                                        </select>
                                        <div id="prog">

                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <label for="saldo" class="control-label">Saldo</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" name="saldo" id="saldo">
                                    </div>

                                </div>



                            </div>

                            <table class="table"  id="grilladetalle">
                                <thead>
                                    <tr>
                                        <th hidden="">#venta</th>
                                        <th style="text-align: center;">#Cuota</th>
                                        <th style="text-align: center;">#Factura</th>
                                        <th style="text-align: center;">Vencimiento</th>
                                        <th style="text-align: right;">Monto</th>
                                        <th style="text-align: right;"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>

                        <div class="panel panel-info">

                            <div class="panel-heading"><strong>Formas de Cobros</strong> <span class="label label-primary right-block" id="lbtotalcobrado" style="padding-left: 5px; padding-right: 5px; font-size: large;">0</span></div>
                            <div class="panel-body">

                                <div class="panel-group" id="acordeon">
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#acordeon" href="#cobroefectivo">Cobro en Efectivo <span class="label label-success right-block" id="lbmontoefe" style="background-color: green; color: white; padding-left: 5px; padding-right: 5px; font-size: medium;">0</span></a>
                                            </h4>
                                        </div>
                                        <div id="cobroefectivo" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-sm-2">
                                                        <label for="efectivo" class="control-label">Efectivo</label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" name="efectivo" id="efectivo" value="0" onkeyup="calcularVuelto();">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#acordeon" href="#cobrocheque">Cobro con Cheques <span class="label label-success right-block" id="lbmontocheque" style="background-color: green; color: white; padding-left: 5px; padding-right: 5px; font-size: medium;">0</span></a>
                                            </h4>
                                        </div>
                                        <div id="cobrocheque" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-sm-1">
                                                        <label for="identidad" class="control-label">Entidad</label>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <select class="form-control chosen-select" name="identidad" id="identidad">
                                                            <?php
                                                            $sqlentidades = "select * from entidad_emisoras";
                                                            $rsentidades = consultas::get_datos($sqlentidades);
                                                            foreach ($rsentidades as $rsentidad) {
                                                                ?>
                                                                <option value="<?php echo $rsentidad['id_entidad']; ?>"><?php echo $rsentidad['ent_descripcion']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="numero" class="control-label">Numero</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="number" class="form-control" name="numeroch" id="numeroch" value="0">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="titular" class="control-label">Titular</label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" name="titularch" id="titularch">
                                                    </div>
                                                    
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-1">
                                                        <label for="emision" class="control-label">Emision</label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="date" class="form-control" name="emision" id="emision">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="vence" class="control-label">Vence</label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="date" class="form-control" name="vence" id="vence">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="importech" class="control-label">Importe</label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" name="importech" id="importech">
                                                    </div>
                                                    
                                                </div>
                                                <table class="table"  id="grillacheques">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center;" hidden="">idEntidad</th>
                                                            <th style="text-align: left;">Entidad</th>
                                                            <th style="text-align: center;">Nro. Cheque</th>
                                                            <th style="text-align: left;">Titular</th>
                                                            <th style="text-align: center;">Emisión</th>
                                                            <th style="text-align: center;">Vencimiento</th>
                                                            <th style="text-align: right;">Importe</th>
                                                            <th style="text-align: right;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#acordeon" href="#cobrotarjeta">Cobro con Tarjetas <span class="label label-success right-block" id="lbmontotarjeta" style="background-color: green; color: white; padding-left: 5px; padding-right: 5px; font-size: medium;">0</span></a>
                                            </h4>
                                        </div>
                                        <div id="cobrotarjeta" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-1">
                                                        <label for="idtarjeta" class="control-label">Tarjeta</label>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <select class="form-control chosen-select" name="idtarjeta" id="idtarjeta">
                                                            <?php
                                                            $sqltarjetas = "select * from v_tarjetas";
                                                            $rstarjetas = consultas::get_datos($sqltarjetas);
                                                            foreach ($rstarjetas as $rstarjeta) {
                                                                ?>
                                                                <option value="<?php echo $rstarjeta['id_tarjeta']; ?>"><?php echo $rstarjeta['tarjeta']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="identidadtar" class="control-label">Entidad</label>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <select class="form-control chosen-select" name="identidadtar" id="identidadtar">
                                                            <?php
                                                            $sqlentidadestar = "select * from entidad_emisoras";
                                                            $rsentidadestar = consultas::get_datos($sqlentidadestar);
                                                            foreach ($rsentidadestar as $rsentidad) {
                                                                ?>
                                                                <option value="<?php echo $rsentidad['id_entidad']; ?>"><?php echo $rsentidad['ent_descripcion']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    
                                                    
                                                    
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-1">
                                                        <label for="numerotar" class="control-label">Numero</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="number" class="form-control" name="numerotar" id="numerotar" value="0">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="caut" class="control-label">C. Aut.</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="number" class="form-control" name="caut" id="caut">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <label for="importetar" class="control-label">Importe</label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" name="importetar" id="importetar">
                                                    </div>
                                                    
                                                    
                                                </div>
                                                
                                                <table class="table"  id="grillatarjetas">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center;" hidden="">idTarj</th>
                                                            <th style="text-align: left;">Tarjeta</th>
                                                            <th style="text-align: center;" hidden="">idEnti</th>
                                                            <th style="text-align: left;">Entidad</th>
                                                            <th style="text-align: center;">Nro. Tarjeta</th>
                                                            <th style="text-align: center;">C. Aut.</th>
                                                            <th style="text-align: right;">Importe</th>
                                                            <th style="text-align: right;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>


                        </div>


                        <div id='imagenFlotante'  style="text-align: right;">
                            <div class="panel panel-danger" style="padding-top: 10px; font-size: x-large; background-color: #A9D0F5;">

                                <p class="label label-primary center-block" id="lbvuelto"><strong>Faltan</strong></p>
                                <p class="label label-danger center-block" id="vuelto"><strong>0</strong></p>

                            </div>
                        </div>




                    </form>
                </div>
            </div>
        </div>


        <div id="panelBuscador" class="modal" style="top: 5%;">
            <div class="modal-lg" style="width: 70%">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Buscador de Cobros</h4>
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
                                                            <th class="info text-center">Recibo</th>
                                                            <th class="info">Cliente</th>
                                                            <th class="info">Ruc</th>
                                                            <th class="info">Fecha</th>
                                                            <th class="hidden">idClie</th>
                                                            <th class="hidden">efectivo</th>
                                                            <th class="hidden">aper</th>
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