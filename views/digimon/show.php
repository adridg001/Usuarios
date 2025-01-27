<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto

try {
    $conexion = db::conexion();
    $sql = "SELECT id, nombre, ataque, defensa, nivel, tipo, evo_id, imagen FROM digimones";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $digimones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Digimones</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-image: url('fondo_VerDigimones.webp');
        background-size: 100% 100%; 
        background-position: center; 
        background-repeat: no-repeat; 
        min-height: 100vh; 
        margin: 0; 
        padding: 0; 
        overflow-x: hidden; 
    }

    .container {
        background-color: rgba(255, 255, 255, 0.8); 
        border-radius: 15px; 
        padding: 20px; 
        margin: 10px auto; 
        max-width: 1200px;
    }
</style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Lista de Digimones</h1>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ataque</th>
                    <th>Defensa</th>
                    <th>Nivel</th>
                    <th>Tipo</th>
                    <th>Evo ID</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($digimones && $stmt->rowCount() > 0): ?>
                    <?php foreach ($digimones as $digimon): ?>
                        <tr>
                            <td><?= $digimon['id'] ?></td>
                            <td><?= $digimon['nombre'] ?></td>
                            <td><?= $digimon['ataque'] ?></td>
                            <td><?= $digimon['defensa'] ?></td>
                            <td><?= $digimon['nivel'] ?></td>
                            <td><?= $digimon['tipo'] ?></td>
                            <td><?= $digimon['evo_id'] ?></td>
                            <td><img src="../../digimones/<?= $digimon['nombre'] ?>/<?= $digimon['imagen'] ?>" alt="<?= $digimon['nombre'] ?>" width="50"></td>
                            <td>
                                <a href="datosDigimon.php?id=<?= $digimon['id'] ?>" class="btn btn-primary btn-sm">Ver Digimon</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No se encontraron digimones.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="../../index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>