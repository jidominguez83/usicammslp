$(document).ready(function(){
    // Muestra u oculta el div para subir documento de incidencia.
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

    // Muestra y oculta el div para seleccionar el nivel educativo en el caso de elegir "Otro" al seleccionar un proceso.
    /*$("#div_nivel_educativo").hide();
    $("#proceso").change(function(){
        var $proceso = $(this);
        let contador = 1;

        if($proceso.val() == 0){
            $("#nivel_educativo").prop("disabled", true);
            $("#div_nivel_educativo").hide('normal');
            console.log($proceso.val());
        } else {
            $("#nivel_educativo").prop("disabled", false);
            $("#div_nivel_educativo").show('normal');
            console.log($proceso.val());
        }
    })
    .change();*/

    // Habilita y deshabilita las opciones de búsqueda según se elija por CURP o por Tipo de Evaluación.
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

});
