$(document).ready(function() {
    $('.table').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }
      });
} );

function cambiarEstado(idCanje, estado){
    if(estado == 0){
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
                            url: get_base_url()+'/Api-V1/Points/send_prize',
                            type: 'put',
                            data: {'numGuia':numGuia, 'idCanje':idCanje},
                            format: 'json',
                            success: function (result) {
                                    $.confirm({
                                        title: '¡INFORMACIÓN!',
                                        content: 'Se actualizo el estado correctamente',
                                        type: 'green',
                                        typeAnimated: true,
                                        buttons: {
                                            cerrar: function () {
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
                            url: get_base_url()+'/Api-V1/Points/closed_send',
                            type: 'put',
                            data: {'fecha':fecha, 'idCanje':idCanje},
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

function cambioEstadoProducto(id, estado){
    $.ajax({
        url: get_base_url()+'/Api-V1/Prize/change_state_product',
        type: 'put',
        data: {'idCatalogue':id, 'state':estado},
        format: 'json',
        success: function (result) {
            location.reload();
        }
    });
}

function editarProducto(idProducto, puntos, unidades, descripcion){
    $('#puntos').val(puntos);
    $('#unidades').val(unidades);
    $('#descripcion').val(descripcion);
    $('#idProducto').val(idProducto);
    $('#modalAddProduct').modal('show');
}

$('#frmAddProduct').submit(function(event){
    event.preventDefault();
    var formData = new FormData();
    var files = '';

    if($('#idProducto').val()==''){
        var files = $('#imagen')[0].files[0];
        var data = JSON.stringify({'puntos':$('#puntos').val(),'unidades':$('#unidades').val(),'descripcion':$('#descripcion').val()});
        formData.append('imagen',files);
        formData.append('data',data);
        $.ajax({
            url: get_base_url()+'/api/v1/product/add_product',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            format: 'json',
            success: function (result) {
                $.confirm({
                    title: result['title'],
                    content: result['content'],
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
        if($('#imagen').val()!=''){
            var files = $('#imagen')[0].files[0];
        }
        var data = JSON.stringify({'puntos':$('#puntos').val(),'unidades':$('#unidades').val(),'descripcion':$('#descripcion').val(),'idProducto':$('#idProducto').val()});
        formData.append('imagen',files);
        formData.append('data',data);
        $.ajax({
            url: get_base_url()+'/api/v1/product/update_product',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            format: 'json',
            success: function (result) {
                $.confirm({
                    title: result['title'],
                    content: result['content'],
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
});

$('#frmPointReferens').submit(function(event){
    event.preventDefault();
    $.ajax({
        url: get_base_url()+'/Api-V1/Points/insert_point_referens',
        data: $('#frmPointReferens').serialize(),
        type: 'post',
        dataType: "json",
        success: function (result) {
            $.confirm({
                title: result['title'],
                content: result['content'],
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
});

function cambioEstadoReglaPuntosCompras(id, estado){
    $.ajax({
        url: get_base_url()+'/Api-V1/Points/change_state_points_purchase',
        type: 'put',
        data: {'id':id, 'state':estado},
        format: 'json',
        success: function (result) {
            location.reload();
        }
    });
}

$('#frmPuntosCompras').submit(function(event){
    event.preventDefault();
    $.ajax({
        url: get_base_url()+'/Api-V1/Points/insert_point_purchase',
        data: $('#frmPuntosCompras').serialize(),
        type: 'post',
        dataType: "json",
        success: function (result) {
            $.confirm({
                title: result['title'],
                content: result['content'],
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
});

function cancelarPedido(idCanje){
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
                        url: get_base_url()+'/Api-V1/Points/cancel_send',
                        type: 'put',
                        data: {'idCanje':idCanje},
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

$('#frmCodigoCompromiso').submit(function(event){
    event.preventDefault();
    $.ajax({
        url: get_base_url()+'/Api-V1/Points/change_code_budget_points',
        data: $('#frmCodigoCompromiso').serialize(),
        type: 'put',
        dataType: "json",
        success: function (result) {
            $.confirm({
                title: '¡INFORMACIÓN!',
                content: 'Se asocio el código del compromiso satisfactoriamente.',
                type: 'green',
                typeAnimated: true,
                buttons: {
                        cerrar: function () {
                            location.reload();
                        }
                    }
                });
            }
    });
});