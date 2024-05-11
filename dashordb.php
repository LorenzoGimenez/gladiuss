 <?php
 session_start(); // Iniciar la sesión

 require 'conexion.php'; // Incluir el archivo de conexión a la base de datos
 
 // Obtener el ID del usuario de la sesión
 $id_usuario = $_SESSION['id_usuario'];
?> 
 <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <h2>Dashboard</h2>
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
        <a href="obtenernotificiacion.php">
    <svg id="notificacion-icono" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
        <path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path>
    </svg>
</a>
<svg class="close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M5.002 21h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2h-14c-1.103 0-2 .897-2 2v6.001H10V7l6 5-6 5v-3.999H3.002V19c0 1.103.897 2 2 2z"></path></svg>
</div>


            <br>
            <div class="contenedores">
            <div class="inicio">
        <h2>Eventos <br> Iniciados</h2>
        <?php
        // Consulta SQL para obtener la cantidad de eventos iniciados para el usuario actual
        try {
            $stmt = $conexion->prepare("SELECT COUNT(*) as cantidad FROM eventos WHERE id_usuario = ? AND estado = 'Inicio'");
            $stmt->execute([$id_usuario]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $cantidadIniciados = $resultado['cantidad'];
            echo "<h3>$cantidadIniciados</h3>";
        } catch(PDOException $e) {
            echo "Error al conectar con la base de datos: " . $e->getMessage();
        }
        ?>
     
                </div>
                <div class="proceso">
    <h2>Eventos en <br> Proceso</h2>
    <?php
    // Consulta SQL para obtener la cantidad de eventos en proceso para el usuario actual
    try {
        $stmt = $conexion->prepare("SELECT COUNT(*) as cantidad FROM eventos WHERE id_usuario = ? AND estado = 'Proceso'");
        $stmt->execute([$id_usuario]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidadEnProceso = $resultado['cantidad'];
        echo "<h3>$cantidadEnProceso</h3>";
    } catch(PDOException $e) {
        echo "Error al conectar con la base de datos: " . $e->getMessage();
    }
    ?>
</div>

<div class="finalizacion">
    <h2>Eventos <br> Finalizados</h2>
    <?php

    // Consulta SQL para obtener la cantidad de eventos finalizados para el usuario actual
    try {
        $stmt = $conexion->prepare("SELECT COUNT(*) as cantidad FROM eventos WHERE id_usuario = ? AND estado = 'Completado'");
        $stmt->execute([$id_usuario]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidadFinalizados = $resultado['cantidad'];
        echo "<h3>$cantidadFinalizados</h3>";
    } catch(PDOException $e) {
        echo "Error al conectar con la base de datos: " . $e->getMessage();
    }
    ?>
</div>

                
            </div>
            <?php

try {
    // Consulta SQL para obtener los últimos 5 eventos del usuario
    $stmt = $conexion->prepare("SELECT * FROM eventos WHERE id_usuario = ? ORDER BY id_evento DESC LIMIT 5");
    $stmt->execute([$id_usuario]);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($eventos)) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Número de Evento</th>";
        echo "<th>Tipo</th>";
        echo "<th>Subtipo</th>";
        echo "<th>Descripción</th>";
        echo "<th>Fecha y Hora</th>";
        echo "<th>Estado</th>";
        echo "<th>Avance</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($eventos as $evento) {
            echo "<tr>";
            echo "<td>" . $evento['id_evento'] . "</td>";
            echo "<td>" . $evento['tipo'] . "</td>";
            echo "<td>" . $evento['subtipo'] . "</td>";
            echo "<td>" . $evento['descripcion'] . "</td>";
            echo "<td>" . $evento['fecha_hora'] . "</td>";
            echo "<td>" . $evento['estado'] . "</td>";
            echo "<td>" . $evento['avance'] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No hay eventos disponibles para este usuario.";
    }
} catch(PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}

try {
    // Consulta SQL para obtener la cantidad de cada tipo de evento
    $stmt = $conexion->prepare("SELECT tipo, COUNT(*) as cantidad FROM eventos GROUP BY tipo");
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar arrays para almacenar los tipos de eventos y sus cantidades
    $tiposEventos = [];
    $cantidadesEventos = [];

    foreach ($resultados as $resultado) {
        $tiposEventos[] = $resultado['tipo'];
        $cantidadesEventos[] = $resultado['cantidad'];
    }

} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
try {
    // Consulta SQL para obtener la cantidad de eventos en cada estado por usuario
    $stmt = $conexion->prepare("
        SELECT estado, COUNT(*) as cantidad
        FROM eventos
        WHERE id_usuario = ?
        GROUP BY estado
    ");
    $stmt->execute([$id_usuario]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar un array para almacenar los datos de los eventos por estado
    $eventosPorEstado = [];

    // Iterar sobre los resultados y almacenar los datos en el array
    foreach ($resultados as $resultado) {
        $eventosPorEstado[$resultado['estado']] = $resultado['cantidad'];
    }
} catch(PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}

try {
    $stmt = $conexion->prepare("SELECT tipo, COUNT(*) as cantidad FROM eventos WHERE id_usuario = ? GROUP BY tipo");
    $stmt->execute([$id_usuario]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar arrays para almacenar los tipos de eventos y sus cantidades
    $tiposEventos = [];
    $cantidadesEventos = [];

    foreach ($resultados as $resultado) {
        $tiposEventos[] = $resultado['tipo'];
        $cantidadesEventos[] = $resultado['cantidad'];
    }

} catch (PDOException $e) {
    echo "Error al obtener los datos: " . $e->getMessage();
}
?>  
<div class="graficos">
  <canvas id="diagramaSectores"></canvas>
    <canvas id="graficoBarras"></canvas>

</div>
  

        <script>
             var tiposEventos = <?php echo json_encode($tiposEventos); ?>;
    var cantidadesEventos = <?php echo json_encode($cantidadesEventos); ?>;

    // Crear el gráfico de barras con Chart.js
    var ctx = document.getElementById('graficoBarras').getContext('2d');
    var graficoBarras = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: tiposEventos,
            datasets: [{
                label: 'Cantidad de Eventos',
                data: cantidadesEventos,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
         
            var eventosPorEstado = <?php echo json_encode($eventosPorEstado); ?>;
        var colores = ['#FF6384', '#36A2EB', '#FFCE56']; // Colores para los sectores

        var ctx = document.getElementById('diagramaSectores').getContext('2d');
        var diagramaSectores = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(eventosPorEstado),
                datasets: [{
                    data: Object.values(eventosPorEstado),
                    backgroundColor: colores
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Proporción de Eventos por Estado'
                }
            }
        });
        function cerrarSesion() {
            // Redirigir a la página de cierre de sesión
            window.location.href = 'login.php';
        }

        // Obtener el icono de cierre de sesión
        const iconoCerrarSesion = document.querySelector('.close');

        // Agregar un evento de clic al icono de cierre de sesión
        iconoCerrarSesion.addEventListener('click', cerrarSesion);
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

        </script>

    </body>
    </html>
