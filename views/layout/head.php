<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>APP MVC Y PDO</title>

  <!-- Bootstrap core CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="assets/css/dashboard.css" rel="stylesheet">
  <link href="assets/css/404.css" rel="stylesheet">

  <style>
    /* Estilo Unificado para Navbar */
    header.navbar {
      background: linear-gradient(135deg, #0a0a23, #1b1b3a);  /* Degradado oscuro */
      border-bottom: 3px solid #ffcc00;  /* Borde naranja */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
    }

    .texto_enca {
      color: #ffcc00;  /* Amarillo dorado */
      font-weight: bold;
      font-size: 1.3rem;
      text-shadow: 2px 2px 4px #000;
    }

    .texto_enca-icon {
      background-color: #ffcc00;
    }

    .nav-link {
      color: #f0f0f0 !important;
      font-weight: bold;
    }

    .nav-link.disabled {
      color: #ffcc00 !important;  /* Naranja brillante para el texto del usuario conectado */
      text-shadow: 1px 1px 3px #000;
    }
  </style>
</head>
<body>

<header class="navbar navbar-dark sticky-top flex-md-nowrap p-0 shadow">
  <a class="texto_enca col-md-3 col-lg-2 me-0 px-3" href="#">Zona Digimon</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3 disabled">Usuario Conectado: <?= htmlspecialchars($_SESSION["usuario"]->nombre); ?></a>
    </div>
  </div>
</header>

</body>
</html>
