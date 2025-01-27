<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pagina de Inicio</h1>
  </div>
  <div id="contenido">
    CONTENIDO DE DIGIMONES
    <table class="table">
      <thead>
        <tr>
          <th>Acción</th>
          <th>Descripción</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/views/user/list.php'">Listar Usuarios</button></td>
          <td>Ver la lista de todos los usuarios</td>
        </tr>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/views/user/create.php'">Dar de alta un Usuario</button></td>
          <td>Dar de alta a un usuario</td>
        </tr>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/views/digimon/create.php'">Dar de alta un Digimon</button></td>
          <td>Registrar un nuevo Digimon</td>
        </tr>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/views/digimon/show.php'">Ver Digimones</button></td>
          <td>Ver la lista de todos los Digimones</td>
        </tr>
      </tbody>
    </table>
  </div>
</main>