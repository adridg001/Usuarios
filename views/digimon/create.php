<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $ataque = $_POST['ataque'];
    $defensa = $_POST['defensa'];
    $tipo = $_POST['tipo'];
    $nivel = $_POST['nivel'];
    $imagen = $_FILES['imagen'];
    $imagenVictoria = $_FILES['imagen_victoria'];
    $imagenDerrota = $_FILES['imagen_derrota'];

    if (empty($nombre) || empty($ataque) || empty($defensa) || empty($tipo) || empty($nivel) || empty($imagen['name']) || empty($imagenVictoria['name']) || empty($imagenDerrota['name'])) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        try {
            $conexion = db::conexion();
            
            // Inserción del Digimon en la base de datos
            $sql = "INSERT INTO digimones (nombre, ataque, defensa, tipo, nivel, imagen, imagen_victoria, imagen_derrota) VALUES (:nombre, :ataque, :defensa, :tipo, :nivel, :imagen, :imagen_victoria, :imagen_derrota)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ataque', $ataque);
            $stmt->bindParam(':defensa', $defensa);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':nivel', $nivel);
            $stmt->bindParam(':imagen', $imagen['name']);
            $stmt->bindParam(':imagen_victoria', $imagenVictoria['name']);
            $stmt->bindParam(':imagen_derrota', $imagenDerrota['name']);
            $stmt->execute();

            // Mover las imágenes a la carpeta del Digimon
            $carpeta = '../../digimones/' . $nombre;
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            move_uploaded_file($imagen['tmp_name'], $carpeta . '/' . $imagen['name']);
            move_uploaded_file($imagenVictoria['tmp_name'], $carpeta . '/' . $imagenVictoria['name']);
            move_uploaded_file($imagenDerrota['tmp_name'], $carpeta . '/' . $imagenDerrota['name']);

            // Redirigir a la página de lista de Digimones con un mensaje de éxito
            $mensaje = "Digimon creado exitosamente.";
            header("Location: show.php?mensaje=" . urlencode($mensaje));
            exit();

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
    <title>Alta Digimon</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-image: url('../../fondo_CrearDigimones.jpg');
        background-size: 100% 100%; 
        background-position: center; 
        background-repeat: no-repeat; 
        min-height: 100vh; 
        margin: 0; 
        padding: 0; 
        overflow-x: hidden; 
    }

    .container {
        background-color: rgba(255, 255, 255, 0.8); 
        border-radius: 15px; 
        padding: 20px; 
        margin: 10px auto; 
        max-width: 1200px;
    }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Registrar Nuevo Digimon</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <form method="post" action="create.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="ataque" class="form-label">Ataque</label>
                <input type="number" class="form-control" id="ataque" name="ataque" required>
            </div>
            <div class="mb-3">
                <label for="defensa" class="form-label">Defensa</label>
                <input type="number" class="form-control" id="defensa" name="defensa" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="Vacuna">Vacuna</option>
                    <option value="Virus">Virus</option>
                    <option value="Animal">Animal</option>
                    <option value="Planta">Planta</option>
                    <option value="Elemental">Elemental</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nivel" class="form-label">Nivel</label>
                <select class="form-control" id="nivel" name="nivel" required>
                    <option value="1">1: Bebe</option>
                    <option value="2">2: Infantil</option>
                    <option value="3">3: Adulto</option>
                    <option value="4">4: Perfecto</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen Principal</label>
                <input type="file" class="form-control" id="imagen" name="imagen" required>
            </div>
            <div class="mb-3">
                <label for="imagen_victoria" class="form-label">Imagen de Victoria</label>
                <input type="file" class="form-control" id="imagen_victoria" name="imagen_victoria" required>
            </div>
            <div class="mb-3">
                <label for="imagen_derrota" class="form-label">Imagen de Derrota</label>
                <input type="file" class="form-control" id="imagen_derrota" name="imagen_derrota" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        <a href="../../index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>