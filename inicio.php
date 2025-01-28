<?php

// Verifica si el usuario está logueado
if (!isset($_SESSION["usuario"])) {
    header("location:login.php"); 
    exit();
}

// Obtén el nombre del usuario desde la sesión
$nombreUsuario = $_SESSION["usuario"]->nombre;
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pagina de Inicio, Bienvenido <?= htmlspecialchars($nombreUsuario) ?></h1>
  </div>
  <div id="contenido">
    <P>PREPARA TUS COMBATES</P>
    <table class="table">
      <thead>
        <tr>
          <th>Acción</th>
          <th>Descripción</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/digimon/show.php'">Ver mis Digimon</button></td>
          <td>Ver la lista de todos mis Digimon</td>
        </tr>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/equipo/organize.php'">Organizar mi equipo</button></td>
          <td>Organizar mi equipo de Digimon</td>
        </tr>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/juego/play.php'">Jugar partida</button></td>
          <td>Iniciar una nueva partida</td>
        </tr>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/digimon/evolucion.php'">Digievolucionar</button></td>
          <td>Digievolucionar un Digimon</td>
        </tr>
      </tbody>
    </table>
  </div>
</main>