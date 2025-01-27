<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active disabled" aria-current="page" href="#">
          <span data-feather="home"></span>
          Dashboard
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="far fa-user"></i> Usuarios</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?tabla=user&accion=crear">Añadir</a></li>
          <li><a class="dropdown-item" href="index.php?tabla=user&accion=listar">Listar </a></li>
          <li><a class="dropdown-item" href="index.php?tabla=user&accion=buscar">Buscar </a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="far fa-user"></i> Clientes</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?tabla=client&accion=crear">Añadir</a></li>
          <li><a class="dropdown-item" href="index.php?tabla=client&accion=listar">Listar </a></li>
          <li><a class="dropdown-item" href="index.php?tabla=client&accion=buscar">Buscar </a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-project-diagram"></i> Proyectos</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?tabla=project&accion=crear">Añadir</a></li>
          <li><a class="dropdown-item" href="index.php?tabla=project&accion=buscar">Buscar </a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-tasks"></i> Mis Tareas</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?tabla=task&accion=buscar">Buscar </a></li>
        </ul>
      </li>
    </ul>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
      <span>MAS OPCIONES</span>
      <a class="link-secondary" href="#" aria-label="Add a new report">
        <span data-feather="plus-circle"></span>
      </a>
    </h6>
    <ul class="nav flex-column mb-2">
      <li class="nav-item">
        <a class="nav-link" href="#">
          <span data-feather="file-text"></span>
          Ejemplo Mas Opciones
        </a>
      </li>
    </ul>
  </div>
</nav>