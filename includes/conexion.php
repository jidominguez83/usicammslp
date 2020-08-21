<?php
function Conectarse() 
{ 
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "servicioprofesionaldocente";
   
   if (!($link = mysqli_connect($host, $user, $pass, $db)))        
   { 
      echo "Error conectando a la base de datos."; 
      exit(); 
   }      
   mysqli_query($link, "SET NAMES 'utf8'");
   return $link; 
}

session_start();
$dbase = Conectarse();
?>