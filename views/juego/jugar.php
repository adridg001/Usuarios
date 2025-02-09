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
    $conexion = db::conexion();

    while (count($digimonesUsuario) < 3) {
        // 1. Obtenenemos un digimon y comprobamos que no sea idéntico a ninguno que tenga el usuario en su equipo
        $sqlSelect = "SELECT digimones.* 
                      FROM digimones
                      WHERE digimones.nivel = 1
                      AND digimones.id NOT IN (
                          SELECT equipo.digimon_id 
                          FROM equipo 
                          WHERE equipo.usuario_id = :usuario_id
                      )
                      ORDER BY RAND()
                      LIMIT 1";

        $stmtSelect = $conexion->prepare($sqlSelect);
        $stmtSelect->bindParam(':usuario_id', $usuarioId);
        $stmtSelect->execute();
        $digimonNuevo = $stmtSelect->fetch(PDO::FETCH_OBJ);

        // Si no hay más Digimon disponibles, salimos del bucle
        if (!$digimonNuevo) {
            break;
        }

        // 2. Añadir el Digimon al equipo
        $sqlInsertarEquipo = "INSERT INTO equipo (usuario_id, digimon_id) VALUES (:usuario_id, :digimon_id)";
        $stmtInsertarEquipo = $conexion->prepare($sqlInsertarEquipo);
        $stmtInsertarEquipo->bindParam(':usuario_id', $usuarioId);
        $stmtInsertarEquipo->bindParam(':digimon_id', $digimonNuevo->id);
        $stmtInsertarEquipo->execute();

        // 3. Añadir el Digimon a la lista general de digimones del usuario
        $sqlInsertarDigimonesUsuario = "INSERT INTO digimones_usuario (usuario_id, digimon_id) VALUES (:usuario_id, :digimon_id)";
        $stmtInsertarDigimonesUsuario = $conexion->prepare($sqlInsertarDigimonesUsuario);
        $stmtInsertarDigimonesUsuario->bindParam(':usuario_id', $usuarioId);
        $stmtInsertarDigimonesUsuario->bindParam(':digimon_id', $digimonNuevo->id);
        $stmtInsertarDigimonesUsuario->execute();

        // Añadir el nuevo Digimon a la lista actual de Digimones del usuario para que el bucle lo reconozca
        $digimonesUsuario[] = $digimonNuevo;
    }
}

$usuario = $usuariosController->ver($usuarioId);  
$rivalId = obtenerRivalAleatorio($usuarioId);
$rival = $usuariosController->ver($rivalId);
$digimonesRival = $digimonesController->obtenerEquipoPorUsuario($rivalId);

if (count($digimonesRival) < 3) {
    $conexion = db::conexion();

    while (count($digimonesRival) < 3) {
        // 1. Seleccionar un Digimon de nivel 1 que el rival no tenga
        $sqlSelectRival = "SELECT digimones.* 
                           FROM digimones
                           WHERE digimones.nivel = 1
                           AND digimones.id NOT IN (
                               SELECT equipo.digimon_id 
                               FROM equipo 
                               WHERE equipo.usuario_id = :rival_id
                           )
                           ORDER BY RAND()
                           LIMIT 1";

        $stmtSelectRival = $conexion->prepare($sqlSelectRival);
        $stmtSelectRival->bindParam(':rival_id', $rivalId);
        $stmtSelectRival->execute();
        $digimonNuevoRival = $stmtSelectRival->fetch(PDO::FETCH_OBJ);

        // Si no hay más Digimones disponibles, salir del bucle
        if (!$digimonNuevoRival) {
            break;
        }

        // 2. Añadir el Digimon al equipo del rival
        $sqlInsertarEquipoRival = "INSERT INTO equipo (usuario_id, digimon_id) VALUES (:rival_id, :digimon_id)";
        $stmtInsertarEquipoRival = $conexion->prepare($sqlInsertarEquipoRival);
        $stmtInsertarEquipoRival->bindParam(':rival_id', $rivalId, PDO::PARAM_INT);
        $stmtInsertarEquipoRival->bindParam(':digimon_id', $digimonNuevoRival->id, PDO::PARAM_INT);
        $stmtInsertarEquipoRival->execute();

        // 3. Añadir el Digimon a la lista general de Digimones del rival
        $sqlInsertarDigimonesRival = "INSERT INTO digimones_usuario (usuario_id, digimon_id) VALUES (:rival_id, :digimon_id)";
        $stmtInsertarDigimonesRival = $conexion->prepare($sqlInsertarDigimonesRival);
        $stmtInsertarDigimonesRival->bindParam(':rival_id', $rivalId, PDO::PARAM_INT);
        $stmtInsertarDigimonesRival->bindParam(':digimon_id', $digimonNuevoRival->id, PDO::PARAM_INT);
        $stmtInsertarDigimonesRival->execute();

        // Añadir el nuevo Digimon a la lista actual de Digimones del rival para que el bucle lo reconozca
        $digimonesRival[] = $digimonNuevoRival;
    }
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
        $imagenResultadoUsuario = "/Digimon/Administracion/digimones/{$digimonUsuario->nombre}/victoria.png";
        $imagenResultadoRival = "/Digimon/Administracion/digimones/{$digimonRival->nombre}/derrota.png";
    } else {
        $resultado = "Perdiste esta ronda";
        $victoriasRival++;
        $imagenResultadoUsuario = "/Digimon/Administracion/digimones/{$digimonUsuario->nombre}/derrota.png";
        $imagenResultadoRival = "/Digimon/Administracion/digimones/{$digimonRival->nombre}/victoria.png";
    }

    // Almacenar el resultado de la ronda
    $rondas[] = [
        "numero" => $i + 1,
        "digimonUsuario" => $digimonUsuario,
        "digimonRival" => $digimonRival,
        "poderUsuario" => $poderUsuario,
        "poderRival" => $poderRival,
        "resultado" => $resultado,
        "imagenResultadoUsuario" => $imagenResultadoUsuario,
        "imagenResultadoRival" => $imagenResultadoRival
    ];
}

// Determinar el ganador de la partida
$mensajeFinal = ($victoriasUsuario >= 2) ? "¡Has ganado la partida!" : "¡Has perdido la partida!";

// Actualizar las estadísticas del usuario y rival
$usuariosController->actualizarEstadisticas($usuarioId, $victoriasUsuario >= 2);

// Obtener las estadísticas actualizadas
$usuarioActualizado = $usuariosController->ver($usuarioId);

// Verificar digievoluciones cada 10 partidas ganadas
$evoDigimon = $usuariosController->actualizarEvolucion($usuarioId);
if ($evoDigimon) {
    echo "<div class='evo'>¡Felicidades! Has ganado una digievolución por tus 10 victorias.</div>";
}

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
        .evo { font-size: 1.5em; margin-top: 20px; font-weight: bold; color: #ffcc00; }
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
                    <img src="<?= $ronda['imagenResultadoUsuario']; ?>" alt="<?= $ronda['resultado']; ?>" style="width: 150px; height: 150px;">
                    <p>Digimon: <?= htmlspecialchars($ronda['digimonUsuario']->nombre); ?> (Poder: <?= htmlspecialchars($ronda['poderUsuario']); ?>)</p>
                </div>
                <div class="player">
                    <h2>Rival: <?= $rival->nombre; ?></h2>
                    <img src="<?= $ronda['imagenResultadoRival']; ?>" alt="<?= $ronda['resultado']; ?>" style="width: 150px; height: 150px;">
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