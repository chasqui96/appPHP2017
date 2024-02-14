<?php
require_once '../../clases/conexion.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APERTURA/CIERRE DE CAJAS</title>

    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../../src/bootstrap-5.3.2/css/bootstrap.min.css">

    <!-- <link rel="stylesheet" type="text/css" href="../../src/jqgrid/css/ui.jqgrid-bootstrap5.css"> -->
  
    <link rel="stylesheet" type="text/css" href="../../src/jqgrid/css/ui.jqgrid-bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="../../src/jqgrid/plugins/css/ui.multiselect.min.css">
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

        form {
            background: #A9D0F5;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #A9D0F5, #E0F8F1);
            background: -moz-linear-gradient(right, #A9D0F5, #E0F8F1);
            background: -o-linear-gradient(right, #A9D0F5, #E0F8F1);
            background: linear-gradient(to left, #A9D0F5, #E0F8F1);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>

<body>




    <div class="container" style="padding-top: 30px;">
 
        <div class="row ">
            
            <div class="col-md-12">

                <form id="formulario" class="form-horizontal" role="form" style="background-color: lemonchiffon; padding-top: 20px; border-radius: 15px; padding-left: 15px; padding-right: 15px;">

                    <div class="card m-3">
                        <div class="card-header">Detalles</div>
                        <div class="card-body">
                            <div class="row m-3">
                                <div class="col-md-2 text-right">
                                    <label class="control-label" for="codigo">Código</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="txtcodigo" id="txtcodigo" title="txtcodigo" class="form-control" placeholder="Autoincrementable" disabled />
                                </div>
                                <div class="col-md-2 text-right">
                                    <label class="control-label" for="txtmontoini">Monto Inicial</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="txtmontoini" id="txtmontoini" title="txtmontoini" class="form-control" placeholder="Monto" disabled />
                                </div>
                                <div class="col-md-2 text-right">
                                    <label class="control-label" for="caja">Caja</label>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="cbocaja" id="cbocaja" title="cbocaja" disabled="">
                                        <?php

                                        date_default_timezone_set('America/Asuncion');
                                        session_start();
                                        $sqlsuc = "select * from cajas where id_sucursal = " . $_SESSION['id_sucursal'] . " and caja_estado = 'CERRADA' order by 2";
                                        $rssuc = consultas::get_datos($sqlsuc);
                                        if (!empty($rssuc)) {
                                            foreach ($rssuc as $rss) {
                                        ?>
                                                <option value="<?php echo $rss['id_caja']; ?>"><?php echo $rss['caja_descripcion']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row m-3">

                                <div class="col-md-2 text-right">
                                    <label class="control-label" for="feaper">Fecha Apertura</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="txtfeaper" id="txtfeaper" title="txtfeaper" class="form-control" value="<?php echo date('d/m/Y H:i:s'); ?>" disabled="" />
                                </div>

                                <div class="col-md-2 text-right">
                                    <label class="control-label" for="fecierre">Fecha Cierre</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="txtfecierre" id="txtfecierre" title="txtfecierre" class="form-control" value="" disabled="" />
                                </div>

                                <div class="col-md-2 text-right">
                                    <label class="control-label" for="montocierre">Monto Cierre</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="txtmontocierre" id="txtmontocierre" title="txtmontocierre" class="form-control" value="" disabled="" />

                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-1"></div>
                                <div class="col-md-2 text-right">
                                    <input id="btnAbrir" type="button" class="form-control btn-primary" value="Abrir" onclick="apertura();" />
                                </div>

                                <div class="col-md-2 text-right">
                                    <input id="btnCerrar" type="button" class="form-control btn-danger" value="Cerrar" onclick="cierre();" disabled="" />
                                </div>
                                <div class="col-md-2 text-right">
                                    <input id="btnCancelar" type="button" class="form-control btn-info" value="Cancelar" disabled="" onclick="cancelar();" />
                                </div>
                                <div class="col-md-2 text-right">
                                    <input id="btnGrabar" type="button" class="form-control btn-success" value="Grabar" disabled="" onclick="grabar();" />
                                </div>
                                <div class="col-md-2 text-right">
                                    <a href="../../menu.php"><input id="btnSalir" type="button" class="form-control btn-default" value="Salir"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card m-3">
                        <div class="card-header">Buscador</div>
                        <div class="card-body">
                            <div class="row m-3">
                                <div class="col-md-3"></div>
                                <div class="col-md-4">
                                    <input type="text" name="txtbuscador" id="txtbuscador" class="form-control" placeholder="Ingrese datos a buscar" />
                                </div>
                                <div class="col-md-2">
                                    <input id="btnBuscar" type="button" class="form-control btn-primary" value="Buscar" onclick="get_datos($('#txtbuscador').val());$('#txtbuscador').val('');">
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="card m-3">
                        <div class="card-header">
                            Tabla
                        </div>
                        <div class="card-body align-self-center" >
                            <div class="row ">
                                <div class="col " >
                                    <!-- Contenido de tu div -->
                                    <table id="gridCobros"></table>
                                    <div id="jqGridPager"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="operacion" />
                    <input type="hidden" id="idcaja" value="<?php echo $_SESSION['idcaja']; ?>" />
                    <input type="hidden" id="idusuario" value="<?php echo $_SESSION['id_usuario']; ?>" />
                </form>
            </div>
        </div>
    </div>









    <script src="../../src/js/jquery-3.7.1.min.js"></script>
    <script src="../../src/js/autoNumeric.js"></script>
    <script src="../../src/bootstrap-5.3.2/js/bootstrap.min.js"></script>
    <script src="../../src/jqgrid/js/jquery.jqgrid.min.js"></script>
    <script src="../../src/jqgrid/js/jquery.sortable.js"></script>
    <script src="../../src/jqgrid/js/i18n/grid.locale-es.js"></script>


    <script src="../../js/chosenselect.js"></script>
    <script src="../../src/bootbox/bootbox.all.js"></script>
    <script src="metodosjs.js"></script>

</body>

</html>