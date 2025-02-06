<?php
session_start();
require_once __DIR__ . '/../../config/db.php'; 
require_once __DIR__ . '/../../controllers/digimonesController.php'; 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Digimon/Usuarios/login.php");
    exit();
}

$usuarioId = $_SESSION['usuario_id'];
$controlador = new DigimonesController();
$digimones = $controlador->listarPorUsuario((int)$usuarioId);

// Recuperar el equipo actual desde la base de datos
$conexion = db::conexion();
$sqlEquipo = "SELECT digimon_id FROM equipo WHERE usuario_id = :usuarioId";
$stmtEquipo = $conexion->prepare($sqlEquipo);
$stmtEquipo->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
$stmtEquipo->execute();
$equipoActual = $stmtEquipo->fetchAll(PDO::FETCH_COLUMN);

// Si se ha enviado el formulario, actualizar equipo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevoEquipo = $_POST['equipo'] ?? [];

    if (count($nuevoEquipo) == 3) {
        // Limpiar el equipo anterior en la base de datos
        $stmtLimpiar = $conexion->prepare("DELETE FROM equipo WHERE usuario_id = :usuarioId");
        $stmtLimpiar->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmtLimpiar->execute();

        // Insertar los nuevos Digimones en la tabla 'equipo'
        $stmtInsertar = $conexion->prepare("INSERT INTO equipo (usuario_id, digimon_id) VALUES (:usuarioId, :digimonId)");
        foreach ($nuevoEquipo as $digimonId) {
            $stmtInsertar->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
            $stmtInsertar->bindParam(':digimonId', $digimonId, PDO::PARAM_INT);
            $stmtInsertar->execute();
        }

        // Guardar los IDs de los Digimones en la sesión
        $_SESSION['equipo'] = $nuevoEquipo;

        // Confirmar actualización
        $equipoActual = $nuevoEquipo;
        $mensaje = "¡Tu equipo ha sido guardado exitosamente!";
    } else {
        $mensaje = "Debes seleccionar exactamente 3 Digimones para formar tu equipo.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Equipo - Digimon Battle</title>
    <link href="/Digimon/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;
        }
        body {
            background: linear-gradient(to bottom, #4e54c8, #8f94fb);
            font-family: 'Trebuchet MS', sans-serif;
            color: #fff;
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            margin-top: 20px;
            text-shadow: 2px 2px 4px #000;
        }

        .container {
            margin-top: 1%;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            justify-items: center;
        }

        .gallery-item {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            margin-top: 2%;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.6);
        }

        .gallery-item img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        }

        .gallery-item p {
            font-size: 1.2rem;
            margin-top: 10px;
            color: #ffeb3b;
            text-shadow: 1px 1px 2px #000;
        }

        input[type="checkbox"] {
            margin-top: 10px;
            width: 20px;
            height: 20px;
        }

        .btn {
            font-size: 1.2rem;
            background-color: #ff5722;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
            margin-top: 4%;
        }

        .btn:hover {
            background-color: #ff3d00;
            transform: scale(1.05);
        }

        .alert {
            font-size: 1.2rem;
            margin-top: 20px;
        }

        a.btn {
            margin-top: 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Forma tu Equipo de Digimon!</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="post" action="equipo.php">
            <div class="gallery">
                <?php foreach ($digimones as $digimon): ?>
                    <div class="gallery-item">
                        <img src="/Digimon/Administracion/digimones/<?= htmlspecialchars($digimon->nombre) ?>/<?= htmlspecialchars($digimon->imagen) ?>" 
                             alt="<?= htmlspecialchars($digimon->nombre) ?>">
                        <p><?= htmlspecialchars($digimon->nombre) ?></p>
                        <input type="checkbox" name="equipo[]" value="<?= htmlspecialchars($digimon->id) ?>" 
                            <?= (isset($_SESSION['equipo']) && in_array($digimon->id, $_SESSION['equipo'])) ? 'checked' : '' ?>>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn mt-3">Guardar Equipo</button>
        </form>
        <br>
        <a href="../../index.php" class="btn mt-3">Volver a Inicio</a>
        <br>
    </div>
</body>
</html>
