<?php
require 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">

    <style>
   body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .notificacion {
            background-color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .notificacion h3 {
            margin-top: 0;
            color: #002D62;
        }

        .notificacion p {
            margin-bottom: 0;
            color: #333;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }

        .no-notificaciones {
            color: #555;
            font-style: italic;
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
    .graficos{
        display:block;

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
    }

        </style>
    </head>
    <body>

    <div class="container">
        <div class="sidebar">
            <h2>Notificación</h2>
            <hr>
            <ul> <br>
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

<svg class="close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M5.002 21h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2h-14c-1.103 0-2 .897-2 2v6.001H10V7l6 5-6 5v-3.999H3.002V19c0 1.103.897 2 2 2z"></path></svg>
</div>
<?php
try {
    $stmt = $conexion->prepare("SELECT * FROM Notificaciones");
    $stmt->execute();
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($notificaciones)) {
        foreach ($notificaciones as $notificacion) {
            echo "<div class='notificacion'>";
            echo "<h3>" . htmlspecialchars($notificacion['nombre']) . "</h3>";
            echo "<p><strong>Agente:</strong> " . htmlspecialchars($notificacion['agente']) . "</p>";
            echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($notificacion['mensaje']) . "</p>";
            echo "<p><strong>Fecha y Hora:</strong> " . htmlspecialchars($notificacion['fecha_hora']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p class='no-notificaciones'>No hay notificaciones disponibles.</p>";
    }
} catch (PDOException $e) {
    echo "Error al obtener las notificaciones: " . $e->getMessage();
}
?>
</body>
</html>
