$(document).ready(function(){
    // Muestra u oculta el div para subir documento de incidencia
    $("#div_documento").hide();
    $("#trae_documento").change(function(){
        var $input = $(this);

        if($input.prop("checked") == true){
            $("#div_documento").show('normal');
        } else {
            $("#div_documento").hide('normal');
        }
    })
    .change();

    /* Habilita y deshabilita las opciones de búsqueda según se elija por CURP o por Tipo de Evaluación. */
    $("#buscar_curp").keyup(function() {
        if ($("#buscar_curp").val() == "") {
            $("#buscar_tipo_eval").prop("disabled", false);
            $("#buscar_lista").prop("disabled", false);
        } else {
            $("#buscar_tipo_eval").prop("disabled", true);
            $("#buscar_lista").prop("disabled", true);
        }
    });

    $("#buscar_tipo_eval").change(function() {
        if (parseInt($("#buscar_tipo_eval").val()) === 0) {
            $("#buscar_curp").prop("disabled", false);
        } else {
            $("#buscar_curp").prop("disabled", true);
        }
    });

    $("#buscar_lista").change(function() {
        if (parseInt($("#buscar_lista").val()) === "TODOS") {
            $("#buscar_curp").prop("disabled", false);
        } else {
            $("#buscar_curp").prop("disabled", true);
        }
    });

})
