<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <?php
  require_once __DIR__ . '/../../controllers/usersController.php';
  // Verifica si el usuario está logueado
if (!isset($_SESSION["usuario"])) {
  header("location:login.php"); 
  exit();
}
$usuarioId = $_SESSION['usuario_id']; 
$nombreUsuario = $_SESSION["usuario"]->nombre;
$controladorUsuario = new UsersController();
$usuario = $controladorUsuario->ver($usuarioId); 

  ?>
  <style>
   /* Contenedor general del usuario */
.user-info {
  display: flex;
  flex-direction: column;  /* Apila elementos verticalmente */
  align-items: center;     /* Centra los elementos horizontalmente */
  padding: 10px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
  text-align: center;      /* Asegura que todo esté centrado en el contenedor */
}

/* Estilo del avatar */
.user-info .avatar img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 2px solid #f79c42;
  object-fit: cover;
  margin-bottom: 10px;
}

/* Nombre del usuario */
.user-info .greeting h4 {
  margin: 5px 0;
  font-size: 1.1em;
  color: #333;
  font-weight: bold;
}

/* Estilo para las estadísticas */
.user-info .stats {
  margin-top: 10px;
  width: 100%;
}

.user-info .stats p {
  margin: 5px 0;
  font-size: 0.9em;
  color: #555;
  display: flex;
  justify-content: space-between;  /* Alinea el texto a la izquierda y los números a la derecha */
  padding: 5px 10px;
  background-color: #f4f7fc;
  border-radius: 6px;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
}

.user-info .stats p span {
  font-weight: bold;
  color: #333;
}


/* Enlaces del menú compactos */
.nav-links {
  list-style: none;
  padding: 0;
}

.nav-links li {
  margin-bottom: 10px;
}

.nav-links a {
  display: block;
  padding: 8px 10px;
  background-color: #ffffff;
  color: #333;
  text-decoration: none;
  border-radius: 6px;
  font-size: 0.9em;
  transition: all 0.2s ease;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  text-align: center;  /* Centrar texto para sidebar estrecho */
}

.nav-links a:hover {
  background-color: #f79c42;
  color: #fff;
}

/* Estilo especial para el botón de cerrar sesión */
.nav-links .logout {
  background-color: #dc3545;
  color: #fff;
}

.nav-links .logout:hover {
  background-color: #c82333;
}

  </style>
  <div class="user-info">
  <div class="avatar">
    <!-- Aquí puedes poner la imagen del avatar si la tienes -->
    <!-- <img src="/Digimon/Usuarios/usuarios/" alt="Avatar de <?= htmlspecialchars($nombreUsuario) ?>"> -->
  </div>

  <div class="greeting">
    <h4>Usuario: <?= htmlspecialchars($nombreUsuario) ?></h4>
  </div>

  <div class="stats">
  <p>Partidas Jugadas: <span><?= $usuario->partidas_jugadas = $usuario->partidas_ganadas + $usuario->partidas_perdidas;?></span></p>
    <p>Partidas Ganadas: <span><?= $usuario->partidas_ganadas; ?></span></p>
    <p>Partidas Perdidas: <span><?= $usuario->partidas_perdidas; ?></span></p>
  </div>
</div>
  <ul class="nav-links">
    <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
  </ul>
</nav>