
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Evento y Denunciante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
        }
        .details {
            border-bottom: 1px solid #ccc;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .details h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        .details p {
            color: #666;
            margin-bottom: 10px;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            padding: 10px ;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn{
            display:flex;

        }
        @media screen and (max-width: 768px) {
            *{
                margin:0;
                padding:0;
            }
            .btn{
                display: block;

            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="css/img/logo-removebg-preview.png" alt="Logo" class="logo">
        <div class="imprimir" id="imprimir">
            <h1>Parte Urgente</h1>
            <?php
            require 'conexion.php';

            // Verificar si se proporcionó el ID del evento en la URL
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_evento'])) {
                try {
                    $id_evento = $_GET['id_evento'];

                    // Consultar los detalles del evento y los datos del usuario asociado
                    $stmt_evento = $conexion->prepare("SELECT eventos.*, usuarios.nombre AS nombre_usuario, usuarios.agente FROM eventos INNER JOIN usuarios ON eventos.id_usuario = usuarios.id_usuario WHERE eventos.id_evento = ?");
                    $stmt_evento->execute([$id_evento]);
                    $detalle_evento = $stmt_evento->fetch(PDO::FETCH_ASSOC);

                    // Consultar los detalles del denunciante asociado al evento
                    $stmt_denunciante = $conexion->prepare("SELECT * FROM denunciantes WHERE id_evento = ?");
                    $stmt_denunciante->execute([$id_evento]);
                    $detalle_denunciante = $stmt_denunciante->fetch(PDO::FETCH_ASSOC);

                    if ($detalle_evento && $detalle_denunciante) {
                        // Mostrar los detalles del evento
                        echo "<div class='details'>";
                        echo "<h2>Detalles del Evento</h2>";
                        echo "<p><strong>Número de Evento:</strong> " . $detalle_evento['id_evento'] . "</p>";
                        echo "<p><strong>Tipo:</strong> " . $detalle_evento['tipo'] . "</p>";
                        echo "<p><strong>Subtipo:</strong> " . $detalle_evento['subtipo'] . "</p>";
                        echo "<p><strong>Descripción:</strong> " . $detalle_evento['descripcion'] . "</p>";
                        echo "<p><strong>Fecha y Hora:</strong> " . $detalle_evento['fecha_hora'] . "</p>";
                        echo "<p><strong>Estado:</strong> " . $detalle_evento['estado'] . "</p>";
                        echo "<p><strong>Nombre del Usuario:</strong> " . $detalle_evento['nombre_usuario'] . "</p>";
                        echo "<p><strong>Agente:</strong> " . $detalle_evento['agente'] . "</p>";
                        echo "</div>";

                        // Mostrar los detalles del denunciante
                        echo "<div class='details'>";
                        echo "<h2>Detalles del Denunciante</h2>";
                        echo "<p><strong>Nombre:</strong> " . $detalle_denunciante['nombre'] . "</p>";
                        echo "<p><strong>Apellido:</strong> " . $detalle_denunciante['apellido'] . "</p>";
                        echo "<p><strong>Tipo de Documento:</strong> " . $detalle_denunciante['tipo_documento'] . "</p>";
                        echo "<p><strong>Documento:</strong> " . $detalle_denunciante['documento'] . "</p>";
                        echo "<p><strong>Teléfono:</strong> " . $detalle_denunciante['tel'] . "</p>";
                        echo "</div>";
                    } else {
                        echo "<p>No se encontraron detalles para el evento o el denunciante.</p>";
                    }
                } catch(PDOException $e) {
                    echo "<p>Error al procesar la solicitud: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p>No se proporcionó el ID del evento.</p>";
            }
            ?>
        </div>
    </div>
<div class="btn">
    <button class="print-button" onclick="printDiv('imprimir')">Imprimir</button>
    <button id="sendToTelegram"  class="print-button">Enviar a Canal de Telegram</button>
    <button id="generatePDF" class="print-button">Generar PDF</button>

    </div>


</div>
<script src="enviar_pdf_por_correo.js"></script>
<script>
   document.getElementById('sendToTelegram').addEventListener('click', function() {
    // Reemplaza 'TOKEN' con el token de tu bot
    const botToken = '7056538489:AAHvG4cXMBnlo8lhLs_uaRXF-cgZ_GLy8xI';

    // Reemplaza 'CHAT_ID' con el ID del chat de tu canal
    const chatId = '1998249772';

    // Construye el mensaje con los detalles del evento y del denunciante
    const message = `Detalles del Evento:
ID de Evento: <?php echo $detalle_evento['id_evento']; ?>\n
Tipo: <?php echo $detalle_evento['tipo']; ?>\n
Subtipo: <?php echo $detalle_evento['subtipo']; ?>\n
Descripción: <?php echo $detalle_evento['descripcion']; ?>\n
Fecha y Hora: <?php echo $detalle_evento['fecha_hora']; ?>\n
Estado: <?php echo $detalle_evento['estado']; ?>\n
Nombre del Usuario: <?php echo $detalle_evento['nombre_usuario']; ?>\n
Agente: <?php echo $detalle_evento['agente']; ?>\n\n
Detalles del Denunciante:
Nombre: <?php echo $detalle_denunciante['nombre']; ?>\n
Apellido: <?php echo $detalle_denunciante['apellido']; ?>\n
Tipo de Documento: <?php echo $detalle_denunciante['tipo_documento']; ?>\n
Documento: <?php echo $detalle_denunciante['documento']; ?>\n
Teléfono: <?php echo $detalle_denunciante['tel']; ?>\n`;

    // URL para enviar el mensaje a través de la API de Telegram
    const url = `https://api.telegram.org/bot${botToken}/sendMessage?chat_id=${chatId}&text=${encodeURIComponent(message)}`;

    // Envía una solicitud GET a la URL
    fetch(url)
    .then(response => {
        if (response.ok) {
            alert('Mensaje enviado al canal de Telegram');
        } else {
            alert('Error al enviar el mensaje');
        }
    })
    .catch(error => console.error('Error:', error));
});

  // Función para imprimir solo el div con clase "imprimir"
  function printDiv(divName) {
        var printContents = document.getElementsByClassName(divName)[0].innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
     
    document.getElementById('generatePDF').addEventListener('click', function() {
        var eventoID = <?php echo $id_evento; ?>;
        generatePDF(eventoID);
    });

    function generatePDF(eventoID) {
    var element = document.getElementById('imprimir');
    var fileName = "Parte_Urgente_Evento_" + eventoID + ".pdf";
        html2pdf().from(element).toPdf().get('pdf').then(function(pdf) {
            pdf.save(fileName);
        });
    }
    
    document.getElementById('sendToRepository').addEventListener('click', function() {
    // Genera el PDF del contenido del div con id "imprimir"
    generatePDF();

    function generatePDF() {
        var element = document.getElementById('imprimir');
        html2pdf().from(element).toPdf().get('pdf').then(function(pdf) {
            // Convierte el PDF a una cadena de bytes
            var pdfData = pdf.output('arraybuffer');

            // Crea un objeto FormData para enviar el PDF al servidor
            var formData = new FormData();
            formData.append('pdf', new Blob([pdfData], { type: 'application/pdf' }), 'parte_urgente.pdf'); // Cambia el nombre del archivo si es necesario

            // Realiza una solicitud AJAX para enviar el PDF al repositorio.php
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'repositorio.php');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('El documento se ha enviado al repositorio correctamente.');
                } else {
                    alert('Error al enviar el documento al repositorio.');
                }
            };
            xhr.send(formData);
        });
    }
});

</script>

</body>
</html>
