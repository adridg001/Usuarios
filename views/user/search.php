<?php
require_once "controllers/usersController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$mostrarDatos = false;
$controlador = new UsersController();
$usuario = "";
$campo = "";
$modo = "";

if (isset($_REQUEST["evento"])) {
    $mostrarDatos = true;
    switch ($_REQUEST["evento"]) {
        case "todos":
            $users = $controlador->listar(comprobarSiEsBorrable: true);
            $mostrarDatos = true;
            break;
        case "filtrar":
            $campo = ($_REQUEST["campo"]) ?? "usuario";
            $metodo = ($_REQUEST["modo"]) ?? "contiene";
            $texto = ($_REQUEST["busqueda"]) ?? "";
            //es borrable Parametro con nombre
            $users = $controlador->buscar($campo, $metodo, $texto, comprobarSiEsBorrable: true); //solo añadimos esto
            break;
        case "borrar":
            $visibilidad = "visibility";
            $mostrarDatos = true;
            $clase = "alert alert-success";
            //Mejorar y poner el nombre/usuario
            $mensaje = "El usuario con id: {$_REQUEST['id']} Borrado correctamente";
            if (isset($_REQUEST["error"])) {
                $clase = "alert alert-danger ";
                $mensaje = "ERROR!!! No se ha podido borrar el usuario con id: {$_REQUEST['id']}";
            }
            break;
    }
} ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Buscar Usuario</h1>
    </div>
    <div id="contenido">
        <div class="<?= $clase ?>" <?= $visibilidad ?> role="alert">
            <?= $mensaje ?>
        </div>
        <div>
            <form action="index.php?tabla=user&accion=buscar&evento=filtrar" method="POST">
                <div class="form-group">
                    <label for="usuario">Buscar Usuario</label><br>
                    <select class="form-select" aria-label="Default select example" id="campo" name="campo">
                        <option value="id">ID</option>
                        <option value="usuario" selected>Usuario</option>
                        <option value="name">Nombre</option>
                        <option value="email">Email</option>
                    </select>
                    <select class="form-select" aria-label="Default select example" id="modo" name="modo">
                        <option value="empieza" selected>Empieza Por</option>
                        <option value="acaba">Acaba En</option>
                        <option value="contiene">Contiene</option>
                        <option value="igual">Igual A</option>
                    </select>
                    <input type="text" required class="form-control" id="busqueda" name="busqueda" value="<?= $usuario ?>" placeholder="Buscar por Usuario">
                </div>
                <button type="submit" class="btn btn-success" name="Filtrar"><i class="fas fa-search"></i> Buscar</button>
            </form>
            <!-- Este formulario es para ver todos los datos    -->
            <form action="index.php?tabla=user&accion=buscar&evento=todos" method="POST">
                <button type="submit" class="btn btn-info" name="Todos"><i class="fas fa-list"></i> Listar</button>
            </form>
        </div>
        <?php
        if ($mostrarDatos) {
        ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) :
                        $id = $user->id;
                    ?>
                        <tr>
                            <th scope="row"><?= $user->id ?></th>
                            <td><?= $user->usuario ?></td>
                            <td><?= $user->name ?></td>
                            <td><?= $user->email ?></td>
                            <td>
                                <?php
                                $disable = "";
                                $ruta = "index.php?tabla=user&accion=borrar&id={$id}";
                                if (isset($user->esBorrable) && $user->esBorrable == false) {
                                    $disable = "disabled";
                                    $ruta = "#";
                                }
                                ?>
                                <a class="btn btn-danger <?= $disable ?>" href="<?= $ruta ?>"><i class="fa fa-trash"></i> Borrar</a>
                            </td>
                            <td><a class="btn btn-success" href="index.php?tabla=user&accion=editar&id=<?= $id ?>"><i class="fa fa-pencil"></i>Editar</a></td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        <?php
        }
        ?>
    </div>
</main>