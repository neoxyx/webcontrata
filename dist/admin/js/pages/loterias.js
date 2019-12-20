$(document).ready(function() {
    $('#tblLoterias').DataTable({
        "pageLength": 25,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }
    });
    $.ajax({
        url: get_base_url()+'/Api/Loterias',
        type: 'get',
        format: 'json',
		success: function (result) {
            $.each(result, function(i, item){
                $("#trLoterias").append("<tr><td>"+item.EMPRESA+"</td><td>"+item.NOMBRE+"</td><td>"+item.COLOR+"</td><tr>");
            })
        }
    });
    $(".loader").fadeOut("slow");
});
function loader(){
    $(".loader").fadeIn("slow");
}
function order(id) {
    var url = get_base_url() + '/admin/Loterias/updateOrder';
    var order = $("#order_"+id).val();
    $.ajax({
        url: url,
        data: {id:id,order:order},
        type: 'post',
        success: function (data) {
            console.log(data);
            location.reload();
        },
        fail: function () {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        }
    });
};