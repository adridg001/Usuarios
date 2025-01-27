<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto

try {
    $conexion = db::conexion();
    $sql = "SELECT id, nombre, partidas_ganadas, partidas_perdidas, (partidas_ganadas + partidas_perdidas) AS partidas_totales FROM usuarios";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuarios</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Lista de Usuarios</h1>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($_GET['mensaje']); ?></div>
        <?php endif; ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Partidas Ganadas</th>
                    <th>Partidas Perdidas</th>
                    <th>Partidas Totales</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($usuarios && $stmt->rowCount() > 0): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['partidas_ganadas']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['partidas_perdidas']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['partidas_totales']); ?></td>
                            <td>
                                <a href="/Digimon/views/user/show.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary btn-sm">Ver Usuario</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No se encontraron usuarios.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="../../index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>