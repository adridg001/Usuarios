<?php
require_once '../../controllers/digimonesController.php'; // Asegúrate de que esta ruta es correcta

$mensaje = '';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $controlador = new DigimonesController();
    try {
        $borrado = $controlador->borrar($id);
        $mensaje = $borrado ? 'Digimon eliminado exitosamente.' : 'Error al eliminar el Digimon.';
    } catch (Exception $error) {
        $mensaje = 'Error: ' . $error->getMessage();
    }
} else {
    $mensaje = 'ID de Digimon no proporcionado.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Digimon</title>
    <link href="/Digimon/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Eliminar Digimon</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
            <a href="/Digimon/views/digimon/verDigimones.php" class="btn btn-primary">Volver a la lista de Digimones</a>
        <?php endif; ?>
    </div>
</body>
</html>