<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos
require_once 'models/userModel.php'; // Asegúrate de que este archivo contiene la clase userModel

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    if (empty($nombre) || empty($password)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        try {
            $userModel = new userModel();
            $user = [
                "nombre" => $nombre,
                "password" => $password,
            ];
            $resultado = $userModel->insert($user);

            if ($resultado) {
                $mensaje = 'Usuario registrado exitosamente.';
            } else {
                $mensaje = 'Error al registrar el usuario.';
            }
            header("Location: listarusuarios.php?id=$id");
            exit();

        } catch (Exception $error) {
            $mensaje = 'Error: ' . $error->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Usuario</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Registrar Nuevo Usuario</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <form method="post" action="altaUsuario.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        <a href="index.php" class="btn btn-primary mt-3">Volver a Inicio</a>

    </div>
</body>
</html>