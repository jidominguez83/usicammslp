<?php
require_once '../includes/conexion.php'; // Conexión a la base de datos.
require_once '../includes/funciones.php'; // Carga archivo de funciones.

if(isset($_POST)){
	$folio                = isset($_POST['folio']) ? $_POST['folio'] : false;
    $observacion          = isset($_POST['observacion']) ? $_POST['observacion'] : false;
    $atendido_por         = isset($_POST['atendido_por']) ? $_POST['atendido_por'] : false;
	$cerrar_caso          = isset($_POST['cerrar_caso']) ? $_POST['cerrar_caso'] : false;
}
$PageName = '../detalle_incidencia.php';

$errores = array();

// Valida que el folio no esté vacío.
if(!empty($folio)){
	$folio_validado = true;
} else {
	$folio_validado   = false;
	$errores['folio'] = 'El folio está vacío.';
}

// Valida que la observación no esté vacía.
if(!empty($observacion)){
	$observacion_validado = true;
} else {
	$observacion_validado   = false;
	$errores['observacion'] = 'La observacion está vacía.';
}

// Valida que la información del usuario que atendió el caso no esté vacía.
if(!empty($atendido_por)){
	$atendido_por_validado = true;
} else {
	$atendido_por_validado   = false;
	$errores['atendido_por'] = 'La información del usuario que atendió está vacía.';
}

if(count($errores) == 0){
	// Guarda la información del seguimiento de la incidencia.
	$consulta  = "INSERT INTO seguimiento_incidencia (folio_incidencia, observacion, atendido_por, fecha) VALUES ('$folio', '$observacion', '$atendido_por', CURDATE())";
    mysqli_query($dbase, $consulta) or die ("Error al guardar el seguimiento de la incidencia.");
    
    // Verifica si se actualiza el estatus de incidencia en caso de que el estatus de la variable $cerrar_caso sea igual a "CERRADO".
    if($cerrar_caso == 'CERRADO'){
        $consulta2 = "UPDATE incidencia SET estatus = '$cerrar_caso' WHERE folio = '$folio'";
        mysqli_query($dbase, $consulta2) or die ("Error al actualizar el estatus de la incidencia.");
	}
	pagina($PageName."?error=NO&folio=".$folio);
} else {
	$_SESSION['errores'] = $errores;
	pagina($PageName."?folio=".$folio);
}	 
?>