<?php
require 'conexion.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

try {
    $stmt = $conexion->prepare("SELECT * FROM eventos WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error al consultar la base de datos: " . $e->getMessage();
}

// Verificar si se ha enviado un formulario de actualización de avance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_evento'], $_POST['avance'])) {
    $id_evento = $_POST['id_evento'];
    $avance = $_POST['avance'];

    try {
        $stmt = $conexion->prepare("UPDATE eventos SET avance = ? WHERE id_evento = ?");
        $stmt->execute([$avance, $id_evento]);
        echo "Avance actualizado correctamente.";
    } catch(PDOException $e) {
        echo "Error al actualizar el avance: " . $e->getMessage();
    }
}

// Verificar si se ha enviado un formulario de actualización de estado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_evento'], $_POST['estado'])) {
    $id_evento = $_POST['id_evento'];
    $estado = $_POST['estado'];

    try {
        $stmt = $conexion->prepare("UPDATE eventos SET estado = ? WHERE id_evento = ?");
        $stmt->execute([$estado, $id_evento]);
        echo "Estado actualizado correctamente.";
    } catch(PDOException $e) {
        echo "Error al actualizar el estado: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Eventos</title>
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

        }

        th {
            background-color: #002D62;
            padding: 10px;
            color: #fff;
        }
        .select-options {
    display: none;
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    z-index: 1;
}

.select-options .option {
    padding: 8px 12px;
    cursor: pointer;
}

.select-options .option:hover {
    background-color: #f2f2f2;
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
    width: 100%; /* Ocupa todo el ancho en dispositivos móviles */
}

.content {
    flex: 1;
    padding: 20px;
}

table {
        font-size: 11px;
        text-align:center;
    }

    th, td {
        text-align:center;
        padding: 2px; /* Reducir el espaciado de las celdas para dispositivos móviles */
    }
   
    }
        
    </style>
    <link rel="shortcut icon" href="css\img\logo-removebg-preview.png" type="image/x-icon">
</head>
<body>
<div class="container">
        <div class="sidebar">
            <h2>Eventos Avance</h2>
            <hr>
            <ul> <br>
            <li><a href="dashordb.php">Inicio</a></li>
                <li><a href="registro.php">Registrar Usuarios</a></li>
                <li><a href="buscar.php">Buscar Evento</a></li>
                <li><a href="evento.html">Crear Evento</a></li>
                <li><a href="notificaciones.php">Notificaciones</a></li>
                <li><a href="repositorio.php">Repositorio</a></li>
                <li><a href="notas.php">Notas</a></li>
                <li><a href="admin.php">Administrar Recursos</a></li>
            </ul>
        </div>
        <div class="content">
<table>
    <tr>
        <th>Número de Evento</th>
        <th>Tipo</th>
        <th>Subtipo</th>
        <th>Descripción</th>
        <th>Fecha y Hora</th>
        <th>Estado</th>
        <th>Avance</th>
        <th>Editar</th>
        <th>Parte Urgente</th>
    </tr>
    <?php foreach ($eventos as $evento): ?>
        <tr>
            <td><?php echo $evento['id_evento']; ?></td>
            <td><?php echo $evento['tipo']; ?></td>
            <td><?php echo $evento['subtipo']; ?></td>
            <td><?php echo $evento['descripcion']; ?></td>
            <td><?php echo $evento['fecha_hora']; ?></td>
            <td class="editableEstado" data-id="<?php echo $evento['id_evento']; ?>">
    <div class="select-wrapper">
        <div class="selected-option"><?php echo $evento['estado']; ?></div>
        <div class="select-options">
            <div class="option">Inicio</div>
            <div class="option">Proceso</div>
            <div class="option">Completado</div>
        </div>
    </div>
</td>

            <td class="editable" data-id="<?php echo $evento['id_evento']; ?>"><?php echo $evento['avance']; ?></td>
            <td><a href="detalle_evento.php?id_evento=<?php echo $evento['id_evento']; ?>">Editar</a></td>
            <td><a href="parte_urgente.php?id_evento=<?php echo $evento['id_evento']; ?>">Parte Urgente</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
document.querySelectorAll('.editableEstado').forEach(item => {
    item.addEventListener('click', event => {
        const selectOptions = item.querySelector('.select-options');
        selectOptions.style.display = 'block';
    });
});

document.querySelectorAll('.option').forEach(option => {
    option.addEventListener('click', event => {
        const value = option.textContent.trim();
        const selectWrapper = option.closest('.select-wrapper');
        const selectedOption = selectWrapper.querySelector('.selected-option');
        selectedOption.textContent = value;
        selectWrapper.querySelector('.select-options').style.display = 'none';
        const idEvento = selectWrapper.closest('td').dataset.id;
        saveField('estado', idEvento, value);
    });
});
// Agregar un controlador de eventos para ocultar el select cuando se hace clic fuera de él
document.addEventListener('click', function(event) {
    const selectWrappers = document.querySelectorAll('.select-wrapper');
    selectWrappers.forEach(selectWrapper => {
        if (!selectWrapper.contains(event.target)) {
            selectWrapper.querySelector('.select-options').style.display = 'none';
        }
    });
});

    function saveField(fieldName, idEvento, value) {
        fetch('eventos_avance2.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                id_evento: idEvento,
                [fieldName]: value
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
    }
</script>

</body>
</html>
    