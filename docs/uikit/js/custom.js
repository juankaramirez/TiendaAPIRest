jQuery('#slider').camera({
    fx: 'simpleFade',
    time: 10000
});

$('#reset1').click(function(){
    $('#opciones').addClass('uk-push-7-10');
});

$(document).ready(function() {
    var consulta;
    
    //hacemos focus al campo de búsqueda
    $("#buscar").focus();

    //comprobamos si se pulsa una tecla
    $("#buscar").keyup(function(e) {

        //obtenemos el texto introducido en el campo de búsqueda
        consulta = $("#buscar").val();

        //hace la búsqueda                                                                  
        $.ajax({
            type: "POST",
            url: "buscar.php",
            data: "b=" + consulta,
            dataType: "html",
            beforeSend: function() {
                //imagen de carga
                $("#principal").html("<p align='center'><img src='ajax-loader.gif' /></p>");
            },
            error: function() {
                alert("error petición ajax");
            },
            success: function(data) {
                $("#principal").empty();
                $("#principal").append(data);

            }
        });


    });

});



