$(document).ready(function() {
    $('#tblSorteos').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }
      });
    $.ajax({
        url: get_base_url()+'/Api/Numeros',
        type: 'get',
        format: 'json',
		success: function (result) {
            $.each(result, function(i, item){
                $("#trSorteos").append("<tr><td>"+item.EMPRESA+"</td><td>"+item.SORTEOLOTERIA+"</td><td>"+item.VALORPREMIOMAYOR+"</td><tr>");
            })
        }
    });
} );