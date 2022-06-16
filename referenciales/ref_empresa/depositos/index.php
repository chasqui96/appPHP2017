<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MANTENER DEPOSITOS</title>

        <!-- CSS de Bootstrap -->
        <link rel="stylesheet" type="text/css" href="../../../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../../../css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" type="text/css" href="../../../css/menuresponsive.css" media="screen">
        <link rel="stylesheet" type="text/css" href="../../../css/chosenselect.css" media="screen">
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
                require '../../../clases/conexion.php';
                //require '../../../cabecera.php';
                ?>
            </header>
        </div>   


        <div class="container" style="padding-top: 15px;">

            <div class="row">
                <div class="col-md-12">

                    <form id="formulario" class="form-horizontal" role="form" style="background-color: lemonchiffon; padding-top: 20px; border-radius: 15px;">
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <label class="control-label" for="codigo">Código</label> 
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="txtcodigo" id="txtcodigo" class="form-control" placeholder="Autoincrementable" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <label class="control-label" for="codigo">Descripción</label> 
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="txtdescripcion" id="txtdescripcion" title="txtdescripcion" class="form-control" placeholder="Ingrese descripción" disabled=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <label class="control-label" for="codigo">Sucursal</label> 
                            </div>
                            <div class="col-md-4">
                                <select id="cbosucursal" class="form-control chosen-select">
                                    <?php
                                    $sqlsuc = "select * from sucursales order by 2";
                                    $result = consultas::get_datos($sqlsuc);
                                    foreach ($result as $rs) {
                                       
                                    ?>
                                    <option value="<?php echo $rs['id_sucursal'];?>">
                                        <?php echo $rs['suc_descripcion'];?>
                                    </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3"></div>

                            <div class="col-md-2">
                                <input id="btnAgregar" type="button" class="form-control btn-primary" value="Agregar" onclick="agregar();"/>
                            </div>
                            <div class="col-md-2">
                                <input id="btnModificar" type="button" class="form-control btn-warning" value="Modificar" onclick="modificar();"/>
                            </div>
                            <div class="col-md-2">
                                <input id="btnBorrar" type="button" class="form-control btn-danger" value="Borrar" onclick="borrar();"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <input id="btnCancelar" type="button" class="form-control btn-info" value="Cancelar" disabled="" onclick="cancelar();"/>
                            </div>
                            <div class="col-md-2">
                                <input id="btnGrabar" type="button" class="form-control btn-success" value="Grabar" disabled="" onclick="grabar();"/>
                            </div>
                            <div class="col-md-2">
                                <a href="../../../menu.php"><input id="btnSalir" type="button" class="form-control btn-default" value="Salir"></a>
                            </div>
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
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <table class="table table-hover" id="grilla">
                                    <thead>
                                        <tr>
                                            <th class="warning text-center">Código</th>
                                            <th class="warning">Descripción</th>
                                            <th class="hidden">idsuc</th>
                                            <th class="warning">Sucursal</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="operacion"/>
                    </form>
                </div>
            </div>
        </div>







        <!-- Librería jQuery requerida por los plugins de JavaScript -->
        <script src="../../../js/jquery-1.12.2.min.js"></script>

        <!-- Todos los plugins JavaScript de Bootstrap (también puedes
             incluir archivos JavaScript individuales de los únicos
             plugins que utilices) -->
        <script src="../../../js/bootstrap.min.js"></script>
        <script src="../../../js/menuresponsive.js"></script>
        <script src="../../../js/chosenselect.js"></script>
        <script src="metodosjs.js"></script>
        <script src="../../../js/bootbox.min.js"></script>
        
    </body>
</html>