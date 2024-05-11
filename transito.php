<?php
require 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: dashordb.php');
    exit;
}

// Obtener el rol del usuario desde la base de datos
$id_usuario = $_SESSION['id_usuario'];
try {
    $stmt = $conexion->prepare("SELECT agente FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $rol = $row['agente'];
} catch(PDOException $e) {
    echo "Error al consultar la base de datos: " . $e->getMessage();
    exit;
}

// Verificar si el usuario tiene el rol adecuado
if ($rol !== 'transito') {
    header('Location: dashordb.php');
    exit;
}

// Procesar el formulario de carga de datos solo si se ha enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
    // Obtener los datos del formulario
    $encargado_de_turno = $_POST['encargado_de_turno'];
    $inspectores_moviles = $_POST['inspectores_moviles'];
    $moviles_asignados = $_POST['moviles_asignados'];
    $inspectores_motos = $_POST['inspectores_motos'];
    $motos_asignadas = $_POST['motos_asignadas'];
    $inspectores_grua = $_POST['inspectores_grua'];
    $grua_asignada = $_POST['grua_asignada'];

    try {
        // Insertar los datos en la tabla "transito"
        $stmt = $conexion->prepare("INSERT INTO transito (id_usuario, encargado_de_turno, inspectores_moviles, moviles_asignados, inspectores_motos, motos_asignadas, inspectores_grua, grua_asignada) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_usuario, $encargado_de_turno, $inspectores_moviles, $moviles_asignados, $inspectores_motos, $motos_asignadas, $inspectores_grua, $grua_asignada]);
        echo "";
    } catch(PDOException $e) {
        echo "Error al guardar los datos: " . $e->getMessage();
    }
}

// Procesar la eliminación si se ha recibido un ID de fila para eliminar
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmt = $conexion->prepare("DELETE FROM transito WHERE id = ?");
        $stmt->execute([$delete_id]);
        echo "";
    } catch(PDOException $e) {
        echo "Error al eliminar el registro: " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tránsito</title>
    <link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">
    <style>
            body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        
    }

    .container {
    display: flex;
    min-height: 100vh; /* Cambiado de 'height' a 'min-height' */
    width: 100%;
}


.sidebar {
    width: 170px;
    position: sticky;
    background-color: #002D62;
    color: #fff;
    padding: 20px;
}

.content {
    flex: 1;
    padding: 20px;
}
    .sidebar h2 {
        margin-bottom: 20px;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom:30px;
    }

    .sidebar ul li a {
        color: #fff;
        text-decoration: none;
    }

    .contenedores{
        display:flex;
        margin-left:60px;
      
    }
    .inicio{
        margin-right:100px;
        width:28%;
        border-radius:10px;
        text-align:center;
        font-family:Noto Sans, sans-serif;
        background-color:green;
        color:white;
    }
    .proceso{
        margin-right:100px;
        width:28%;
        text-align:center;
        font-size:15px;
        color:white;
        border-radius:10px;
        font-family:Noto Sans, sans-serif;
        background-color:orange;
    }
    .finalizacion{
        margin-right:50px;
        border-radius:10px;
    color:white;
    font-family:Noto Sans, sans-serif;
    text-align:center;
        width:28%;
        background-color:red;
    }
    table {
            border-collapse: collapse;
            width: 100%;
            margin-top:20px;
            background-color: white;
            color: black;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #002D62;
            color: #fff;
        }
        .graficos{
            display:flex;
            margin-top:50px;
          margin-right:20px;
     margin-left:50px;
            height:350px;
        }
        .icons {
            display: flex;
            justify-content: flex-end;
        }

        .icons svg {
            margin-left: 20px; 
        }
    /* Estilos para el formulario */
    form {
            display: flex;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 0 0 calc(50% - 20px);
            margin-right: 20px;
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-right: 0;
        }


        .form-group input,
        .form-group textarea,
        .form-group select  {
            width: 100%;
            padding: 6px; /* Se redujo el padding */
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            font-size: 14px; /* Se redujo el tamaño de fuente */
        }

        input[type="submit"],
        input[type="reset"] {
            width: calc(40% - 20px);
            padding: 10px; /* Se ajustó el padding */
            margin-top: 10px;
            background-color: #002D62;
            color: #fff;
            margin: auto;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #0056b3;
        }
    
    @media screen and (max-width: 768px) {
        body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    width:100%;
}

.container {
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Ocupa al menos el 100% de la altura de la ventana gráfica */
}

.sidebar {
    width: 90%; /* Ocupa todo el ancho en dispositivos móviles */
}

.content {
    flex: 1;
    padding: 20px;
}

        .contenedores {
        display: block; /* Cambiar a display block para que los elementos se muestren uno debajo del otro */
        margin-left: auto; /* Centrar horizontalmente */
        margin-right: auto; /* Centrar horizontalmente */
        text-align: center; /* Centrar el texto */
        font-size: 14px; /* Tamaño de fuente ajustado para mejorar la legibilidad */
    }
    .inicio,
    .proceso,
    .finalizacion {
        margin-bottom: 20px;
        width: 40%; /* Ancho del contenedor ajustable */
        margin-left: auto;
        
        margin-right: auto;
    }
    table {
        font-size: 11px;
        text-align:center;
    }

    th, td {
        text-align:center;
        padding: 2px; /* Reducir el espaciado de las celdas para dispositivos móviles */
    }

    #diagramaSectores, #graficoBarras{
        margin-bottom: 20px;
    }
    .icons {
          margin-top:15px;
          margin-right:10px;
        }
    .icons svg {
        
            margin-right: 20px; 
        }
        .form-group {
                flex: 0 0 100%;
                margin-right: 0;
            }

            input[type="submit"],
            input[type="reset"] {
                width: 100%;
                margin-bottom: 20px;
            }
    }

        </style>
    </head>
    <body>

    <div class="container">
        <div class="sidebar">
            <h2>Dashboard</h2>
            <hr>
            <ul> <br>
            <li><a href="dashordb.php">Inicio</a></li>
                <li><a href="evento.html">Crear Evento</a></li>
                <li><a href="registro.php">Registrar Usuarios</a></li>
                <li><a href="buscar.php">Buscar Evento</a></li>
                <li><a href="notificaciones.php">Notificaciones</a></li>
                <li><a href="eventos_avance2.php">Evento Avance</a></li>
                <li><a href="repositorio.php">Repositorio</a></li>
                <li><a href="notas.php">Notas</a></li>
                <li><a href="admin.php">Administrar Recursos</a></li>
            </ul>
        </div>
        <div class="content">

        <div class="icons">

        <a href="obtenernotificiacion.php">
    <svg id="notificacion-icono" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
        <path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path>
    </svg>
</a>       
<svg class="close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M5.002 21h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2h-14c-1.103 0-2 .897-2 2v6.001H10V7l6 5-6 5v-3.999H3.002V19c0 1.103.897 2 2 2z"></path></svg>
</div>

    <form action="transito.php" method="POST">

        <input type="hidden" name="form_submitted" value="1">
            <div class="form-group">

        <label for="encargado_de_turno">Encargado de Turno:</label>
        <input type="text" id="encargado_de_turno" name="encargado_de_turno" required><br><br>
        
        <label for="inspectores_moviles">Inspectores Móviles:</label>
        <input type="text" id="inspectores_moviles" name="inspectores_moviles" required><br><br>
        
        <label for="moviles_asignados">Móviles Asignados:</label>
        <input type="number" id="moviles_asignados" name="moviles_asignados" required><br><br>
</div>
        <div class="form-group">

        <label for="inspectores_motos">Inspectores Motos:</label>
        <input type="text" id="inspectores_motos" name="inspectores_motos" required><br><br>
        
        <label for="motos_asignadas">Motos Asignadas:</label>
        <input type="number" id="motos_asignadas" name="motos_asignadas" required><br><br>
        
        <label for="inspectores_grua">Inspectores Grúa:</label>
        <input type="text" id="inspectores_grua" name="inspectores_grua" required><br><br>
        </div>

<div class="form-group">
        <label for="grua_asignada">Grúa Asignada:</label>
        <input type="text" id="grua_asignada" name="grua_asignada" required><br><br>

        <input type="submit" value="Guardar Datos">
        </div>

    </form>
  
 
    <table border="1">
        <thead>
            <tr>
    
                <th>Encargado de Turno</th>
                <th>Inspectores Móviles</th>
                <th>Móviles Asignados</th>
                <th>Inspectores Motos</th>
                <th>Motos Asignadas</th>
                <th>Inspectores Grúa</th>
                <th>Grúa Asignada</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $conexion->query("SELECT * FROM transito");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
  
                    echo "<td>" . $row['encargado_de_turno'] . "</td>";
                    echo "<td>" . $row['inspectores_moviles'] . "</td>";
                    echo "<td>" . $row['moviles_asignados'] . "</td>";
                    echo "<td>" . $row['inspectores_motos'] . "</td>";
                    echo "<td>" . $row['motos_asignadas'] . "</td>";
                    echo "<td>" . $row['inspectores_grua'] . "</td>";
                    echo "<td>" . $row['grua_asignada'] . "</td>";
                    echo "<td><a href='transito.php?delete_id=" . $row['id'] . "'>Borrar</td>";
                    echo "</tr>";
                }
            } catch(PDOException $e) {
                echo "Error al consultar la base de datos: " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>
    </div></div>
   
<script>
 
    function cerrarSesion() {
            // Redirigir a la página de cierre de sesión
            window.location.href = 'login.php';
        }

        // Obtener el icono de cierre de sesión
        const iconoCerrarSesion = document.querySelector('.close');

        // Agregar un evento de clic al icono de cierre de sesión
        iconoCerrarSesion.addEventListener('click', cerrarSesion);
</script>   
</body>
</html>
