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

$usuarioId = $_SESSION['usuario_id'];  // Obtén el ID del usuario desde la sesión

// Obtener los digimones del usuario
$digimonesUsuario = $digimonesController->obtenerDigimonesPorUsuario($usuarioId);
if (count($digimonesUsuario) < 3) {
    die("No tienes suficientes digimones en tu equipo.");
}

$usuario = $usuariosController->ver($usuarioId);  // Obtén el nombre del usuario

// ** Definir las variables para la vista **
$usuarioNombre = $usuario->nombre;
$turnoJugador = 0;  // Este es el índice que vamos a usar para iterar por los digimones del jugador

// Seleccionar rival (en este ejemplo, tomamos al azar a otro usuario)
$rivalId = obtenerRivalAleatorio($usuarioId);
$digimonesRival = $digimonesController->obtenerDigimonesPorUsuario($rivalId);

// ** Depuración**: Mostrar el ID del rival y el número de digimones
echo "ID del rival: " . $rivalId . "<br>";
echo "Número de digimones del rival: " . count($digimonesRival) . "<br>";

// Verificar que el rival tiene al menos 3 digimones
if (count($digimonesRival) < 3) {
    die("El rival no tiene suficientes digimones.");
}

// Función para obtener un rival aleatorio
function obtenerRivalAleatorio($usuarioId) {
    $conexion = db::conexion();

    $sql = "SELECT id FROM usuarios WHERE id != :usuario_id ORDER BY RAND() LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->execute();
    $rival = $stmt->fetch(PDO::FETCH_OBJ);
    
    if (!$rival) {
        die("No se pudo obtener un rival aleatorio.");
    }
    
    return $rival->id;
}

// Función para calcular el valor total de un digimon
function calcularPoderDigimon($digimon, $tipoRival) {
    $tablaTipos = [
        'VACUNA' => ['VACUNA' => 10, 'VIRUS' => 5, 'ANIMAL' => -5, 'PLANTA' => -10, 'ELEMENTAL' => 0],
        'VIRUS' => ['VACUNA' => -10, 'VIRUS' => 10, 'ANIMAL' => 5, 'PLANTA' => -5, 'ELEMENTAL' => 0],
        'ANIMAL' => ['VACUNA' => -5, 'VIRUS' => -10, 'ANIMAL' => 10, 'PLANTA' => 5, 'ELEMENTAL' => -5],
        'PLANTA' => ['VACUNA' => 5, 'VIRUS' => -5, 'ANIMAL' => 10, 'PLANTA' => 10, 'ELEMENTAL' => -5],
        'ELEMENTAL' => ['VACUNA' => -10, 'VIRUS' => 5, 'ANIMAL' => -5, 'PLANTA' => 10, 'ELEMENTAL' => 10]
    ];

    $tipoUsuario = $digimon->tipo;
    $modificadorTipo = isset($tablaTipos[$tipoUsuario][$tipoRival]) ? $tablaTipos[$tipoUsuario][$tipoRival] : 0;
    $modificadorRandom = rand(1, 50);

    $poderTotal = (int)$digimon->ataque + (int)$digimon->defensa + $modificadorTipo + $modificadorRandom;
    return $poderTotal;
}

// Función para obtener el tipo en formato adecuado
function obtenerTipo($tipoId) {
    $tipoId = ucfirst(strtolower($tipoId));

    $tiposValidos = ['Vacuna', 'Virus', 'Animal', 'Planta', 'Elemental'];

    if (!in_array($tipoId, $tiposValidos)) {
        die("Tipo de digimon inválido. Tipo recibido: $tipoId");
    }

    return $tipoId;
}

// Realizar los combates
$victoriasUsuario = 0;
$victoriasRival = 0;

for ($i = 0; $i < 3; $i++) {
    $digimonUsuario = $digimonesUsuario[$i];
    if ($digimonUsuario === null) {
        die("El digimon del usuario no existe.");
    }
    $digimonRival = $digimonesRival[$i];
    if ($digimonRival === null) {
        die("El digimon del rival no existe.");
    }

    $poderUsuario = calcularPoderDigimon($digimonUsuario, obtenerTipo($digimonRival->tipo));
    $poderRival = calcularPoderDigimon($digimonRival, obtenerTipo($digimonUsuario->tipo));

    if ($poderUsuario > $poderRival) {
        $victoriasUsuario++;
    } else {
        $victoriasRival++;
    }
}

if ($victoriasUsuario >= 2) {
    $mensaje = "¡Has ganado la partida!";
    $usuariosController->actualizarEstadisticas($usuarioId, true);
} else {
    $mensaje = "¡Has perdido la partida!";
    $usuariosController->actualizarEstadisticas($usuarioId, false);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campo de Batalla - Digimones</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: #222; /* Fondo oscuro para un ambiente de batalla */
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background: linear-gradient(135deg, #1c1c1c, #444); /* Fondo con gradiente oscuro para el contenedor */
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
}

h1 {
    text-align: center;
    color: #ffcc00; /* Un color amarillo para resaltar el título */
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.6);
    font-size: 36px;
}

.battlefield {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #333; /* Fondo oscuro para la zona de batalla */
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.8);
    margin-top: 20px;
}

.player {
    width: 45%;
    text-align: center;
    background: #222; /* Fondo más oscuro para las secciones del jugador */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
}

.player h2 {
    margin-bottom: 15px;
    color: #fff;
    font-size: 22px;
}

.player img {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    border: 5px solid #ffcc00; /* Borde amarillo para los digimones */
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.6);
}

.digimon-name {
    margin-top: 10px;
    color: #ffcc00;
    font-size: 18px;
}

.digimon-info {
    margin-top: 10px;
    color: #fff;
}

.digimon-info p {
    font-size: 16px;
    margin: 5px 0;
}

.battle-info {
    width: 10%;
    text-align: center;
    color: #fff;
}

.battle-info h3 {
    margin-bottom: 10px;
    font-size: 2em;
    font-weight: bold;
    color: #ffcc00; /* Amarillo para destacar el "VS" */
}

.battle-info p {
    font-size: 1.2em;
    color: #fff;
}

.result {
    margin-top: 30px;
    text-align: center;
    font-size: 1.8em;
    font-weight: bold;
    color: #ffcc00;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
}

.button {
    display: block;
    margin: 30px auto;
    padding: 15px 25px;
    background-color: #28a745; /* Un verde para el botón de acción */
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 2px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.button:hover {
    background-color: #218838;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.6);
}

.button:active {
    background-color: #1e7e34;
    transform: scale(0.98);
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Campo de Batalla - Digimones</h1>
        <div class="battlefield">
            <!-- Jugador -->
            <div class="player">
                <h2>Jugador: <?php echo $usuarioNombre; ?></h2>
                <img src="ruta/del/digimon/usuario.jpg" alt="Digimon del Jugador">
                <div class="digimon-name"><?php echo $digimonesUsuario[$turnoJugador]->nombre; ?></div>
                <div class="digimon-info">
                    <p>Tipo: <?php echo obtenerTipo($digimonesUsuario[$turnoJugador]->tipo); ?></p>
                    <p>Poder: <?php echo calcularPoderDigimon($digimonesUsuario[0], obtenerTipo($digimonesRival[0]->tipo)); ?></p>
                </div>
            </div>

            <!-- Información de combate -->
            <div class="battle-info">
                <h3>VS</h3>
                <p>Resultado: <?php echo $mensaje; ?></p>
            </div>

            <!-- Rival -->
            <!-- Mostrar los datos del rival -->
<div class="player">
    <h2>Rival: <?php echo $rivalId; ?></h2>
    <img src="ruta/del/digimon/rival.jpg" alt="Digimon del Rival">
    <div class="digimon-name"><?php echo $digimonesRival[0]->nombre; ?></div>
    <div class="digimon-info">
        <p>Tipo: <?php echo obtenerTipo($digimonesRival[0]->tipo); ?></p>
        <p>Poder: <?php echo calcularPoderDigimon($digimonesRival[0], obtenerTipo($digimonesUsuario[0]->tipo)); ?></p>
    </div>
</div>
        </div>

        <div class="result">
            <?php echo $mensaje; ?>
        </div>

        <button class="button" onclick="location.reload()">Volver a jugar</button>
    </div>
</body>
</html>
