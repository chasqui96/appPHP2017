var opcionesDolar = { aSep: '.', aDec: ',', aSign: '$', vMin: '-999999999.9999', mDec: '4', mRound: 'A', wEmpty: 'zero', lZero: 'deny' };
var opcionesGuarani = { aSep: '.', aDec: ',', vMin: '-999999999', mDec: '0', mRound: 'A', wEmpty: 'empty', lZero: 'deny' };
function inicializarFormatoNumerico() {

    $(".formatoGuarani").autoNumeric('init', opcionesGuarani);
    $(".formatoDolar").autoNumeric('init', opcionesDolar);

}
$(document).ready(function () {
    $("#gridCobros").jqGrid({
        url: 'listado_grid.php',
        datatype: "json",
        colModel: [
            
            { label: '#', name: 'id_cobros', width: 75,formatter: formatearEnlaceId,key:true },
            { label: 'CLIENTE', name: 'Cliente', width: 200 },
            { label: 'NUMERO DOCUMENTO', name: 'ruc', width: 100 },
            { label: 'FECHA', name: 'cob_fecha_f', width: 100 },
            { label: 'MONTO', name: 'cob_efectivo', width: 100 },
            { name: 'id_cliente', hidden: true },
            { name: 'id_apercierre', hidden: true },
            { name: 'fechanormal', hidden: true }
        ],
        styleUI: "Bootstrap5",
        viewrecords: true,
        width: 1000,
        height: 200,
        rowNum: 10,
        sortname:"id_cobros",
        sortorder:'desc',
        pager: "#jqGridPager",
        onSelectRow: function (rowid, status, e) {
            var rowData = $("#gridCobros").getRowData(rowid);
            console.log(rowData);
           seleccion(rowData,rowid);
            // Aquí puedes realizar acciones adicionales cuando se selecciona una fila
        }
    });
    inicializarFormatoNumerico();
});

function formatearEnlaceId(cellvalue, options, rowObject) {

    var enlace = '<a style="color:blue;" target="_blank" href="imprimir_recibo.php?valor='+cellvalue+'">'+cellvalue+'</a>';
    return enlace;
}
function agregar() {
    //HABILITAR
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");

    //DESHABILITAR
    $("#btnAgregar").attr("disabled", "true");
    $("#btnAnular").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");

    //VALOR DE LA OPERACION
    $("#operacion").val("1");
}
function cobrar() {
    //HABILITAR

    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");

    //DESHABILITAR
    $("#btnCobrar").attr("disabled", "true");
    $("#btnAnular").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");
    //VALOR DE LA OPERACION
    $("#operacion").val("3");
}


function anular() {
    //HABILITAR
    $("#btnGrabar").removeAttr("disabled");
    $("#btnCancelar").removeAttr("disabled");

    //DESHABILITAR
    $("#btnAgregar").attr("disabled", "true");
    $("#btnAnular").attr("disabled", "true");
    $("#btnSalir").attr("disabled", "true");

    //VALOR DE LA OPERACION
    $("#operacion").val("2");
}

function grabar() {
    generarArrayCtas();
    generarArrayCheques();
    generarArrayTarjetas();

    var sql = "select sp_cobros("+ $('#codigo').val() +","+ $('#efectivo').autoNumeric("get") +","+ $('#idaper').val() +",'"+ $('#detallecobro').val() +"','"+ $('#detallecheque').val() +"','"+$('#detalletarjeta').val() +"',"+ $('#operacion').val() +")";
    //bootbox.alert(sql);

    if ($("#codigo").val() === "0" && $("#operacion").val() === "2") {
        bootbox.alert("SELECCIONE UN REGISTRO PARA ANULAR");
    } else if ($('#detallecobro').val() === "{}") {
        bootbox.alert("DEBE AL MENOS AGREGAR UNA CUOTA");
    } else {
        bootbox.confirm("¿DESEA GRABAR LA OPERACION? " +sql+" aca", function (e) {
            if (e) {
                $.post("../../clases/operaciones_bd.php", {sql: sql})
                        .done(function (data) {
                            bootbox.alert(data, function () {
                                location.reload();
                            });
                        });
            }
        });
    }

}



function eliminarfila(parent) {
    $(parent).remove();
    calcularTotales();
}



function calcularTotales() {
    var total = 0;

    $("#grilladetalle tbody tr").each(function (fila) {
        $(this).children("td").each(function (col) {
            if (col === 4) {
                //console.log();
                total += parseFloat($("#saldo").autoNumeric("get"));
                //total += parseFloat($(this).text().replace(/\./g, ''));
            }
        });
    });

    if (total <= 0) {
        $("#idclientes").removeAttr("disabled").trigger("chosen:updated");
    } else {
        $("#idclientes").attr("disabled", "true").trigger("chosen:updated");
    }
    

    var totales = "";

    totales += "<tr>";
    totales += "<th class=\"danger\" colspan=\"3\"><h6>TOTAL GENERAL</h6></th>";
    totales += "<th class=\"danger\" style=\"text-align: center;\"><h6>" + total.toLocaleString() + "</h6></th>";
    totales += "<th class=\"default\"><h6></h6></th>";
    totales += "</tr>";
    $("#totalcobrar").autoNumeric("set",total);
    $("#grilladetalle tfoot").html(totales);
}
$("#efectivo").keyup(function(event) {
    if (event.keyCode === 8) {
        $("#efectivo").autoNumeric('set',0);
        calcularVuelto();
     
    }
});
function calcularVuelto() {
    var acobrar = parseFloat($("#totalcobrar").autoNumeric('get'));
    var efe = parseFloat($("#efectivo").autoNumeric('get'));
    var totalch = parseFloat($("#totalcheques").autoNumeric('get'));
    var totaltar = parseFloat($("#totaltarjetas").autoNumeric('get'));
    var totalcobrado = parseFloat(efe + totalch + totaltar);

    $("#montoEfectivo").autoNumeric("set",efe);
    $("#montoTarjeta").autoNumeric("set",totaltar);
    $("#montoCheque").autoNumeric("set",totalch);

    var vuelto = parseFloat( acobrar - totalcobrado);
    
    console.log(vuelto);
    
    if(totalcobrado === acobrar){
        $("#vuelto").autoNumeric("set", 0);
        $("#lbvuelto").html("SIN VUELTO");
        $("#lbtotalcobrado").html(totalcobrado.toLocaleString());
        console.log("entra1")
    }
   else if (vuelto <= 0) {
    $("#vuelto").autoNumeric("set", vuelto * -1);
    // $("#vuelto").val(vuelto);
    $("#lbvuelto").html("Vuelto");

    } else {
        $("#vuelto").autoNumeric("set", vuelto);
        $("#lbvuelto").html("Faltan");
        $("#lbtotalcobrado").html((vuelto).toLocaleString());
     
        
    }
    

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
                //alert(data);
                $("#grilladetalle tbody").html(data);
                calcularTotales();
                calcularVuelto();
            });
    $.post("cheques.php", {fil: filtro})
            .done(function (data) {
                //alert(data);
                $("#grillacheques tbody").html(data);
                calcularTotales();
                calcularTotalCheque();
                calcularVuelto();
            });
    $.post("tarjetas.php", {fil: filtro})
            .done(function (data) {
                //alert(data);
                $("#grillatarjetas tbody").html(data);
                calcularTotales();
                calcularTotalTarjeta();
                calcularVuelto();
            });
}
function seleccion(fila,id_cobro) {
    console.log(id_cobro);
    $("#codigo").val(id_cobro);
    $("#cboidclientes").val(fila.id_cliente).trigger("chosen:updated");
    $("#efectivo").val(fila.cob_efectivo);
    $("#idaper").val(fila.id_apercierre);
    $("#fecha").val(fila.fechanormal);
    $("#txtnro").val(22);
    // fila.find("td").each(function (index) {

    //     switch (index) {
    //         case 0:
    //             $("#codigo").val($(this).text());
    //             break;

    //         case 1:
    //             $("#txtnro").val($(this).text());
    //             break;

           

    //         case 5:
    //             $("#cboidclientes").val(parseInt($(this).text())).trigger("chosen:updated");
    //             break;

    //         case 6:
    //             $("#efectivo").val($(this).text());
    //             break;
                
    //         case 7:
    //             $("#idaper").val($(this).text());
    //             break;
                
    //         case 8:
    //             $("#fecha").val($(this).text());
    //             break;
    //     }
    //     
    // });
    $("#panelBuscador").modal('hide');
    get_detalles(id_cobro);
}


function agregar_fila() {
    var productodesc = $('#cbomercaderias option:selected').html();
    var prod = $('#cbomercaderias').val();
    var producto = prod.split('~');
    var cant = parseInt($('#txtcantidad').val());
    var prec = parseInt($('#txtprecio').val());
    var exenta = 0;
    var grav5 = 0;
    var grav10 = 0;
    $("#cboiddeposito").attr("disabled", "true").trigger('chosen:updated');

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
        bootbox.confirm('SOLO HAY ' + producto[3] + ' EN STOCK ESTE PRODUCTO. ¿DESEA VENDER TODOS?', function (resp) {
            if (resp) {
                $('#txtcantidad').val(producto[3]);
                agregar_fila();
            } else {
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
                                    cant = (cant + parseInt($(this).text().replace(/\./g, "")));
                                    $(this).text(cant.toLocaleString());
                                }
                                if (i === 3) {

                                    $(this).text(prec.toLocaleString());
                                }
                                if (i === 4) {
                                    exenta = (exenta + parseInt($(this).text().replace(/\./g, "")));
                                    $(this).text(exenta.toLocaleString());
                                }
                                if (i === 5) {
                                    grav5 = (grav5 + parseInt($(this).text().replace(/\./g, "")));
                                    $(this).text(grav5.toLocaleString());
                                }
                                if (i === 6) {
                                    grav10 = (grav10 + parseInt($(this).text().replace(/\./g, "")));
                                    $(this).text(grav10.toLocaleString());
                                }

                            });
                        });
                    }
                }

            });
        });

        if (!repetido) {
            $('#grilladetalle > tbody:last').append('<tr><td style="text-align: center;">' + producto[0] + '</td><td>' + productodesc + '</td><td style="text-align: center;">' + cant.toLocaleString() + '</td><td style="text-align: center;">' + prec.toLocaleString() + '</td><td style="text-align: center;">' + exenta.toLocaleString() + '</td><td style="text-align: center;">' + grav5.toLocaleString() + '</td><td style="text-align: center;">' + grav10.toLocaleString() + '</td><td style="text-align: center;" onclick="eliminarfila($(this).parent())"><button type="button" class="btn btn-danger btn-sm ">x</button></td></tr>');
            contador++;
        }
        calcularTotales();
        $('#txtcantidad').val("");
    }
}


function ubicarCheque() {

    var ent = $('#identidad').val();
    var entdesc = $('#identidad option:selected').html();
    var num = $('#numeroch').val();
    var titu = $('#titularch').val();
    var emi = $('#emision').val();
    var venc = $('#vence').val();

    var impor = parseFloat($('#importech').autoNumeric("get"));


    var repetido = false;
    var codent = 0;
    var nroch = 0;
    var contador = 0;
    if (impor <= 0) {
        bootbox.alert('EL MONTO DEBE SER POSITIVO');
    } else {
        $("#grillacheques tbody tr").each(function (index) {
            $(this).children("td").each(function (index2) {
                if (index2 === 0) {
                    codent = $(this).text();
                }
                if (index2 === 2) {
                    nroch = $(this).text();
                }
                if (codent === ent && nroch === num) {
                    repetido = true;
                }

            });
        });

        if (!repetido) {
            $('#grillacheques > tbody:last').append('<tr class="ultimo"><td hidden>' + ent + '</td><td style="text-align: left;">' + entdesc + '</td><td style="text-align: center;">' + num + '</td><td style="text-align: left;">' + titu + '</td><td style="text-align: center;">' + emi + '</td><td style="text-align: center;">' + venc + '</td><td style="text-align: center;">' + impor.toLocaleString() + '</td><td style="text-align: center;" onclick="eliminarfila($(this).parent())"><button type="button" class="btn btn-danger btn-sm">x</button></td></tr>');
            contador++;
        } else {
            bootbox.alert("ESTE CHEQUE YA FUE SELECCIONADO");
        }
        calcularTotalCheque();
        calcularVuelto();
    }

}


function calcularTotalCheque() {
    var total = 0;

    $("#grillacheques tbody tr").each(function (fila) {
        $(this).children("td").each(function (col) {
            if (col === 6) {
                total += parseFloat($(this).text().replace(",",""));
            }
        });
    });
    var totales = "";

    totales += "<tr>";
    totales += "<th class=\"danger\" colspan=\"5\"><h5>TOTAL CHEQUE</h5></th>";
    totales += "<th class=\"danger\" style=\"text-align: center;\"><h5>" + total.toLocaleString() + "</h5></th>";
    totales += "<th class=\"default\"><h5></h5></th>";
    totales += "</tr>";
    $("#totalcheques").autoNumeric("set",total);
    $("#lbmontocheque").html(total.toLocaleString());
    $("#grillacheques tfoot").html(totales);
}

function ubicarTarjeta() {

    var ent = $('#identidadtar').val();
    var entdesc = $('#identidadtar option:selected').html();
    var tar = $('#idtarjeta').val();
    var tardesc = $('#idtarjeta option:selected').html();
    var num = $('#numerotar').val();
    var caut = $('#caut').val();

    var impor = parseFloat($('#importetar').autoNumeric("get"));


    var repetido = false;
    var codtar = 0;
    var nrotar = 0;
    var codaut = 0;
    var contador = 0;
    if (impor <= 0) {
        alert('EL MONTO DEBE SER POSITIVO');
    } else {
        $("#grillatarjetas tbody tr").each(function (index) {
            $(this).children("td").each(function (index2) {
                if (index2 === 0) {
                    codtar = $(this).text();
                }
                if (index2 === 4) {
                    nrotar = $(this).text();
                }
                if (index2 === 5) {
                    codaut = $(this).text();
                }
                if (codtar === tar && nrotar === num && codaut === caut) {
                    repetido = true;
                }

            });
        });

        if (!repetido) {
            $('#grillatarjetas > tbody:last').append('<tr class="ultimo"><td hidden>' + tar + '</td><td style="text-align: left;">' + tardesc + '</td><td style="text-align: center;" hidden>' + ent + '</td><td style="text-align: left;">' + entdesc + '</td><td style="text-align: center;">' + num + '</td><td style="text-align: center;">' + caut + '</td><td style="text-align: center;">' + impor.toLocaleString() + '</td><td style="text-align: center;" onclick="eliminarfila($(this).parent())"><button type="button" class="btn btn-danger btn-sm">x</button></td></tr>');
            contador++;
        } else {
            alert("ESTA TARJETA YA FUE SELECCIONADA");
        }
        calcularTotalTarjeta();
        calcularVuelto();
    }

}



function calcularTotalTarjeta() {
    var total = 0;

    $("#grillatarjetas tbody tr").each(function (fila) {
        $(this).children("td").each(function (col) {
            if (col === 6) {
                total += parseFloat($(this).text().replace(",",""));
            }
        });
    });
    var totales = "";

    totales += "<tr>";
    totales += "<th class=\"danger\" colspan=\"4\"><h5>TOTAL TARJETA</h5></th>";
    totales += "<th class=\"danger\" style=\"text-align: center;\"><h5>" + total.toLocaleString() + "</h5></th>";
    totales += "<th class=\"default\"><h5></h5></th>";
    totales += "</tr>";
    $("#totaltarjetas").val(total);
    $("#lbmontotarjeta").html(total.toLocaleString());
    $("#grillatarjetas tfoot").html(totales);

}


function generarArrayCtas() {
    var salidacta = '{';
    $("#grilladetalle tbody tr").each(function (index) {


        salidacta = salidacta + '{';

        $(this).children("td").each(function (index2) {

            if (index2 < 4) {
                salidacta = salidacta + $(this).text().split(".").join("").split(",").join("").replace("x","") + ",";
            } else {
                salidacta = salidacta + $(this).text().split(".").join("").split(",").join("").replace("x","") ;
            }



        });
        if (index < $("#grilladetalle tbody tr").length - 1) {
            salidacta = salidacta + '},';
        } else {
            salidacta = salidacta + '}';
        }
    });
    salidacta = salidacta + '}';
    $('#detallecobro').val(salidacta);
    //alert(salidacta);
}




function generarArrayCheques() {
    // var salidacta = '{';
    // $("#grillacheques tbody tr").each(function (index) {


    //     salidacta = salidacta + '{';

    //     $(this).children("td").each(function (index2) {

    //         if (index2 < 6) {
    //             salidacta = salidacta + $(this).text().split(".").join("") + ",";
    //         } else {
    //             salidacta = salidacta + $(this).text().split(".").join("");
    //         }



    //     });
    //     if (index < $("#grillacheques tbody tr").length - 1) {
    //         salidacta = salidacta + '},';
    //     } else {
    //         salidacta = salidacta + '}';
    //     }
    // });
    // salidacta = salidacta + '}';
    // $('#detallecheque').val(salidacta);
    var cheques = []; // Array para almacenar los cheques
    
    $("#grillacheques tbody tr").each(function () {
        var cheque = {}; // Objeto para almacenar la información de cada cheque

        // Itera sobre cada celda de la fila actual
        $(this).children("td").each(function (index) {
            var value = $(this).text().split(".").join("").split(",").join(""); // Elimina puntos del texto
            cheque["campo" + (index + 1)] = value; // Almacena el valor en una propiedad del objeto cheque
        });

        cheques.push(cheque); // Agrega el objeto cheque al array de cheques
    });
    
    var jsonCheques = JSON.stringify(cheques); // Serializa el array de cheques a JSON

    $('#detallecheque').val(jsonCheques); // Establece el valor del campo oculto con el JSON resultante
    //alert(jsonCheques); // Opcional: muestra el JSON resultante en una alerta
    //alert(salidacta);
}



function generarArrayTarjetas() {
    // var salidacta = '{';
    // $("#grillatarjetas tbody tr").each(function (index) {


    //     salidacta = salidacta + '{';

    //     $(this).children("td").each(function (index2) {

    //         if (index2 < 6) {
    //             salidacta = salidacta + $(this).text().split(".").join("") + ",";
    //         } else {
    //             salidacta = salidacta + $(this).text().split(".").join("");
    //         }



    //     });
    //     if (index < $("#grillatarjetas tbody tr").length - 1) {
    //         salidacta = salidacta + '},';
    //     } else {
    //         salidacta = salidacta + '}';
    //     }
    // });
    // salidacta = salidacta + '}';
    // $('#detalletarjeta').val(salidacta);
    var tarjetas = []; // Array para almacenar los cheques
    
    $("#grillatarjetas tbody tr").each(function () {
        var tarjeta = {}; // Objeto para almacenar la información de cada cheque

        // Itera sobre cada celda de la fila actual
        $(this).children("td").each(function (index) {
            var value = $(this).text().split(".").join("").split(",").join(""); // Elimina puntos y coma del texto
            tarjeta["campo" + (index + 1)] = value; // Almacena el valor en una propiedad del objeto cheque
        });

        tarjetas.push(tarjeta); // Agrega el objeto cheque al array de cheques
    });
    
    var jsontarjeta = JSON.stringify(tarjetas); // Serializa el array de cheques a JSON

    $('#detalletarjeta').val(jsontarjeta);
    //alert(salidacta);
}


function cuentaselect() {
    var id = $('#cuentas').val();
    var dat = id.split("~");
    $('#saldo').autoNumeric("set",dat[2]);
    $('#saldo').select();
}



function eliminarfila(parent) {
    $(parent).remove();
    calcularTotales();
    calcularTotalCheque();
    calcularTotalTarjeta();
    calcularVuelto();
}

function getCuentas() {
    var cli = $('#idclientes').val();
    //alert(dep);
    var ajax_load = "<div class='progress'>" + "<div id='progress_id' class='progress-bar progress-bar-striped active' " +
            "role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 45%'>" +
            "Cargando...</div></div>";
    $("#prog").html(ajax_load);



    $.post("cuentas.php", {id_cliente: cli})
            .done(function (data) {
                var ajax_load = "<div class='progress'>" + "<div id='progress_id' class='progress-bar progress-bar-striped active' " +
                        "role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 100%'>" +
                        "Completado</div></div>";
                $("#prog").html(ajax_load);
                setTimeout('$("#prog").html("")', 500);
                $("#cuentas").html(data).trigger('chosen:updated');
                cuentaselect();

            });
}

function agregar_cuentas() {

    var cta = $('#cuentas').val();
    var cuentas = cta.split('~');
    var sal = parseFloat($('#saldo').autoNumeric("get"));


    var repetido = false;
    var cov = 0;
    var coc = 0;
    var contador = 0;
    if (parseFloat(cuentas[2]) < sal) {
        bootbox.alert('EL MONTO INGRESADO SUPERA EL SALDO DE LA CUENTA');
    } else {
        $("#grilladetalle tbody tr").each(function (index) {
            $(this).children("td").each(function (index2) {
                if (index2 === 0) {
                    cov = $(this).text();
                }
                if (index2 === 1) {
                    coc = $(this).text();
                }
                if (cov === cuentas[0] && coc === cuentas[1]) {
                    repetido = true;
                }

            });
        });

        if (!repetido) {
            $('#grilladetalle > tbody:last').append('<tr class="ultimo"><td hidden>' + cuentas[0] + '</td><td style="text-align: center;">' + cuentas[1] + '</td><td style="text-align: center;">' + cuentas[3] + '</td><td style="text-align: center;">' + cuentas[4] + '</td><td style="text-align: center;">' + sal.toLocaleString() + '</td><td style="text-align: center;" onclick="eliminarfila($(this).parent())"><button type="button" class="btn btn-danger btn-sm">x</button></td></tr>');
            contador++;
        } else {
            bootbox.alert("ESTA CUENTA YA FUE SELECCIONADA");
        }
        calcularTotales();
        calcularVuelto();
    }
}


$(function () {
    $('#txtcantidad').keypress(function (e) {
        if (e.which === 13) {
            agregar_fila();
        }
    });


    $('#saldo').keypress(function (e) {
        if (e.which === 13) {
            agregar_cuentas();
        }
    });
    
    
    $('#importech').keypress(function (e) {
        if (e.which === 13) {
            ubicarCheque();
        }
    });
    $('#importetar').keypress(function (e) {
        if (e.which === 13) {
            ubicarTarjeta();
        }
    });
    
    $(".chosen-select").chosen({width: "100%"});


    calcularTotales();
    calcularVuelto();
    getCuentas();

});







