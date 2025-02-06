<?php
require_once __DIR__ . '/../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once __DIR__ . '/../models/DigimonModel.php'; // Asegúrate de que esta ruta es correcta

class DigimonesController {
    private $model;

    public function __construct() {
        $this->model = new DigimonModel();
    }

// Método para obtener los Digimones seleccionados por el usuario en su equipo
public function obtenerEquipoPorUsuario($usuarioId) {
    try {
        // Conexión a la base de datos
        $conexion = db::conexion();
        
        // Consulta para obtener los Digimones en el equipo del usuario
        $sql = "SELECT d.* FROM digimones d
                INNER JOIN equipo e ON d.id = e.digimon_id
                WHERE e.usuario_id = :usuario_id";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Método para asignar un Digimon por defecto a un usuario (nivel 1)
public function asignarDigimonesPorDefecto(int $usuarioId): void {
    try {
        // Obtener Digimones de nivel 1 disponibles
        $conexion = db::conexion();
        $sql = "SELECT * FROM digimones WHERE nivel = 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $digimonesNivel1 = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Si hay menos de 3, asignar más Digimones
        $digimonesAsignados = $this->obtenerEquipoPorUsuario($usuarioId);
        $digimonesFaltantes = 3 - count($digimonesAsignados);

        for ($i = 0; $i < $digimonesFaltantes; $i++) {
            // Asignar un Digimon aleatorio de nivel 1
            $digimonAleatorio = $digimonesNivel1[array_rand($digimonesNivel1)];

            // Insertar en la tabla 'digimones_usuario'
            $sql = "INSERT INTO digimones_usuario (usuario_id, digimon_id, seleccionado) 
                    VALUES (:usuario_id, :digimon_id, 0)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId);
            $stmt->bindParam(':digimon_id', $digimonAleatorio->id);
            $stmt->execute();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
    
    public function listarPorUsuario(int $usuarioId): array {
        return $this->model->readByUsuario($usuarioId);
    }
    public function crear(array $arrayDigimon): void {
        try {
            $id = $this->model->insert($arrayDigimon);
            if ($id == null) {
                header("location:../views/digimon/create.php?error=true");
            } else {
                header("location:../views/digimon/show.php?id=" . $id);
            }
            exit();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
        }
    }

    public function ver(int $id): ?stdClass {
        return $this->model->read($id);
    }

    public function listar(): array {
        return $this->model->readAll();
    }

    public function actualizar(int $id, array $arrayDigimon): void {
        try {
            $actualizado = $this->model->update($id, $arrayDigimon);
            if ($actualizado) {
                header("location:../views/digimon/show.php?id=" . $id);
            } else {
                header("location:../views/digimon/edit.php?id=" . $id . "&error=true");
            }
            exit();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
        }
    }

    public function borrar(int $id): void {
        try {
            $borrado = $this->model->delete($id);
            $mensaje = $borrado ? 'Digimon eliminado exitosamente.' : 'Error al eliminar el Digimon.';
            $redireccion = "location:../digimon/show.php?mensaje=" . urlencode($mensaje);
            header($redireccion);
            exit();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
        }
    }

 // Obtener un Digimon de nivel 1 que no esté en la lista del usuario
    public function obtenerDigimonPartida($usuarioId) {
        $conexion = db::conexion();
        $sql = "
            SELECT * FROM digimones
            WHERE nivel = 1 
            AND id NOT IN (SELECT digimon_id FROM digimones_usuario WHERE usuario_id = :usuario_id)
            ORDER BY RAND() LIMIT 1
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ); // Devuelve un digimon de nivel 1 que no sea del usuario
    }

    // Agregar un digimon a la lista de un usuario
    public function agregarDigimonAUsuario($usuarioId, $digimonId) {
        $conexion = db::conexion();
        $sql = "INSERT INTO digimones_usuario (usuario_id, digimon_id) VALUES (:usuario_id, :digimon_id)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':digimon_id', $digimonId);
        return $stmt->execute(); // Retorna verdadero si la inserción es exitosa
    }

}