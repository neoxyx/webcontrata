$("#frmRegisterCandidate").submit(function (e) {
    e.preventDefault();
    var url = 'https://www.webcontrata.com/Api/RegisterCandidate';
    $.ajax({
        url: url,
        data: $("#frmRegisterCandidate").serialize(),
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