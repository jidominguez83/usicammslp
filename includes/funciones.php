<?php
// Función para redireccionar página.
function pagina($PageName){
	print "<html><body>";
	print "<script language=\"Javascript\">";
	print "window.location.href=\"$PageName\"";
	print " </script></body></html>";	
}

// Muestra errores resultantes de la validación al insertar en la BD.
function mostrarErrores($errores, $campo){
	$alerta = '';
	if(isset($errores[$campo]) && !empty($campo)){
		$alerta = "<div style='color: red; font-size: 10pt'>".$errores[$campo]."</div>";
	}
	return $alerta;
}

// Borra los errores resultantes de la validación al insertar en la BD.
function borrarErrores(){
	$borrado = false;
	
	if(isset($_SESSION['errores'])){
		$_SESSION['errores'] = null;
		$borrado = true;
	}
}
?>