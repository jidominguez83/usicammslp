<?php
$consulta3 = "SELECT * FROM nivel_educativo";
$sql3      = mysqli_query($dbase, $consulta3);
?>
<script type="text/javascript">
$(document).ready(function(){
    var niveles = new Array();
    <?php
    while($row3 = mysqli_fetch_array($sql3)):
    ?>
        niveles["<?= $row3['id_nivel_educativo'] ?>"] = "<?= $row3['nombre_nivel'] ?>";
    <?php
    endwhile;
    ?>

    $(".sel_proceso_participa").change(function() {
        var proceso_participa       = $(this);
        var id_proceso_participa    = proceso_participa.val();
        var nivel_educativo         = $("#nivel_educativo");
        nivel_educativo.empty();

        if(id_proceso_participa == "otro"){
            nivel_educativo.append('<option value="">-Seleccione un nivel-</option>');
            $.each(niveles, function(j,value2){
                if(j > 0){
                    nivel_educativo.append('<option value="'+j+'">'+value2+'</option>');
                }
            });
        } else {
            var array_proceso_participa = id_proceso_participa.split('-');
            var array_id_niveles        = array_proceso_participa[1];
            var separa_id_niveles       = array_id_niveles.split(',');
            $.each(separa_id_niveles, function(i,value1){
                $.each(niveles, function(j,value2){
                    if(value1 == j){
                        nivel_educativo.append('<option value="'+j+'">'+value2+'</option>');
                    }
                });
            });
        }
    });
});
</script>