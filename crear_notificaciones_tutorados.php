<?php
require_once 'includes/conexion.php'; // Conexión a la base de datos.
require_once 'librerias/FPDF/fpdf.php'; // Carga la librería para generar documento PDF.

// Crea una nueva clase heredada de la clase principal FPDF.
class PDF extends FPDF{
    // Función para crear el encabezado.
    function Header(){
        $this->AddLink();
        $this->Image('css/images/SEP.png', 10, 10, 50, 0, '', ''); // Agrega logotipo de la SEP.
        $this->Image('css/images/USICAMM.png', 150, 15, 50, 0, '', ''); // Agrega logotipo de la USICAMM.
        $this->Ln(25); // Salto de línea después del encabezado.
    }
}

// Consulta para obtener tutorados.
$consulta = "SELECT * FROM tutoria_asignaciones WHERE tutorado_notificacion_generada = 0";
$sql      = mysqli_query($dbase, $consulta);

while($row = mysqli_fetch_assoc($sql)){
    $curp_tutorado            = $row['tutorado_curp'];
    $curp_tutor               = $row['tutor_curp'];
    $nombre_completo_tutorado = $row['tutorado_nombre'].' '.$row['tutorado_paterno'].' '.$row['tutorado_materno'];
    $modalidad                = $row['tutor_modalidad_tutoria'];

    // Inicia el cuerpo del documento PDF.
    $pdf    = new PDF();

    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(122,0,0);
    $pdf->Cell(0, 7, utf8_decode('TUTORÍA A DOCENTES Y TÉCNICOS DOCENTES DE NUEVO INGRESO EN EDUCACIÓN BÁSICA CICLO ESCOLAR 2020-2021'), 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(25,0,0);
    $pdf->Cell(0, 10, utf8_decode('Asunto: Notificación de asignación de Tutor (a) '.$nombre_completo_tutorado), 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);

    // Compara la modalidad de la tutoría (En línea, Línea 2a Fase, Presencial, Zonas Rurales) y hace el llenado correspondiente.
    /*********************************************************
    *    Valida si es modalidad en Línea ó Línea 2a Fase.    *
    *********************************************************/
    if($modalidad == 'En Línea' || $modalidad == 'Línea 2a Fase'){
        $pdf->MultiCell(0, 4, utf8_decode('Con fundamento en el artículo 77 de la Ley General del Sistema para la Carrera de las Maestras y los Maestros, las Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como tutor en Educación Básica y la Convocatoria estatal para la selección de los Tutores, por este medio se le notifica que la Autoridad Educativa del estado le asignó a un Tutor (a) en la modalidad en línea quien le ofrecerá apoyo y acompañamiento para favorecer su inserción al servicio público educativo durante el ciclo escolar 2020-2021. '), 0, 'J');
        $pdf->Ln(5);
        $pdf->MultiCell(0, 4, utf8_decode('Los datos de contacto de su Tutor (a) son los siguientes:'), 0, 'J');
        $pdf->Ln(10);

        // Inicia tabla con datos del tutor.

        // Carga de datos
        // Colores, ancho de línea y fuente en negrita.
        $pdf->SetFillColor(122,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B', 8);
        
        // Encabezado de la tabla.
        $pdf->Cell(70, 5, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(120, 5, utf8_decode('Correo electrónico'), 1, 0, 'C', true);
        $pdf->Ln();

        // Restauración de color y fuente.
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('','', 6);
        
        $nombre_completo_tutor = $row['tutor_nombre'].' '.$row['tutor_paterno'].' '.$row['tutor_materno'];

        // Llenado de filas de tabla.
        $pdf->Cell(70, 5, utf8_decode($nombre_completo_tutor), 'LR', 0, 'L');
        $pdf->Cell(120, 5, trim($row['tutor_correo']), 'LR', 1, 'L');
        $pdf->Cell(190,0,'','T'); // Última línea de la tabla.
        $pdf->Ln(10);
        // Termina tabla con datos del tutor.
    
        $pdf->SetFont('Arial','BI',8);
        $pdf->Cell(0, 5, utf8_decode('Inicio de la tutoría'), 0, 1, 'L');
        $pdf->Ln(3);

        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('A partir de este momento se llevará a cabo el proceso de matriculación del grupo en el que usted participará, lo que le permitirá iniciar la comunicación con su Tutor (a), así como las actividades programadas en el módulo que le corresponda. La plataforma Moodle se encuentra en la siguiente liga http://143.137.111.86/moodle/'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Para ingresar utilice su Clave Única de Registro de Población (CURP) como usuario y los primeros diez caracteres de su CURP como contraseña.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('En esta plataforma además de acceder al módulo de la tutoría que le corresponde usted podrá:'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'a)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Consultar el calendario de actividades de la tutoría en línea para el ciclo escolar 2020-2021 y,'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'b)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Acceder a los recursos y materiales de apoyo que se encuentran disponibles en las siguientes direcciones electrónicas'), 0, 'J');
        $pdf->Ln(3);

        $pdf->SetTextColor(5,99,193);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Portal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'http://143.137.111.80/dgpromocion/tutoria/?page_id=37', 0, 1, 'L');
        $pdf->Cell(40, 5, utf8_decode('Canal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'https://www.youtube.com/channel/UCOYkEIKZK8XOgXM_T3LGX5Q', 0, 1, 'L');
        $pdf->Ln(3);

        $pdf->SetTextColor(25,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10, 4, 'c)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Mantener la comunicación con su Tutor (a) antes, durante y al término de cada módulo con la finalidad de que reciba el apoyo y asesoría que exige su contexto educativo con oportunidad.'), 0, 'J');
        $pdf->Ln(3);
    }
    
    /*******************************************
    *    Valida si es modalidad Presencial.    *
    *******************************************/
    if($modalidad == 'Presencial'){
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('Con fundamento en el artículo 77 de la Ley General del Sistema para la Carrera de las Maestras y los Maestros, las Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como tutor en Educación Básica y la Convocatoria estatal para la selección de los Tutores, por este medio se le notifica que la Autoridad Educativa del estado le asignó a un Tutor (a) en la modalidad presencial quien le ofrecerá apoyo y acompañamiento para favorecer su inserción al servicio público educativo durante el ciclo escolar 2020-2021.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Los datos de contacto de su Tutor (a) son los siguientes:'), 0, 'J');
        $pdf->Ln(10);

        // Inicia tabla con datos del tutor.

        // Carga de datos
        // Colores, ancho de línea y fuente en negrita.
        $pdf->SetFillColor(122,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B', 8);
        
        // Encabezado de la tabla.
        $pdf->Cell(70, 5, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(120, 5, utf8_decode('Correo electrónico'), 1, 0, 'C', true);
        $pdf->Ln();

        // Restauración de color y fuente.
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('','', 6);
        
        $nombre_completo_tutor = $row['tutor_nombre'].' '.$row['tutor_paterno'].' '.$row['tutor_materno'];

        // Llenado de filas de tabla.
        $pdf->Cell(70, 5, utf8_decode($nombre_completo_tutor), 'LR', 0, 'L');
        $pdf->Cell(120, 5, trim($row['tutor_correo']), 'LR', 1, 'L');
        $pdf->Cell(190,0,'','T'); // Última línea de la tabla.
        $pdf->Ln(10);
        // Termina tabla con datos del tutor.

        $pdf->SetFont('Arial','BI',8);
        $pdf->Cell(0, 5, utf8_decode('Inicio de la tutoría'), 0, 1, 'L');
        $pdf->Ln(3);

        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('A partir de este momento será contactado por su Tutor (a), con quien acordará la forma de trabajo en este periodo de contingencia sanitaria.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Para contar con información relacionada con el modelo de la tutoría y acceder a materiales y recursos que fortalecerán el desarrollo de las actividades le sugerimos ingresar a las siguientes direcciones electrónicas:'), 0, 'J');
        $pdf->Ln(3);

        $pdf->SetTextColor(5,99,193);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Portal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'http://143.137.111.80/dgpromocion/tutoria/?page_id=37', 0, 1, 'L');
        $pdf->Cell(40, 5, utf8_decode('Canal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'https://www.youtube.com/channel/UCOYkEIKZK8XOgXM_T3LGX5Q', 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetTextColor(25,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 4, utf8_decode('La Tutoría presencial implica la asistencia física del tutor y de los tutorados en las reuniones de trabajo, la observación en las aulas, así como la comunicación directa y, en caso de considerarlo conveniente, a través de medios electrónicos. Sin embargo, como se mencionó anteriormente en cumplimiento de las medidas sanitarias vigentes cada entidad realizará las adecuaciones que considere pertinentes para el desarrollo de esta modalidad.'), 0, 'J');
        $pdf->Ln(5);
    }
    
    /**********************************************
    *    Valida si es modalidad Zonas Rurales.    *
    **********************************************/
    if($modalidad == 'Zonas Rurales'){
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('Con fundamento en el artículo 77 de la Ley General del Sistema para la Carrera de las Maestras y los Maestros, las Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como tutor en Educación Básica y la Convocatoria estatal para la selección de los Tutores, por este medio se le notifica que la Autoridad Educativa del estado le asignó a un Tutor (a) en la modalidad de atención en zonas rurales quien le ofrecerá apoyo y acompañamiento para favorecer su inserción al servicio público educativo durante el ciclo escolar 2020-2021.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Los datos de contacto de su Tutor (a) son los siguientes:'), 0, 'J');
        $pdf->Ln(10);

        // Inicia tabla con datos del tutor.

        // Carga de datos
        // Colores, ancho de línea y fuente en negrita.
        $pdf->SetFillColor(122,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B', 8);
        
        // Encabezado de la tabla.
        $pdf->Cell(70, 5, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(120, 5, utf8_decode('Correo electrónico'), 1, 0, 'C', true);
        $pdf->Ln();

        // Restauración de color y fuente.
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('','', 6);
        
        $nombre_completo_tutor = $row['tutor_nombre'].' '.$row['tutor_paterno'].' '.$row['tutor_materno'];

        // Llenado de filas de tabla.
        $pdf->Cell(70, 5, utf8_decode($nombre_completo_tutor), 'LR', 0, 'L');
        $pdf->Cell(120, 5, trim($row['tutor_correo']), 'LR', 1, 'L');
        $pdf->Cell(190,0,'','T'); // Última línea de la tabla.
        $pdf->Ln(10);
        // Termina tabla con datos del tutor.

        $pdf->SetFont('Arial','BI',8);
        $pdf->Cell(0, 5, utf8_decode('Inicio de la tutoría'), 0, 1, 'L');
        $pdf->Ln(3);

        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('A partir de este momento será contactado por su Tutor (a), con quien acordará la forma de trabajo en este periodo de contingencia sanitaria.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Para contar con información relacionada con el modelo de la tutoría y acceder a materiales y recursos que fortalecerán el desarrollo de las actividades le sugerimos ingresar a las siguientes direcciones electrónicas:'), 0, 'J');
        $pdf->Ln(3);

        $pdf->SetTextColor(5,99,193);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Portal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'http://143.137.111.80/dgpromocion/tutoria/?page_id=37', 0, 1, 'L');
        $pdf->Cell(40, 5, utf8_decode('Canal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'https://www.youtube.com/channel/UCOYkEIKZK8XOgXM_T3LGX5Q', 0, 1, 'L');
        $pdf->Ln(5);
        
        $pdf->SetTextColor(25,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 4, utf8_decode('La Tutoría presencial implica la asistencia física del tutor y de los tutorados en las reuniones de trabajo, la observación en las aulas, así como la comunicación directa y, en caso de considerarlo conveniente, a través de medios electrónicos. Sin embargo, como se mencionó anteriormente en cumplimiento de las medidas sanitarias vigentes cada entidad realizará las adecuaciones que considere pertinentes para el desarrollo de esta modalidad.'), 0, 'J');
        $pdf->Ln(10);
    }

    // Parte final de la notificación.
    $pdf->SetFont('Arial','BI',8);
    $pdf->Cell(0, 5, utf8_decode('Responsabilidades de los Docentes y Técnicos Docentes de nuevo ingreso.'), 0, 1, 'L');
    $pdf->Ln(3);
    $pdf->SetFont('Arial','',8);

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 4, 'a.', 0, 0, 'C');
    $pdf->MultiCell(180, 4, utf8_decode('Participar activamente en todas las actividades de la tutoría previstas en la modalidad asignada, en las condiciones y los tiempos establecidos.'), 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(10, 4, 'b.', 0, 0, 'C');
    $pdf->MultiCell(180, 4, utf8_decode('Mantener comunicación con el Tutor asignado, para dar inicio y continuidad a las actividades propias de la modalidad de tutoría en la que participa.'), 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(10, 4, 'c.', 0, 0, 'C');
    $pdf->MultiCell(180, 4, utf8_decode('Asistir a las reuniones, los encuentros o los foros que realicen en la modalidad con fines de desarrollo de la tutoría.'), 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(10, 4, 'd.', 0, 0, 'C');
    $pdf->MultiCell(180, 4, utf8_decode('Proporcionar información que solicite la Autoridad Educativa Local para recopilar o actualizar datos, dar seguimiento a las acciones programas y evaluar la operación de resultados de la tutoría.'), 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(10, 4, 'e.', 0, 0, 'C');
    $pdf->MultiCell(180, 4, utf8_decode('Dar respuesta a las solicitudes de información que se le requieran a través del Sistema de Registro y Seguimiento para la Tutoría sobre el desarrollo de las actividades en esta materia.'), 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(10, 4, 'f.', 0, 0, 'C');
    $pdf->MultiCell(180, 4, utf8_decode('Elaborar un expediente sistematizado del trabajo que desarrolla en la tutoría, conforme a lo definido en la modalidad en que participa.'), 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(10, 4, 'g.', 0, 0, 'C');
    $pdf->MultiCell(180, 4, utf8_decode('Responder a los instrumentos de consulta o evaluación del proceso de Tutoría que aplique la Autoridad Educativa.'), 0, 'J');
    $pdf->Ln(3);

    $pdf->MultiCell(0, 4, utf8_decode('La Tutoría tiene un carácter obligatorio, por lo que su incumplimiento lo hace acreedor de una nota desfavorable en su expediente fundamentado en los Artículos 40 y 42 de la Ley General del Sistema para la Carrera de las Maestras y los Maestros. '), 0, 'J');
    if ($modalidad == 'Zonas Rurales'){
        $pdf->Ln(20);       
    } else if ($modalidad == 'Presencial') {
        $pdf->Ln(25);  
    } else {
        $pdf->Ln(10); 
    }

    $pdf->SetFont('Arial','BI',8);
    $pdf->Cell(0, 5, 'Datos de contacto de la Autoridad Educativa en la entidad', 0, 1, 'L');
    $pdf->Ln(3);
    $pdf->SetFont('Arial','',8);
    $pdf->MultiCell(0, 4, utf8_decode('Finalmente le informamos que el personal responsable de la tutoría en la entidad dará atención a sus dudas e inquietudes a través de los siguientes medios:'), 0, 'J');

    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 8, utf8_decode('COORDINACIÓN DE TUTORÍA'), 0, 1, 'C');
    $pdf->SetTextColor(122,0,0);
    $pdf->Cell(0, 8, utf8_decode('Calle 18 de Marzo No. 210 Col. Jardín,'), 0, 1, 'C');
    $pdf->Cell(0, 8, utf8_decode('San Luis Potosí, S.L.P.'), 0, 1, 'C');
    $pdf->AddLink();
    $pdf->SetTextColor(5,99,193);
    $pdf->Cell(0, 8, utf8_decode('coordtutorias.slp@gmail.com'), 0, 1, 'C');
    $pdf->SetTextColor(25,0,0);
    $pdf->Cell(0, 8, utf8_decode('Tel. (444) 814 29 50  ext. 106'), 0, 1, 'C');
    $pdf->Ln(2);

    $pdf->SetTextColor(122,0,0);
    $pdf->MultiCell(0, 4, utf8_decode('La Unidad del Sistema para la Carrera de las Maestras y los Maestros (USICAMM) agradece su participación'), 0, 'C');

    // Elige la carpeta donde se guardará la notificación de acuerdo a su modalidad de tutoría.
    switch($modalidad){
        case 'En Línea':
            $carpeta = 'en_linea';
        break;
        
        case 'Línea 2a Fase':
            $carpeta = 'en_linea_2a_fase';
        break;

        case 'Presencial':
            $carpeta = 'presencial';
        break;

        case 'Zonas Rurales':
            $carpeta = 'zonas_rurales';
        break;
    }
    $pdf->Output('F', 'notificaciones_tutoria/tutorados/'.$carpeta.'/'.$curp_tutorado.'.pdf');

    // Termina documento PDF.

    // Actualiza registro en la base de datos.
    $consulta1 = "UPDATE tutoria_asignaciones SET tutorado_notificacion_generada = 1 WHERE tutorado_curp = '$curp_tutorado'";
    mysqli_query($dbase, $consulta1) or die ("Error al actualizar el estatus de la notificación generada al tutorado.");
}

?>
<script type="text/javascript">
    alert('Las notificaciones de los tutores se crearon correctamente.');
    window.location.href = "notificacion_y_correos_tutoria.php";
</script>