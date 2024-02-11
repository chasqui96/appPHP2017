<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>APERTURA/CIERRE DE CAJAS</title>

        <!-- CSS de Bootstrap -->
        <link rel="stylesheet" type="text/css" href="../../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../../src/css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" type="text/css" href="../../css/menuresponsive.css" media="screen">
        <link rel="stylesheet" type="text/css" href="../../css/chosenselect.css" media="screen">
        <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
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
            }
        </style>
    </head>
    <body>


        <div class="container">
            <header>
                <?php
                require '../../clases/conexion.php';
                ?>
            </header>
        </div>   


        <div class="container" style="padding-top: 15px;">

            <div class="row">
                <div class="col-md-12">

                    <form id="formulario" class="form-horizontal" role="form" style="background-color: lemonchiffon; padding-top: 20px; border-radius: 15px; padding-left: 15px; padding-right: 15px;">
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label class="control-label" for="codigo">Código</label> 
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="txtcodigo" id="txtcodigo" title="txtcodigo" class="form-control" placeholder="Autoincrementable" disabled/>
                            </div>
                            <div class="col-md-2 text-right">
                                <label class="control-label" for="txtmontoini">Monto Inicial</label> 
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="txtmontoini" id="txtmontoini" title="txtmontoini" class="form-control" placeholder="Monto" disabled/>
                            </div>
                            <div class="col-md-2 text-right">
                                <label class="control-label" for="caja">Caja</label> 
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="cbocaja" id="cbocaja" title="cbocaja" disabled="">
                                    <?php
                                    
                                    date_default_timezone_set( 'America/Asuncion' );
                                    session_start();
                                    $sqlsuc = "select * from cajas where id_sucursal = " . $_SESSION['id_sucursal'] . " and caja_estado = 'CERRADA' order by 2";
                                    $rssuc = consultas::get_datos($sqlsuc);
                                    foreach ($rssuc as $rss) {
                                        ?>
                                        <option value="<?php echo $rss['id_caja']; ?>"><?php echo $rss['caja_descripcion']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-md-2 text-right">
                                <label class="control-label" for="feaper">Fecha Apertura</label> 
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="txtfeaper" id="txtfeaper" title="txtfeaper" class="form-control" value="<?php echo date('d/m/Y H:i:s'); ?>" disabled=""/>
                            </div>

                            <div class="col-md-2 text-right">
                                <label class="control-label" for="fecierre">Fecha Cierre</label> 
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="txtfecierre" id="txtfecierre" title="txtfecierre" class="form-control" value="" disabled=""/>
                            </div>

                            <div class="col-md-2 text-right">
                                <label class="control-label" for="montocierre">Monto Cierre</label> 
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="txtmontocierre" id="txtmontocierre" title="txtmontocierre" class="form-control" value="" disabled=""/>
                                
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-md-1"></div>
                            <div class="col-md-2 text-right">
                                <input id="btnAbrir" type="button" class="form-control btn-primary" value="Abrir" onclick="apertura();"/>
                            </div>

                            <div class="col-md-2 text-right">
                                <input id="btnCerrar" type="button" class="form-control btn-danger" value="Cerrar" onclick="cierre();" disabled=""/>
                            </div>
                            <div class="col-md-2 text-right">
                                <input id="btnCancelar" type="button" class="form-control btn-info" value="Cancelar" disabled="" onclick="cancelar();"/>
                            </div>
                            <div class="col-md-2 text-right">
                                <input id="btnGrabar" type="button" class="form-control btn-success" value="Grabar" disabled="" onclick="grabar();"/>
                            </div>
                            <div class="col-md-2 text-right">
                                <a href="../../menu.php"><input id="btnSalir" type="button" class="form-control btn-default" value="Salir"></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-4">
                                <input type="text" name="txtbuscador" id="txtbuscador" class="form-control" placeholder="Ingrese datos a buscar"/>
                            </div>
                            <div class="col-md-2">
                                <input id="btnBuscar" type="button" class="form-control btn-primary" value="Buscar" onclick="get_datos($('#txtbuscador').val());$('#txtbuscador').val('');">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <table class="table table-hover" id="grilla">
                                    <thead>
                                        <tr>
                                            <th class="info  text-center">#</th>
                                            <th class="info">Fecha Aper.</th>
                                            <th class="info">Fecha Cierre</th>
                                            <th class="info text-right">Monto Aper.</th>
                                            <th class="hidden">IdCaja</th>
                                            <th class="info">Caja</th>
                                            <th class="info">Siguiente Factura</th>
                                            <th class="info text-right">Efectivo</th>
                                            <th class="info text-right">Cheque</th>
                                            <th class="info text-right">Tarjeta</th>
                                            <th class="info text-right">Total Cierre</th>
                                        </tr>
                                    
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="operacion"/>
                        <input type="hidden" id="idcaja" value="<?php echo $_SESSION['idcaja']; ?>" />
                        <input type="hidden" id="idusuario" value="<?php echo $_SESSION['id_usuario']; ?>" />
                    </form>
                </div>
            </div>
        </div>







        <!-- Librería jQuery requerida por los plugins de JavaScript -->
        <script src="../../js/jquery-1.12.2.min.js"></script>

        <!-- Todos los plugins JavaScript de Bootstrap (también puedes
             incluir archivos JavaScript individuales de los únicos
             plugins que utilices) -->
        <script src="../../src/js/bootstrap.min.js"></script>
        <script src="../../js/menuresponsive.js"></script>
        <script src="../../js/chosenselect.js"></script>
        <script src="metodosjs.js"></script>
        <script src="../../js/bootbox.min.js"></script>

    </body>
</html>