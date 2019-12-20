$('#frmSaldoInicial').submit(function(event){
    event.preventDefault();
    $.ajax({
        url: get_base_url()+'/Api-V1/User/add_initial_balance',
        type: 'post',
        data: {'valor':$("#valor").val(), 'compromiso':$("#compromiso").val()},
        format: 'json',
		success: function (result) {
                $.confirm({
                    title: '¡INFORMACIÓN!',
                    content: 'El nuevo saldo inicial, se ha registrado satisfactoriamente.',
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
} );