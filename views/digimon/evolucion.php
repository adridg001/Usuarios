<?php
session_start();
require_once __DIR__ . '/../../config/db.php';  
require_once __DIR__ . '/../../controllers/digimonesController.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Digimon/Usuarios/login.php");
    exit();
}

$usuarioId = $_SESSION['usuario_id'];
$controladorDigimon = new DigimonesController();
$digimones = $controladorDigimon->listarPorUsuario($usuarioId);

$conexion = db::conexion();  
$sql = "SELECT digievoluciones_disponibles FROM usuarios WHERE id = :id"; 
$stmt = $conexion->prepare($sql);  
$stmt->bindParam(':id', $usuarioId, PDO::PARAM_INT);  
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$digievoluciones = $usuario ? $usuario['digievoluciones_disponibles'] : 0;

$digimonSeleccionadoId = null; // Inicializar variable para rastrear el Digimon seleccionado
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['action'])) {
        
        // Manejar la selección de un Digimon
        if (strpos($_POST['action'], 'seleccionar_') === 0) {
            $digimonSeleccionadoId = str_replace('seleccionar_', '', $_POST['action']);
            $evoluciones = obtenerEvolucionesPosibles($digimonSeleccionadoId, $conexion);

            // Mostrar mensaje si no hay evoluciones disponibles
            if (empty($evoluciones)) {
                $mensaje = "Este Digimon no tiene evoluciones disponibles.";
            }
        }
        
        // Manejar la evolución del Digimon
        if ($_POST['action'] === 'evolucionar' && isset($_POST['digimon_id']) && isset($_POST['evolucion_id']) && $digievoluciones > 0) {
            $digimonId = $_POST['digimon_id'];
            $evolucionId = $_POST['evolucion_id'];

            $sqlCheck = "SELECT COUNT(*) FROM digimones_usuario WHERE usuario_id = :usuario_id AND digimon_id = :evolucion_id";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmtCheck->bindParam(':evolucion_id', $evolucionId, PDO::PARAM_INT);
            $stmtCheck->execute();
            $yaExiste = $stmtCheck->fetchColumn();

            if ($yaExiste > 0) {
                $mensaje = "¡Ya has evolucionado a este Digimon antes! No puedes repetir la evolución.";
            } else {
                $sqlUpdate = "UPDATE digimones_usuario SET digimon_id = :evolucion_id WHERE usuario_id = :usuario_id AND digimon_id = :digimon_id";
                $stmtUpdate = $conexion->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':evolucion_id', $evolucionId, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':digimon_id', $digimonId, PDO::PARAM_INT);
                $stmtUpdate->execute();

                $nuevasDigievoluciones = $digievoluciones - 1;
                $sqlUpdateUsuario = "UPDATE usuarios SET digievoluciones_disponibles = :digievoluciones WHERE id = :id";
                $stmtUpdateUsuario = $conexion->prepare($sqlUpdateUsuario);
                $stmtUpdateUsuario->bindParam(':digievoluciones', $nuevasDigievoluciones, PDO::PARAM_INT);
                $stmtUpdateUsuario->bindParam(':id', $usuarioId, PDO::PARAM_INT);
                $stmtUpdateUsuario->execute();

                $_SESSION['digievoluciones'] = $nuevasDigievoluciones;
                $digievoluciones = $nuevasDigievoluciones;  // Actualizar la variable para mostrar en la página
                $mensaje = "¡Tu Digimon ha evolucionado con éxito!";
                $digimonSeleccionadoId = null;  // Reiniciar la selección para permitir seleccionar otro Digimon
            }
        }
    }
}

function obtenerEvolucionesPosibles($digimonId, $conexion) {
    $sqlTipoNivel = "SELECT tipo, nivel FROM digimones WHERE id = :digimon_id";
    $stmtTipoNivel = $conexion->prepare($sqlTipoNivel);
    $stmtTipoNivel->bindParam(':digimon_id', $digimonId, PDO::PARAM_INT);
    $stmtTipoNivel->execute();
    $datosDigimon = $stmtTipoNivel->fetch(PDO::FETCH_ASSOC);

    if ($datosDigimon) {
        $tipo = $datosDigimon['tipo'];
        $nivelActual = $datosDigimon['nivel'];

        $sql = "SELECT * FROM digimones WHERE nivel = :nivel_siguiente AND tipo = :tipo";
        $stmt = $conexion->prepare($sql);
        $nivelSiguiente = $nivelActual + 1;
        $stmt->bindParam(':nivel_siguiente', $nivelSiguiente, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return [];
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evolucionar Digimon</title>
</head>
<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    color: #333;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
.container {
    width: 80%;
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
h1 {
    text-align: center;
    font-size: 2.5em;
    margin-bottom: 20px;
    color: #333;
}
p {
    text-align: center;
    font-size: 1.2em;
    color: #555;
}
.digimon-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}
.digimon-item {
    background-color: #f9f9f9;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin: 10px;
    width: 200px;
    text-align: center;
    transition: all 0.3s ease;
}
.digimon-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.digimon-item img {
    max-width: 100%;
    border-radius: 8px;
}
.digimon-item h3 {
    font-size: 1.2em;
    margin-top: 10px;
    color: #333;
}
.digimon-item p {
    font-size: 1em;
    color: #666;
}
button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #f79c42;
    color: white;
    font-size: 1.2em;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
}
button:hover {
    background-color: #f27f23;
}
select {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    font-size: 1em;
    border: 2px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}
select:hover {
    border-color: #f79c42;
}

select:focus {
    outline: none;
    border-color: #f79c42;
}
p.mensaje {
    text-align: center;
    font-size: 1.2em;
    font-weight: bold;
    margin-top: 20px;
}
.mensaje.success {
    color: #28a745;
}
.mensaje.error {
    color: #dc3545;
}
a {
    display: inline-block;
    margin-top: 20px;
    text-align: center;
    text-decoration: none;
    color: #f79c42;
    font-size: 1.1em;
}
a:hover {
    color: #f27f23;
}
</style>
<body>
<div class="container">
    <h1>Selecciona un Digimon para Evolucionar</h1>
    <p>Digievoluciones disponibles: <?= $digievoluciones ?></p>

    <?php 
    if (!empty($mensaje)) {
        $claseMensaje = (strpos($mensaje, 'éxito') !== false) ? 'success' : 'error';
        echo "<p class='mensaje $claseMensaje'>$mensaje</p>";
    }
    ?>

    <form method="post" action="evolucion.php">
        <div class="digimon-list">
            <?php foreach ($digimones as $digimon): ?>
                <?php if ($digimon->nivel < 4): ?>
                    <div class="digimon-item">
                        <img src="/Digimon/Administracion/digimones/<?= htmlspecialchars($digimon->nombre) ?>/<?= htmlspecialchars($digimon->imagen) ?>" >
                        <h3><?= $digimon->nombre ?></h3>
                        <p>Nivel <?= $digimon->nivel ?></p>

                        <button type="submit" name="action" value="seleccionar_<?= $digimon->id ?>">Seleccionar para Evolucionar</button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?php 
        if ($digimonSeleccionadoId): 
            $evoluciones = obtenerEvolucionesPosibles($digimonSeleccionadoId, $conexion);
        ?>
            <input type="hidden" name="digimon_id" value="<?= $digimonSeleccionadoId ?>">
            <?php if (!empty($evoluciones)): ?>
                <h3>Selecciona la evolución:</h3>
                <select name="evolucion_id" required>
                    <?php foreach ($evoluciones as $evolucion): ?>
                        <option value="<?= $evolucion['id'] ?>">
                            <?= $evolucion['nombre'] ?> (Nivel <?= $evolucion['nivel'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="action" value="evolucionar">Evolucionar</button>
            <?php else: ?>
                <p class="mensaje error">Este Digimon no tiene evoluciones disponibles.</p>
            <?php endif; ?>
        <?php endif; ?>
    </form>

    <a href="../../index.php">Volver</a>
</div>


</body>
</html>