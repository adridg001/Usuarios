<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/DigimonesController.php';
require_once __DIR__ . '/../../controllers/usersController.php';

$digimonesController = new DigimonesController();
$usuariosController = new UsersController();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuarioId = $_SESSION['usuario_id'];  
$digimonesUsuario = $digimonesController->obtenerEquipoPorUsuario($usuarioId);

if (count($digimonesUsuario) < 3) {
    die("No tienes suficientes digimones en tu equipo.");
}

$usuario = $usuariosController->ver($usuarioId);  
$rivalId = obtenerRivalAleatorio($usuarioId);
$rival = $usuariosController->ver($rivalId);
$digimonesRival = $digimonesController->obtenerEquipoPorUsuario($rivalId);

if (count($digimonesRival) < 3) {
    die("El rival no tiene suficientes digimones.");
}

function obtenerRivalAleatorio($usuarioId) {
    $conexion = db::conexion();
    $sql = "SELECT id FROM usuarios WHERE id != :usuario_id ORDER BY RAND() LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->execute();
    $rival = $stmt->fetch(PDO::FETCH_OBJ);
    return $rival ? $rival->id : die("No se pudo obtener un rival aleatorio.");
}

function calcularPoderDigimon($digimon, $tipoRival) {
    $tablaTipos = [
        'VACUNA' => ['VACUNA' => 10, 'VIRUS' => 5, 'ANIMAL' => -5, 'PLANTA' => -10, 'ELEMENTAL' => 0],
        'VIRUS' => ['VACUNA' => -10, 'VIRUS' => 10, 'ANIMAL' => 5, 'PLANTA' => -5, 'ELEMENTAL' => 0],
        'ANIMAL' => ['VACUNA' => -5, 'VIRUS' => -10, 'ANIMAL' => 10, 'PLANTA' => 5, 'ELEMENTAL' => -5],
        'PLANTA' => ['VACUNA' => 5, 'VIRUS' => -5, 'ANIMAL' => 10, 'PLANTA' => 10, 'ELEMENTAL' => -5],
        'ELEMENTAL' => ['VACUNA' => -10, 'VIRUS' => 5, 'ANIMAL' => -5, 'PLANTA' => 10, 'ELEMENTAL' => 10]
    ];

    $tipoUsuario = $digimon->tipo;
    $modificadorTipo = $tablaTipos[$tipoUsuario][$tipoRival] ?? 0;
    $modificadorRandom = rand(1, 50);
    
    return $digimon->ataque + $digimon->defensa + $modificadorTipo + $modificadorRandom;
}

// Inicializar las variables para contar victorias y derrotas
$victoriasUsuario = 0;
$victoriasRival = 0;
$rondas = [];

for ($i = 0; $i < 3; $i++) {
    $digimonUsuario = $digimonesUsuario[$i];
    $digimonRival = $digimonesRival[$i];

    // Calcular el poder de cada Digimon
    $poderUsuario = calcularPoderDigimon($digimonUsuario, $digimonRival->tipo);
    $poderRival = calcularPoderDigimon($digimonRival, $digimonUsuario->tipo);

    // Determinar el resultado de la ronda
    if ($poderUsuario > $poderRival) {
        $resultado = "Ganaste esta ronda";
        $victoriasUsuario++;
    } else {
        $resultado = "Perdiste esta ronda";
        $victoriasRival++;
    }

    // Almacenar el resultado de la ronda
    $rondas[] = [
        "numero" => $i + 1,
        "digimonUsuario" => $digimonUsuario,
        "digimonRival" => $digimonRival,
        "poderUsuario" => $poderUsuario,
        "poderRival" => $poderRival,
        "resultado" => $resultado
    ];
}

// Determinar el ganador de la partida
$mensajeFinal = ($victoriasUsuario >= 2) ? "¡Has ganado la partida!" : "¡Has perdido la partida!";

// Actualizar las estadísticas del usuario y rival
$usuariosController->actualizarEstadisticas($usuarioId, $victoriasUsuario >= 2);

// Obtener las estadísticas actualizadas
$usuarioActualizado = $usuariosController->ver($usuarioId);

// Verificar si el usuario ha jugado 10 partidas
if ($usuarioActualizado->partidas_jugadas % 10 === 0) {
    // Regalar un Digimon de nivel 1 que no tenga
    $digimonNuevo = $digimonesController->obtenerDigimonPartida($usuarioId);
    if ($digimonNuevo) {
        // Agregar el nuevo Digimon a la lista del usuario
        $digimonesController->agregarDigimonAUsuario($usuarioId, $digimonNuevo->id);
        echo "<div class='result'>¡Felicidades! Has recibido un nuevo Digimon de nivel 1: {$digimonNuevo->nombre}</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campo de Batalla - Digimones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #222; color: #fff; text-align: center; }
        .container { width: 80%; margin: 0 auto; padding: 20px; background: #333; border-radius: 15px; }
        .battle { display: flex; justify-content: space-between; padding: 20px; margin: 10px 0; background: #444; border-radius: 10px; }
        .player { width: 45%; text-align: center; }
        .player img { width: 150px; height: 150px; border-radius: 50%; border: 3px solid #ffcc00; }
        .result { font-size: 1.5em; margin-top: 20px; font-weight: bold; color: #ffcc00; }
        .button { display: inline-block; margin: 20px; padding: 10px 20px; background: #28a745; color: #fff; border: none; cursor: pointer; }
        .round-indicator { font-size: 1.2em; margin-top: 10px; color: #ffcc00; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Campo de Batalla - Digimones</h1>

        <div class="round-indicator">Rondas ganadas: <?= $victoriasUsuario; ?> | Rondas perdidas: <?= $victoriasRival; ?></div>

        <?php foreach ($rondas as $ronda): ?>
            <div class="battle">
                <div class="player">
                    <h2>Jugador: <?= $usuario->nombre; ?></h2>
                    <img src="/Digimon/Administracion/digimones/<?= htmlspecialchars(string: $ronda['digimonUsuario']->nombre); ?>/<?= htmlspecialchars($ronda['digimonUsuario']->imagen); ?>" alt="<?= htmlspecialchars($ronda['digimonUsuario']->nombre); ?>">
                    <p>Digimon:<?= htmlspecialchars($ronda['digimonUsuario']->nombre); ?> (Poder: <?= htmlspecialchars($ronda['poderUsuario']); ?>)</p>
                </div>
                <div class="player">
                    <h2>Rival: <?= $rival->nombre; ?></h2>
                    <img src="/Digimon/Administracion/digimones/<?= htmlspecialchars(string: $ronda['digimonRival']->nombre); ?>/<?= htmlspecialchars($ronda['digimonRival']->imagen); ?>" alt="<?= htmlspecialchars($ronda['digimonRival']->nombre); ?>">
                    <p>Digimon: <?= htmlspecialchars($ronda['digimonRival']->nombre); ?> (Poder: <?= htmlspecialchars($ronda['poderRival']); ?>)</p>
                </div>
            </div>
            <div class="result"><?= $ronda['resultado']; ?></div>
        <?php endforeach; ?>

        <div class="result"><?= $mensajeFinal; ?></div>
        <button class="button" onclick="location.reload()">Volver a jugar</button>
        <button class="button" onclick="window.location.href='../../index.php'">Volver al inicio</button>
    </div>
</body>
</html>
