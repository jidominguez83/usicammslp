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

/*
    // Muestra u oculta el div para subir documento de incidencia
    $("#nivel_educativo").hide();
    $("#id_proceso_otro").change(function(){
        var $input = $(this);

        if($input.prop("checked") == true){
            $("#nivel_educativo").show('normal');
            console.log("entro");
        } else {
            $("#nivel_educativo").hide('normal');
            console.log("salgo");
        }
    })
    .change(); 
    */    

    /* Habilita y deshabilita las opciones de búsqueda según se elija por CURP o por Tipo de Evaluación. */
    $("#buscar_curp").keyup(function() {
        if ($("#buscar_curp").val() == "") {
            $("#buscar_tipo_eval").prop("disabled", false);
        } else {
            $("#buscar_tipo_eval").prop("disabled", true );
        }
    });
    $("#buscar_tipo_eval").change(function() {
        if (parseInt($("#buscar_tipo_eval").val()) === 0) {
            $("#buscar_curp").prop("disabled", false);
        } else {
            $("#buscar_curp").prop("disabled", true);
        }
    });
})
