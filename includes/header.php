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

<?php
if(isset($_SESSION['usuario'])):
  $usuario = $_SESSION['usuario'];
?>
  <!-- Inicio del menú de navegación -->
  <nav id="nav">
      <ul>
          <li><a href="agregar_incidencia.php">Agregar incidencia</a></li><li><a href="listado_incidencias.php">Ver incidencias</a></li><li><a href="#">Admisión</a>
            <ul>
              <li><a href="listado_admision.php?ciclo=2020-2021">2020-2021</a></li>
              <li><a href="listado_admision.php?ciclo=2019-2020">2019-2020</a></li>
              <li><a href="listado_admision.php?ciclo=2018-2019">2018-2019</a></li>
              <li><a href="listado_admision.php?ciclo=2017-2018">2017-2018</a></li>
              <li><a href="listado_admision.php?ciclo=2016-2017">2016-2017</a></li>
              <li><a href="listado_admision.php?ciclo=2015-2016">2015-2016</a></li>
            </ul>
          </li>
      </ul>
  </nav>
  <!-- Fin del menú de navegación -->

  <div id="user">
    <?php
    if($usuario['genero'] == 'H'){
      $saludo = 'Bienvenido';
    } elseif ($usuario['genero'] == 'M'){
      $saludo = 'Bienvenida';
    }
    ?>
      <?= $saludo ?>: <?= $usuario['nombre_completo'] ?> | <a href="actions/logout.php" id="cerrar_sesion" class="cerrar_sesion" title="Da click para cerrar la sesión">Cerrar sesión</a>
  </div>
<?php
else:
  pagina('denegado.php');
endif;
?>
<br><br>