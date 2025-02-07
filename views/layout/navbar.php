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

// Calcular el total de partidas jugadas
$usuario->partidas_jugadas = $usuario->partidas_ganadas + $usuario->partidas_perdidas;

// Evitar división por cero
if ($usuario->partidas_jugadas > 0) {
    $porcentaje_victorias = round(($usuario->partidas_ganadas / $usuario->partidas_jugadas) * 100, 2);
    $porcentaje_derrotas = round(($usuario->partidas_perdidas / $usuario->partidas_jugadas) * 100, 2);
} else {
    $porcentaje_victorias = 0;
    $porcentaje_derrotas = 0;
}

  ?>
   <style>
    /* Estilo Unificado para Sidebar */
    #sidebarMenu {
      background: linear-gradient(135deg, #0a0a23, #1b1b3a);  /* Igual que el navbar */
      border-right: 3px solid #ffcc00;
      box-shadow: 4px 0 10px rgba(0, 0, 0, 0.7);
      color: #f0f0f0;
    }

    .user-info {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px;
      background-color: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      margin-bottom: 20px;
      color: #ffcc00;
      text-shadow: 1px 1px 3px #000;
    }

    .user-info .avatar img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 2px solid #ffcc00;
      object-fit: cover;
      margin-bottom: 10px;
    }

    .user-info .greeting h4 {
      margin: 5px 0;
      font-size: 1.1em;
      color: #ffcc00;
      font-weight: bold;
    }

    .user-info .stats p {
      margin: 5px 0;
      padding: 5px 10px;
      background-color: rgba(0, 0, 0, 0.3);
      border-radius: 6px;
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
      color: #f0f0f0;
      display: flex;
      justify-content: space-between;
    }

    .user-info .stats p span {
      font-weight: bold;
      color: #ffcc00;
    }

    .nav-links {
      list-style: none;
      padding: 0;
      margin-top: -24px;
    }

    .nav-links li {
      margin-bottom: 10px;
    }

    .nav-links a {
      display: block;
      padding: 8px 10px;
      background-color: rgba(255, 255, 255, 0.1);
      color: #ffcc00;
      text-decoration: none;
      border-radius: 6px;
      font-size: 0.9em;
      transition: all 0.3s ease;
      text-align: center;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .nav-links a:hover {
      background-color: #ff4500;
      color: #fff;
    }

    .nav-links .logout {
      background: linear-gradient(135deg, #ff6347, #ff4500);
      color: #fff;
      font-weight: bold;
    }

    .nav-links .logout:hover {
      background: linear-gradient(135deg, #ff6347, #ff4500);
      transform: scale(1.05);
    }
  </style>

  <div class="user-info">
    <div class="avatar">
      <!-- Imagen del avatar si está disponible -->
    </div>
    <div class="greeting">
      <h4>Usuario: <?= htmlspecialchars($nombreUsuario) ?></h4>
    </div>
    <div class="stats">
      <p>Partidas Jugadas: <span><?= $usuario->partidas_jugadas; ?></span></p>
      <p>Partidas Ganadas: <span><?= $usuario->partidas_ganadas; ?></span></p>
      <p>Partidas Perdidas: <span><?= $usuario->partidas_perdidas; ?></span></p>
      <p>Victorias: <span><?= $porcentaje_victorias; ?>%</span></p>
      <p>Derrotas: <span><?= $porcentaje_derrotas; ?>%</span></p>
    </div>
  </div>

  <ul class="nav-links">
    <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
  </ul>
</nav>