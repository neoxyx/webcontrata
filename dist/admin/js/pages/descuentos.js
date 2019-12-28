$(function () {
    $('select[multiple]').multiselect({
        search: true,
        selectAll: true,
        texts: {
            placeholder: 'Seleccione',
            search: 'Buscar Loteria',
            selectAll: 'Seleccionar todos',
            unselectAll: 'Borrar Selección'
        }
    });
});

$(function () {
    $.getJSON(get_base_url() + '/Api/TipoComprador', function (data) {
        $.each(data, function (k, x) {
            $("#idtipo_comprador").append('<option value="' + x.IDTIPO_COMPRADOR + '">' + x.DESCR + '</option>');
        });
    });
});

$(function () {
    $.getJSON(get_base_url() + '/Api/TipoPromocion', function (res) {
        $.each(res, function (key, y) {
            $("#idtipo_promocion").append('<option value="' + y.IDTIPO_PROMOCION + '">' + y.DESCP + '</option>');
        });
    });
});

$(function () {
    $.getJSON(get_base_url() + '/Api/Compromisos', function (res) {
        $.each(res, function (key, y) {
            $("#compromiso").append('<option value="' + y.CODIGO_COMPROMISO + '">' + y.CODIGO_COMPROMISO + '</option>');
        });
    });
});

$("#frmTipoc").submit(function (e) {
    e.preventDefault();
    var url = get_base_url() + '/Api/TipoComprador';
    $.ajax({
        url: url,
        data: $("#frmTipoc").serialize(),
        type: 'post',
        dataType: "json",
        success: function (data) {
            location.reload();
        },
        fail: function () {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        }
    });
});

$("#frmTipop").submit(function (e) {
    e.preventDefault();
    var url = get_base_url() + '/Api/TipoPromocion';
    $.ajax({
        url: url,
        data: $("#frmTipop").serialize(),
        type: 'post',
        dataType: "json",
        success: function (data) {
            location.reload();
        },
        fail: function () {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        }
    });
})

$("#frmDcto").submit(function (e) {
    e.preventDefault();
    var IDDESCUENTO = $("#id").val();
    var NOMBRE = $("#descuento").val();
    var COMPROMISO = $("#compromiso").val();
    var FECHAHORAINICIO = $("#fechaIni").val();
    var FECHAHORAFIN = $("#fechaFin").val();
    var DCTO = $("#desc").val();
    var VRINICIAL = $("#vrIn").val();
    var VRFINAL = $("#vrF").val();
    var VRCUPON = $("#vrCupon").val();
    var LOTERIA = $("#loteria").val().toString();
    var CANTSORTEOSABONADOS = $("#cantSorteosAbonados").val();
    var CODIGO = $("#codigo").val();
    var CANTCUPONESDISP = $("#cantCuponesDisp").val();
    var IDTIPO_DCTO = $("#idtipo_dcto").val();
    var IDTIPO_COMPRADOR = $("#idtipo_comprador").val();
    var IDTIPO_PROMOCION = $("#idtipo_promocion").val();
    var ESTADO = $("#estado").val();
    var url = get_base_url() + '/Api/Descuentos';
    if (IDDESCUENTO === '') {
        $.ajax({
            url: url,
            data: { NOMBRE, COMPROMISO, FECHAHORAINICIO, FECHAHORAFIN, DCTO, ESTADO, VRINICIAL, VRFINAL, LOTERIA, CANTSORTEOSABONADOS, CODIGO, CANTCUPONESDISP, VRCUPON, IDTIPO_COMPRADOR, IDTIPO_PROMOCION, IDTIPO_DCTO },
            type: 'post',
            dataType: "json",
            success: function (data) {
                location.reload();
            },
            fail: function () {
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus);
                }
            }
        });
    } else {
        $.ajax({
            url: url + '/' + IDDESCUENTO,
            data: { NOMBRE, COMPROMISO, FECHAHORAINICIO, FECHAHORAFIN, DCTO, ESTADO, VRINICIAL, VRFINAL, LOTERIA, CANTSORTEOSABONADOS, CODIGO, CANTCUPONESDISP, VRCUPON, IDTIPO_COMPRADOR, IDTIPO_PROMOCION, IDTIPO_DCTO },
            type: 'put',
            dataType: "json",
            success: function (data) {
                location.reload();
            },
            fail: function () {
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus);
                }
            }
        });
    }

})

function cambioEstado(id, estado) {
    $.ajax({
        url: get_base_url() + '/Api/Descuentos/estado/' + id,
        data: { 'ESTADO': estado },
        type: 'put',
        dataType: 'json',
        success: function (data) {
            $.alert({
                title: 'Exito!',
                content: 'Cambio de estado realizado',
            });
            location.reload();
        },
        fail: function () {
            if (console && console.log) {
                $.alert({
                    title: 'Alerta!',
                    content: 'La solicitud a fallado: '+ textStatus,
                });
            }
        }
    });
}

function editDcto(id) {
    var url = get_base_url() + '/Api/Descuentos/' + id;
    $.getJSON(url, function (data) {
        $("#titleForm").html("Editar Descuento");
        $("#id").val(data.IDDESCUENTO);
        $("#descuento").val(data.NOMBRE);
        $("#compromiso").val(data.COMPROMISO);
        $("#desc").val(data.DCTO);
        $("#vrIn").val(formatNumber(data.VRINICIAL));
        $("#vrF").val(formatNumber(data.VRFINAL));
        $("#vrInicial").val(data.VRINICIAL);
        $("#vrFin").val(data.VRFINAL);
        $("#cantSorteosAbonados").val(data.CANTSORTEOSABONADOS);
        $("#codigo").val(data.CODIGO);
        $("#cantCuponesDisp").val(data.CANTCUPONESDISP);
        $("#vrCupon").val(data.VRCUPON);
        $("#textBtnForm").html("Actualizar");
        $.getJSON(get_base_url() + '/Api/TipoComprador', function (res) {
            $.each(res, function (key, val) {
                if (data.IDTIPO_COMPRADOR == val.IDTIPO_COMPRADOR)
                    $("#idtipo_comprador option[value=" + val.IDTIPO_COMPRADOR + "]").attr("selected", true);
            });
        });
        $.getJSON(get_base_url() + '/Api/TipoPromocion', function (resp) {
            $.each(resp, function (key, val) {
                if (data.IDTIPO_PROMOCION == val.IDTIPO_PROMOCION)
                    $("#idtipo_promocion option[value=" + val.IDTIPO_PROMOCION + "]").attr("selected", true);
            });
        });
    });
}

function deleteDcto(id) {
    var url = get_base_url() + '/Api/Descuentos/' + id;
    $.confirm({
        title: 'Aviso de Eliminación',
        content: '¿Esta seguro de eliminar el descuento?',
        type: 'orange',
        typeAnimated: true,
        buttons: {
            si: {
                text: 'Si',
                btnClass: 'btn-yellow',
                action: function () {
                    $.ajax({
                        url: url,
                        type: 'delete',
                        success: function (data) {
                            location.reload();
                        },
                        fail: function () {
                            if (console && console.log) {
                                console.log("La solicitud a fallado: " + textStatus);
                            }
                        }
                    });
                }
            },
            No: {
                text: 'No',
                btnClass: 'btn-blue',
                action: function () {

                }
            }
        }
    });
}

function formatNumber(num) {
    if (!num || num === 'NaN')
        return '-';
    if (num === 'Infinity')
        return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num === (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    num = Math.floor(num / 100).toString();

    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' +
                num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '') + num);
}