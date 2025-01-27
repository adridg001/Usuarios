<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$mensaje = '';
$digimon = null;
$modificado = false;

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
            $mensaje = 'Digimon no encontrado.';
        }
    } catch (PDOException $error) {
        $mensaje = 'Error: ' . $error->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $ataque = $_POST['ataque'];
    $defensa = $_POST['defensa'];
    $tipo = $_POST['tipo'];
    $nivel = $_POST['nivel'];

    if (empty($nombre) || empty($ataque) || empty($defensa) || empty($tipo) || empty($nivel)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        try {
            $conexion = db::conexion();
            $sql = "UPDATE digimones SET nombre = :nombre, ataque = :ataque, defensa = :defensa, tipo = :tipo, nivel = :nivel, evo_id = NULL WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ataque', $ataque);
            $stmt->bindParam(':defensa', $defensa);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':nivel', $nivel);
            $stmt->execute();

            $mensaje = 'Digimon modificado exitosamente.';
            $modificado = true;
        } catch (PDOException $error) {
            $mensaje = 'Error: ' . $error->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Digimon</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Modificar Digimon</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
            <?php if ($modificado): ?>
                <a href="verDigimones.php" class="btn btn-primary">Volver a la lista de Digimones</a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($digimon && !$modificado): ?>
            <form method="post" action="modificarDigimon.php?id=<?php echo htmlspecialchars($digimon['id']); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($digimon['id']); ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($digimon['nombre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="ataque" class="form-label">Ataque</label>
                    <input type="number" class="form-control" id="ataque" name="ataque" value="<?php echo htmlspecialchars($digimon['ataque']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="defensa" class="form-label">Defensa</label>
                    <input type="number" class="form-control" id="defensa" name="defensa" value="<?php echo htmlspecialchars($digimon['defensa']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="Vacuna" <?php echo $digimon['tipo'] == 'Vacuna' ? 'selected' : ''; ?>>Vacuna</option>
                        <option value="Virus" <?php echo $digimon['tipo'] == 'Virus' ? 'selected' : ''; ?>>Virus</option>
                        <option value="Animal" <?php echo $digimon['tipo'] == 'Animal' ? 'selected' : ''; ?>>Animal</option>
                        <option value="Planta" <?php echo $digimon['tipo'] == 'Planta' ? 'selected' : ''; ?>>Planta</option>
                        <option value="Elemental" <?php echo $digimon['tipo'] == 'Elemental' ? 'selected' : ''; ?>>Elemental</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nivel" class="form-label">Nivel</label>
                    <select class="form-control" id="nivel" name="nivel" required>
                        <option value="1" <?php echo $digimon['nivel'] == 1 ? 'selected' : ''; ?>>1: Bebe</option>
                        <option value="2" <?php echo $digimon['nivel'] == 2 ? 'selected' : ''; ?>>2: Infantil</option>
                        <option value="3" <?php echo $digimon['nivel'] == 3 ? 'selected' : ''; ?>>3: Adulto</option>
                        <option value="4" <?php echo $digimon['nivel'] == 4 ? 'selected' : ''; ?>>4: Perfecto</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Modificar</button>
            </form>
        <?php elseif (!$digimon): ?>
            <p>No se encontró el Digimon.</p>
        <?php endif; ?>
    </div>
</body>
</html>