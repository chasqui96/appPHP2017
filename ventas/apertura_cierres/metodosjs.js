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
    $.post("../../clases/ultimo_codigo.php", {pk: "id_apercierre", tabla: "apertura_cierres"})
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
        if($("#operacion").val()==="1"){
            $("#txtmontocierre").val("0");
            $("#idcaja").val($("#cbocaja").val());
        }
        var sql = "select sp_apercierre(" + $("#txtcodigo").val() + "," + $("#txtmontoini").val().replace(/\./g,"") + "," + $("#txtmontocierre").val().replace(/\./g,"") + "," + $("#idcaja").val() + "," + $("#idusuario").val() + "," + $("#operacion").val() + ")";

        bootbox.confirm("¿DESEA GRABAR LA OPERACION?\n"+sql, function (e) {
            if (e) {
                $.post("../../clases/operaciones_bd.php", {sql: sql})
                        .done(function (data) {
                            bootbox.alert(data, function(){cancelar();});
                            
                        });
            }
        });
    
}
function get_datos(filtro) {
    $.post("listado.php", {fil: filtro})
            .done(function (data) {
                $("#grilla tbody").html(data);
            });
}

function seleccion(fila) {
    fila.find("td").each(function (index) {

        switch (index) {
            case 0:
                $("#txtcodigo").val($(this).text());
                break;

            case 1:
                $("#txtfeaper").val($(this).text());
                break;
                
            case 2:
                $("#txtfecierre").val($(this).text());
                if($(this).text()===""){
                    $('#btnCerrar').removeAttr("disabled");
                }else{
                    $('#btnCerrar').attr("disabled","true");
                }
                break;
                
            case 3:
                $("#txtmontoini").val($(this).text());
                break;
                
            case 4:
                $("#idcaja").val($(this).text());
                break;
               
            case 10:
                $("#txtmontocierre").val($(this).text());
                break;
              
               
            
                
                



        }
    });
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
    $(".chosen-select").chosen({width: "100%"});

$('#txtbuscador').keypress(function (evt) {
                        if (evt.which === 13) {
                            get_datos($("#txtbuscador").val());
                        }
                    });

});



