<?php 
require_once 'includes/conexion.php'; // Conexión a la base de datos.
require_once 'includes/funciones.php'; // Llama al archivo de funciones del sistema.
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="content-type" charset="utf-8">
    <title>SEGE | Unidad Estatal del Sistema para la Carrera de las Maestras y los Maestros</title>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <link rel="icon" type="favicon/x-icon" href="css/images/gobslp_icon.png">
    <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/efectos.js"></script>
  </head>
<body>
  <!-- Inicio del encabezado -->
  <table width="100%">
    <tr>
      <td><img src="css/images/logo-CNSPD.png" alt="" width="300"align="left"></td>
      <td align="center"><h1>UNIDAD ESTATAL DEL SISTEMA PARA LA CARRERA<br>DE LAS MAESTRAS Y LOS MAESTROS</h1></td>
      <td><img src="css/images/logo-SEGE.png" alt="" width="300" align="right"></td>
    </tr>
  </table>
  <!-- Fin del encabezado -->

<br><br>
<!-- BEGIN Record proceso_participa -->
<?php 
require_once 'includes/funciones.php'; // Llama el archivo de funciones del sistema.

if(isset($_SESSION['errores'])):
  $errores = $_SESSION['errores'];
endif;
?>

  <table class="MainTable" cellspacing="0" cellpadding="0" border="0" align="center">
    <tr>
      <td valign="top">
        <table class="Header" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="HeaderLeft"><img alt="" src="css/Images/Spacer.gif"></td> 
            <td class="th">
                Iniciar sesión
            </td> 
            <td class="HeaderRight"><img alt="" src="css/Images/Spacer.gif"></td> 
          </tr>
        </table>

      <!-- Inicio formulario para iniciar sesión -->     
      <form id="iniciar_sesion" method="post" name="iniciar_sesion" action="actions/verifica_login.php">
        <table class="Record" cellspacing="0" cellpadding="0">
          <tr class="Controls">
            <td class="th">
              <label for="username" class="icon">U</label>
            </td> 
            <td>
              <input type="text" id="username" name="username" placeholder="Usuario" required="required">
              <?php
              if(isset($_SESSION['errores'])):
                echo mostrarErrores($errores, 'username');
              endif;
              ?>
            </td> 
          </tr>

          <tr class="Controls">
            <td class="th">
              <label for="password" class="icon">&nbsp;w</label>
            </td> 
            <td>
              <input type="password" id="password" name="password" placeholder="Contraseña" required="required">
              <?php
              if(isset($_SESSION['errores'])):
                echo mostrarErrores($errores, 'password');
              endif;
              ?>
            </td> 
          </tr>

          <tr class="Bottom">
            <td style="TEXT-ALIGN: right" colspan="2">
            <input name="guardar" type="submit" value="Acceder" class="Button">
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