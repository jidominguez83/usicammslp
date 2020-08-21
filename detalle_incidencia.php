<?php
require_once 'includes/header.php'; // Carga el encabezado de la página.
?>

<script language="JavaScript">
function comfirmaCerrarCaso(){
  var isChecked = document.getElementById('cerrar_caso').checked;
  if(isChecked){
    if(confirm('Estas a punto de dar por cerrado el caso, ¿Deseas continuar?')){
      document.detalle_incidencia.submit()
    }
  }
}
</script> 

<?php
$folio = $_GET['folio'];

$usuario = 16;

if(isset($_GET['error'])):
  if($_GET['error'] == 'NO') echo "<script> alert('La incidencia se guardó correctamente.'); </script>";
endif;

if(isset($_SESSION['errores'])):
  $errores = $_SESSION['errores'];
endif;

$consulta = "SELECT i.*, CONCAT(dp.nombre, ' ', dp.apellido_paterno, ' ', dp.apellido_materno) AS nombre_completo FROM incidencia i INNER JOIN datos_personales dp ON i.curp = dp.curp LEFT JOIN proceso_participa pp ON i.id_proceso_participa = pp.id INNER JOIN proceso p ON pp.id_proceso = p.id WHERE folio = $folio";
$sql      = mysqli_query($dbase, $consulta);
?>

  <table class="MainTable" cellspacing="0" cellpadding="0" border="0" align="center" width="50%">
    <tr>
      <td valign="top">
        <table class="Header" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="HeaderLeft"><img alt="" src="css/images/Spacer.gif"></td> 
            <td class="th">
            Detalle de la incidencia
            </td> 
            <td class="HeaderRight"><img alt="" src="css/images/Spacer.gif"></td> 
          </tr>
        </table>

      <!-- Inicio formulario para mostrar y actualizar datos del detalle de la incidencia -->     
      <form id="detalle_incidencia" method="post" name="detalle_incidencia" action="actions/guardar_detalle_incidencia.php">
      <?php
      while($incidencia = mysqli_fetch_array($sql)):
      ?>
        <table class="Record" cellspacing="0" cellpadding="0">
          <tr class="Controls">
            <td class="th"><h1>Folio</h1></td> 
            <td><h1><?= $incidencia['folio'] ?></h1></td> 
          </tr>

          <tr class="Controls">
            <td class="th"><label for="curp">CURP</label></td> 
            <td>
            <b><?= $incidencia['curp'] ?></b>
            </td> 
          </tr>

          <tr class="Controls">
            <td class="th"><label for="nombre">Nombre</label></td> 
            <td>
            <b><?= $incidencia['nombre_completo'] ?></b>
            </td> 
          </tr>   

          <tr class="Controls">
            <td class="th"><label for="nivel_educativo">Nivel educativo</label></td> 
            <td><?= $incidencia['id_nivel_educativo'] ?></td> 
          </tr>

          <tr class="Controls">
            <td class="th"><label for="tipo_evaluacion">Tipo de valoración/evaluación</label></td> 
            <td>
              
          <?php 

            ?>
          </tr>

          <tr class="Controls">
            <td class="th"><label for="descripcion">Descripción del asunto/incidencia</label></td> 
            <td><h2><?= $incidencia['descripcion'] ?></h2></td> 
          </tr>

          <tr class="Controls">
            <td class="th"><label for="actualizaciones">Actualizaciones del caso</label></td> 
            <td>
              <?php
              $consulta_actualizaciones = "SELECT observacion, si.fecha, url, requiere_respuesta FROM seguimiento_incidencia si LEFT JOIN documento_incidencia di ON si.id = di.id_seguimiento WHERE folio_incidencia = '$folio'";
              $sql_actualizaciones = mysqli_query($dbase, $consulta_actualizaciones);
              while($actualizaciones = mysqli_fetch_array($sql_actualizaciones)):
                $fecha              = $actualizaciones['fecha'];
                $observacion        = $actualizaciones['observacion'];
                $documento          = $actualizaciones['url'];
                $requiere_respuesta = $actualizaciones['requiere_respuesta'];
              ?>
                Fecha: <b><?= date("d/m/Y", strtotime($fecha)) ?></b>
                <?php if($documento){ echo " | <a href='documentos_incidencias/".$documento."' target='_blank' title='Da click para ver el documento'>Ver documento</a>"; } ?>
                <?php if($requiere_respuesta == 1){ echo " | <span class='red'><img src='css/images/alert.png' width='15px'> Requiere respuesta</span>"; } ?>
                <br>
                <?php if($observacion){ echo '<h3>'.$observacion.'</h3>'; } else { echo 'Sin observaciones'; } ?>
                <hr>
              <?php
              endwhile;
              ?>
            </td> 
          </tr>

          <?php
          if($incidencia['estatus'] == 'EN PROCESO'):
          ?>
          <tr class="Controls">
            <td class="th"><label for="observacion">Observación (atención del caso)</label></td> 
            <td>
              <label for="observacion"></label>
              <textarea name="observacion" cols="70" rows="10" id="observacion" required="required"></textarea>
              <?php 
              //var_dump($_SESSION['errores']);
              if(isset($_SESSION['errores'])):
                echo mostrarErrores($errores, 'observacion');
              endif; 
              ?>
            </td> 
          </tr>
          <?php
          endif;
          ?>

          <tr class="Controls">
            <td class="th" colspan="2">
            <?php if($incidencia['estatus'] == 'EN PROCESO'): ?>
              <input name="cerrar_caso" id="cerrar_caso" type="checkbox" value="CERRADO"><label for="cerrar_caso">Cerrar caso</label>
              <input name="atendido_por" type="hidden" value="<?= $usuario ?>">
              <input name="folio" id="folio" type="hidden" value="<?= $folio ?>">
            <?php else: ?>
              <span class="red">El caso se ha cerrado</span>
              <?php endif; ?>
            </td> 
          </tr>

          <tr class="Bottom">
            <td style="TEXT-ALIGN: right" colspan="2">
            <?php if($incidencia['estatus'] == 'EN PROCESO'): ?>
              <input name="guardar" type="submit" value="Guardar" class="Button" onclick="comfirmaCerrarCaso();">
            <?php endif; ?> 
            </td> 
          </tr>
        </table>
        <?php
        endwhile;
        ?>
        </form>
      <!-- Fin de formulario para mostrar y actualizar datos del detalle de la incidencia --> 
 	    </td> 
    </tr>
  </table>
<?php 
borrarErrores();
require_once 'includes/footer.php' 
?>