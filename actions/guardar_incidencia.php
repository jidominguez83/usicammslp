<?php
require_once '../includes/conexion.php'; // Conexión a la base de datos.
require_once '../includes/funciones.php'; // Carga archivo de funciones.

if(isset($_POST)){
	$curp                 = isset($_POST['curp_buscada']) ? trim($_POST['curp_buscada']) : false;
	$nivel_educativo      = isset($_POST['nivel_educativo']) ? $_POST['nivel_educativo'] : false;
	$proceso_participa    = isset($_POST['proceso']) ? $_POST['proceso'] : false;
	$selproceso           = isset($_POST['selproceso']) ? $_POST['selproceso'] : false;
	$descripcion          = isset($_POST['descripcion']) ? $_POST['descripcion'] : false;
	$trae_documento       = isset($_POST['trae_documento']) ? $_POST['trae_documento'] : false;
	$documento_incidencia = isset($_FILES['documento_incidencia']) ? $_FILES['documento_incidencia'] : false;
	$requiere_respuesta   = isset($_POST['requiere_respuesta']) ? $_POST['requiere_respuesta'] : false;
}
$PageName = '../agregar_incidencia.php';

if($proceso_participa == 'otro'){
	$proceso_participa = $selproceso;
}

$errores = array();

// Valida la CURP.
if(!empty($curp)){
	$curp_validada   = true;
} else {
	$curp_validada   = false;
	$errores['curp'] = 'La CURP no es válida.';
}

// Valida que el nivel educativo no esté vacío.
if(!empty($nivel_educativo)){
	$nivel_educativo_validado = true;
} else {
	$nivel_educativo_validado   = false;
	$errores['nivel_educativo'] = 'El nivel educativo está vacío.';
}

// Valida que el proceso donde participa el aspirante no esté vacío.
if(!empty($proceso_participa)){
	$proceso_participa_validado = true;
} else {
	$proceso_participa_validado   = false;
	$errores['proceso_participa'] = 'Seleccione alguno de los procesos donde ha participado y a donde corresponde la incidencia.';
}

// Valida el asunto (incidencia).
if(!empty($descripcion) && is_string($descripcion)){
	$descripcion_validada   = true;
} else {
	$descripcion_validada   = false;
	$errores['descripcion'] = 'Capture la descripción de la incidencia.';
}

// Valida que el archivo que se subirá al servidor exista y que sea en el formato correcto (PDF).
if(!empty($trae_documento)){
	echo "estoy aqui";
	if(isset($_FILES['documento_incidencia']) || !empty($_FILES['documento_incidencia'])){
		$documento = $_FILES['documento_incidencia'];
		$tipo      = $documento['type'];

		if($tipo == 'application/pdf'){
			$subir_archivo = true;
		}else{
			$errores['documento_incidencia'] = 'El archivo que intenta subir no está en formato PDF.';
		}
	} else {
		$subir_archivo = false;
	}
} else {
	$subir_archivo = false;
}

// Valida que la opción "Requiere respuesta" no esté seleccionada si la opción "Trae documento está vacía".
if(empty($trae_documento) && !empty($requiere_respuesta)){
	$requiere_respuesta_validado   = false;
	$errores['requiere_respuesta'] = 'Para seleccionar la opción "Requiere respuesta" es necesario seleccionar también la opción "Trae documento".';
} else {
	$requiere_respuesta_validado = false;
}

if(count($errores) == 0){
	// Guarda la información principal del la incidencia.
	$consulta  = "INSERT INTO incidencia (id_proceso_participa, id_nivel_educativo, curp, descripcion, remitido_a, trae_documento, estatus) VALUES ('$proceso_participa', '$nivel_educativo', '$curp', '$descripcion', '$se_remite_a', '$trae_documento', 'EN PROCESO')";
	mysqli_query($dbase, $consulta) or die ("Error al guardar la incidencia.");

	// Obtiene el último folio correspondiente a la CURP.
	$consulta1 = "SELECT MAX(folio) AS folio_incidencia FROM incidencia WHERE curp = '$curp'";
	$sql       = mysqli_query($dbase, $consulta1) or die ("Error al obtener el folio y fecha de la incidencia.");
	if($row = mysqli_fetch_row($sql)){
		$folio = $row[0];
	}

	// Guarda la información del seguimiento de la incidencia.
	$consulta2 = "INSERT INTO seguimiento_incidencia (folio_incidencia, observacion, fecha) VALUES ('$folio', 'Se recibió solicitud.', CURDATE())";
	mysqli_query($dbase, $consulta2) or die ("Error al guardar el seguimiento de la incidencia.");

	// Obtiene el id y la fecha del seguimiento de la incidencia.
	$consulta3 = "SELECT MAX(id) AS id_seguimiento, fecha FROM seguimiento_incidencia WHERE folio_incidencia = '$folio'"; 
	$sql3           = mysqli_query($dbase, $consulta3) or die ("Error al obtener el folio y fecha de la incidencia.");
	if($row3 = mysqli_fetch_row($sql3)){
		$id_seguimiento = $row3[0];
		$fecha          = $row3[1];
	}

	if($subir_archivo == true){
		if(!is_dir('documentos_incidencias')){ // Comprueba si la carpeta "documentos_incidencias" existe.
			mkdir('documentos_incidencias', 0777); // Si no existe la carpeta la crea.
		}
		$url = $curp.'-'.$folio.'-'.$id_seguimiento.'-'.$fecha.'.pdf';
		move_uploaded_file($documento['tmp_name'], '../documentos_incidencias/'.$url);

		$consulta4 = "INSERT INTO documento_incidencia (id_seguimiento, url, requiere_respuesta, fecha)VALUES ('$id_seguimiento', '$url', '$requiere_respuesta', CURDATE())";
		mysqli_query($dbase, $consulta4) or die ("Error al guardar el documento de la incidencia.");
	}
	pagina($PageName."?error=NO");
} else {
	$_SESSION['errores'] = $errores;
	pagina($PageName."?curp=".$curp);
}	
?>