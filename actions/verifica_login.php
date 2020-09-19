<?php
require_once '../includes/conexion.php'; // Conexión a la base de datos.
require_once '../includes/funciones.php'; // Carga archivo de funciones.

$errores = array();

// Recoge datos del formulario.
if(isset($_POST)){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Consulta a la base de datos para comprobar las credenciales usuario.
    $sql   = "SELECT * FROM usuario WHERE username = '$username'";
    $login = mysqli_query($dbase, $sql);
    
    if($login && mysqli_num_rows($login) == 1){

        // Verifica si la contraseña es correcta.
        $usuario = mysqli_fetch_assoc($login);
        echo 'Contraseña escrita: '.$password;
        echo 'Contraseña de la BD: '.$usuario['password'];
        // $verify = password_verify($password, $usuario['password']); Verificar con hash
        if($password == $usuario['password']){
            $verify = true;
        } else {
            $verify = false;
        }

        var_dump($verify);
        if($verify){
            $_SESSION['usuario'] = $usuario;
        } else {
            $errores['password'] = 'La contraseña es incorrecta.';
        }
    } else {
        $errores['username'] = 'El usuario es incorrecto.';
    }
} else {
    $errores['post'] = 'Datos vacíos.';
}

if(count($errores) == 0){
    $PageName = '../index.php';
    pagina($PageName);
} else {
    $PageName = '../login.php';
    pagina($PageName);   
}
?>