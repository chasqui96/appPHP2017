function agregar() {
    //HABILITAR BOTONES Y CAMPO DE TEXTO
    $("#txtdescripcion").removeAttr("disabled");
    $("#cbosucursal").removeAttr("disabled").trigger("chosen:updated");
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    //DESHABILITAR BOTONES Y CAMPO DE TEXTO
    $("#btnAgregar").attr("disabled", "true");
    $("#btnModificar").attr("disabled", "true");
    $("#btnBorrar").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");
    $("#operacion").val("1");
    
    $.post("../../../clases/ultimo_codigo.php", {pk: "id_deposito", tabla: "depositos"})
            .done(function (data) {
                $("#txtcodigo").val(data);
            });
}
function modificar() {
    //alertify.alert("USTED HA PULSADO BOTON AGREGAR");
    $("#txtdescripcion").removeAttr("disabled");
    $("#cbosucursal").removeAttr("disabled").trigger("chosen:updated");
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    $("#btnAgregar").attr("disabled", "true");
    $("#btnModificar").attr("disabled", "true");
    $("#btnBorrar").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");
    $("#operacion").val("2");
    $("#txtdescripcion").select();
}
function borrar() {
    //alertify.alert("USTED HA PULSADO BOTON AGREGAR");
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    $("#btnAgregar").attr("disabled", "true");
    $("#btnModificar").attr("disabled", "true");
    $("#btnBorrar").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");
    $("#operacion").val("3");
    grabar();
}

function cancelar() {
    $("#txtcodigo").val("");
    $("#txtdescripcion").val("");
    $("#txtdescripcion").attr("disabled", "true");
    $("#cbosucursal").attr("disabled", "true").trigger("chosen:updated");
    $("#btnGrabar").attr("disabled", "true");
    $("#btnCancelar").attr("disabled", "true");
    $("#btnAgregar").removeAttr("disabled");
    $("#btnModificar").removeAttr("disabled");
    $("#btnBorrar").removeAttr("disabled");
    $("#btnSalir").removeAttr("disabled");
    get_datos("");
}
function grabar() {
    var d = $.trim($("#txtdescripcion").val());

    if (d === "") {
        bootbox.alert('DEBES LLENAR TODOS LOS CAMPOS');
    } else {
        var sql = "select sp_depositos("+$("#txtcodigo").val()+",'"+$("#txtdescripcion").val()+"',"+$("#cbosucursal").val()+","+$("#operacion").val()+")";
        
        var oper = $('#operacion').val();
        var conf = "";
        
        if(oper === "1"){
            conf = "¿DESEA GRABAR EL NUEVO REGISTRO?";
        }
        if(oper === "2"){
            conf = "¿DESEA ACTUALIZAR ESTE REGISTRO?";
        }
        if(oper === "3"){
            conf = "¿DESEA ELIMINAR ESTE REGISTRO?";
        }
        
        bootbox.confirm(conf, function (e) {
            if (e) {
                $.post("../../../clases/operaciones_bd.php", {sql: sql})
                        .done(function (data) {
                            bootbox.alert(data);
                            cancelar();
                        });
            }
        });
    }
}
function get_datos(filtro) {
    $.post("listado.php", {fil: filtro})
            .done(function (data) {
                $("#grilla tbody").html(data);
            });
}

function seleccion(parent) {
    parent.find("td").each(function(index){
        
                switch(index){
                    case 0:
                        $("#txtcodigo").val($(this).text());
                        break;
                    case 1:
                        $("#txtdescripcion").val($(this).text());
                        break;
                    case 2:
                        $('#cbosucursal').val(parseInt($(this).text()));
                        $('#cbosucursal').trigger("chosen:updated");
                        break;
                }
            });
}

$(function () {
    get_datos("");
    $(".chosen-select").chosen({width: "100%"});
    
});

