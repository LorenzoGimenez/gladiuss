<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se enviaron datos por POST para agregar notificación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['agente'], $_POST['mensaje'])) {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $agente = $_POST['agente'];
    $mensaje = $_POST['mensaje'];
    
    // Obtener el id del usuario desde la sesión
    $id_usuario = $_SESSION['id_usuario'];
    
    // Obtener la fecha y hora actual en la zona horaria de Argentina/Buenos_Aires
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_hora_argentina = date('Y-m-d H:i:s');
    
    // Insertar la notificación en la base de datos
    try {
        $stmt = $conexion->prepare("INSERT INTO Notificaciones (id_usuario, nombre, agente, mensaje, fecha_hora) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_usuario, $nombre, $agente, $mensaje, $fecha_hora_argentina]);
        
        // Redirigir a una página de éxito o hacer cualquier otra operación después de la inserción
        header('Location: notificaciones.php');
        exit;
    } catch (PDOException $e) {
        // Manejar cualquier error de inserción
        echo "Error al insertar la notificación: " . $e->getMessage();
    }
}

// Verificar si se envió un ID de notificación para eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_notificacion'])) {
    // Obtener el ID de la notificación a eliminar
    $id_notificacion = $_POST['eliminar_notificacion'];
    
    // Eliminar la notificación de la base de datos
    try {
        $stmt = $conexion->prepare("DELETE FROM Notificaciones WHERE id_notificacion = ?");
        $stmt->execute([$id_notificacion]);
        
        // Redirigir a una página de éxito o hacer cualquier otra operación después de la eliminación
        header('Location: notificaciones.php');
        exit;
    } catch (PDOException $e) {
        // Manejar cualquier error de eliminación
        echo "Error al eliminar la notificación: " . $e->getMessage();
    }
}

// Función para verificar si el usuario está autenticado
function estaAutenticado() {
    return isset($_SESSION['id_usuario']);
}

// Función para obtener el rol del usuario desde la base de datos
function obtenerRolUsuario($conexion, $id_usuario) {
    try {
        $stmt = $conexion->prepare("SELECT agente FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['agente'];
    } catch(PDOException $e) {
        return false;
    }
}

// Verificar autenticación
if (!estaAutenticado()) {
    header('Location: login.php');
    exit;
}

// Obtener ID de usuario de la sesión
$id_usuario = $_SESSION['id_usuario'];

// Obtener rol del usuario
$rol = obtenerRolUsuario($conexion, $id_usuario);

// Verificar si el usuario tiene el rol de "administrador"
try {
    $stmt = $conexion->prepare("SELECT agente FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $agente = $stmt->fetchColumn();

    // Si el usuario no tiene el rol de "administrador", redirigir a una página de acceso denegado
    if ($agente !== 'administrador') {
        header('Location: dashordb.php');
        exit;
    }
} catch (PDOException $e) {
    // Si ocurre un error, mostrar un mensaje de error
    echo "Error al obtener el agente del usuario: " . $e->getMessage();
}
// Consultar la base de datos para obtener las notificaciones
try {
    $stmt = $conexion->query("SELECT * FROM Notificaciones");
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejar cualquier error de consulta
    echo "Error al obtener las notificaciones: " . $e->getMessage();
    // Asignar un arreglo vacío a $notificaciones para evitar errores
    $notificaciones = [];
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .conta {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            background-color: #002D62;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
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
    .icons {
            display: flex;
            justify-content: flex-end;
        }

        .icons svg {
            margin-left: 20px; 
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
.conta{
    width:70%;
}
.icons {
          margin-top:15px;
          margin-right:10px;
        }
    .icons svg {
        
            margin-right: 20px; 
        }
    }
    </style>
    <link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">

</head>
<body>
<div class="container">
        <div class="sidebar">
            <h2>Notificación</h2>
            <hr>
            <ul> <br>
            <li><a href="dashordb.php">Inicio</a></li>
                <li><a href="evento.html">Crear Evento</a></li>
                <li><a href="registro.php">Registrar Usuarios</a></li>
                <li><a href="buscar.php">Buscar Evento</a></li>
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
<div id="notificacion-container" style="display: none;">
   
</div>


<div class="conta">

    <h1 class="title">Agregar Notificación</h1>

    <!-- Formulario para agregar una nueva notificación -->
    <form action="notificaciones.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="agente">Agente:</label>
            <input type="text" id="agente" name="agente" required>
        </div>
        <div class="form-group">
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
        </div>
        <button type="submit">Enviar Notificación</button>
    </form>
    
</div>
<table border='1'>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Agente</th>
                <th>Mensaje</th>
                <th>Fecha y Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notificaciones as $notificacion): ?>
                <tr>
                    <td><?= htmlspecialchars($notificacion['nombre']) ?></td>
                    <td><?= htmlspecialchars($notificacion['agente']) ?></td>
                    <td><?= htmlspecialchars($notificacion['mensaje']) ?></td>
                    <td><?= htmlspecialchars($notificacion['fecha_hora']) ?></td>
                    <td>
                        <!-- Formulario para eliminar notificación -->
                        <form action='notificaciones.php' method='POST'>
                            <input type='hidden' name='eliminar_notificacion' value='<?= $notificacion['id_notificacion'] ?>'>
                            <button type='submit'>Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    

</div>
</div>
<script>
    const iconoNotificacion = document.getElementById('notificacion-icono');
    const contenedorNotificacion = document.getElementById('notificacion-container');

    // Escuchar clics en el documento
    document.addEventListener('click', function(event) {
        // Si el clic ocurre fuera del contenedor de notificaciones, ocultarlo
        if (!contenedorNotificacion.contains(event.target) && event.target !== iconoNotificacion) {
            contenedorNotificacion.style.display = 'none';
        }
    });

    // Escuchar clics en el ícono de notificación para alternar su visibilidad
    iconoNotificacion.addEventListener('click', function() {
        if (contenedorNotificacion.style.display === 'block') {
            contenedorNotificacion.style.display = 'none';
        } else {
            contenedorNotificacion.style.display = 'block';
        }
    });
    document.getElementById('notificacion-icono').addEventListener('click', function() {
    // Hacer una solicitud al servidor para obtener la notificación correspondiente
    fetch('obtenernotificiacion.php')
        .then(response => response.text())
        .then(data => {
            // Mostrar la notificación en el contenedor
            document.getElementById('notificacion-container').innerHTML = data;
            document.getElementById('notificacion-container').style.display = 'block';
        })
        .catch(error => {
            console.error('Error al obtener la notificación:', error);
        });
});

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
