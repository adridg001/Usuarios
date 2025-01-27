<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$mensaje = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $conexion = db::conexion();
        // Obtener el nombre del Digimon para eliminar la carpeta de imágenes
        $sql = "SELECT nombre FROM digimones WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $digimon = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($digimon) {
            // Eliminar el Digimon de la base de datos
            $sql = "DELETE FROM digimones WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Eliminar la carpeta de imágenes del Digimon
            $carpeta = 'digimones/' . $digimon['nombre'];
            if (is_dir($carpeta)) {
                array_map('unlink', glob("$carpeta/*.*"));
                rmdir($carpeta);
            }

            $mensaje = 'Digimon eliminado exitosamente.';
        } else {
            $mensaje = 'Digimon no encontrado.';
        }
    } catch (PDOException $error) {
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
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Eliminar Digimon</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
            <a href="verDigimones.php" class="btn btn-primary">Volver a la lista de Digimones</a>
        <?php endif; ?>
    </div>
</body>
</html>