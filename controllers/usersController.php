<?php
require_once __DIR__ . '/../models/userModel.php'; // Ajusta la ruta según la estructura de tu proyecto

class UsersController { 
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function crear (array $arrayUser):void {
        $id=$this->model->insert ($arrayUser);
        ($id==null)?header("location:index.php?tabla=user&accion=crear&error=true&id={$id}"): header("location:index.php?tabla=user&accion=ver&id=".$id);
        exit ();
    }

    public function ver(int $id): ?stdClass {
        $usuario = $this->model->read($id);  // Obtener el usuario de la base de datos
    
        // Si el usuario existe, calculamos las partidas jugadas sumando victorias y derrotas
        if ($usuario) {
            $usuario->partidas_jugadas = $usuario->partidas_ganadas + $usuario->partidas_perdidas;
        }
    
        return $usuario;
    }
    

    public function listar (){
        return $this->model->readAll ();
   }

   public function borrar(int $id ): void
   {   
       $usuario= $this->ver($id);
       $borrado = $this->model->delete($id);
       $mensaje = $borrado ? 'Usuario eliminado exitosamente.' : 'Error al eliminar el usuario.';
       $redireccion = "location:/Digimon/views/user/list.php?mensaje=" . urlencode($mensaje);
       header($redireccion);
       exit();
   }

 // Este método actualiza las estadísticas del usuario (ganadas o perdidas)
 public function actualizarEstadisticas($usuarioId, $victoria) {
    $conexion = db::conexion();  // Conexión a la base de datos

    if ($victoria) {
        // Si el usuario ganó, aumentamos las partidas ganadas
        $sql = "UPDATE usuarios SET partidas_ganadas = partidas_ganadas + 1 WHERE id = :usuario_id";
    } else {
        // Si el usuario perdió, aumentamos las partidas perdidas
        $sql = "UPDATE usuarios SET partidas_perdidas = partidas_perdidas + 1 WHERE id = :usuario_id";
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->execute();
}

public function actualizarEvolucion($usuarioId) {
    try {
        $conexion = db::conexion();

        // Obtener el número de partidas ganadas y digievoluciones disponibles
        $sql = "SELECT partidas_ganadas, digievoluciones_disponibles FROM usuarios WHERE id = :usuario_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);

        if ($usuario && $usuario->partidas_ganadas > 0) {
            // Calcular cuántas digievoluciones debería tener el usuario según sus victorias
            $digievolucionesEsperadas = intdiv($usuario->partidas_ganadas, 10);

            // Verificar si el usuario ya tiene las digievoluciones correspondientes a sus victorias
            if ($usuario->digievoluciones_disponibles < $digievolucionesEsperadas) {
                // Sumar 1 a las digievoluciones disponibles
                $sqlUpdate = "UPDATE usuarios SET digievoluciones_disponibles = digievoluciones_disponibles + 1 WHERE id = :usuario_id";
                $stmtUpdate = $conexion->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
                $stmtUpdate->execute();

                return "¡Has ganado una nueva Digievolución!";
            }
        }
        return null;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}



}