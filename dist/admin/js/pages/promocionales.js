$(document).ready(function() {
    var table = $('.table').DataTable( {
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        fixedColumns:   true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }
    } );
} );

$("#tipo").change(function(){
    ocultar();
    if($("#tipo").val()==1)
        $('#dvArticulo').show();
    if($("#tipo").val()==2)
        $('#dvBono').show();
    if($("#tipo").val()==3)
        $('#dvFraccion').show();
    if($("#tipo").val()==4)
        $('#dvPromCobro').show();
});

function ocultar(){
    $('#dvArticulo').hide();
    $('#dvBono').hide();
    $('#dvFraccion').hide();
    $('#dvPromCobro').hide();
}

$('#frmRegistrarPromocional').submit(function(event){
    event.preventDefault();
    if($("#tipo").val()==4){
        $.ajax({
            url: get_base_url()+'/Api-V1/Promotional/collection_incentive',
            type: 'put',
            data: {'ID_LOTERIA': $("#loterias").val(), 'SORTEO': $("#sorteo").val(), 'VALOR': $("#valorPromCobro").val()},
            format: 'json',
            success: function (result) {
                    $.confirm({
                        title: result['title'],
                        content: result['text'],
                        type: result['type'],
                        typeAnimated: true,
                        buttons: {
                            Cerrar: function () {
                                location.reload();
                            }
                        }
                    });
                }
        });
    }
    else{
        NOMBRE = '';
        UNIDADES = '';
        COMPROMISO = '';

        if($("#tipo").val()==1){
            NOMBRE = $("#articulo").val();
            UNIDADES = $("#unidadesArticulo").val();
        }
        if($("#tipo").val()==2){
            NOMBRE = $("#bono").val();
            UNIDADES = $("#unidadesBono").val();
            COMPROMISO = $("#compromiso").val();
        }
        if($("#tipo").val()==3){
            NOMBRE = $("#loterias").val();
            UNIDADES = $("#unidadesFracciones").val();
        }

        $.ajax({
            url: get_base_url()+'/Api-V1/Promotional',
            type: 'post',
            data: {'ID_TIPO': $("#tipo").val(), 'ID_LOTERIA': $("#loterias").val(), 'NOMBRE': NOMBRE, 'UNIDADES': UNIDADES, 'COMPROMISO': COMPROMISO},
            format: 'json',
            success: function (result) {
                    $.confirm({
                        title: result['title'],
                        content: result['text'],
                        type: result['type'],
                        typeAnimated: true,
                        buttons: {
                            Cerrar: function () {
                                location.reload();
                            }
                        }
                    });
                }
        });
    }
} );

function cambiarEstadoSP(idSolicitud, estado){
    if(estado == 2){
        $.confirm({
            title: 'Enviar premio',
            content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Número de guía de envío</label>' +
            '<input type="number" placeholder="Número de guía de envío" class="numGuia form-control" required />' +
            '</div>' +
            '</form>',
            buttons: {
                formSubmit: {
                    text: 'Guardar',
                    btnClass: 'btn-blue',
                    action: function () {
                        var numGuia = this.$content.find('.numGuia').val();
                        
                        $.ajax({
                            url: get_base_url()+'/Api-V1/Promotional/send_prize',
                            type: 'put',
                            data: {'numGuia':numGuia, 'idSolicitud':idSolicitud},
                            format: 'json',
                            success: function (result) {
                                    $.confirm({
                                        title: '¡INFORMACIÓN!',
                                        content: 'Se actualizo el estado correctamente',
                                        type: 'green',
                                        typeAnimated: true,
                                        buttons: {
                                            Cerrar: function () {
                                                location.reload();
                                            }
                                        }
                                    });
                                }
                        });
                    }
                },
                Cancelar: function () {
                    //close
                },
            },
            onContentReady: function () {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    }
    else{
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;

        $.confirm({
            title: 'Cierre de envío de premios',
            content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Fecha de recepción de premio:</label>' +
            '<input type="date" max="'+today+'" placeholder="Fecha recepción" class="fechaRecepcion form-control" required />' +
            '</div>' +
            '</form>',
            buttons: {
                formSubmit: {
                    text: 'Guardar',
                    btnClass: 'btn-blue',
                    action: function () {
                        var fecha = this.$content.find('.fechaRecepcion').val();
                        
                        $.ajax({
                            url: get_base_url()+'/Api-V1/Promotional/closed_send',
                            type: 'put',
                            data: {'fecha':fecha, 'idSolicitud':idSolicitud},
                            format: 'json',
                            success: function (result) {
                                    $.confirm({
                                        title: '¡INFORMACIÓN!',
                                        content: 'Se cerro el envío del premio correctamente',
                                        type: 'green',
                                        typeAnimated: true,
                                        buttons: {
                                            Cerrar: function () {
                                                location.reload();
                                            }
                                        }
                                    });
                                }
                        });
                    }
                },
                Cancelar: function () {
                    //close
                },
            },
            onContentReady: function () {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    }
}

function cancelarSolicitudSP(idSolicitud){
    event.preventDefault();
    $.confirm({
        title: 'Cancelar envío',
        content: 'De sea cancelar la solicitud de envío de premio.',
        buttons: {
            formSubmit: {
                text: 'Si',
                btnClass: 'btn-blue',
                action: function () {                    
                    $.ajax({
                        url: get_base_url()+'/Api-V1/Promotional/cancel_send',
                        type: 'put',
                        data: {'idSolicitud':idSolicitud},
                        format: 'json',
                        success: function (result) {
                                $.confirm({
                                    title: '¡INFORMACIÓN!',
                                    content: 'Se canceló el pedido correctamente.',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        Cerrar: function () {
                                            location.reload();
                                        }
                                    }
                                });
                            }
                    });
                }
            },
            Cancelar: function () {
                //close
            },
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
}