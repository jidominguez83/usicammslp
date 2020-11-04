<!-- BEGIN Record proceso_participa -->
<?php 
require_once 'includes/header.php'; // Carga el encabezado de la página.

if(!empty($_GET['curp'])):
  $curp     = $_GET['curp'];
  $consulta = "SELECT CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo FROM datos_personales WHERE curp = '$curp'";
  $sql      = mysqli_query($dbase, $consulta);
  $num_rows = mysqli_num_rows($sql);
  if($num_rows < 1){
    echo "<script> alert('No se encuentra la CURP'); </script>";
  }
endif;

if(isset($_GET['error'])):
  if($_GET['error'] == 'NO') echo "<script> alert('La incidencia se guardó correctamente.'); </script>";
endif;

if(isset($_SESSION['errores'])):
  $errores = $_SESSION['errores'];
endif;
?>
  <center><h2>AGREGAR INCIDENCIA</h2></center>
  <table class="MainTable" cellspacing="0" cellpadding="0" border="0" align="center" width="40%">
    <tr>
      <td valign="top">
      <!-- Inicio formulario de búsqueda de CURP -->
      <form action="agregar_incidencia.php" method="get" name="formbuscar">
        <table class="Header" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="HeaderLeft"><img alt="" src="css/images/Spacer.gif"></td> 
            <td class="th">
            CURP
            <input type="text" id="curp" minlength="18" maxlength="18" size="18" value="<?php if(isset($curp)) { echo $curp; } ?>" name="curp" placeholder="Escribe una CURP válida" required="required">
            <input name="buscar" type="submit" value="L" class="button-icon">
            <?php 
            if(isset($_SESSION['errores'])):
              echo mostrarErrores($errores, 'curp');
            endif; 
            ?>
            </td> 
            <td class="HeaderRight"><img alt="" src="css/images/Spacer.gif"></td> 
          </tr>
        </table>
	    </form>
      <!-- Fin formulario de búsqueda de CURP -->

      <!-- Inicio formulario para agregar incidencia -->     
      <form id="proceso_participa" method="post" name="formagregar" action="actions/guardar_incidencia.php" enctype="multipart/form-data">
        <table class="Record" cellspacing="0" cellpadding="0">
          <tr class="Controls">
            <td class="th"><label for="nombre">Nombre</label></td> 
            <td>
            <?php
            if(!empty($_REQUEST['curp'])):
              while($row = mysqli_fetch_array($sql)):
                $nombre_completo = $row['nombre_completo'];
              endwhile;
            endif;
            if(isset($nombre_completo)){ echo "<b>".$nombre_completo."<b>"; }
			      ?>
            </td> 
          </tr>

          <tr class="Controls">
            <td class="th"><label for="proceso_participatipo_evaluacion">Seleccione el proceso</label></td> 
            <td>
            <?php 
            $consulta2 = "SELECT * FROM proceso";
            $sql2      = mysqli_query($dbase, $consulta2);
            
            if(isset($curp) && !empty($curp)):
              $consulta4 = "SELECT proceso_participa.id, proceso_participa.id_proceso, proceso.nombre_proceso, proceso.abrev_proceso, proceso_participa.ciclo, tipo_evaluacion.nombre_evaluacion, tipo_evaluacion.id_nivel_educativo, proceso_participa.gpo_desemp, proceso_participa.pos_prelac, len.nombre_lengua FROM proceso_participa INNER JOIN proceso ON proceso_participa.id_proceso = proceso.id INNER JOIN tipo_evaluacion ON proceso_participa.id_tipo_evaluacion = tipo_evaluacion.id LEFT JOIN lengua len ON proceso_participa.id_lengua = len.id WHERE proceso_participa.curp = '$curp' ORDER BY proceso_participa.ciclo ASC";

              $sql4 = mysqli_query($dbase, $consulta4);
              $num4 = mysqli_num_rows($sql4);

              while($row4 = mysqli_fetch_array($sql4)):
                $id_proceso_aspirante      = $row4['id_proceso'];
                $abrev_proceso_aspirante[] = $row4['abrev_proceso'];
            ?>
          
            <input type="radio" id="id_proceso_<?= $row4['id'] ?>" name="proceso" value="<?= $row4['id'].'-'.$row4['id_nivel_educativo'] ?>" required="required" class="sel_proceso_participa">
            <label for="id_proceso"><?= $row4['nombre_proceso']." ".$row4['ciclo']."<br>Tipo de evaluación: ".$row4['nombre_evaluacion'] ?><?php if(!empty($row4['nombre_lengua'])){ echo '. '.$row4['nombre_lengua']; } ?></label><br><br> 
            <?php      
              endwhile;
            endif; 
            if(isset($num4)): 
              if($num4 > 0):
                echo '<input type="radio" id="id_proceso_otro" name="proceso" value="otro" class="sel_proceso_participa"><label for="id_proceso">Otro</label>&nbsp;&nbsp;';
              endif;
            endif;
			    ?>
          <br>    
          <select name="selproceso" id="proceso">
            <option value="0">-Selecciona un proceso-</option>
            <?php
			      while($row2 = mysqli_fetch_array($sql2)):
				  	  $abrev_proceso  = $row2['abrev_proceso'];				
              $nombre_proceso = $row2['nombre_proceso'];
              
              $existe_proceso = false;
              for($i = 0; $i < count($abrev_proceso_aspirante); $i++):
                if($abrev_proceso == $abrev_proceso_aspirante[$i]):
                  $existe_proceso = true;
                endif;                 
              endfor;
              if($existe_proceso == false):
                echo "<option value='$abrev_proceso'>$nombre_proceso</option>";
              endif;
			      endwhile;
			      ?>
          </select>

          <tr class="Controls">
            <td class="th"><label for="nivel_educativo">Nivel educativo</label></td>
            <td>
            <div id="div_nivel_educativo">
              <?php 
              require_once 'includes/carga_niveles.php';
              ?>
              <select name="nivel_educativo" id="nivel_educativo" required="required">
              </select>
              <?php 
              if(isset($_SESSION['errores'])):
                echo mostrarErrores($errores, 'nivel_educativo');
              endif; 
              ?>
            </div>
            </td>
          </tr>

          <?php 
            if(isset($_SESSION['errores'])):
              echo mostrarErrores($errores, 'nombre_proceso');
            endif; 
            ?>
          </tr> 

          <tr class="Controls">
            <td class="th">
            <label for="proceso_participafolio_federal">Asunto / Incidencia</label></td> 
            <td>
            <label for="asunto"></label>
            <textarea name="descripcion" cols="50" rows="10" id="descripcion" placeholder="Describe la incidencia" required="required"></textarea>
            <?php 
            if(isset($_SESSION['errores'])):
              echo mostrarErrores($errores, 'descripcion');
            endif; 
            ?>
            </td> 
          </tr>

          <tr class="Controls">
            <td class="th" colspan="2">
            <input name="trae_documento" id="trae_documento" type="checkbox" value="1"><label for="trae_documento">Trae documento</label>
            <div id="div_documento" id="div_documento">
              <br>Sólo archivos en formato PDF
              <input type="file" name="documento_incidencia">
              <br><br>
              <input name="requiere_respuesta" type="checkbox" value="1"><input name="curp_buscada" type="hidden" value="<?php if(isset($_GET['curp']))echo $_GET['curp']; ?>"><label for="requiere_respuesta">Requiere respuesta</label>
              <?php 
              if(isset($_SESSION['errores'])):
                echo mostrarErrores($errores, 'requiere_respuesta');
                echo '<br>'.mostrarErrores($errores, 'documento_incidencia');
              endif; 
              ?>
            </div>
            </td> 
          </tr>

          <tr class="Bottom">
            <td style="TEXT-ALIGN: right" colspan="2">
            <input name="guardar" type="submit" value="Guardar" class="Button">
            </td> 
          </tr>
        </table>
        </form>
        <!-- Fin de formulario para agregar incidencia --> 
      </td> 
    </tr>
  </table>
<!-- END Record proceso_participa --><br>
<?php
borrarErrores();
require_once 'includes/footer.php'; // Carga el pie de página.
?> 