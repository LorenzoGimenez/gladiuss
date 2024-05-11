<?php
require 'conexion.php';
session_start();

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
    if ($agente !== 'transito' &&$agente !== 'administrador') {
        header('Location: dashordb.php');
        exit;
    }
} catch (PDOException $e) {
    // Si ocurre un error, mostrar un mensaje de error
    echo "Error al obtener el agente del usuario: " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de Recursos</title>
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


        .icons {
            display: flex;
            justify-content: flex-end;
        }

        .icons svg {
            margin-left: 20px; 
        }
        /* Estilos generales */
.contenedor1, .contenedore2 {
    display: flex;
}

/* Estilos para los enlaces */
.contenedor1 a, .contenedore2 a {
    text-decoration: none; /* Quita la subrayado predeterminado de los enlaces */
}
.transito, .defesa-civil, .motorizada, .obj-fisico, .patrulla, .dim {
    width: 200px;
    border-radius:10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); 
    height: 200px;
    margin: 10px;

    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 20px;
    transition: transform 0.3s; /* Agrega una transición suave */
}

.transito:hover, .defesa-civil:hover, .motorizada:hover, .obj-fisico:hover, .patrulla:hover, .dim:hover {
    transform: scale(1.1); /* Hace zoom al 110% del tamaño original al pasar el cursor */
}


.transito {
    background-image: url('img-cargadmin/transito.jpg'); /* Corregido */
    background-size: cover;
 margin-left:200px;
    background-position: center;
}

.defesa-civil {
    background-image: url('img-cargadmin/defensa-civil.jpg'); /* Corregido */
    background-size: cover;
    background-position: center;
}

.motorizada {
    background-image: url('img-cargadmin/motorizada.jpg'); /* Corregido */
    background-size: cover;
    background-position: center;
}
.obj-fisico{
    text-align:center;
    background-image: url('img-cargadmin/obj-fisico.jpeg'); /* Corregido */
    background-size: cover;
    margin-left:200px;
    background-position: center;
}
.patrulla{
    text-align:center;
    background-image: url('img-cargadmin/patrulla.jpg'); /* Corregido */
    background-size: cover;
    background-position: center;
}
.dim{
    text-align:center;
    background-image: url('img-cargadmin/dim-obligado.jpg'); /* Corregido */
    background-size: cover;
    background-position: center;
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


    .icons {
          margin-top:15px;
          margin-right:10px;
        }
    .icons svg {
        
            margin-right: 20px; 
        }
        .contenedor1 {
            margin-top:20px;

        flex-direction: column; /* Cambia la dirección de los elementos a columnas */
        align-items: center; /* Centra los elementos horizontalmente */
    }

    .transito, .defesa-civil, .motorizada {
        height: auto; /* Cambia la altura a automática para mantener la proporción */
        margin: 5px 0; /* Ajusta el margen para separar los divs verticalmente */
    }
        
    }

        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>
<div class="container">
        <div class="sidebar">
            <h2>Carga Administrativa</h2>
            <hr>
            <ul> <br>
            <li><a href="dashordb.php">Inicio</a></li>
                <li><a href="evento.html">Crear Evento</a></li>
                <li><a href="registro.php">Registrar Usuarios</a></li>
                <li><a href="buscar.php">Buscar Evento</a></li>
                <li><a href="eventos_avance2.php">Evento Avance</a></li>
                <li><a href="repositorio.php">Repositorio</a></li>
                <li><a href="notas.php">Notas</a></li>
                <li><a href="notificaciones.php">Notificaciones</a></li>
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
<div class="contenedores">
    <div class="contenedor1">
        <a href="transito.php">
        <div class="transito">
            <h2>Transito</h2>
        </div>
        </a>
        <a href="#">
        <div class="defesa-civil">
            <h2>Defensa Civil</h2>
        </div>
        </a>
        <a href="#">
        <div class="motorizada">
            <h2>Motorizada</h2>
        </div>
        </a>
    </div>
    <br>
    <div class="contenedore2">
        <a href="#">
        <div class="obj-fisico">
            <h2>Objetivo Fisico</h2>
        </div>
        </a>
        <a href="#">
        <div class="patrulla">
            <h2>Patrulla Municipal</h2>
        </div></a>
        <a href="#">
        <div class="dim">
            <h2>Dim Obligado</h2>
        </div>
        </a>
    </div>
</div>
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
