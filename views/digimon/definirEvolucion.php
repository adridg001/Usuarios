<?php
require_once '../../config/db.php'; 
require_once '../../controllers/digimonesController.php'; 

$mensaje = '';
$digimon = null;
$evoluciones = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $controlador = new DigimonesController();
    $digimon = $controlador->ver($id);
    $evoluciones = $controlador->listar(); 

  
    $evoluciones = array_filter($evoluciones, function($evolucion) use ($digimon) {
        return $evolucion->nivel == $digimon->nivel + 1 && $evolucion->tipo == $digimon->tipo;
    });
} else {
    $mensaje = 'ID de Digimon no proporcionado o no válido.';
}

if (isset($_POST["id"])) {
    $id = $_POST['id'];
    $evo_id = $_POST['evo_id'];
  
    if (empty($id) || empty($evo_id)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        try {
            $conexion = db::conexion();
            $sql = "UPDATE digimones SET evo_id = :evo_id WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':evo_id', $evo_id);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $mensaje = 'Evolución definida exitosamente.';

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
    <title>Definir Evolución</title>
    <link href="/Digimon/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Definir Evolución</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
            <a href="/Digimon/views/digimon/show.php" class="btn btn-primary">Volver a la lista de Digimones</a>
        <?php endif; ?>
        <?php if ($digimon): ?>
            <div class="mb-3">
                <h2><?= htmlspecialchars($digimon->nombre) ?></h2>
                <img src="/Digimon/digimones/<?= htmlspecialchars($digimon->nombre) ?>/<?= htmlspecialchars($digimon->imagen) ?>" alt="<?= htmlspecialchars($digimon->nombre) ?>" width="100">
            </div>
            <form method="post" action="definirEvolucion.php?id=<?= htmlspecialchars($digimon->id) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($digimon->id) ?>">
                <div class="mb-3">
                    <label for="evo_id" class="form-label">Evolucionar a</label>
                    <select class="form-control" id="evo_id" name="evo_id" required>
                        <?php foreach ($evoluciones as $evolucion): ?>
                            <option value="<?= htmlspecialchars($evolucion->id) ?>"><?= htmlspecialchars($evolucion->nombre) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Definir Evolución</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>