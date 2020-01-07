$("#frmLogin").submit(function (e) {
    e.preventDefault();
    var url = get_base_url() + "Api/LoginCandidate";
    $.ajax({
        url: url,
        data: $("#frmLogin").serialize(),
        type: 'get',
        dataType: "json",
        success: function (data) {
            if (data.mens == 'ok') {
                location.reload();
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Usuario y/o contraseña erroneas',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            }
        },
        fail: function () {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        }
    });
});