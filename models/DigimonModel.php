<?php
require_once __DIR__ . '/../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto

class DigimonModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function insert(array $digimon): ?int {
        try {
            $sql = "INSERT INTO digimones (nombre, ataque, defensa, tipo, nivel, imagen, imagen_victoria, imagen_derrota) VALUES (:nombre, :ataque, :defensa, :tipo, :nivel, :imagen, :imagen_victoria, :imagen_derrota);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":nombre" => $digimon["nombre"],
                ":ataque" => $digimon["ataque"],
                ":defensa" => $digimon["defensa"],
                ":tipo" => $digimon["tipo"],
                ":nivel" => $digimon["nivel"],
                ":imagen" => $digimon["imagen"],
                ":imagen_victoria" => $digimon["imagen_victoria"],
                ":imagen_derrota" => $digimon["imagen_derrota"]
            ];
            $resultado = $sentencia->execute($arrayDatos);
            return ($resultado == true) ? $this->conexion->lastInsertId() : null;
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return null;
        }
    }

    public function read(int $id): ?stdClass {
        try {
            $sql = "SELECT * FROM digimones WHERE id = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return null;
        }
    }

    public function readByUsuario(int $usuarioId): array {
        try {
            $sql = "SELECT d.* FROM digimones d
                    JOIN DIGIMONES_USUARIO du ON d.id = du.digimon_id
                    WHERE du.usuario_id = :usuario_id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return [];
        }
    }
    public function readAll(): array {
        try {
            $sql = "SELECT * FROM digimones";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return [];
        }
    }

    public function update(int $id, array $digimon): bool {
        try {
            $sql = "UPDATE digimones SET nombre = :nombre, ataque = :ataque, defensa = :defensa, tipo = :tipo, nivel = :nivel, imagen = :imagen, imagen_victoria = :imagen_victoria, imagen_derrota = :imagen_derrota WHERE id = :id";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":nombre" => $digimon["nombre"],
                ":ataque" => $digimon["ataque"],
                ":defensa" => $digimon["defensa"],
                ":tipo" => $digimon["tipo"],
                ":nivel" => $digimon["nivel"],
                ":imagen" => $digimon["imagen"],
                ":imagen_victoria" => $digimon["imagen_victoria"],
                ":imagen_derrota" => $digimon["imagen_derrota"],
                ":id" => $id
            ];
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return false;
        }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM digimones WHERE id = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
            return $sentencia->execute();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return false;
        }
    }
}