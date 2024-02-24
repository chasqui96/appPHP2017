var opcionesDolar = { aSep: '.', aDec: ',', aSign: '$', vMin: '-999999999.9999', mDec: '4', mRound: 'A', wEmpty: 'zero', lZero: 'deny' };
var opcionesGuarani = { aSep: '.', aDec: ',', vMin: '-999999999', mDec: '0', mRound: 'A', wEmpty: 'empty', lZero: 'deny' };
function inicializarFormatoNumerico() {

    $(".formatoGuarani").autoNumeric('init', opcionesGuarani);
    $(".formatoDolar").autoNumeric('init', opcionesDolar);

}

$(document).ready(function () {


    var selectedRowId; // Variable para almacenar el rowId seleccionado
    var cellvalueJs;
    cargarTablaDetalle();
    getDeposito();
    
    
    $("#productosGrid").jqGrid({
        url: 'items_grid.php', // URL para obtener los productos (cambia por la URL correcta)
        datatype: 'json',
        editurl: 'clientArray',
        colModel: [
        
            { label: 'Código', name: 'codigo', width: 100 },
            { label: 'Descripción', name: 'mer_descripcion', width: 250,     search: true,
            stype: 'text',
            searchoptions: {
                sopt: ['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc'], // Opciones de búsqueda específicas para descripción
                clearSearch: true // Mostrar botón para limpiar la búsqueda
            }
          },
            { label: 'Cantidad', name: 'cantidad', width: 80, editable: true },
            { label: 'Precio', name: 'mer_precio', width: 200 },
            {name: 'mer_tipoimpuesto',hidden:true },
            { label: 'Stock', name: 'stock', width: 70,
              formatter: function (cellvalue) {
                  // Aplicar color rojo o verde según el stock
                  return '<span style="color:' + (cellvalue < 5 ? 'red' : 'green') + '">' + cellvalue + '</span>';
              }
            },
            { label: 'Deposito', name: 'dep_descripcion', width: 200 },
            {name: 'id_deposito', hidden:true},
            {name: 'id_mercaderia', hidden:true},
            {name: 'stock_deposito', hidden:true}
            
        ],
        styleUI: "Bootstrap5",
        viewrecords: true,
        width: 800,
        height: 300,
        rowNum: 10,
        multiselect: true, // Habilitar la selección múltiple
        pager: "#productosPager",
        mtype: 'GET', // Especifica que quieres usar el método GET
        search: true, // Habilitar la barra de búsqueda
        // Configurar el evento onChange del campo de búsqueda
        
        onSelectRow: function(rowId) {
            //var rowData = $("#productosGrid").jqGrid('getRowData', rowId);
            $("#productosGrid").jqGrid("editRow", rowId, { keys: true });
            // Agregar el producto seleccionado a la tabla de detalle
           
       
        },
 
    });
    $("#productosGrid").jqGrid('filterToolbar', { defaultSearch: 'cn' });
    $("#openModalBtn").click(function() {
        $("#panelBuscador").modal("show");
        var numberOfRows1 = $("#productosGrid").jqGrid("getGridParam", "records");
        for (var i2 = 1; i2 <= numberOfRows1; i2++) {
            $("#productosGrid").jqGrid("restoreRow", i2);
            $("#productosGrid").jqGrid("setCell",i2, "cantidad", 1);
         
            $("#productosGrid").jqGrid("saveRow", i2);
        }
    });
    $("#agregarDetalleBtn").on("click", function() {
       
        var numberOfRows = $("#productosGrid").jqGrid("getGridParam", "records");
    
        // Iterar sobre todas las filas y restaurar la edición
        for (var i = 1; i <= numberOfRows; i++) {
            $("#productosGrid").jqGrid("saveRow", i);
    
        }
        var selectedIds = $("#productosGrid").jqGrid('getGridParam', 'selarrrow');
        if (selectedIds.length > 0) {
            // Recorrer los IDs de los productos seleccionados
            $.each(selectedIds, function(index, id) {
                var rowData = $("#productosGrid").jqGrid('getRowData', id);
                agregarProductoDetalle(rowData);
                
            });
    
            // Iterar sobre todas las filas y restaurar la edición
           
            $("#productosGrid").jqGrid("resetSelection").trigger('reloadGrid');
        } else {
            bootbox.alert("Por favor, seleccione al menos un producto antes de agregarlo al detalle.");
        }
       
        calcularYActualizarTotal();
    });
    
  
    inicializarFormatoNumerico();
});

function getDeposito() {
    
    $.post("deposito.php")
            .done(function (data) {
                $("#cboiddepositobuscador").html(data).trigger('chosen:updated').trigger('change');
        
            });
            
}
function filtrarPorDeposito() {
    var idDeposito = $("#cboiddepositobuscador").val();
    console.log(idDeposito);
    // Aquí puedes utilizar el valor de idDeposito para filtrar tu jqGrid
    $("#productosGrid").jqGrid('setGridParam', { postData: { id_deposito: idDeposito }, page: 1 }).trigger('reloadGrid');
}
function cargarTablaDetalle(){
    $("#gridDetalleVenta").jqGrid({
        editurl: 'clientArray',
  
        colModel: [
            { label: "Código", name: "codigo", width: 100 },
            { label: "Descripción", name: "mer_descripcion", width: 200 },
            { label: "Cantidad", name: "cantidad", width: 100, editable: true},
            { label: "Precio Unitario", name: "mer_precio", width: 150, editable: true ,edittype: 'text'  ,formatoptions: { thousandsSeparator: '.', decimalSeparator: ',' }}, // Columna editable
            { label: "Exenta", name: "exenta", width: 100 ,formatter: 'number', formatoptions: {  thousandsSeparator: '.', decimalSeparator: ',' }},
            { label: "Grav. 5%", name: "grav_5", width: 100,formatter: 'number', formatoptions: {  thousandsSeparator: '.', decimalSeparator: ',' } },
            { label: "Grav. 10%", name: "grav_10", width: 100,formatter: 'number', formatoptions: {  thousandsSeparator: '.', decimalSeparator: ',' } },
            { name: "mer_tipoimpuesto",hidden:true },
            { name: "id_deposito",hidden:true },
            {name: 'id_mercaderia', hidden:true}
        ],
        styleUI: "Bootstrap5",
        viewrecords: true,
        width: 900,
        height: 200,
        rowNum: 10,
        footerrow: true,
        userDataOnFooter: true,
        onSelectRow: function(rowId,cellvalue) {
            console.log(cellvalue);
            selectedRowId = rowId;
            $("#habilitarEdicion, #cerrarEdicion").prop("disabled", false);
        },
        beforeEditCell: function(rowid, cellname, value, iRow, iCol) {
            console.log("aca");
            if (cellname === 'mer_precio') {
                // Obtener el input asociado a la celda editada
                var input = $("#" + rowid + "_" + cellname);
                
                // Agregar la clase al input
                input.addClass("formatoGuarani");
            }
        },
       
    });
   
}

$("#habilitarEdicion").on("click", function() {
    // Habilitar la edición de campos en el jqGrid

    if (selectedRowId) {
        // Habilitar la edición en línea en el jqGrid con el rowId seleccionado
        $("#gridDetalleVenta").jqGrid("editRow", selectedRowId, { keys: true })
        
    } else {
        bootbox.alert("Por favor, seleccione una fila antes de habilitar la edición.");
    }
});
$("#cerrarEdicion").on("click", function() {
    // Cancelar la edición en línea en el jqGrid
    var numberOfRows = $("#gridDetalleVenta").jqGrid("getGridParam", "records");

    // Iterar sobre todas las filas y restaurar la edición
    for (var i = 1; i <= numberOfRows; i++) {
        $("#gridDetalleVenta").jqGrid("saveRow", i);
    }
    calcularYActualizarTotal();
});


function calcularYActualizarTotal() {
// Calcular y actualizar el total en el footer
var $grid = $("#gridDetalleVenta");
var numRows = $grid.jqGrid("getGridParam", "reccount");

for (var i = 1; i <= numRows; i++) {
    // Obtener los valores actuales de cantidad, mer_precio y mer_tipoimpuesto para cada fila
    var cantidad = parseFloat($grid.jqGrid("getCell", i, "cantidad"));
    var prec = parseFloat($grid.jqGrid("getCell", i, "mer_precio"));
    var tipo = $grid.jqGrid("getCell", i, "mer_tipoimpuesto");

    // Calcular los montos según los valores actuales
    var exenta = 0;
    var grav5 = 0;
    var grav10 = 0;
    if (tipo === 'EXENTA') {
        exenta = cantidad * prec;
    } else if (tipo === 'GRAVADA 5') {
        grav5 = cantidad * prec;
    } else if (tipo === 'GRAVADA 10') {
        grav10 = cantidad * prec;
    }

    // Formatear los montos en guaraníes
    // exenta =new Intl.NumberFormat("es-PY").format(exenta.toFixed(2));
    // grav5 = new Intl.NumberFormat("es-PY").format(grav5.toFixed(2));
    // grav10 =new Intl.NumberFormat("es-PY").format(grav10.toFixed(2));

    // Actualizar los valores en las columnas exenta, grav_5 y grav_10 para cada fila
    $grid.jqGrid("setCell", i, "exenta", exenta);
    $grid.jqGrid("setCell", i, "grav_5", grav5);
    $grid.jqGrid("setCell", i, "grav_10", grav10);
}

// Recalcular y actualizar el total en el footer
var totalExenta = $grid.jqGrid("getCol", "exenta", false, "sum");
var totalGrav5 = $grid.jqGrid("getCol", "grav_5", false, "sum");
var totalGrav10 = $grid.jqGrid("getCol", "grav_10", false, "sum");


// Formatear los totales en guaraníes
var totalAPagar = (parseFloat(totalExenta)+parseFloat(totalGrav5)+parseFloat(totalGrav10));

var iva5 = parseFloat(totalAPagar / 21);
var iva10 = parseFloat(totalAPagar / 11);

$grid.jqGrid("footerData", "set", {
    codigo: "SubTotal",
    exenta: totalExenta,
    grav_5: totalGrav5,
    grav_10: totalGrav10
});
// Seleccionar el footer del jqGrid


var footer = $("#gridDetalleVenta").closest(".ui-jqgrid").find(".ui-jqgrid-ftable");


// Construir la fila HTML personalizada
var customFooterRowHtml = '<tr role="row" id="filaTotal" class="footrow footrow-ltr total">' +
    '<td role="gridcell" colspan="6"  style="width: 104px;" aria-describedby="gridDetalleVenta_codigo" title="Total">Total a Cobrar</td>' +
    
    '<td role="gridcell" style="width: 101px;" aria-describedby="gridDetalleVenta_grav_10" title="total">' + new Intl.NumberFormat("es-PY").format(totalAPagar) + '</td>' +
    '</tr>';

customFooterRowHtml += '<tr role="row" id="filaIva" class="footrow footrow-ltr via">' +
    '<td role="gridcell" style="width: 104px;" aria-describedby="gridDetalleVenta_id" title="Total">Liquidacion IVA</td>' +
    '<td role="gridcell" style="width: 104px;" aria-describedby="gridDetalleVenta_mer_descripcion">(5%): ' + new Intl.NumberFormat("es-PY").format(iva5) + '</td>' +
    '<td role="gridcell" style="width: 155px;" aria-describedby="gridDetalleVenta_cantidad">(10%): ' + new Intl.NumberFormat("es-PY").format(iva10) + '</td>' +
    '<td role="gridcell" style="width: 155px;" aria-describedby="gridDetalleVenta_mer_precio">&nbsp;</td>' +
    '<td role="gridcell" style="width: 155px;" aria-describedby="gridDetalleVenta_exenta">&nbsp;</td>' +
    '<td role="gridcell" style="width: 155px;" aria-describedby="gridDetalleVenta_grav_5">&nbsp;</td>' +
    '<td role="gridcell" style="width: 101px;" aria-describedby="gridDetalleVenta_grav_10" title="total"></td>' +
    '</tr>';

// Agregar la fila HTML personalizada al footer del jqGrid
footer.append(customFooterRowHtml);




}
function agregarProductoDetalle(producto) {
    // Obtener el jqGrid de detalle
    var detalleGrid = $("#gridDetalleVenta");

    // Obtener el ID de la última fila en el jqGrid de detalle
    var lastRowId = detalleGrid.jqGrid("getDataIDs").length + 1;

    // Convertir la cantidad y el precio a números flotantes
    var cantidad = parseFloat(producto.cantidad);
    var prec = parseFloat(producto.mer_precio.replace(".", ""));

    // Calcular los montos gravados y exentos según el tipo de impuesto
    var exenta = 0;
    var grav5 = 0;
    var grav10 = 0;

    if (producto.mer_tipoimpuesto === 'EXENTA') {
        exenta = parseFloat(cantidad * prec);
    } else if (producto.mer_tipoimpuesto === 'GRAVADA 5') {
        grav5 = parseFloat(cantidad * prec);
    } else if (producto.mer_tipoimpuesto === 'GRAVADA 10') {
        grav10 = parseFloat(cantidad * prec);
    }
   

    // Buscar si la mercadería ya está en el detalle
    var rowId = detalleGrid.jqGrid("getDataIDs").find(function(id) {
        return detalleGrid.jqGrid("getCell", id, "id_mercaderia") === producto.id_mercaderia;
    });
    

    if (rowId) {
        
        var existingCantidad = parseFloat(detalleGrid.jqGrid("getCell", rowId, "cantidad"));
        existingCantidad =  existingCantidad + cantidad
    }
    console.log(producto.stock_deposito);
    console.log(    producto.cantidad);

    if (producto.stock_deposito < existingCantidad) {
        bootbox.alert('NO HAY SUFICIENTE STOCK PARA AGREGAR <span class="glyphicon glyphicon-ban-circle" style="font-size:50px"></span>'); 	
        return; // Detener la función si no hay suficiente stock
    }else{
        if (rowId) {
            // Si la mercadería ya está en el detalle, sumar la cantidad a la fila existente
            bootbox.alert('EL ITEMS '+producto.mer_descripcion+', YA EXISTE EN EL DETALLE , SE AGREGRAN '+cantidad+' ITEMS MAS AL DETALLE   <span class="glyphicon glyphicon-warning-sign" style="font-size:50px"></span>')
            // var existingCantidad = parseFloat(detalleGrid.jqGrid("getCell", rowId, "cantidad"));
            detalleGrid.jqGrid("setCell", rowId, "cantidad", existingCantidad);
        } else {
            // Si la mercadería no está en el detalle, agregar una nueva fila
            detalleGrid.jqGrid("addRowData", lastRowId, {
                codigo: producto.codigo,
                mer_descripcion: producto.mer_descripcion,
                cantidad: cantidad,
                mer_precio: prec,
                exenta: exenta,
                grav_5: grav5,
                grav_10: grav10,
                mer_tipoimpuesto: producto.mer_tipoimpuesto,
                id_deposito: producto.id_deposito,
                id_mercaderia: producto.id_mercaderia
            });
        }
    }
   
   
    // Actualizar los datos del jqGrid y recargar la cuadrícula
    detalleGrid.jqGrid('setGridParam', { data: detalleGrid[0].p.data }).trigger('reloadGrid');
}


function agregar(){
    //HABILITAR
    $("#cboidfuncionario").removeAttr("disabled").trigger("chosen:updated");
    $("#cboiddeposito").removeAttr("disabled").trigger("chosen:updated");
    $("#cboventipo").removeAttr("disabled").trigger("chosen:updated");
    $("#cboidclientes").removeAttr("disabled").trigger("chosen:updated");
    $("#cbomercaderias").removeAttr("disabled").trigger("chosen:updated");
    $("#txtcantidad").removeAttr("disabled");
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    
    //DESHABILITAR
    $("#btnAgregar").attr("disabled","true");
    $("#btnAnular").attr("disabled","true");
    $("#btnSalir").attr("disabled","true");
    
    //VALOR DE LA OPERACION
    $("#operacion").val("1");
}



function anular(){
    //HABILITAR
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    
    //DESHABILITAR
    $("#btnAgregar").attr("disabled","true");
    $("#btnAnular").attr("disabled","true");
    $("#btnSalir").attr("disabled","true");
    
    //VALOR DE LA OPERACION
    $("#operacion").val("2");
}
$("#buscarClienteBtn").click(function() {
    // Obtener el número de documento ingresado por el usuario
    var numero_documento = $("#numero_documento").val();

    // Realizar la solicitud AJAX
    $.ajax({
        url: `consulta_cliente_buscador.php`, // Cambia "ruta_destino.php" por la URL a la que deseas enviar la solicitud AJAX
        method: "POST",
        data: { numero_documento: numero_documento },
        success: function(response) {
            // Mostrar los resultados en el contenedor designado
            $("#resultadoCliente").empty();
            $("#resultadoCliente").html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error al realizar la solicitud AJAX: " + error);
        }
    });
});
function grabar(){
    var salida = '{';
        $("#grilladetalle tbody tr").each(function (index) {


            salida = salida + '{';

            $(this).children("td").each(function (index2) {

                if (index2 < 6) {
                    salida = salida + $(this).text().replace(/\./g,"").replace(/\"/g,"") + ",";
                } else {
                    salida = salida + $(this).text().replace(/\./g,"");
                }



            });
            if (index < $("#grilladetalle tbody tr").length - 1) {
                salida = salida + '},';
            } else {
                salida = salida + '}';
            }
        });
        salida = salida + '}';
        $('#txtdetalles').val(salida);
        //alert(salida);
        $('#txtvenintervalo').removeAttr('disabled');
        $('#txtvencantcuotas').removeAttr('disabled');
        $("#cboiddeposito").removeAttr("disabled").trigger('chosen:updated');
        //bootbox.alert(salida);
        
        
        
        var sql = "select sp_ventas(" + $("#txtcodigo").val() + ",'" + $("#cboventipo").val() + "'," + $("#txtvenintervalo").val() + ", " + $("#txtvencantcuotas").val() + ", " + $("#cboidclientes").val() + ", " + $("#cboidfuncionario").val() + "," + $("#txtusuario").val() + "," + $("#txtidtimbrado").val() + "," + $("#txtidaper").val() + "," + $("#cboiddeposito").val() + ",'" + $("#txtdetalles").val() + "'," + $("#operacion").val() + ")";
        //bootbox.alert(sql);
        
        if($("#txtcodigo").val()==="0" && $("#operacion").val()==="2"){
            bootbox.alert("SELECCIONE UN REGISTRO PARA ANULAR");
        }else if(salida==="{}"){
            bootbox.alert("DEBE AL MENOS AGREGAR UN DETALLE");
        }else{
            bootbox.confirm("¿DESEA GRABAR LA OPERACION?", function (evt) {
            if (evt) {
                $.post("../../clases/operaciones_bd.php", {sql: sql})
                        .done(function (data) {
                            bootbox.alert(data, function(){location.reload();});
                        });
            }
        });
        }
        
}

function merselect() {

    var id = $("#cbomercaderias").val();
    //bootbox.alert("EL ID ES: "+id);
    //var dat = id.split("~");
    //alert(id);
    //var letra = NumeroALetras(parseInt(dat[1]));
    //alert(letra);
    //$("#txtprecio").val(dat[1]);
    //$('#txtcantidad').select();
}

function tiposelect() {
    if ($("#cboventipo").val() === "CONTADO") {
        $('#txtvenintervalo').attr('disabled', 'true');
        $('#txtvenintervalo').val('0');
        $('#txtvencantcuotas').attr('disabled', 'true');
        $('#txtvencantcuotas').val('1');
    } else {
        $('#txtvenintervalo').removeAttr('disabled');
        $('#txtvencantcuotas').removeAttr('disabled');
    }
}

function eliminarfila(parent) {
    $(parent).remove();
    calcularTotales();
}

function getMercaderias() {
    var dep = $('#cboiddeposito').val();
    //alert(dep);
    var ajax_load = "<div class='progress'>" + "<div id='progress_id' class='progress-bar progress-bar-striped active' " +
            "role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 45%'>" +
            "Cargando...</div></div>";
    $("#prog").html(ajax_load);



    $.post("mercaderias.php", {id_deposito: dep})
            .done(function (data) {
                //alert(data);
                var ajax_load = "<div class='progress'>" + "<div id='progress_id' class='progress-bar progress-bar-striped active' " +
                        "role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 100%'>" +
                        "Completado</div></div>";
                $("#prog").html(ajax_load);
                setTimeout('$("#prog").html("")',1000);
                $("#cbomercaderias").html(data).trigger('chosen:updated');
                merselect();

            });
            
}

function calcularTotales() {
    var exe = 0;
    var g5 = 0;
    var g10 = 0;

    $("#grilladetalle tbody tr").each(function (fila) {
        $(this).children("td").each(function (col) {
            if(col === 4){
                exe = exe + parseInt($(this).text().replace(/\./g,""));
            }
            if(col === 5){
                g5 = g5 + parseInt($(this).text().replace(/\./g,""));
            }
            if(col === 6){
                g10 = g10 + parseInt($(this).text().replace(/\./g,""));
            }
        });
    });
    
    
    
    var totales = "<tr>";
    totales += "<th colspan=\"4\">SUB TOTALES</th>";
    totales += "<th style=\"text-align: right;\">"+exe.toLocaleString()+"</th>";
    totales += "<th style=\"text-align: right;\">"+g5.toLocaleString()+"</th>";
    totales += "<th style=\"text-align: right;\">"+g10.toLocaleString()+"</th>";
    totales += "<th></th>";
    totales += "</tr>";
    var iva5 = Math.round((g5/21));
    var iva10 = Math.round((g10/11));
    totales += "<tr>";
    totales += "<th colspan=\"5\">LIQUIDACION DE IVA</th>";
    totales += "<th style=\"text-align: right;\">"+iva5.toLocaleString()+"</th>";
    totales += "<th style=\"text-align: right;\">"+iva10.toLocaleString()+"</th>";
    totales += "<th style=\"text-align: right;\"></th>";
    totales += "</tr>";
    
    var totaliva = Math.round((g5/21)+(g10/11));
    totales += "<th colspan=\"6\">TOTAL DE IVA</th>";
    totales += "<th style=\"text-align: right;\">"+totaliva.toLocaleString()+"</th>";
    totales += "<th></th>";
    totales += "</tr>";
    var totalgral = (exe+g5+g10);
    totales += "<tr class=\"danger\">";
    totales += "<th colspan=\"6\"><h4>TOTAL GENERAL</h4></th>";
    totales += "<th style=\"text-align: right;\"><h4>"+ totalgral.toLocaleString() +"</h4></th>";
    totales += "<th><h4></h4></th>";
    totales += "</tr>";

    $("#grilladetalle tfoot").html(totales);
}

function get_datos(filtro) {
    $.post("listado.php", {fil: filtro})
            .done(function (data) {
                $("#grillaBusc tbody").html(data);
            });
            }            

function get_detalles(filtro) {
    $.post("detalles.php", {fil: filtro})
            .done(function (data) {
                var separado = data.split("|");
                $("#grilladetalle tbody").html(separado[1]);
                $("#cboiddeposito").val(parseInt(separado[0])).trigger("chosen:updated");
                calcularTotales();
            });
            }            
function seleccion(fila) {
    fila.find("td").each(function (index) {

        switch (index) {
            case 0:
                $("#txtcodigo").val($(this).text());
                break;

            case 1:
                $("#txtnrofactura").val($(this).text());
                break;
                
            case 5:
                $("#cboventipo").val($.trim($(this).text())).trigger("chosen:updated");
                break;
                
            case 7:
                $("#cboidfuncionario").val(parseInt($(this).text())).trigger("chosen:updated");
                break;
               
            case 8:
                $("#txtvenintervalo").val($(this).text());
                break;
                
            case 9:
                $("#txtvencantcuotas").val($(this).text());
                break;
                
            case 10:
                $("#cboidclientes").val(parseInt($(this).text())).trigger("chosen:updated");
                break;
                
            case 11:
                $("#txtfecha").val($(this).text());
                break;
        }
        $("#panelBuscador").modal('hide');
    });
    get_detalles($("#txtcodigo").val());
}


function agregar_fila(){
    var productodesc = $('#cbomercaderias option:selected').html();
            var prod = $('#cbomercaderias').val();
            var producto = prod.split('~');
            var cant = parseInt($('#txtcantidad').val());
            var prec = parseInt($('#txtprecio').val());
            var exenta = 0;
            var grav5 = 0;
            var grav10 = 0;
            $("#cboiddeposito").attr("disabled","true").trigger('chosen:updated');

            if (producto[2] === 'EXENTA') {
                exenta = cant * prec;
                grav5 = 0;
                grav10 = 0;
            } else if (producto[2] === 'GRAVADA 5') {
                exenta = 0;
                grav5 = cant * prec;
                grav10 = 0;
            } else if (producto[2] === 'GRAVADA 10') {
                exenta = 0;
                grav5 = 0;
                grav10 = cant * prec;
            }

            var repetido = false;
            var co = 0;
            var contador = 0;
            if (parseInt(producto[3]) < parseInt(cant)) {
                bootbox.confirm('SOLO HAY ' + producto[3] + ' EN STOCK ESTE PRODUCTO. ¿DESEA VENDER TODOS?',function (resp){
                    if(resp){
                        $('#txtcantidad').val(producto[3]);
                        agregar_fila();
                    }else{
                        $('#txtcantidad').val("");
                    }
                });
                
            } else {
                $("#grilladetalle tbody tr").each(function (index) {
                    $(this).children("td").each(function (index2) {
                        if (index2 === 0) {
                            co = $(this).text();
                            if (co === producto[0]) {
                                repetido = true;
                                $('#grilladetalle tbody tr').eq(index).each(function () {
                                    $(this).find('td').each(function (i) {
                                        if (i === 2) {
                                            //var cantgrilla = parseInt($(this).html());
                                            cant = (cant+parseInt($(this).text().replace(/\./g,"")));
                                            $(this).text(cant.toLocaleString());
                                        }
                                        if (i === 3) {

                                            $(this).text(prec.toLocaleString());
                                        }
                                        if (i === 4) {
                                            exenta = (exenta+parseInt($(this).text().replace(/\./g,"")));
                                            $(this).text(exenta.toLocaleString());
                                        }
                                        if (i === 5) {
                                            grav5 = (grav5+parseInt($(this).text().replace(/\./g,"")));
                                            $(this).text(grav5.toLocaleString());
                                        }
                                        if (i === 6) {
                                            grav10 = (grav10+parseInt($(this).text().replace(/\./g,"")));
                                            $(this).text(grav10.toLocaleString());
                                        }

                                    });
                                });
                            }
                        }

                    });
                });

                if (!repetido) {
                    $('#grilladetalle > tbody:last').append('<tr><td style="text-align: center;">' + producto[0] + '</td><td>' + productodesc + '</td><td style="text-align: center;">' + cant.toLocaleString() + '</td><td style="text-align: right;">' + prec.toLocaleString() + '</td><td style="text-align: right;">' + exenta.toLocaleString() + '</td><td style="text-align: right;">' + grav5.toLocaleString() + '</td><td style="text-align: right;">' + grav10.toLocaleString() + '</td><td style="text-align: right;" onclick="eliminarfila($(this).parent())"><button type="button" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button></td></tr>');
                    contador++;
                }
                calcularTotales();
                $('#txtcantidad').val("");
            }
}

$(function () {
    $('#txtcantidad').keypress(function (e) {
        if (e.which === 13) {
            agregar_fila();
        }
    });

    $(".chosen-select").chosen({width: "100%"});
    
    
    getMercaderias();
    tiposelect();
    calcularTotales();
    
});







