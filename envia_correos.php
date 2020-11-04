<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/PHPMailer/src/Exception.php';
require 'librerias/PHPMailer/src/PHPMailer.php';
require 'librerias/PHPMailer/src/SMTP.php';

require_once 'includes/conexion.php'; // Conexión a la base de datos.

$consulta = "SELECT DISTINCT(tutor_curp), tutor_nombre, tutor_paterno, tutor_materno, tutor_modalidad_tutoria, tutor_correo FROM tutoria_asignaciones WHERE tutor_notificacion_generada = 1 AND tutor_correo_enviado = 0";
$sql      = mysqli_query($dbase, $consulta);

$i = 1;
while($row = mysqli_fetch_array($sql)){

    if($i <= 475){
    $curp          = $row['tutor_curp'];
    $nombre        = $row['tutor_nombre'].' '.$row['tutor_paterno'].' '.$row['tutor_materno'];
    $modalidad     = $row['tutor_modalidad_tutoria'];
    $correos_tutor = $row['tutor_correo'];

    // Elige la carpeta de donde tomará la notificación que se adjuntará en el correo de acuerdo a su modalidad de tutoría.
    switch($modalidad){
        case 'En Línea':
            $carpeta      = 'en_linea';
            $modalidadtxt = 'en línea';
        break;
        
        case 'Línea 2a Fase':
            $carpeta      = 'en_linea_2a_fase';
            $modalidadtxt = 'en línea';
        break;

        case 'Presencial':
            $carpeta      = 'presencial';
            $modalidadtxt = 'presencial';
        break;

        case 'Zonas Rurales':
            $carpeta      = 'zonas_rurales';
            $modalidadtxt = 'de atención a zonas rurales';
        break;
    }

    $separa_tutorcorreos = explode(",", $correos_tutor);

    // Extrae los correos del tutor y envía la notificación.
    foreach($separa_tutorcorreos as $correo_tutor){
        $mail   = new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host       = 'smtp.gmail.com';
        $mail->Port       = '587';
        $mail->Username   = 'coordtutorias.slp@gmail.com';
        $mail->Password   = 'ctspdslp16';
        //$mail->Username   = 'domingueztorres.ji@gmail.com';
        //$mail->Password   = '1v4ngmail';

        $mail->setFrom('coordtutorias.slp@gmail.com', 'Coordinación de Tutorías');
        $mail->addAddress($correo_tutor, $nombre);
        $mail->addAttachment('notificaciones_tutoria/tutores/'.$carpeta.'/'.$curp.'.pdf','Notificación para '.$curp.'.pdf');
        $mail->Subject = 'Notificación Tutoría';
        $mail->isHTML(true);

        $mail->Body = '<p align="right">Octubre, 2020</p>

        <b><i>Estimado Tutor(a) de la modalidad '.$modalidadtxt.'.</i></b>
        
        <p>Con fundamento en el Artículo 77 de la <i>Ley General del Sistema para la Carrera de las Maestras y los Maestros</i> y de conformidad con lo establecido en las <i>Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como Tutor en Educación Básica y a la Convocatoria estatal para la selección de Tutores,</i> se hace llegar por éste medio la notificación del grupo de tutorados que ha sido asignado en la <b><i>modalidad '.$modalidadtxt.'.</i></b></p>
        
        <p>Es importante establecer contacto con el grupo de tutorados para dar inicio a los trabajos de la modalidad.</p>
        
        <p>Cabe señalar que, además de la presente notificación estará recibiendo por parte del nivel educativo al que pertenece el Oficio de Asignación correspondiente, mismo que complementa su asignación como tutor.</p>
        
        <p>A partir de éste momento se harán llegar documentos y materiales que son de suma importancia para la puesta en práctica de la Tutoría.</p>
        
        <p>Cualquier duda o comentario estoy a sus órdenes.</p>
        
        <p>¡Bienvenidos y éxito en esta maravillosa experiencia como tutor!</p>';

        $mail->CharSet = 'UTF-8';

        if($mail->send()){
            echo "<p style='color:#008F39';>$i Correo enviado al tutor $nombre ($correo_tutor) con éxito.</p>";
            // Cambia el estatus a 1 de correo enviado del tutor en la base de datos.
            $consulta1 = "UPDATE tutoria_asignaciones SET tutor_correo_enviado = 1 WHERE tutor_curp = '$curp'";
            $sql1      = mysqli_query($dbase, $consulta1);
            $i++;
        } else {
            echo "<p style='color:#FF0000';>Ocurrió un problema al enviar el correo a $nombre ($correo_tutor).</p>";
        }
    }




    // Consulta para obtener tutados asignados al tutor.
    $consulta2 = "SELECT CONCAT(tutorado_nombre, ' ', tutorado_paterno, ' ', tutorado_materno) AS nombre_completo_tutorado, tutorado_curp, tutorado_correo FROM tutoria_asignaciones WHERE tutor_curp = '$curp' AND tutorado_correo_enviado = 0";
    $sql2      = mysqli_query($dbase, $consulta2);
    
    while($row2 = mysqli_fetch_array($sql2)){
        $curp_tutorado    = $row2['tutorado_curp'];
        $nombre_tutorado  = $row2['nombre_completo_tutorado'];
        $correos_tutorado = $row2['tutorado_correo'];

        $separa_tutoradocorreos = explode(",", $correos_tutorado);

        foreach($separa_tutoradocorreos as $correo_tutorado){
            $mail   = new PHPMailer();

            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = '587';
            $mail->Username   = 'coordtutorias.slp@gmail.com';
            $mail->Password   = 'ctspdslp16';
            //$mail->Username   = 'domingueztorres.ji@gmail.com';
            //$mail->Password   = '1v4ngmail';
    
            $mail->setFrom('coordtutorias.slp@gmail.com', 'Coordinación de Tutorías');
            $mail->addAddress($correo_tutorado, $nombre_tutorado);
            $mail->addAttachment('notificaciones_tutoria/tutorados/'.$carpeta.'/'.$curp_tutorado.'.pdf','Notificación para '.$curp_tutorado.'.pdf');
            $mail->Subject = 'Notificación Tutoría';
            $mail->isHTML(true);
    
            $mail->Body = '<p align="right">Octubre, 2020</p>
            <b><i>Estimado Tutorado(a) de la modalidad '.$modalidadtxt.'.</i></b>
            
            <p>Con fundamento en el Artículo 77 de la <i>Ley General del Sistema para la Carrera de las Maestras y los Maestros</i> y de conformidad con lo establecido en las <i>Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como Tutor en Educación Básica</i>,se hace llegar por éste medio la notificación de la asignación de Tutor en la <b><i>modalidad '.$modalidadtxt.'.</i></b></p>
            
            <p>Es importante establecer contacto con su tutor para dar inicio a los trabajos de la modalidad.</p>
            
            <p>Así mismo, hacemos de conocimiento que el proceso de Tutoría es de carácter obligatorio para los docentes y técnicos docentes de nuevo ingreso por un período de dos ciclos escolares completos, cuyas responsabilidades se incluyen en la presente notificación.</p>
            
            <p>Cualquier duda o comentario estoy a sus órdenes.</p>
            
            <p>¡Bienvenidos y éxito en su proceso de Tutoría!</p>';
    
            $mail->CharSet = 'UTF-8';
    
            if($mail->send()){
                echo "<p style='color:#008F39';>$i Correo enviado al tutorado $nombre_tutorado ($correo_tutorado) con éxito.</p>";
                // Cambia el estatus a 1 de correo enviado del tutorado en la base de datos.
                $consulta3 = "UPDATE tutoria_asignaciones SET tutorado_correo_enviado = 1 WHERE tutorado_curp = '$curp_tutorado'";
                $sql3      = mysqli_query($dbase, $consulta3);
                $i++;
            } else {
                echo "<p style='color:#FF0000';>Ocurrió un problema al enviar el correo a $nombre_tutorado ($correo_tutorado).</p>";
            }
        }
    }
    }
}
?>
<script type="text/javascript">
    if(confirm('La rutina ha terminado el envío masivo de correos electrónicos. Si deseas cerrar la ventana presiona ACEPTAR, si deseas ver el detalle del envío presiona CANCELAR.')){
        window.close();
    }
</script>