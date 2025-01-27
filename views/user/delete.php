<?php
require_once '../../controllers/usersController.php'; 
//pagina invisible
if (!isset($_REQUEST["id"])) {
    header('location:index.php');
    exit();
}

//recoger datos
$id = $_REQUEST["id"];

$controlador = new UsersController();
$borrado = $controlador->borrar($id);

$mensaje = $borrado ? 'Usuario eliminado exitosamente.' : 'Error al eliminar el usuario.';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Eliminar Usuario</h1>
        <div class="alert alert-info"><?= $mensaje ?></div>
        <a href="listarUsuarios.php" class="btn btn-primary">Volver a la lista de Usuarios</a>
    </div>
</body>
</html>