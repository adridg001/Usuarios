<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once '../../controllers/digimonesController.php'; // Asegúrate de que esta ruta es correcta

session_start();
$usuarioId = $_SESSION['usuario_id']; // Asegúrate de que el ID del usuario está almacenado en la sesión

$controlador = new DigimonesController();
$digimones = $controlador->listarPorUsuario($usuarioId);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Digimones</title>
    <link href="/Digimon/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Mis Digimones</h1>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ataque</th>
                    <th>Defensa</th>
                    <th>Nivel</th>
                    <th>Tipo</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($digimones)): ?>
                    <?php foreach ($digimones as $digimon): ?>
                        <tr>
                            <td><?= htmlspecialchars($digimon->id) ?></td>
                            <td><?= htmlspecialchars($digimon->nombre) ?></td>
                            <td><?= htmlspecialchars($digimon->ataque) ?></td>
                            <td><?= htmlspecialchars($digimon->defensa) ?></td>
                            <td><?= htmlspecialchars($digimon->nivel) ?></td>
                            <td><?= htmlspecialchars($digimon->tipo) ?></td>
                            <td><img src="/Digimon/digimones/<?= htmlspecialchars($digimon->nombre) ?>/<?= htmlspecialchars($digimon->imagen) ?>" alt="<?= htmlspecialchars($digimon->nombre) ?>" width="50"></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No se encontraron Digimones.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="../../index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>