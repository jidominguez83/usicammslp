<!-- BEGIN Record proceso_participa -->
<?php 
require_once 'includes/header.php'; // Carga el encabezado de la página.
require_once 'includes/funciones.php'; // Llama el archivo de funciones del sistema.
?>
<div class="div_principal_notificaciones">

    <div class="notificaciones" id="not_tut">
    <form name="notificaciones_tutor" action="crear_notificaciones_tutores.php">
        <h1>NOTIFICACIONES</h1>
        <h3>TUTORES</h3>
        <?php
        $consulta1  = "SELECT COUNT(DISTINCT(tutor_curp)) AS total_tutores FROM tutoria_asignaciones";
        $sql1       = mysqli_query($dbase, $consulta1);
        $row1       = mysqli_fetch_assoc($sql1);
        ?>
        <p>Total: <?= $row1['total_tutores'] ?></p>
        <?php
        $consulta2 = "SELECT COUNT(DISTINCT(tutor_curp)) AS total_tutores_notificacion FROM tutoria_asignaciones WHERE tutor_notificacion_generada = 1";
        $sql2      = mysqli_query($dbase, $consulta2);
        $row2      = mysqli_fetch_assoc($sql2);
        ?>
        <p style='color:#008F39';>Notificaciones generadas: <?= $row2['total_tutores_notificacion'] ?></p>
        <?php
        $consulta3 = "SELECT COUNT(DISTINCT(tutor_curp)) AS total_tutores_sin_notificacion FROM tutoria_asignaciones WHERE tutor_notificacion_generada = 0";
        $sql3      = mysqli_query($dbase, $consulta3);
        $row3      = mysqli_fetch_assoc($sql3);
        ?>
        <p style='color:#FF0000';>Notificaciones faltantes: <?= $row3['total_tutores_sin_notificacion'] ?></p>
        <br>
        <center>
        <p><button id="gen_not_tutor" name="gen_not_tutor" type="submit">Generar notificaciones</button></p>
        <div id="cargando_tutores">
            <img src="css/images/gifs/loading2.gif" width="80px">
        </div>
        </center>
        </form>
    </div>

    <div class="notificaciones" id="not_tut">
    <form name="notificaciones_tutor" action="crear_notificaciones_tutorados.php">
        <h1>NOTIFICACIONES</h1>
        <h3>TUTORADOS</h3>
        <?php
        $consulta4  = "SELECT COUNT(tutorado_curp) AS total_tutorados FROM tutoria_asignaciones";
        $sql4       = mysqli_query($dbase, $consulta4);
        $row4       = mysqli_fetch_assoc($sql4);
        ?>
        <p>Total: <?= $row4['total_tutorados'] ?></p>
        <?php
        $consulta5 = "SELECT COUNT(tutorado_curp) AS total_tutorados_notificacion FROM tutoria_asignaciones WHERE tutorado_notificacion_generada = 1";
        $sql5      = mysqli_query($dbase, $consulta5);
        $row5      = mysqli_fetch_assoc($sql5);
        ?>
        <p style='color:#008F39';>Notificaciones generadas:  <?= $row5['total_tutorados_notificacion'] ?></p>
        <?php
        $consulta6 = "SELECT COUNT(tutorado_curp) AS total_tutorados_sin_notificacion FROM tutoria_asignaciones WHERE tutorado_notificacion_generada = 0";
        $sql6      = mysqli_query($dbase, $consulta6);
        $row6      = mysqli_fetch_assoc($sql6);
        ?>
        <p style='color:#FF0000';>Notificaciones faltantes: <?= $row6['total_tutorados_sin_notificacion'] ?></p>
        <br>
        <center>
        <p><button id="gen_not_tutorado" name="gen_not_tutorado" type="submit">Generar notificaciones</button></p>
        <div id="cargando_tutorados">
            <img src="css/images/gifs/loading2.gif" width="80px">
        </div>
        </center>
    </form>
    </div>

    <div class="notificaciones" id="not_enviadas">
    <form name="notificaciones_correo" action="envia_correos.php">
        <h1>ENVÍO MASIVO DE CORREOS</h1>
        <h3>TUTORES</h3>
        <?php
        $consulta7 = "SELECT COUNT(DISTINCT(tutor_curp)) AS total_tutores_correo FROM tutoria_asignaciones WHERE tutor_correo_enviado = 1";
        $sql7      = mysqli_query($dbase, $consulta7);
        $row7      = mysqli_fetch_assoc($sql7);
        ?>
        <p style='color:#008F39';>Enviados exitosamente: <?= $row7['total_tutores_correo'] ?></p>
        <?php
        $consulta8 = "SELECT COUNT(DISTINCT(tutor_curp)) AS total_tutores_sin_correo FROM tutoria_asignaciones WHERE tutor_correo_enviado = 0";
        $sql8      = mysqli_query($dbase, $consulta8);
        $row8      = mysqli_fetch_assoc($sql8);
        ?>
        <p style='color:#FF0000';>Enviados sin éxito: <?= $row8['total_tutores_sin_correo'] ?></p>
        <h3>TUTORADOS</h3>
        <?php
        $consulta9 = "SELECT COUNT(tutorado_curp) AS total_tutorados_correo FROM tutoria_asignaciones WHERE tutorado_correo_enviado = 1";
        $sql9      = mysqli_query($dbase, $consulta9);
        $row9      = mysqli_fetch_assoc($sql9);
        ?>
        <p style='color:#008F39'>Enviados exitosamente: <?= $row9['total_tutorados_correo'] ?></p>
        <?php
        $consulta10 = "SELECT COUNT(tutorado_curp) AS total_tutorados_sin_correo FROM tutoria_asignaciones WHERE tutorado_correo_enviado = 0";
        $sql10      = mysqli_query($dbase, $consulta10);
        $row10      = mysqli_fetch_assoc($sql10);
        ?>
        <p style='color:#FF0000';>Enviados sin éxito: <?= $row10['total_tutorados_sin_correo'] ?></p>
        <center>
        <p><button id="envia_not_tutoria" name="envia_not_tutoria" type="submit">Enviar correos</button></p>
        </center>
    </form>
    </div>

</div>
<?php
require_once 'includes/footer.php'; // Carga el pie de página.
?>