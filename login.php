<?php
session_start();
if (isset($_SESSION["usuario"])) {
    header("location:index.php");
    exit();
}   
session_destroy();
session_start();
$user = ($_POST["usuario"]) ?? "";
$password = ($_POST["password"]) ?? "";
$error = false;

if (isset($_POST["usuario"], $_POST["password"])) {
    require_once  'config/db.php'; // Ajusta la ruta según la estructura de tu proyecto
    require_once 'models/userModel.php'; // Asegúrate de que esta ruta es correcta

    $userModel = new UserModel();
    $usuario = $userModel->login($user, $password);

    if ($usuario != null) { // Usuario correcto
        $_SESSION["usuario"] = $usuario;
        $_SESSION["usuario_id"] = $usuario->id; // Almacenar el usuario_id en la sesión
        header("location:index.php");
        exit();
    }
    // Si no existe el usuario se ha producido un error
    $error = true;
}

$msg = "";
$visibilidad = "";
$style = "";
if ($error) {
    $msg = "Error, Usuario o Password Incorrectos";
    $visibilidad = "visible";
    $style = "alert-danger";
}
if (isset($_GET["session"]) && ($_GET["session"] == "logout")) {
    $msg = "Fin de Sesion";
    $visibilidad = "visible";
    $style = "alert-success";
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-100 m-auto">
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <center><img class="mb-4" src="assets/img/proyecto.png" alt="" width="150" height="150"></center>
        <div class="alert <?= $style . ' ' . $visibilidad ?>"><?= $msg ?></div>
        <h1 class="h3 mb-3 fw-normal">Inicio de Sesión</h1>
        <div class="form-floating">
            <input type="usuario" class="form-control" id="usuario" name="usuario" value="<?= $user ?>">
            <label for="usuario">Usuario</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" value="<?= $password ?>" placeholder="Password">
            <label for="password">Password</label>
        </div>
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Recuerdame
            </label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    </form>
</main>
</body>
</html>