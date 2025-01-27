<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once '../../controllers/usersController.php'; // Asegúrate de que esta ruta es correcta

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $controlador = new UsersController();
    $usuario = $controlador->ver($id);
} else {
    echo "ID de usuario no proporcionado o no válido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('fondo_VerUsuario.jpg');
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat; 
            height: 100vh;
            margin: 0;
        }
        .container{
            background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 15px;
            padding: 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Detalles del Usuario</h1>
        <?php if (!empty($usuario)): ?>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($usuario->id); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario->nombre); ?></p>
            <p><strong>Partidas Ganadas:</strong> <?php echo htmlspecialchars($usuario->partidas_ganadas); ?></p>
            <p><strong>Partidas Perdidas:</strong> <?php echo htmlspecialchars($usuario->partidas_perdidas); ?></p>
            <p><strong>Partidas Totales:</strong> <?php echo htmlspecialchars($usuario->partidas_ganadas + $usuario->partidas_perdidas); ?></p>
            <div class="d-flex gap-2 mt-3">
                <a href="delete.php?id=<?php echo $usuario->id; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                <a href="list.php" class="btn btn-primary">Volver a Lista de Usuarios</a>
            </div>
        <?php else: ?>
            <p>No se encontró información del usuario.</p>
        <?php endif; ?>
    </div>
</body>
</html>