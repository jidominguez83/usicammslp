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

// Consulta para obtener tutores.
$consulta = "SELECT DISTINCT(tutor_curp), tutor_nombre, tutor_paterno, tutor_materno, tutor_modalidad_tutoria FROM tutoria_asignaciones WHERE tutor_notificacion_generada = 0";
$sql      = mysqli_query($dbase, $consulta);

while($row = mysqli_fetch_assoc($sql)){
    $curp      = $row['tutor_curp'];
    $nombre    = $row['tutor_nombre'].' '.$row['tutor_paterno'].' '.$row['tutor_materno'];
    $modalidad = $row['tutor_modalidad_tutoria'];

    // Consulta para obtener tutorados.
    $consulta2 = "SELECT CONCAT(tutorado_nombre, ' ', tutorado_paterno, ' ', tutorado_materno) AS nombre_completo_tutorado, tutorado_correo, inicio_tutoria, ciclo_escolar FROM tutoria_asignaciones WHERE tutor_curp = '$curp'";
    $sql2      = mysqli_query($dbase, $consulta2) or die ('No se pudo acceder a los tutorados.');

    // Inicia el cuerpo del documento PDF.
    $pdf    = new PDF();

    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(122,0,0);
    $pdf->Cell(0, 7, utf8_decode('TUTORÍA A DOCENTES Y TÉCNICOS DOCENTES DE NUEVO INGRESO EN EDUCACIÓN BÁSICA CICLO ESCOLAR 2020-2021'), 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(25,0,0);
    $pdf->Cell(0, 10, utf8_decode('Asunto: Notificación de asignación como Tutor (a) '.$nombre), 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);

    // Compara la modalidad de la tutoría (En línea, Línea 2a Fase, Presencial, Zonas Rurales) y hace el llenado correspondiente.
    /*********************************************************
    *    Valida si es modalidad en Línea ó Línea 2a Fase.    *
    *********************************************************/
    if($modalidad == 'En Línea' || $modalidad == 'Línea 2a Fase'){ 
        $pdf->MultiCell(0, 4, utf8_decode('Con fundamento en el artículo 77 de la Ley General del Sistema para la Carrera de las Maestras y los Maestros el personal de nuevo ingreso al servicio público educativo en la educación básica recibirá tutoría que lo apoye a mejorar su práctica profesional durante dos años, las Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como tutor en Educación Básica y la Convocatoria estatal para la selección de los Tutores, por este medio se le notifica que la Autoridad Educativa del estado lo asignó como Tutor en la modalidad en línea en apoyo de los siguientes Docentes y Técnicos Docentes noveles:'), 0, 'J');
        $pdf->Ln(5);
    
        // Inicia tabla con datos de los tutorados.
    
        // Carga de datos
        // Colores, ancho de línea y fuente en negrita.
        $pdf->SetFillColor(122,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B', 8);
        
        // Encabezado de la tabla.
        $pdf->Cell(60, 5, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(25, 5, utf8_decode('Inicio de la tutoría'), 1, 0, 'C', true);
        $pdf->Cell(80, 5, utf8_decode('Correo electrónico'), 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Ciclo escolar', 1, 0, 'C', true);
        $pdf->Ln();
    
        // Restauración de color y fuente.
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('','', 6);
        
        $fill = false; // Relleno de color de fila.
    
        // Llenado de filas de tabla.
        while($row2 = mysqli_fetch_assoc($sql2)){
            $pdf->Cell(60, 5, utf8_decode($row2['nombre_completo_tutorado']), 'LR', 0, 'L', $fill);
            $pdf->Cell(25, 5, date('d/m/Y',strtotime($row2['inicio_tutoria'])), 'LR', 0, 'C', $fill);
            $pdf->Cell(80, 5, trim($row2['tutorado_correo']), 'LR', 0, 'L', $fill);
            $pdf->Cell(25, 5, $row2['ciclo_escolar'], 'LR', 1, 'C', $fill);
            $fill = !$fill;
        }
        $pdf->Cell(190,0,'','T'); // Última línea de la tabla.
        $pdf->Ln(5);
        // Termina tabla con datos de los tutorados.
        $pdf->SetFont('Arial','BI',8);
        $pdf->Ln(3);
        $pdf->Cell(0, 5, 'Ingreso a la plataforma Moodle', 0, 1, 'L');
        $pdf->Ln(3);

        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('A partir de este momento, dará inicio el proceso de matriculación de su grupo en la plataforma Moodle correspondiente, lo que le permitirá a usted y a los Tutorados iniciar la comunicación y las actividades programadas en cada Módulo.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Para comenzar deberá ingresar a la siguiente liga http://143.137.111.86/moodle/ utilizando como usuario su Clave Única de Registro de Población (CURP), y los primeros diez caracteres como contraseña además, se recomienda realizar lo siguiente:'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'a)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Consultar el calendario de actividades de la tutoría en línea para el ciclo escolar 2020-2021 e ingresar a la plataforma con oportunidad al Módulo que corresponda.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'b)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Acceder a los recursos y materiales de apoyo disponibles en dicha plataforma a fin de enriquecer el trabajo con su grupo de Tutorados. Dichos recursos están disponibles en las siguientes direcciones:'), 0, 'J');
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
        $pdf->Cell(10, 4, 'c)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Establecer la comunicación con su grupo de Tutorados antes, durante y al término de cada módulo con la finalidad de llevar a cabo las actividades sugeridas y ofrecer atención a las inquietudes de los Docentes en función del contexto educativo al que pertenecen.'), 0, 'J');
        $pdf->Ln(3);

        $pdf->SetFont('Arial','BI',8);
        $pdf->Cell(0, 5, 'Ingreso a la plataforma Moodle', 0, 1, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial','',8);

        $bullet = chr(149);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El registro de las evidencias es responsabilidad del Tutor (a) en función.'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Antes de iniciar esta actividad deberá obtener el usuario y la contraseña a través del sitio VENUS ubicado en la siguiente liga http://proyecto-venus.cnspd.mx:8080/venus/'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('En caso de tener dificultad para recuperar su contraseña deberá contactar al responsable de tutoría en su entidad.'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('La plataforma se habilitará durante los últimos 3 días de cada mes y solo podrá registrar las evidencias que correspondan a este periodo por lo tanto el sistema no le permitirá ingresar información del trabajo realizado en meses anteriores.'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El calendario de la modalidad en la que participa estará disponible en el portal de tutoría.'), 0, 'L');
        $pdf->Ln(3);

        $pdf->SetTextColor(122,0,0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 8, utf8_decode('IMPORTANTE'), 0, 1, 'C');
        $pdf->Cell(0, 8, utf8_decode('La plataforma Moodle y la de registro de evidencias son independientes por lo tanto NO comparten usuario y contraseña.'), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetTextColor(25,0,0);
        $pdf->SetFont('Arial', 'BI', 8);
        $pdf->Cell(0, 8, 'Datos de contacto de la Autoridad Educativa en la entidad', 0, 1, 'J');
        $pdf->Ln(3);

        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 4, utf8_decode('Con la finalidad de dar atención a las dudas e inquietudes específicas respecto del desarrollo de la tutoría en línea se pone a su disposición los siguientes datos de contacto:'), 0, 'J');
    }

    /*******************************************
    *    Valida si es modalidad Presencial.    *
    *******************************************/
    if($modalidad == 'Presencial'){
        $pdf->MultiCell(0, 4, utf8_decode('Con fundamento en el artículo 77 de la Ley General del Sistema para la Carrera de las Maestras y los Maestros el personal de nuevo ingreso al servicio público educativo en la educación básica recibirá tutoría que lo apoye a mejorar su práctica profesional durante dos años, las Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como tutor en Educación Básica y la Convocatoria estatal para la selección de los Tutores, por este medio se le notifica que la Autoridad Educativa del estado lo asignó como Tutor en la modalidad presencial en apoyo de los siguientes Docentes y Técnicos Docentes noveles:'), 0, 'J');
        $pdf->Ln(10);
    
        // Inicia tabla con datos de los tutorados.
        // Encabezados de columnas
        $header = array('Nombre', 'Correo electrónico', 'Inicio de tutoría', 'Ciclo escolar');
    
        // Carga de datos
        // Colores, ancho de línea y fuente en negrita.
        $pdf->SetFillColor(122,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B', 8);
        
        // Encabezado de la tabla.
        $pdf->Cell(60, 5, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(25, 5, utf8_decode('Inicio de la tutoría'), 1, 0, 'C', true);
        $pdf->Cell(80, 5, utf8_decode('Correo electrónico'), 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Ciclo escolar', 1, 0, 'C', true);
        $pdf->Ln();
    
        // Restauración de color y fuente.
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('','', 6);
        
        $fill = false; // Relleno de color de fila.
    
        // Llenado de filas de tabla.
        while($row2 = mysqli_fetch_assoc($sql2)){
            $pdf->Cell(60, 5, utf8_decode($row2['nombre_completo_tutorado']), 'LR', 0, 'L', $fill);
            $pdf->Cell(25, 5, date('d/m/Y',strtotime($row2['inicio_tutoria'])), 'LR', 0, 'C', $fill);
            $pdf->Cell(80, 5, trim($row2['tutorado_correo']), 'LR', 0, 'L', $fill);
            $pdf->Cell(25, 5, $row2['ciclo_escolar'], 'LR', 1, 'C', $fill);
            $fill = !$fill;
        }
        $pdf->Cell(190,0,'','T'); // Última línea de la tabla.
        $pdf->Ln(10);
        // Termina tabla con datos de los tutorados.
        
        $pdf->SetFont('Arial','BI',8);
        $pdf->Ln(3);
        $pdf->Cell(0, 5, utf8_decode('Inicio de la tutoría'), 0, 1, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('A partir de este momento, usted podrá establecer comunicación con sus Tutorados y acordar la forma de trabajo para ofrecer la asesoría y el acompañamiento en este periodo de contingencia sanitaria tomando en cuenta las indicaciones que le proporcione su Autoridad Educativa en la entidad.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Con la finalidad de orientar el desarrollo de la tutoría presencial se sugiere utilizar el Manual para el Tutor de Docentes y Técnicos Docentes de nuevo ingreso. Educación Básica. Para acceder a este material y a otros recursos disponibles para el desempeño de esta función adicional deberá ingresar a las siguientes ligas:'), 0, 'J');
        $pdf->Ln(3);
        $pdf->SetTextColor(5,99,193);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Portal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'http://143.137.111.80/dgpromocion/tutoria/?page_id=37', 0, 1, 'L');
        $pdf->Cell(40, 5, utf8_decode('Canal de tutoría'), 0, 0, 'C');
        $pdf->Cell(140, 5, 'https://www.youtube.com/channel/UCOYkEIKZK8XOgXM_T3LGX5Q', 0, 1, 'L');
        $pdf->Ln(10);

        $pdf->SetTextColor(25,0,0);
        $pdf->SetFont('Arial','BI',8);
        $pdf->Cell(0, 5, 'Ingreso a la plataforma de registro de evidencias', 0, 1, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial','',8);
        
        $bullet = chr(149);
        $pdf->MultiCell(0, 4, utf8_decode('Es importante mencionar que al desempeñar la función de Tutor (a) deberá registrar las evidencias del trabajo que realice con cada uno de los Tutorados que le sean asignados por parte de la Autoridad Educativa de su entidad.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(0, 4, 'Al respecto, le sugerimos considerar lo siguiente:', 0, 1, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El registro de las evidencias es responsabilidad del Tutor (a) en función.'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Antes de iniciar esta actividad deberá obtener el usuario y la contraseña en el sitio VENUS ubicado en la siguiente liga http://proyecto-venus.cnspd.mx:8080/venus/'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('En caso de tener dificultades para recuperar su contraseña deberá contactar al responsable de tutoría en su entidad.'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('La plataforma se habilitará durante los últimos 3 días de cada mes y solo podrá registrar las evidencias que correspondan a este periodo, por lo tanto, la plataforma no le permitirá ingresar actividades que correspondan a meses anteriores. '), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El calendario estará disponible en el portal de tutoría.'), 0, 'L');
        $pdf->Ln(3);

        $pdf->AddPage();

        $pdf->SetFont('Arial', 'BI', 8);
        $pdf->Cell(0, 8, 'Datos de contacto de la Autoridad Educativa en la entidad', 0, 1, 'J');
        $pdf->Ln(3);

        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 4, utf8_decode('Con la finalidad de dar atención a las dudas e inquietudes específicas relacionadas con el desarrollo de la tutoría presencial se pone a su disposición los siguientes datos de contacto:'), 0, 'J');
    }
    
    /**********************************************
    *    Valida si es modalidad Zonas Rurales.    *
    **********************************************/
    if($modalidad == 'Zonas Rurales'){
        $pdf->MultiCell(0, 4, utf8_decode('Con fundamento en el artículo 77 de la Ley General del Sistema para la Carrera de las Maestras y los Maestros el personal de nuevo ingreso al servicio público educativo en la educación básica recibirá tutoría que lo apoye a mejorar su práctica profesional durante dos años, las Disposiciones para normar las funciones de tutoría y el proceso de selección del personal docente y técnico docente que se desempeñará como tutor en Educación Básica y la Convocatoria estatal para la selección de los Tutores, por este medio se le notifica que la Autoridad Educativa del estado lo asignó como Tutor en la modalidad de atención en zonas rurales en apoyo de los siguientes Docentes y Técnicos Docentes noveles:'), 0, 'J');
        $pdf->Ln(5);
    
        // Inicia tabla con datos de los tutorados.
        // Encabezados de columnas
        $header = array('Nombre', 'Correo electrónico', 'Inicio de tutoría', 'Ciclo escolar');
    
        // Carga de datos
        // Colores, ancho de línea y fuente en negrita.
        $pdf->SetFillColor(122,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B', 8);
        
        // Encabezado de la tabla.
        $pdf->Cell(60, 5, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(25, 5, utf8_decode('Inicio de la tutoría'), 1, 0, 'C', true);
        $pdf->Cell(80, 5, utf8_decode('Correo electrónico'), 1, 0, 'C', true);
        $pdf->Cell(25, 5, 'Ciclo escolar', 1, 0, 'C', true);
        $pdf->Ln();
    
        // Restauración de color y fuente.
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('','', 6);
        
        $fill = false; // Relleno de color de fila.
    
        // Llenado de filas de tabla.
        while($row2 = mysqli_fetch_assoc($sql2)){
            $pdf->Cell(60, 5, utf8_decode($row2['nombre_completo_tutorado']), 'LR', 0, 'L', $fill);
            $pdf->Cell(25, 5, date('d/m/Y',strtotime($row2['inicio_tutoria'])), 'LR', 0, 'C', $fill);
            $pdf->Cell(80, 5, trim($row2['tutorado_correo']), 'LR', 0, 'L', $fill);
            $pdf->Cell(25, 5, $row2['ciclo_escolar'], 'LR', 1, 'C', $fill);
            $fill = !$fill;
        }
        $pdf->Cell(190,0,'','T'); // Última línea de la tabla.
        $pdf->Ln(5);
        // Termina tabla con datos de los tutorados.
        
        $pdf->SetFont('Arial','BI',8);
        $pdf->Ln(3);
        $pdf->Cell(0, 5, utf8_decode('Inicio de la tutoría'), 0, 1, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0, 4, utf8_decode('A partir de este momento, usted podrá establecer comunicación con su grupo de Tutorados y acordar la forma de trabajo para ofrecer la asesoría y el acompañamiento en este periodo de contingencia sanitaria tomando en cuenta las indicaciones que le proporcione su Autoridad Educativa en la entidad.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 4, utf8_decode('Debido a las características de esta modalidad requiere conocer lo siguiente:'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'a)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Esta modalidad se basa en el diálogo entre Tutor y Docentes de nuevo ingreso y, se organiza en ocho Módulos. Durante el ciclo escolar 2020-2021 se desarrollarán solo cuatro.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'b)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Cada Módulo se conforma, a su vez, de dos encuentros, uno por mes. De esta manera en el ciclo escolar 2020-2021 el Tutor y Tutorados llevarán a cabo ocho encuentros.'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'c)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El Tutor podrá organizar y desarrollar las actividades con base en la Guía para Tutores. Tutoría en zonas rurales para docentes de nuevo ingreso Educación Básica (escuelas multigrado, generales e indígenas, y telesecundarias).'), 0, 'J');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, 'd)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El Tutor podrá acceder a recursos y materiales de apoyo adicionales para enriquecer las actividades de cada módulo a través del portal de la tutoría y el canal de YouTube ubicados en las siguientes ligas:'), 0, 'J');
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
        $pdf->Cell(10, 4, 'e)', 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('A partir del primer encuentro el Tutor deberá mantener comunicación con el grupo de Tutorados de manera continua con la finalidad de dar seguimiento a las inquietudes y necesidades propias del contexto educativo.'), 0, 'J');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','BI',8);
        $pdf->Cell(0, 5, 'Ingreso a la plataforma de registro de evidencias', 0, 1, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial','',8);

        $bullet = chr(149);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El registro de las evidencias es responsabilidad del Tutor (a) en función.'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('Antes de iniciar esta actividad deberá obtener el usuario y la contraseña en el sitio VENUS ubicado en la siguiente liga http://proyecto-venus.cnspd.mx:8080/venus/'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('En caso de tener dificultades para recuperar su contraseña deberá contactar al responsable de tutoría en su entidad.'), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('La plataforma se habilitará durante los últimos 3 días de cada mes y solo podrá registrar las evidencias que correspondan a este periodo, por lo tanto, la plataforma no le permitirá ingresar actividades que correspondan a meses anteriores. '), 0, 'L');
        $pdf->Ln(3);
        $pdf->Cell(10, 4, $bullet, 0, 0, 'C');
        $pdf->MultiCell(180, 4, utf8_decode('El calendario estará disponible en el portal de tutoría.'), 0, 'L');
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'BI', 8);
        $pdf->Cell(0, 8, 'Datos de contacto de la Autoridad Educativa en la entidad', 0, 1, 'J');
        $pdf->Ln(3);

        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 4, utf8_decode('Con la finalidad de dar atención a sus dudas e inquietudes derivadas del desempeño de su función en la modalidad de atención en zonas rurales se pone a su disposición los siguientes datos de contacto:'), 0, 'J');
    }

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
    $pdf->Output('F', 'notificaciones_tutoria/tutores/'.$carpeta.'/'.$curp.'.pdf');

    // Termina documento PDF.

    // Actualiza registro en la base de datos.
    $consulta1 = "UPDATE tutoria_asignaciones SET tutor_notificacion_generada = 1 WHERE tutor_curp = '$curp'";
    mysqli_query($dbase, $consulta1) or die ("Error al actualizar el estatus de la notificación generada al tutor.");
}

?>
<script type="text/javascript">
    alert('Las notificaciones de los tutores se crearon correctamente.');
    window.location.href = "notificacion_y_correos_tutoria.php";
</script>