<?php
require_once '../../config/db.php'; 
require_once '../../models/userModel.php'; // Asegúrate de que esta ruta es correcta

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    if (empty($nombre) || empty($password)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        try {
            $userModel = new UserModel();
            $user = [
                "nombre" => $nombre,
                "password" => $password,
            ];
            $usuarioId = $userModel->insert($user);

            if ($usuarioId) {
                $conexion = db::conexion();
                $sql = "SELECT id FROM digimones WHERE nivel = 1 ORDER BY RAND() LIMIT 3";
                $stmt = $conexion->query($sql);
                $digimones = $stmt->fetchAll(PDO::FETCH_COLUMN);

                foreach ($digimones as $digimonId) {
                    $sql = "INSERT INTO DIGIMONES_USUARIO (usuario_id, digimon_id) VALUES (:usuario_id, :digimon_id)";
                    $stmt = $conexion->prepare($sql);
                    $stmt->execute([
                        ":usuario_id" => $usuarioId,
                        ":digimon_id" => $digimonId
                    ]);
                }

                $mensaje = 'Usuario registrado exitosamente.';
            } else {
                $mensaje = 'Error al registrar el usuario.';
            }
            header("Location: /Digimon/Administracion/views/user/list.php?mensaje=" . urlencode($mensaje));
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
    <title>Registrar Usuario</title>
    <link href="/Digimon/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Registrar Usuario</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="post" action="create.php">
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
        <a href="../../index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>