<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $conexion = db::conexion();
        $sql = "SELECT * FROM digimones WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $digimon = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$digimon) {
            echo "Digimon no encontrado.";
            exit();
        }
    } catch (PDOException $error) {
        echo "Error: " . $error->getMessage();
        exit();
    }
} else {
    echo "ID de Digimon no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Digimon</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Información Digimon</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Nombre: <?= $digimon['nombre'] ?></h5>
                <p class="card-text">
                    <strong>Ataque:</strong> <?= $digimon['ataque'] ?><br>
                    <strong>Defensa:</strong> <?= $digimon['defensa'] ?><br>
                    <strong>Tipo:</strong> <?= $digimon['tipo'] ?><br>
                    <strong>Nivel:</strong> <?= $digimon['nivel'] ?><br>
                </p>
                <img src="../../digimones/<?= $digimon['nombre'] ?>/<?= $digimon['imagen'] ?>" alt="Imagen del Digimon" style="max-width: 100px; height: auto;">
                <img src="../../digimones/<?= $digimon['nombre'] ?>/<?= $digimon['imagen_victoria'] ?>" alt="Imagen de Victoria" style="max-width: 100px; height: auto;">
                <img src="../../digimones/<?= $digimon['nombre'] ?>/<?= $digimon['imagen_derrota'] ?>" alt="Imagen de Derrota" style="max-width: 100px; height: auto;">

                <div class="mt-3">
                    <a href="edit.php?id=<?= $digimon['id'] ?>" class="btn btn-warning">Modificar</a>
                    <a href="delete.php?id=<?= $digimon['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este Digimon?');">Borrar</a>
                    <a href="definirEvolucion.php?id=<?= $digimon['id'] ?>" class="btn btn-primary">Definir Evoluciones</a>
                    <a href="create.php" class="btn btn-secondary">Registrar Otro Digimon</a>
                    <a href="../../index.php" class="btn btn-light">Inicio</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>