$(document).ready(function () {
    $("#gridCobros").jqGrid({
        url: 'listado_apertura_cierre.php',
        datatype: "json",
        method:"POST",
        colModel: [
            {label: '#',name: 'id_apercierre',width:30,formatter: formatearEnlaceId},
            { label: 'Fecha Apertura', name: 'fecha_aperformat', width: 100 },
            { label: 'Fecha Cierre', name: 'fecha_cierreformat', width: 100 },
            { label: 'Monto Apertura', name: 'aper_monto', width: 100 },
            { label: 'Caja', name: 'caja_descripcion', width: 100 },
         
            { name: 'id_caja', hidden: true },
            { name: 'monto_cierre', hidden: true }
        ],
        styleUI: "Bootstrap5",
        viewrecords: true,
        width: 1000,
        height: 400,
        rowNum: 15, // Mostrar 10 filas por página
        rowList: [10,15, 20, 50], // Opciones de selección de filas por página
        sortname:"id_apercierre",
        sortorder:'desc',
        pager: "#jqGridPager",
        subGrid: true, // Habilita las subgrillas
        subGridRowExpanded: showChildGrid, 
        subGridOptions : {
            // expand all rows on load
            expandOnLoad: false
        },
        onSelectRow: function (rowid, status, e) {
            console.log(e);
            var rowData = $("#gridCobros").getRowData(rowid);
            seleccion(rowData);
        }
    });
    $("#gridCobros").jqGrid('sortGrid','id', true, 'desc');
    // Agregar filtro de búsqueda
    $("#gridCobros").jqGrid('filterToolbar', { defaultSearch: 'cn' });
});

function formatearEnlaceId(cellvalue, options, rowObject) {

    var enlace = '<a style="color:blue;" target="_blank" href="arqueo.php?id='+cellvalue +'">'+cellvalue +'</a>';
    return enlace;
}
function showChildGrid(parentRowID, parentRowKey) {
    var childGridID = parentRowID + "_table";
    var childGridPagerID = parentRowID + "_pager";

    // send the parent row primary key to the server so that we know which grid to show
    var childGridURL = "subgrilla.php?rowId="+parentRowKey;
    //childGridURL = childGridURL + "&parentRowID=" + encodeURIComponent(parentRowKey)

    // add a table and pager HTML elements to the parent grid row - we will render the child grid here
    $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + '></div>');

    $("#" + childGridID).jqGrid({
        url: childGridURL,
        mtype: "GET",
        datatype: "json",
        page: 1,
        colModel: [
            { label: 'Monto Efectivo', name: 'monto_efectivo', key: true, width: 75 },
            { label: 'Monto Cheque', name: 'monto_cheque', width: 100 },
            { label: 'Monto Tarjeta', name: 'monto_tarjeta', width: 100 },

        ],
        width: 700,
        height: '100%',
        pager: "#" + childGridPagerID
    });

}


function apertura() {
    $("#txtmontoini").removeAttr("disabled");
    $("#cbocaja").removeAttr("disabled");
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    $("#btnAbrir").attr("disabled", "true");
    $("#btnCerrar").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");
    $("#operacion").val("1");
    $("#txtmontocierre").val("0");
    $.post("../../clases/ultimo_codigo.php", { pk: "id_apercierre", tabla: "apertura_cierres" })
        .done(function (data) {
            $("#txtcodigo").val(data);
        });
}

function cierre() {
    //alertify.alert("USTED HA PULSADO BOTON AGREGAR");
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    $("#btnSalir").attr("disabled", "true");
    $("#operacion").val("2");
    grabar();
}

function cancelar() {
    location.reload();
}
function grabar() {
    if ($("#operacion").val() === "1") {
        $("#txtmontocierre").val("0");
        $("#idcaja").val($("#cbocaja").val());
    }
    var sql = "select sp_apercierre(" + $("#txtcodigo").val() + "," + $("#txtmontoini").val().replace(/\./g, "") + "," + $("#txtmontocierre").val().replace(/\./g, "") + "," + $("#idcaja").val() + "," + $("#idusuario").val() + "," + $("#operacion").val() + ")";

    bootbox.confirm("¿DESEA GRABAR LA OPERACION?\n" + sql, function (e) {
        if (e) {
            $.post("../../clases/operaciones_bd.php", { sql: sql })
                .done(function (data) {
                    bootbox.alert(data, function () { cancelar(); });

                });
        }
    });

}
function get_datos(filtro) {
    $.post("listado.php", { fil: filtro })
        .done(function (data) {
            $("#grilla tbody").html(data);
        });
}

function seleccion(fila) {

    $("#txtcodigo").val(fila.id);
    $("#txtfeaper").val(fila.fecha_aperformat);
    $("#txtfecierre").val(fila.fecha_cierreformat);
    $("#txtmontoini").val(fila.aper_monto);
    $("#idcaja").val(fila.id_caja);
    $("#txtmontocierre").val(fila.monto_cierre);
    if (fila.fecha_cierreformat === "" || fila.fecha_cierreformat === null) {
        $('#btnCerrar').removeAttr("disabled");
    } else {
        $('#btnCerrar').attr("disabled", "true");
    }
    // fila.find("td").each(function (index) {

    //     switch (index) {
    //         case 0:
    //             $("#txtcodigo").val($(this).text());
    //             break;

    //         case 1:
    //             $("#txtfeaper").val($(this).text());
    //             break;

    //         case 2:
    //             $("#txtfecierre").val($(this).text());
    //             if($(this).text()===""){
    //                 $('#btnCerrar').removeAttr("disabled");
    //             }else{
    //                 $('#btnCerrar').attr("disabled","true");
    //             }
    //             break;

    //         case 3:
    //             $("#txtmontoini").val($(this).text());
    //             break;

    //         case 4:
    //             $("#idcaja").val($(this).text());
    //             break;

    //         case 10:
    //             $("#txtmontocierre").val($(this).text());
    //             break;








    //     }
    // });
}

function comprobaraper() {
    $.post("../caja_abierta.php")
        .done(function (data) {
            if (data === "0") {
                bootbox.alert("<strong> NO HAY CAJA ABIERTA!</strong>");
                $('#btnAbrir').removeAttr('disabled');
            } else {
                bootbox.alert(data);
                $('#btnAbrir').attr('disabled', 'true');
            }
        });
}

$(function () {
    get_datos("");
    comprobaraper();
    $(".chosen-select").chosen({ width: "100%" });

    $('#txtbuscador').keypress(function (evt) {
        if (evt.which === 13) {
            get_datos($("#txtbuscador").val());
        }
    });

});



