<?php
require_once 'includes/header.php';  // Carga el encabezado de la página.

if(isset($_GET['buscar_curp'])){
	$curp     = $_GET['buscar_curp'];
	$consulta = "SELECT pp.*, ra.*, CONCAT(dp.nombre, ' ', dp.apellido_paterno, ' ', dp.apellido_materno) AS nombre_completo, p.nombre_proceso, te.nombre_evaluacion, te.funcion
	FROM proceso_participa pp
    LEFT JOIN resultados_admision ra ON pp.id = ra.id_proceso_participa
	INNER JOIN datos_personales dp ON pp.curp = dp.curp 
	LEFT JOIN proceso p ON pp.id_proceso = p.id 
	LEFT JOIN tipo_evaluacion te ON pp.id_tipo_evaluacion = te.id
    WHERE pp.id_proceso = 1 AND pp.ciclo = '2020-2021' AND pp.curp = '$curp'";

    $nquery = mysqli_query($dbase, $consulta);
    $num_rows = mysqli_num_rows($nquery);
} else if(isset($_GET['buscar_tipo_eval'])){
    $buscar_tipo_eval = $_GET['buscar_tipo_eval'];

	$query_pagination = "SELECT COUNT(pp.id)
	FROM proceso_participa pp
    LEFT JOIN resultados_admision ra ON pp.id = ra.id_proceso_participa
	INNER JOIN datos_personales dp ON pp.curp = dp.curp 
	LEFT JOIN proceso p ON pp.id_proceso = p.id 
	LEFT JOIN tipo_evaluacion te ON pp.id_tipo_evaluacion = te.id
    WHERE pp.id_proceso = 1 AND pp.ciclo = '2020-2021' AND pp.id_tipo_evaluacion = '$buscar_tipo_eval'";

	$limits_query = "SELECT pp.*, ra.*, CONCAT(dp.nombre, ' ', dp.apellido_paterno, ' ', dp.apellido_materno) AS nombre_completo, p.nombre_proceso, te.nombre_evaluacion, te.funcion
	FROM proceso_participa pp
    LEFT JOIN resultados_admision ra ON pp.id = ra.id_proceso_participa
	INNER JOIN datos_personales dp ON pp.curp = dp.curp 
	LEFT JOIN proceso p ON pp.id_proceso = p.id 
	LEFT JOIN tipo_evaluacion te ON pp.id_tipo_evaluacion = te.id
WHERE pp.id_proceso = 1 AND pp.ciclo = '2020-2021' AND pp.id_tipo_evaluacion = '$buscar_tipo_eval' ORDER BY ra.lista_o_grupo, ra.orden_prelacion";
    
    require_once 'includes/pagination.php'; // Carga las funciones para paginar los resultados.
    
    $num_rows = mysqli_num_rows($nquery);
} else {
    $num_rows = 0;
}


?>

<center><h2>LISTADO DE ASPIRANTES DE ADMISIÓN</h2></center>
<!-- BEGIN Grid listado aspirantes -->
<form id="form1" method="get" name="form1" action="listado_admision.php">
<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center" class="MainTable">
    <tr>
		<td valign="top">
			<table class="Header" border="0" cellspacing="0" cellpadding="0" width="800" align="center">
				<tr>
					<td class="HeaderLeft"><img border="0" alt="" src="css/images/Spacer.gif"></td> 
					<td class="th">Búsqueda por CURP&nbsp;&nbsp;
					<input type="text" name="buscar_curp" id="buscar_curp" minlength="18" maxlength="18" value="<?php if(isset($_GET['buscar_curp'])) { echo $_GET['buscar_curp']; } ?>" placeholder="Escribe una CURP válida">
                    &nbsp;ó&nbsp;Tipo de evaluación&nbsp;
                    <select id="buscar_tipo_eval" name="buscar_tipo_eval">
                        <option value="0">-- Selecciona un tipo de evaluación --</option>
                        <?php
                        $consulta_eva = "SELECT * FROM tipo_evaluacion WHERE funcion = 'Docente' OR funcion = 'Técnico docente' ORDER BY nombre_evaluacion";
                        $query = mysqli_query($dbase, $consulta_eva);
                        while($tipo_eval = mysqli_fetch_array($query)){
                        ?>
                        <option value="<?= $tipo_eval['id'] ?>" <?php if(isset($_GET['buscar_tipo_eval'])){ if($_GET['buscar_tipo_eval'] == $tipo_eval['id']){ echo "selected='selected'"; }}?>><?= $tipo_eval['funcion'].'. '.$tipo_eval['nombre_evaluacion'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    &nbsp;&nbsp;<input id="buscar" value="L" class="button-icon" alt="Buscar" type="submit" name="buscar">
                    </td> 
					<td class="HeaderRight"><img border="0" alt="" src="css/images/Spacer.gif"></td>
				</tr>
			</table>
</form> 
<?php
if($num_rows > 0):
?>
    <table class="Record" cellspacing="0" cellpadding="0" width="90%">
    <tr class="Caption"> 
        <th scope="col" width="8%">Folio</th> 
        <th scope="col" width="10%">CURP</th> 				  
        <th scope="col" width="20%">Nombre</th> 
        <th scope="col" width="6%">Ciclo</th> 
        <th scope="col" width="37%">Tipo de valoración/evaluación</th>           
        <th scope="col" width="8%">Puntaje global</th>
        <th scope="col" width="3%">No.<br>Prel.</th>                           
        <th scope="col" width="3%">Lista<br>/Grupo</th>
        <th scope="col" width="5%">Ver detalle</th>
    </tr>
<?php    
	while($admision = mysqli_fetch_array($nquery)):
?>     
				<tr class="Controls">
					<td><?= $admision['folio_federal'] ?></td> 
					<td><?= $admision['curp'] ?></td>  
					<td><?= $admision['nombre_completo'] ?></td>
					<td><?= $admision['ciclo'] ?></td>
					<td><?= $admision['funcion'].". ".$admision['nombre_evaluacion'] ?></td>
					<td><?= $admision['p_global'] ?></td>   
                    <td><?= $admision['orden_prelacion'] ?></td>        
					<td><?= $admision['lista_o_grupo'] ?></td>      
					<td><center><a href="detalle_aspirante_admision.php?id=<?= $admision['id_proceso_participa'] ?>"><img src="css/images/ver.png" width="60%" alt="Ver detalle" title="Da click para ver el detalle del aspirante"></a></center></td>            
				</tr>
<?php
	endwhile;
else:
            if(isset($_GET['buscar_curp']) || isset($_GET['buscar_tipo_eval'])):
?>
				<tr class="Controls">
					<td class="NoRecords" colspan="8"><center><b>NO HAY REGISTROS</b></center></td> 
				</tr>
<?php
            endif;
endif;
            if(isset($_GET['buscar_curp']) || isset($_GET['buscar_tipo_eval'])):
?>
				<tr class="Bottom">
					<td colspan="25">&nbsp;
                    <?php 
                    if(isset($_GET['buscar_tipo_eval'])) {
                        echo $paginationCtrls;
                    } 
                    ?> </td>
				</tr>
<?php
            endif;
?>                
            </table>
        </td>
    </tr>
</table>
</body>
</html>
<?php require_once 'includes/footer.php'; ?>