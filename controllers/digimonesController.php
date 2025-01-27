<?php
require_once __DIR__ . '/../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once __DIR__ . '/../models/DigimonModel.php'; // Asegúrate de que esta ruta es correcta

class DigimonesController {
    private $model;

    public function __construct() {
        $this->model = new DigimonModel();
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
}