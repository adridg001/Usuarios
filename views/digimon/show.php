<?php
session_start();
require_once __DIR__ . '/../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once __DIR__ . '/../../controllers/digimonesController.php'; // Asegúrate de que esta ruta es correcta

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Digimon/Usuarios/login.php");
    exit();
}

$usuarioId = $_SESSION['usuario_id'];
$controlador = new DigimonesController();
$digimones = $controlador->listarPorUsuario((int)$usuarioId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Digimones</title>
    <link href="/Digimon/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/Digimon/assets/images/fondo_digimon.webp'); /* Cambia la ruta según tu proyecto */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            font-size: 3rem;
            color: #f9ca24;
            text-shadow: 2px 2px #000;
        }

        .digimon-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .digimon-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            width: 220px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
        }

        .digimon-card:hover {
            transform: scale(1.05);
        }

        .digimon-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .digimon-card h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: #333;
        }

        .digimon-card p {
            font-size: 0.9rem;
            margin: 2px 0;
            color: #666;
        }

        .btn-back {
            display: block;
            margin: 20px auto;
            text-align: center;
            background-color: #f39c12;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mis Digimones</h1>
        <?php if (!empty($digimones)): ?>
            <div class="digimon-grid">
                <?php foreach ($digimones as $digimon): ?>
                    <div class="digimon-card">
                        <img src="/Digimon/Administracion/digimones/<?= htmlspecialchars($digimon->nombre) ?>/<?= htmlspecialchars($digimon->imagen) ?>" alt="<?= htmlspecialchars($digimon->nombre) ?>">
                        <h3><?= htmlspecialchars($digimon->nombre) ?></h3>
                        <p><strong>Ataque:</strong> <?= htmlspecialchars($digimon->ataque) ?></p>
                        <p><strong>Defensa:</strong> <?= htmlspecialchars($digimon->defensa) ?></p>
                        <p><strong>Nivel:</strong> <?= htmlspecialchars($digimon->nivel) ?></p>
                        <p><strong>Tipo:</strong> <?= htmlspecialchars($digimon->tipo) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center mt-3">No se encontraron Digimones.</p>
        <?php endif; ?>
        <a href="../../index.php" class="btn-back">Volver a Inicio</a>
    </div>
</body>
</html>
