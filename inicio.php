<?php

// Verifica si el usuario está logueado
if (!isset($_SESSION["usuario"])) {
    header("location:login.php"); 
    exit();
}

// Obtén el nombre del usuario desde la sesión
$nombreUsuario = $_SESSION["usuario"]->nombre;
?>

<style>
   body {
            background: linear-gradient(135deg, #0a0a23, #1b1b3a); /* Fondo oscuro con un degradado */
            color: #f0f0f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 4%;
        }
        h1.h2 {
            color: #ffcc00;
            text-shadow: 2px 2px 5px #000;
            font-weight: bold;
        }
        table.table {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        table.table th {
            background-color: #222;
            color: #ffcc00;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #ffcc00;
        }

        table.table td {
            background-color: rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        table.table tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transition: background-color 0.3s ease;
        }

  .btn {
            border: none;
            border-radius: 30px;
            padding: 12px 15px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            text-shadow: 1px 1px 2px #000;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff4500, #ff6347);
            box-shadow: 0 4px 8px rgba(255, 69, 0, 0.5);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff6347, #ff4500);
            box-shadow: 0 6px 12px rgba(255, 69, 0, 0.7);
            transform: scale(1.05);
        }

        /* Íconos animados */
        .fa-bounce, .fa-beat, .fa-fade {
            animation-duration: 1.5s;
            animation-iteration-count: infinite;
        }

        /* Efectos en los botones */
        button i {
            margin-right: 8px;
        }
        /* Responsive Design para pantallas pequeñas */
        @media (max-width: 768px) {
            h1.h2 {
                font-size: 1.5rem;
            }

            #contenido p {
                font-size: 1.2rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
        }
</style>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pagina de Inicio, Bienvenido <?= htmlspecialchars($nombreUsuario) ?></h1>
  </div>
  <div id="contenido">
    <p>Embárcate en una aventura épica, llena de rivales legendarios. Prepara tus digimon!</p>
    <table class="table">
      <thead>
        <tr>
          <th>Acción</th>
          <th>Descripción</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/digimon/show.php'">
          <i class="fa-solid fa-eye fa-bounce"></i>  Ver mis Digimon
          </button>
        </td>
          <td>Ver la lista de todos mis Digimon</td>
        </tr>
        <tr>
          <td>
    <button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/digimon/equipo.php'">
    <i class="fa-solid fa-user fa-bounce"></i>  Organizar mi equipo
    </button>
      </td>
          <td>Organizar mi equipo de Digimon</td>
        </tr>
        <tr>
          <td><button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/juego/jugar.php'">
          <i class="fa-solid fa-fire fa-beat"></i> Jugar partida
          </button>
        </td>
          <td>Iniciar una nueva partida</td>
        </tr>
        <tr>
          <td>
            <button class="btn btn-primary" onclick="location.href='/Digimon/Usuarios/views/digimon/evolucion.php'">
            <i class="fa-solid fa-dragon fa-fade"></i>  Digievolucionar
            </button>
          </td>
          <td>Digievolucionar un Digimon</td>
        </tr>
      </tbody>
    </table>
  </div>
</main>