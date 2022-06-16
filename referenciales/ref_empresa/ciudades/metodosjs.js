function agregar() {
    //HABILITAR BOTONES Y CAMPO DE TEXTO
    $("#txtdescripcion").removeAttr("disabled");
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");
    //DESHABILITAR BOTONES Y CAMPO DE TEXTO
    $("#btnAgregar").attr("disabled", "true");
    $("#btnModificar").attr("disabled", "true");
    $("#btnBorrar").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");
    $("#operacion").val("1");
    
    $.post("../../../clases/ultimo_codigo.php", {pk: "id_ciudad", tabla: "ciudades"})
            .done(function (data) {
                $("#txtcodigo").val(data);
            });
}
function modificar() {
    //alertify.alert("USTED HA PULSADO BOTON AGREGAR");
    $("#txtdescripcion").removeAttr("disabled");
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
        var sql = "select sp_ciudades("+$("#txtcodigo").val()+",'"+$("#txtdescripcion").val()+"',"+$("#operacion").val()+")";
        
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
                if(index === 0){
                    $("#txtcodigo").val($(this).text());
                }
                if(index === 1){
                    $("#txtdescripcion").val($(this).text());
                }
            });
}

$(function () {
    get_datos("");
});

