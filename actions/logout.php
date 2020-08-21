<?php
session_start();
require_once '../includes/funciones.php';
$PageName = '../login.php';

if($_SESSION['usuario']){
    session_destroy();
    pagina($PageName);
}
?>