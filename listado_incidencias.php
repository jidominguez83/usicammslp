<?php
require_once 'includes/header.php';  // Carga el encabezado de la página.

if(isset($_GET['buscar_curp'])){
	$curp     = $_GET['buscar_curp'];
	$consulta = "SELECT i.folio, i.curp, i.estatus, i.id_proceso_participa, CONCAT(dp.nombre, ' ', dp.apellido_paterno, ' ', dp.apellido_materno) AS nombre_completo, p.nombre_proceso, pp.ciclo,  te.nombre_evaluacion, te.funcion
	FROM incidencia i 
	LEFT JOIN proceso_participa pp ON i.id_proceso_participa = pp.id 
	INNER JOIN datos_personales dp ON i.curp = dp.curp 
	LEFT JOIN proceso p ON pp.id_proceso = p.id 
	LEFT JOIN tipo_evaluacion te ON pp.id_tipo_evaluacion = te.id WHERE i.curp = '$curp' ORDER BY folio";

	$nquery = mysqli_query($dbase, $consulta);
} else {
	$query_pagination = "SELECT COUNT(i.folio)
	FROM incidencia i 
	LEFT JOIN proceso_participa pp ON i.id_proceso_participa = pp.id 
	INNER JOIN datos_personales dp ON i.curp = dp.curp 
	LEFT JOIN proceso p ON pp.id_proceso = p.id
	LEFT JOIN tipo_evaluacion te ON pp.id_tipo_evaluacion = te.id";

	$limits_query = "SELECT i.folio, i.curp, i.estatus, i.id_proceso_participa, CONCAT(dp.nombre, ' ', dp.apellido_paterno, ' ', dp.apellido_materno) AS nombre_completo, p.nombre_proceso, pp.ciclo, te.nombre_evaluacion, te.funcion
	FROM incidencia i 
	LEFT JOIN proceso_participa pp ON i.id_proceso_participa = pp.id 
	INNER JOIN datos_personales dp ON i.curp = dp.curp 
	LEFT JOIN proceso p ON pp.id_proceso = p.id
	LEFT JOIN tipo_evaluacion te ON pp.id_tipo_evaluacion = te.id ORDER BY folio";

	require_once 'includes/pagination.php'; // Carga las funciones para paginar los resultados.
}

$num_rows = mysqli_num_rows($nquery);
?>
<center><h2>LISTADO DE INCIDENCIAS REGISTRADAS</h2></center>
<!-- BEGIN Grid modelo -->
<form id="form1" method="get" name="form1" action="listado_incidencias.php">
<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center" class="MainTable">
    <tr>
		<td valign="top">
			<table class="Header" border="0" cellspacing="0" cellpadding="0" width="800" align="center">
				<tr>
					<td class="HeaderLeft"><img border="0" alt="" src="css/images/Spacer.gif"></td> 
					<td class="th">Buscar por CURP&nbsp;&nbsp;
					<input type="text" name="buscar_curp" id="buscar_curp" minlength="18" maxlength="18" value="<?php if(isset($_GET['buscar_curp'])) { echo $_GET['buscar_curp']; } ?>" required="required" placeholder="Escribe una CURP válida">&nbsp;&nbsp;<input id="buscar" value="L" class="button-icon" alt="Buscar" type="submit" name="buscar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="listado_incidencias.php" title="Da click para mostrar todos los registros">Mostrar todos</a></td> 
					<td class="HeaderRight"><img border="0" alt="" src="css/images/Spacer.gif"></td>
				</tr>
			</table>
</form> 
            <table class="Record" cellspacing="0" cellpadding="0" width="90%">
				<tr class="Caption"> 
					<th scope="col" width="3%">Folio</th> 
					<th scope="col" width="10%">CURP</th> 				  
					<th scope="col" width="20%">Nombre</th> 
					<th scope="col" width="9%">Proceso</th> 
					<th scope="col" width="6%">Ciclo</th>           
					<th scope="col" width="40%">Tipo de valoración/evaluación</th>        
					<th scope="col" width="7%">Estatus</th>
					<th scope="col" width="5%">Ver detalle</th>
				</tr>
<?php
if($num_rows > 0):
	while($row = mysqli_fetch_array($nquery)):
?>                     
				<tr class="Controls">
					<td><?= $row['folio'] ?></td> 
					<td><?= $row['curp'] ?></td>  
					<td><?= $row['nombre_completo'] ?></td>
					<td>
					<?php 
					if($row['nombre_proceso'] != null) { 
						echo $row['nombre_proceso']; 
					} else { 
						switch($row['id_proceso_participa']){
							case 'ING':
								echo "Admisión (Ingreso)";
							break;
							case 'PRV':
								echo "Promoción vertical";		
							break;
							case 'PRH':
								echo "Promoción horizontal";
							break;
							case 'HAD':
								echo "Horas adicionales";	
							break;
							case 'K1':
								echo "K1";	
							break;
							case 'DIG':
								echo "Evaluación Diagnóstica";
							break;
							case 'DES':
								echo "Evaluación del Desempeño (Permanencia)";	
							break;
							case 'TUT':
								echo "Tutoría";
							break;								
						} 
					} 
					?>
					</td>
					<td><?= $row['ciclo'] ?></td>
					<td><?php if($row['nombre_evaluacion'] != null) { echo $row['funcion'].". ".$row['nombre_evaluacion']; } else { echo "No aplica."; } ?></td>          
					<td><span class="<?php if($row['estatus'] == 'EN PROCESO'){ echo 'green'; } else { echo 'red'; } ?>"><?= $row['estatus'] ?></span></td>      
					<td><center><a href="detalle_incidencia.php?folio=<?= $row['folio'] ?>"><img src="css/images/ver.png" width="60%" alt="Ver detalle" title="Da click para ver el detalle de la incidencia"></a></center></td>            
				</tr>
<?php
	endwhile;
else:
?>
				<tr class="Controls">
					<td class="NoRecords" colspan="8"><center><b>NO HAY REGISTROS</b></center></td> 
				</tr>   
<?php
endif;
?>
				<tr class="Bottom">
					<td colspan="25">&nbsp;<?php if(!isset($_GET['buscar_curp'])) echo $paginationCtrls; ?> </td>
				</tr>
        	</table>
      	</td>
    </tr>
</table>
</body>
</html>
<?php require_once 'includes/footer.php'; ?>